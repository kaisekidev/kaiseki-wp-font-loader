<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FontFaceFilter;

use Kaiseki\WordPress\FontLoader\FontFaceInterface;

final class VariableFontFilter implements FontFaceFilterInterface
{
    public function __construct(
        private string $weight = '',
        private string $style = '',
        private string $stretch = ''
    ) {
    }

    public function __invoke(FontFaceInterface $fontFace, string $filename): ?FontFaceInterface
    {
        $fontFace->makeVariable();

        if ($this->weight !== '') {
            $fontFace->withWeight($this->weight);
        }

        if ($this->style !== '') {
            $fontFace->withStyle($this->style);
        }

        if ($this->stretch !== '') {
            $fontFace->withStretch($this->stretch);
        }

        return $fontFace;
    }
}
