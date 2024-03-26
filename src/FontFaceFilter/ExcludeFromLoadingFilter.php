<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FontFaceFilter;

use Kaiseki\WordPress\FontLoader\FontFaceInterface;

final class PreloadFilter implements FontFaceFilterInterface
{
    public function __invoke(FontFaceInterface $fontFace, string $filename): ?FontFaceInterface
    {
        return null;
    }
}
