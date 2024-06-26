<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\Loader;

use Kaiseki\Config\Config;
use Kaiseki\WordPress\FontLoader\FontFaceFactory;
use Psr\Container\ContainerInterface;

final class PathLoaderFactory
{
    public function __invoke(
        ContainerInterface $container,
    ): PathLoader {
        $config = Config::fromContainer($container);

        /** @var list<string> $paths */
        $paths = $config->array('font_loader.path_loader.paths');
        /** @var list<int> $locations */
        $locations = $config->array('font_loader.path_loader.locations');

        return new PathLoader(
            $container->get(FontFaceFactory::class),
            $paths,
            $locations,
            $config->string('font_loader.path_loader.display'),
            $config->bool('font_loader.path_loader.without_domain'),
            $config->bool('font_loader.path_loader.include_subfolders')
        );
    }
}
