<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\Config\Config;
use Kaiseki\WordPress\FontLoader\FontFaceFilter\FontFaceFilterInterface;
use Kaiseki\WordPress\FontLoader\FontFaceFilter\FontFaceFilterPipeline;
use Kaiseki\WordPress\FontLoader\Loader\LoaderInterface;
use Psr\Container\ContainerInterface;

use function is_array;

/**
 * @phpstan-type FilterType class-string<FontFaceFilterInterface>|FontFaceFilterInterface
 */
final class FontFaceRegistryFactory
{
    public function __invoke(
        ContainerInterface $container,
    ): FontFaceRegistry {
        $config = Config::fromContainer($container);
        /** @var array<string, FilterType|list<FilterType>> $filterConfig */
        $filterConfig = $config->array('font_loader.filter');
        /** @var array<string, FontFaceFilterInterface> $filters */
        $filters = [];
        foreach ($filterConfig as $key => $filter) {
            $list = Config::initClassMap($container, is_array($filter) ? $filter : [$filter]);
            $filters[$key] = new FontFaceFilterPipeline(...$list);
        }

        return new FontFaceRegistry(
            $container->get(LoaderInterface::class),
            $filters
        );
    }
}
