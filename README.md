<div align="center">

# 🛡️ Consent Pilot

### A privacy-first GDPR cookie consent banner for WordPress

Give visitors a real choice about cookies, and keep tracking scripts on hold until they say yes.

![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-21759B?style=for-the-badge&logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL%20v2-3DA639?style=for-the-badge&logo=gnu&logoColor=white)
![Version](https://img.shields.io/badge/Version-1.0.0-2F80ED?style=for-the-badge)
![No Dependencies](https://img.shields.io/badge/Dependencies-none-FF6B6B?style=for-the-badge)

</div>

---

## ✨ Why Consent Pilot

Most consent plugins are bloated, hook into a paid service, or quietly load a tracker before anyone clicks a thing. Consent Pilot does none of that. It's small, it runs entirely on your own site, and it actually blocks scripts until the visitor opts in, which is the part that matters for GDPR.

No external calls. No account. No upsell wall in front of the basics.

## 🚀 Features

| | |
|---|---|
| 🍪 **Granular categories** | Strictly necessary, functional, analytics, and marketing, each toggled on its own |
| 🚫 **Real script blocking** | Tagged scripts stay dormant until the matching category is granted |
| 🎨 **Fully themeable** | Colours, text, and button labels, all with a live WordPress colour picker |
| 📍 **Flexible placement** | Bottom bar, top bar, or a tidy corner box |
| ↩️ **Reopen anytime** | A shortcode lets visitors change their mind whenever they want |
| 🔒 **Privacy by default** | Optional consent logging stores a hashed IP only, never the raw address |
| 🌍 **Translation ready** | Every string is localised, with a bundled `.pot` file |
| 🧹 **Clean uninstall** | Removes everything it created, single site or multisite |

## 📦 Installation

**From your dashboard**

1. Go to **Plugins → Add New** and upload the plugin zip.
2. Activate it.
3. Head to **Settings → Consent Pilot** to make it yours.

**Manually**

```bash
cd wp-content/plugins
git clone https://github.com/gunjanjaswal/Consent-Pilot-For-Website.git consent-pilot
```

Then activate **Consent Pilot** from the Plugins screen.

## ⚙️ How script blocking works

Any script you want gated behind consent gets a `text/plain` type and a category tag. That stops the browser from running it. Once the visitor grants that category, Consent Pilot swaps in a real script tag and lets it fire.

```html
<!-- Held back until "analytics" is granted -->
<script type="text/plain" data-cp-category="analytics" src="https://example.com/analytics.js"></script>

<!-- Works for inline scripts too -->
<script type="text/plain" data-cp-category="marketing">
    // your marketing pixel here
</script>
```

Categories you can use: `functional`, `analytics`, `marketing`. Strictly necessary cookies always run.

## 🧩 Shortcode

Drop this anywhere (a footer widget or your privacy page is a good spot) to give people a way back to their preferences:

```
[consent_pilot_settings]
```

Add a custom label if you like:

```
[consent_pilot_settings label="Cookie settings"]
```

## 🧑‍💻 Developer hook

Consent Pilot fires a DOM event every time consent changes, so your own scripts can react to it:

```js
document.addEventListener( 'consentPilot:updated', function ( event ) {
    // event.detail is a map of category -> true/false
    if ( event.detail.analytics ) {
        // start your analytics
    }
} );
```

## 📂 Project structure

```
consent-pilot/
├── consent-pilot.php              # Main plugin file
├── uninstall.php                  # Clean removal
├── readme.txt                     # WordPress.org readme
├── includes/
│   ├── class-consent-pilot.php            # Bootstrap
│   ├── class-consent-pilot-settings.php   # Defaults & sanitization
│   └── class-consent-pilot-frontend.php   # Banner, assets, logging
├── admin/
│   ├── class-consent-pilot-admin.php
│   ├── views/settings-page.php
│   ├── css/admin.css
│   └── js/admin.js
├── public/
│   ├── css/consent-pilot.css
│   └── js/consent-pilot.js
└── languages/
    └── consent-pilot.pot
```

## 🔐 A note on compliance

Consent Pilot handles the consent side of things: the banner, the categories, and holding scripts back until someone agrees. Real compliance also depends on your privacy policy, how you handle data, and which services you use. Think of this as one solid piece of the puzzle, not the whole thing.

## 🗺️ Roadmap

- [ ] Consent log viewer and CSV export in the admin
- [ ] Auto-scan for common third-party scripts
- [ ] Per-region behaviour (show the banner only where it's required)
- [ ] Google Consent Mode v2 signals
- [ ] Import/export of settings

## 📝 Changelog

### 1.0.0
- First public release
- Consent banner with bottom, top, and corner placements
- Accept all, reject all, and a per-category preferences panel
- Four cookie categories: necessary, functional, analytics, marketing
- Automatic blocking of tagged scripts until consent is granted
- Colour picker, custom text, and button labels in the admin
- `[consent_pilot_settings]` shortcode to reopen preferences
- Optional anonymised consent logging (hashed IP only)
- `consentPilot:updated` JavaScript event for developers
- Translation ready with a bundled `.pot` file
- Clean uninstall on single site and multisite

## 🤝 Contributing

Issues and pull requests are welcome. If you find a bug or have an idea, open an issue and let's talk it through.

## 📄 License

Released under the **GPLv2 or later** license. Do what you like with it, just keep it free.

## 👋 Author

**Gunjan Jaswal**

[![Website](https://img.shields.io/badge/Website-gunjanjaswal.me-2F80ED?style=flat-square&logo=google-chrome&logoColor=white)](https://www.gunjanjaswal.me)
[![Email](https://img.shields.io/badge/Email-hello@gunjanjaswal.me-EA4335?style=flat-square&logo=gmail&logoColor=white)](mailto:hello@gunjanjaswal.me)
[![Ko-fi](https://img.shields.io/badge/Ko--fi-Support-FF5E5B?style=flat-square&logo=ko-fi&logoColor=white)](https://ko-fi.com/gunjanjaswal)

---

<div align="center">

If Consent Pilot saved you some time, a ⭐ on the repo means a lot.

</div>
