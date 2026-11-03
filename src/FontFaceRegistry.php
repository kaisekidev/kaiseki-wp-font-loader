<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\WordPress\FontLoader\FontFaceFilter\FontFaceFilterInterface;
use Kaiseki\WordPress\FontLoader\Loader\LoaderInterface;
use Kaiseki\WordPress\Hook\HookProviderInterface;

use function add_action;
use function fnmatch;
use function is_admin;
use function pathinfo;
use function wp_add_inline_style;
use function wp_enqueue_style;
use function wp_register_style;

use const PATHINFO_FILENAME;

class FontFaceRegistry implements HookProviderInterface
{
    public const BLOCK_EDITOR_CANVAS_HANDLE = 'kaiseki-font-faces-block-editor';

    /** @var list<FontFaceInterface> */
    private array $fontFaces;
    private FontFaceStylesheetRenderer $fontFaceStylesheetRenderer;

    /**
     * @param LoaderInterface                        $loader
     * @param array<string, FontFaceFilterInterface> $filters
     */
    public function __construct(
        LoaderInterface $loader,
        array $filters = [],
    ) {
        $fontFaces = $loader->load();
        $this->fontFaces = $this->filterFonts($fontFaces, $filters);
        $this->fontFaceStylesheetRenderer = new FontFaceStylesheetRenderer();
    }

    public function addHooks(): void
    {
        add_action('wp_head', [$this, 'renderFrontendStylesheet']);
        add_action('wp_head', [$this, 'renderFrontendPreloadLinks'], 1);
        add_action('admin_head', [$this, 'renderAdminStylesheet']);
        add_action('admin_head', [$this, 'renderAdminPreloadLinks'], 1);
        add_action('login_head', [$this, 'renderLoginStylesheet']);
        add_action('login_head', [$this, 'renderLoginPreloadLinks'], 1);
        add_action('enqueue_block_assets', [$this, 'enqueueBlockEditorCanvasStylesheet']);
    }

    /**
     * Since WordPress 7.1 the block editor content always renders in an iframe. admin_head does not
     * reach that document, but styles enqueued on enqueue_block_assets do. That hook also runs on the
     * frontend, where wp_head already prints the rules.
     */
    public function enqueueBlockEditorCanvasStylesheet(): void
    {
        if (!is_admin()) {
            return;
        }

        $rules = $this->fontFaceStylesheetRenderer->renderFontFaceRules(
            $this->fontFaces,
            FontFaceInterface::BACKEND
        );
        if ($rules === '') {
            return;
        }

        wp_register_style(self::BLOCK_EDITOR_CANVAS_HANDLE, false, [], null);
        wp_add_inline_style(self::BLOCK_EDITOR_CANVAS_HANDLE, $rules);
        wp_enqueue_style(self::BLOCK_EDITOR_CANVAS_HANDLE);
    }

    public function renderFrontendStylesheet(): void
    {
        $this->fontFaceStylesheetRenderer->printFrontend($this->fontFaces);
    }

    public function renderAdminStylesheet(): void
    {
        $this->fontFaceStylesheetRenderer->printAdmin($this->fontFaces);
    }

    public function renderLoginStylesheet(): void
    {
        $this->fontFaceStylesheetRenderer->printLoginStylesheet($this->fontFaces);
    }

    public function renderFrontendPreloadLinks(): void
    {
        $this->fontFaceStylesheetRenderer->printFrontendPreloadLinks($this->fontFaces);
    }

    public function renderAdminPreloadLinks(): void
    {
        $this->fontFaceStylesheetRenderer->printAdminPreloadLinks($this->fontFaces);
    }

    public function renderLoginPreloadLinks(): void
    {
        $this->fontFaceStylesheetRenderer->printLoginStylesheetPreloadLinks($this->fontFaces);
    }

    /**
     * @param array<string, FontFaceInterface>       $fontFaces
     * @param array<string, FontFaceFilterInterface> $filters
     *
     * @return list<FontFaceInterface>
     */
    private function filterFonts(array $fontFaces, array $filters): array
    {
        $filteredFontFaces = [];

        foreach ($fontFaces as $filename => $fontFace) {
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filteredFontFace = $fontFace;

            foreach ($filters as $pattern => $filter) {
                if ($filteredFontFace === null) {
                    break;
                }

                if (!fnmatch($pattern, $name)) {
                    continue;
                }

                $filteredFontFace = $filter($filteredFontFace, $filename);
            }

            if ($filteredFontFace === null) {
                continue;
            }

            $filteredFontFaces[] = $filteredFontFace;
        }

        return $filteredFontFaces;
    }
}
