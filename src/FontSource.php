<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use function pathinfo;

use const PATHINFO_EXTENSION;

class FontSource
{
    public function __construct(
        protected string $url,
        protected string $format,
    ) {
        if ($format !== '') {
            return;
        }

        $this->format = $this->getFormatFromUrl($url);
    }

    public function url(): string
    {
        return $this->url;
    }

    public function format(): string
    {
        return $this->format;
    }

    private function getFormatFromUrl(string $url): string
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);

        return match ($extension) {
            'eot' => 'embedded-opentype',
            'woff' => 'woff',
            'woff2' => 'woff2',
            'ttf' => 'truetype',
            'otf' => 'opentype',
            'svg' => 'svg',
            default => '',
        };
    }
}
