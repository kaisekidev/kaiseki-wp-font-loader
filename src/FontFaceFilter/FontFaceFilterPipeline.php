<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FontFaceFilter;

use Kaiseki\WordPress\FontLoader\FontFaceInterface;

final class FontFaceFilterPipeline implements FontFaceFilterInterface
{
    /** @var array<FontFaceFilterInterface> */
    private array $filter;

    public function __construct(FontFaceFilterInterface ...$filter)
    {
        $this->filter = $filter;
    }

    public function __invoke(FontFaceInterface $fontFace, string $filename): ?FontFaceInterface
    {
        foreach ($this->filter as $filter) {
            if ($fontFace === null) {
                return null;
            }
            $fontFace = ($filter)($fontFace, $filename);
        }

        return $fontFace;
    }
}
