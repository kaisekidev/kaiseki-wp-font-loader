<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\WordPress\FontLoader\FontFaceFilter\FontFaceFilterInterface;
use Kaiseki\WordPress\FontLoader\Loader\LoaderInterface;
use Kaiseki\WordPress\Hook\HookCallbackProviderInterface;

use function fnmatch;
use function pathinfo;

use const PATHINFO_FILENAME;

class FontFaceRegistry implements HookCallbackProviderInterface
{
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

    public function registerHookCallbacks(): void
    {
        add_action('wp_head', [$this, 'renderFrontendStylesheet']);
        add_action('wp_head', [$this, 'renderFrontendPreloadLinks'], 1);
        add_action('admin_head', [$this, 'renderAdminStylesheet']);
        add_action('admin_head', [$this, 'renderAdminPreloadLinks'], 1);
        add_action('login_head', [$this, 'renderLoginStylesheet']);
        add_action('login_head', [$this, 'renderLoginPreloadLinks'], 1);
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
