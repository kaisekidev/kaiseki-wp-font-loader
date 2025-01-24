<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\Loader;

use Kaiseki\WordPress\FontLoader\FontFaceFactory;
use Kaiseki\WordPress\FontLoader\FontFaceInterface;
use RuntimeException;
use Throwable;

use function array_filter;
use function array_map;
use function array_merge;
use function get_stylesheet_directory;
use function get_stylesheet_directory_uri;
use function in_array;
use function is_dir;
use function is_string;
use function parse_url;
use function pathinfo;
use function scandir;
use function str_ends_with;
use function str_replace;
use function trailingslashit;

use const DIRECTORY_SEPARATOR;
use const PATHINFO_EXTENSION;
use const PATHINFO_FILENAME;
use const PHP_URL_PATH;

final class PathLoader implements LoaderInterface
{
    private string $stylesheetDirectory;
    private string $stylesheetDirectoryUri;

    /**
     * @param FontFaceFactory $fontFaceBuilder
     * @param list<string>    $paths
     * @param list<int>       $locations
     * @param string          $display
     * @param bool            $withoutDomain
     * @param bool            $includeSubfolders
     */
    public function __construct(
        private readonly FontFaceFactory $fontFaceBuilder,
        private readonly array $paths,
        private readonly array $locations = [
            FontFaceInterface::FRONTEND,
            FontFaceInterface::BACKEND,
            FontFaceInterface::LOGIN,
        ],
        private readonly string $display = 'swap',
        private readonly bool $withoutDomain = false,
        private readonly bool $includeSubfolders = false
    ) {
        $this->stylesheetDirectory = get_stylesheet_directory();
        $this->stylesheetDirectoryUri = get_stylesheet_directory_uri();
    }

    public function load(): array
    {
        $fontFaces = [];

        foreach ($this->paths as $path) {
            $fontFaces = array_merge($fontFaces, $this->loadFromPath($path));
        }

        return $fontFaces;
    }

    /**
     * @param string $path
     *
     * @return array<string, FontFaceInterface>
     */
    private function loadFromPath(string $path): array
    {
        $fontUrls = array_map(fn($urls) => array_merge(
            array_filter($urls, fn($url) => str_ends_with($url, '.woff2')),
            array_filter($urls, fn($url) => str_ends_with($url, '.woff')),
            array_filter($urls, fn($url) => str_ends_with($url, '.ttf')),
            array_filter($urls, fn($url) => str_ends_with($url, '.otf')),
            array_filter($urls, fn($url) => str_ends_with($url, '.svg')),
            array_filter($urls, fn($url) => str_ends_with($url, '.eot')),
        ), $this->getFontUrls($path));

        $fontFaces = [];

        foreach ($fontUrls as $filename => $urls) {
            $fontFaces[$filename] = $this->fontFaceBuilder
                ->createFromUrls($urls)
                ->withDisplay($this->display)
                ->withLocations(...$this->locations);
        }

        return $fontFaces;
    }

    /**
     * @param string $path
     *
     * @return array<string, list<string>>
     */
    private function getFontUrls(string $path): array
    {
        $fontPaths = $this->getFontPaths($path);

        $fontUrls = [];

        foreach ($fontPaths as $filename => $paths) {
            $fontUrls[$filename] = array_map(function ($path) {
                if (!$this->withoutDomain) {
                    return $this->pathToUrl($path);
                }
                $urlPath = parse_url($this->pathToUrl($path), PHP_URL_PATH);
                if (!is_string($urlPath)) {
                    throw new RuntimeException('Could not parse URL path');
                }

                return $urlPath;
            }, $paths);
        }

        return $fontUrls;
    }

    /**
     * @param string $path
     *
     * @return array<string, list<string>>
     */
    private function getFontPaths(string $path): array
    {
        try {
            $files = scandir($path);
        } catch (Throwable $e) {
            throw new RuntimeException('Could not scan directory ' . $path);
        }

        $fonts = [];

        if ($files === false) {
            return $fonts;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $file;

            if (is_dir($fullPath) && $this->includeSubfolders) {
                $fonts = array_merge($fonts, $this->getFontPaths($fullPath));

                continue;
            }

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            if (!in_array($extension, ['woff', 'woff2', 'ttf', 'otf', 'svg', 'eot'], true)) {
                continue;
            }

            $filename = pathinfo($file, PATHINFO_FILENAME);

            $fonts[$filename][] = $fullPath;
        }

        return $fonts;
    }

    private function pathToUrl(string $path): string
    {
        $pathInStylesheetDirectory = str_replace(
            trailingslashit($this->stylesheetDirectory),
            '',
            $path
        );

        return trailingslashit($this->stylesheetDirectoryUri) . $pathInStylesheetDirectory;
    }
}
