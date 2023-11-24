<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\Config\Config;
use Kaiseki\WordPress\FontLoader\FontFaceFilter\FontFaceFilterInterface;
use Kaiseki\WordPress\FontLoader\Loader\LoaderInterface;
use Psr\Container\ContainerInterface;

final class FontFaceRegistryFactory
{
    public function __invoke(
        ContainerInterface $container,
    ): FontFaceRegistry {
        $config = Config::get($container);
        /** @var array<string, FontFaceFilterInterface> $filter */
        $filter = $config->array('font_loader/filter', []);

        return new FontFaceRegistry(
            $container->get(LoaderInterface::class),
            $filter
        );
    }
}
