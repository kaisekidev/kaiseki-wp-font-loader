# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.0.0 - 2026-06-01

First tagged release.

### Added

- Font loading for WordPress driven by the `font_loader` config key: a `PathLoader` scans configured
  directories, `FontFilenameParser` turns filenames into `FontFace` value objects,
  `FontFaceFilterInterface`s (`PreloadFilter`, `VariableFontFilter`, `ExcludeFromLoadingFilter`) adjust
  or drop faces, and `FontFaceStylesheetRenderer` / `FontFaceRegistry` render the `@font-face` CSS for
  the configured locations. Wired by `ConfigProvider`.

### Changed

- PHP requirement is `^8.2` (PHP 8.4 is the primary target).
- Modernized the dev toolchain (PHPStan 2, PHPUnit 11 schema) and **added**
  `maglnet/composer-require-checker ^4` with a `check-deps` script; depend on
  `kaiseki/php-coding-standard: ^1.0` with the shared PHPStan config; `kaiseki/config` and
  `kaiseki/wp-hook` pinned to `^2.0`. CI now runs via the reusable workflow in `kaisekidev/.github`.
- Clarified the package `description` (it has no `inpsyde/assets` dependency).

### Fixed

- PHPStan 2 (level max), at the root: typed the `FontFace::$locations` property as `list<int>` and used
  `array_values()` in `withLocations()`; narrowed `PreloadFilter`/`VariableFontFilter::__invoke()`
  return types to `FontFaceInterface` (they never return `null`). No behaviour change.
