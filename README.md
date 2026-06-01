# kaiseki/wp-font-loader

Register and render `@font-face` stylesheets in WordPress, with preload, variable-font and exclusion filters.

Point it at a directory of font files; it parses each filename into a `FontFace` (family, weight,
style, …), applies any configured filters, and renders the `@font-face` CSS for the chosen locations
(front end, admin, login). Wired through `ConfigProvider` and the `font_loader` config key.

## Installation

```bash
composer require kaiseki/wp-font-loader
```

Requires PHP 8.2 or newer.

## Usage

Register `ConfigProvider` with your laminas-style config aggregator and configure the `font_loader`
key:

```php
use Kaiseki\WordPress\FontLoader\FontFaceFilter\PreloadFilter;
use Kaiseki\WordPress\FontLoader\FontFaceFilter\VariableFontFilter;

return [
    'font_loader' => [
        // FontFaceFilterInterface instances/class-strings applied to every parsed face.
        'filter' => [
            PreloadFilter::class,
            new VariableFontFilter(weight: '100 900'),
        ],
        'path_loader' => [
            // Directories scanned for font files.
            'paths' => [
                get_stylesheet_directory() . '/assets/fonts',
            ],
            // Where the @font-face CSS is printed.
            'locations' => [
                FontFaceInterface::FRONTEND,
                FontFaceInterface::BACKEND,
                FontFaceInterface::LOGIN,
            ],
            'display'            => 'swap',
            'without_domain'     => false,
            'include_subfolders' => false,
        ],
    ],
    'hook' => [
        'provider' => [
            FontFaceRegistry::class,
        ],
    ],
];
```

`ConfigProvider` wires `FontFaceRegistry` (the hook provider), a `PathLoader` (`LoaderInterface`) that
scans the configured paths, and a `FontFilenameParser` (`FontFilenameParserInterface`). Filenames are
parsed into `FontFace` value objects; the configured `FontFaceFilterInterface`s can adjust each face
(`PreloadFilter`, `VariableFontFilter`) or drop it (`ExcludeFromLoadingFilter`, by returning `null`).

## Development

```bash
composer install
composer check   # check-deps, cs-check, phpstan
```

## License

MIT — see [LICENSE](LICENSE).
