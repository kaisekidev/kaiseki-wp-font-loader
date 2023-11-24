<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\WordPress\FontLoader\FileNameParser\FontFilenameParserInterface;

use function array_map;
use function count;
use function pathinfo;

use const PATHINFO_DIRNAME;
use const PATHINFO_FILENAME;

class FontFaceFactory
{
    public function __construct(
        private FontFilenameParserInterface $filenameParser,
    ) {
    }

    /**
     * @param list<string> $urls
     *
     * @return FontFace
     */
    public function createFromUrls(array $urls): FontFace
    {
        if (count($urls) === 0) {
            throw new Exception\InvalidArgumentException('No URLs given.');
        }
        $filename = pathinfo($urls[0], PATHINFO_FILENAME);
        $directory = pathinfo($urls[0], PATHINFO_DIRNAME);
        return new FontFace(
            family: $this->filenameParser->getFamily($filename, $directory),
            sources: array_map(fn ($url) => new FontSource($url, ''), $urls),
            weight: $this->filenameParser->getWeights($filename, $directory),
            style: $this->filenameParser->getStyles($filename, $directory),
        );
    }
}
