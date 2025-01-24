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
        return $fontFace->makeVariable()
            ->withWeight($this->weight)
            ->withStyle($this->style)
            ->withStretch($this->stretch);
    }

    public function withWeight(string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function withStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function withStretch(string $stretch): self
    {
        $this->stretch = $stretch;

        return $this;
    }
}
