<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FontFaceFilter;

use Kaiseki\WordPress\FontLoader\FontFaceInterface;

interface FontFaceFilterInterface
{
    public function __invoke(FontFaceInterface $fontFace, string $filename): ?FontFaceInterface;
}
