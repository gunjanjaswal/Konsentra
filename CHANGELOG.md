# Changelog

All notable changes to Konsentra are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2026-08-07

### Changed
- Housekeeping release: refreshed the plugin directory assets. No functional changes.

## [1.0.1] - 2026-08-06

### Fixed
- Brightened the banner message text so it reads clearly as white on the dark background.

## [1.0.0] - 2026-07-31

First public release.

### Added
- Consent banner with bottom, top, and corner placements.
- Accept all, reject all, and a per-category preferences panel.
- iOS-style toggle switches with a compact two-column layout for the categories.
- Four cookie categories: strictly necessary, functional, analytics, and marketing.
- Automatic blocking of tagged scripts until the matching category is granted (`type="text/plain"` + `data-cp-category`).
- Colour picker, custom text, and button labels in the admin.
- `[konsentra_settings]` shortcode to reopen preferences anywhere.
- Optional anonymised consent logging (hashed IP only, never a raw address).
- `konsentra:updated` JavaScript event for developers.
- Support and author links, including Ko-fi, in the settings sidebar and the plugins list row.
- Translation ready with a bundled `.pot` file.
- Clean uninstall on single site and multisite.

### Changed
- Named the plugin Konsentra: a distinctive brand up front with a descriptive tail, meeting the wordpress.org naming guideline.

### Fixed
- Distinct Plugin URI and Author URI in the plugin header.
- Escaped the privacy-page dropdown label to satisfy output-escaping checks.
- Prefixed template and uninstall variables with the plugin prefix.
- Dropped the discouraged `load_plugin_textdomain()` call; translations load automatically.

[1.0.2]: https://github.com/gunjanjaswal/Konsentra/releases/tag/v1.0.2
[1.0.1]: https://github.com/gunjanjaswal/Konsentra/releases/tag/v1.0.1
[1.0.0]: https://github.com/gunjanjaswal/Konsentra/releases/tag/v1.0.0
