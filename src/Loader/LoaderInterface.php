<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\Loader;

use Kaiseki\WordPress\FontLoader\FontFaceInterface;

interface LoaderInterface
{
    /**
     * @return array<string, FontFaceInterface>
     */
    public function load(): array;
}
