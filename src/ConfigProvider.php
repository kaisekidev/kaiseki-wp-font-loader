<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

use Kaiseki\WordPress\FontLoader\FileNameParser\FontFilenameParser;
use Kaiseki\WordPress\FontLoader\FileNameParser\FontFilenameParserInterface;
use Kaiseki\WordPress\FontLoader\Loader\LoaderInterface;
use Kaiseki\WordPress\FontLoader\Loader\PathLoader;
use Kaiseki\WordPress\FontLoader\Loader\PathLoaderFactory;

final class ConfigProvider
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return [
            'font_loader' => [
                'filter' => [],
                'path_loader' => [
                    'paths' => [],
                    'locations' => [
                        FontFaceInterface::FRONTEND,
                        FontFaceInterface::BACKEND,
                        FontFaceInterface::LOGIN,
                    ],
                    'display' => 'swap',
                    'without_domain' => false,
                    'include_subfolders' => false,
                ],
            ],
            'hook' => [
                'provider' => [
                    FontFaceRegistry::class,
                ],
            ],
            'dependencies' => [
                'aliases' => [
                    FontFilenameParserInterface::class => FontFilenameParser::class,
                    LoaderInterface::class => PathLoader::class,
                ],
                'factories' => [
                    FontFaceRegistry::class => FontFaceRegistryFactory::class,
                    PathLoader::class => PathLoaderFactory::class,
                ],
            ],
        ];
    }
}
