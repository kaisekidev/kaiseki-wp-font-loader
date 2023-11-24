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
    public function getWeights(string $filename, string $directory): string;

    /**
     * Returns the font style.
     *
     * @param string $filename
     * @param string $directory
     *
     * @return string
     */
    public function getStyles(string $filename, string $directory): string;
}
