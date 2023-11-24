<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FileNameParser;

interface FontFilenameParserInterface
{
    /**
     * Returns the font family name.
     *
     * @param string $filename
     * @param string $directory
     *
     * @return string
     */
    public function getFamily(string $filename, string $directory): string;

    /**
     * Returns the font weight.
     *
     * @param string $filename
     * @param string $directory
     *
     * @return string
     */
    public function getWeight(string $filename, string $directory): string;

    /**
     * Returns the font style.
     *
     * @param string $filename
     * @param string $directory
     *
     * @return string
     */
    public function getStyle(string $filename, string $directory): string;
}
