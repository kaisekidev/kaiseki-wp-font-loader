# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.1.0 - 2026-11-03

### What's Changed

* ci(changelog): mint PR token from kaiseki-doc-bot app by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/3
* ci(docs): notify kaiseki-site to rebuild on push/release by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/4
* ci(docs): harden notify-site (fork guard, Content-Type, retry) by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/5
* chore(ci): sync dependabot config and adopt shared changelog workflow by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/6
* chore(deps): bump actions/create-github-app-token from 1 to 3 in the github-actions group by @dependabot[bot] in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/7
* chore(ci): adopt notify-site reusable workflow and harden changelog caller by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/8
* chore(deps): adopt area:* labels and trim dead dependabot ignores by @wolfgangschaefer in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/9
* feat: add font faces to the iframed block editor canvas by @davidmondok in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/11

### New Contributors

* @dependabot[bot] made their first contribution in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/7
* @davidmondok made their first contribution in https://github.com/kaisekidev/kaiseki-wp-font-loader/pull/11

**Full Changelog**: https://github.com/kaisekidev/kaiseki-wp-font-loader/compare/1.0.0...1.1.0

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
