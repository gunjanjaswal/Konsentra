# Changelog

All notable changes to Consent Pilot are documented here.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-07-31

First public release.

### Added
- Consent banner with bottom, top, and corner placements.
- Accept all, reject all, and a per-category preferences panel.
- iOS-style toggle switches with a compact two-column layout for the categories.
- Four cookie categories: strictly necessary, functional, analytics, and marketing.
- Automatic blocking of tagged scripts until the matching category is granted (`type="text/plain"` + `data-cp-category`).
- Colour picker, custom text, and button labels in the admin.
- `[consent_pilot_settings]` shortcode to reopen preferences anywhere.
- Optional anonymised consent logging (hashed IP only, never a raw address).
- `consentPilot:updated` JavaScript event for developers.
- Support and author links, including Ko-fi, in the settings sidebar and the plugins list row.
- Translation ready with a bundled `.pot` file.
- Clean uninstall on single site and multisite.

[1.0.0]: https://github.com/gunjanjaswal/Consent-Pilot-For-Website/releases/tag/v1.0.0
