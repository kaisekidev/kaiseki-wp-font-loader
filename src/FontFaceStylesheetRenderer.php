<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use function array_filter;
use function array_map;
use function implode;
use function in_array;

class FontFaceStylesheetRenderer
{
    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printFrontend(array $fontFaces): void
    {
        echo $this->renderStylesheet($fontFaces, FontFaceInterface::FRONTEND);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printAdmin(array $fontFaces): void
    {
        echo $this->renderStylesheet($fontFaces, FontFaceInterface::BACKEND);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printLoginStylesheet(array $fontFaces): void
    {
        echo $this->renderStylesheet($fontFaces, FontFaceInterface::LOGIN);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printFrontendPreloadLinks(array $fontFaces): void
    {
        echo $this->renderPreloadLinks($fontFaces, FontFaceInterface::FRONTEND);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printAdminPreloadLinks(array $fontFaces): void
    {
        echo $this->renderPreloadLinks($fontFaces, FontFaceInterface::BACKEND);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     */
    public function printLoginStylesheetPreloadLinks(array $fontFaces): void
    {
        echo $this->renderPreloadLinks($fontFaces, FontFaceInterface::LOGIN);
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     * @param int                     $location
     */
    private function renderPreloadLinks(array $fontFaces, int $location): string
    {
        $fontFaces = array_filter(
            $fontFaces,
            fn(FontFaceInterface $fontFace): bool => $fontFace->preload()
                && in_array($location, $fontFace->locations(), true)
        );
        return implode(
            "\n",
            array_map(
                fn(FontFaceInterface $fontFace): string => $this->renderPreloadLink($fontFace),
                $fontFaces
            )
        );
    }

    private function renderPreloadLink(FontFaceInterface $fontFace): string
    {
        return \Safe\sprintf(
            '<link rel="preload" href="%s" as="font" type="font/%s" crossorigin>',
            $fontFace->sources()[0]->url(),
            $fontFace->sources()[0]->format()
        );
    }

    /**
     * @param list<FontFaceInterface> $fontFaces
     * @param int                     $location
     */
    private function renderStylesheet(array $fontFaces, int $location): string
    {
        $fontFaces = array_filter(
            $fontFaces,
            fn(FontFaceInterface $fontFace): bool => in_array($location, $fontFace->locations(), true)
        );
        return \Safe\sprintf(
            '<style>%s</style>',
            implode(
                "\n",
                array_map(
                    fn(FontFaceInterface $fontFace): string => $this->renderFontFace($fontFace),
                    $fontFaces
                )
            )
        );
    }

    private function renderFontFace(FontFaceInterface $fontFace): string
    {
        $fontFaceContent = [\Safe\sprintf("font-family:'%s';", $fontFace->family())];
        $fontFaceContent[] = $this->renderFontFaceSrc($fontFace);
        if ($fontFace->weight() !== '') {
            $fontFaceContent[] = \Safe\sprintf("font-weight:%s;", $fontFace->weight());
        }
        if ($fontFace->style() !== '') {
            $fontFaceContent[] = \Safe\sprintf("font-style:%s;", $fontFace->style());
        }
        if ($fontFace->display() !== '') {
            $fontFaceContent[] = \Safe\sprintf("font-display:%s;", $fontFace->display());
        }
        return \Safe\sprintf(
            "@font-face{%s}",
            implode("", $fontFaceContent)
        );
    }

    private function renderFontFaceSrc(FontFaceInterface $fontFace): string
    {
        $fontFaceSrc = [];
        foreach ($fontFace->sources() as $source) {
            $fontFaceSrc[] = \Safe\sprintf(
                'url("%s")format("%s")',
                $source->url(),
                $source->format()
            );
        }
        return \Safe\sprintf("src:%s;", implode(",", $fontFaceSrc));
    }
}
