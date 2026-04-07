# ADVapes Navigation Bar

![Version](https://img.shields.io/badge/version-5.0-blue.svg)
![Status](https://img.shields.io/badge/status-stable-green.svg)
[![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/woocommerce-4.0%2B-purple.svg)](https://woocommerce.com/)

Nav bar profiles for the **Nav Template Switcher Pro+** plugin. Download a profile folder, zip it, and upload it through the plugin.

---

## 🚀 Quick Start — Using with Nav Template Switcher Pro+

### 1. Download this repo

Click **Code → Download ZIP** on GitHub, or clone it:

```bash
git clone https://github.com/go0ph/ADVAPES-NAV-BAR.git
```

### 2. Pick a profile

Inside `nav-profiles/` you'll find ready-to-use folders:

| Folder | Description |
|--------|-------------|
| `advapes-nav-v2/` | **Current production** — V2 nav with mobile flyout drawer |
| `advapes-nav-v3/` | **Brand-first** — V3 restructured navigation |

### 3. Zip the folder & upload

1. Navigate into `nav-profiles/`
2. **Zip the folder** you want (e.g. right-click `advapes-nav-v2` → Compress)
3. In WordPress admin go to **Nav Template Switcher Pro+**
4. **Upload** the ZIP
5. **Activate** the profile

### 4. Preview (optional)

Before activating, preview any profile via URL:

```
https://yourdomain.com/?cts_nav_preview=advapes-nav-v2
```

Only admins can see previews.

---

## 📁 Repository Structure

```
ADVAPES-NAV-BAR/
│
├── nav-profiles/                  ← 🔥 READY-TO-USE PLUGIN PROFILES
│   ├── advapes-nav-v2/            ← V2: Current production nav
│   │   ├── header.php             ← REQUIRED — header template
│   │   ├── advapes-nav.php        ← nav logic (dynamic categories + brands)
│   │   └── advapes-nav.css        ← styling (dark theme, mobile drawer)
│   │
│   └── advapes-nav-v3/            ← V3: Brand-first restructure
│       ├── header.php             ← REQUIRED — header template
│       ├── advapes-nav.php        ← nav logic (brand-first structure)
│       └── advapes-nav.css        ← styling (shared with V2)
│
├── preview/
│   └── preview.html               ← open in browser to see the nav design
│
├── docs/                          ← full documentation
│   ├── INSTALLATION-GUIDE.md      ← step-by-step install guide
│   ├── PROJECT-OVERVIEW.md        ← quick project overview
│   ├── README-V3.md               ← V3 documentation
│   ├── QUICKSTART-V3.md           ← V3 quick start
│   ├── V3-COMPARISON.md           ← V2 vs V3 comparison
│   ├── FILES-TO-DOWNLOAD.txt      ← legacy download checklist
│   └── REPOSITORY-UPDATE-SUMMARY.md
│
├── archive/                       ← old versions & changelogs
│   ├── v1/                        ← V1 nav files (for rollback)
│   └── changelogs/                ← historical change logs
│
├── README.md                      ← this file
└── .gitignore
```

---

## ⚠️ Plugin Compatibility — `__DIR__` Requirement

When building or customizing nav profiles for **Nav Template Switcher Pro+**, your `header.php` **must** use `__DIR__` to load `advapes-nav.php`:

```php
// ✅ CORRECT — works from any directory (theme or plugin profile)
require_once __DIR__ . '/advapes-nav.php';

// ❌ WRONG — only works inside the theme folder
require_once get_stylesheet_directory() . '/advapes-nav.php';
```

**Why?** The plugin loads profiles from its own directory (`wp-content/plugins/...`), not the theme folder. Using `get_stylesheet_directory()` forces the path to the theme, causing a silent failure. `__DIR__` resolves to wherever the file actually lives — theme folder, plugin profile folder, or anywhere else.

This also applies to CSS loading inside `advapes-nav.php` — use `__DIR__` for `file_exists()` checks:

```php
$css_file = __DIR__ . '/advapes-nav.css';
```

All profiles in `nav-profiles/` already use `__DIR__`. If you create a custom profile, make sure yours does too.

---

## 📌 Expected ZIP Profile Structure

When uploading to **Nav Template Switcher Pro+**, your ZIP must contain:

```
header.php                 ← REQUIRED
advapes-nav.php            ← recommended (nav logic)
advapes-nav.css            ← optional (styling)
nav.js                     ← optional (JavaScript)
images/                    ← optional (assets)
```

The folders in `nav-profiles/` are already structured this way — just zip and upload.

---

## 🔥 How the Plugin Works

| Action | What happens |
|--------|-------------|
| **📌 Upload** | Upload a ZIP with `header.php` and optional CSS/JS |
| **📌 Activate** | Choose any profile and activate it to replace the site header/nav |
| **📌 Preview** | Preview via `?cts_nav_preview=profile-name` (admins only) |
| **📌 Rollback** | Activate a previous profile to revert |
| **📌 Rename/Delete** | Manage profiles from admin safely |
| **📌 Logging** | Last 50 actions logged and displayed |

---

## 📦 What's in Each Profile

### V2 — `nav-profiles/advapes-nav-v2/`

The current production navigation. Features:

- 10 fixed parent menu items (Deals, Disposables, Pod Systems, Brands, etc.)
- Dynamic dropdowns populated from WooCommerce categories & brands
- Mobile flyout drawer with accordion behavior
- 30-second cache with auto-invalidation
- Dark theme (`#050507`) with red accent (`#e11d2f`)

### V3 — `nav-profiles/advapes-nav-v3/`

Brand-first restructured navigation. Changes from V2:

- Brands promoted to 2nd position in menu
- Devices & Liquids categories consolidated
- Top-level items reduced from 10 → 7
- DL/MTL terminology removed for clarity

See [docs/README-V3.md](docs/README-V3.md) and [docs/V3-COMPARISON.md](docs/V3-COMPARISON.md) for full details.

---

## 🎨 Preview Before Installing

1. Open `preview/preview.html` in any browser
2. See the nav design on desktop and mobile — no server needed

---

## 📋 Requirements

- **WordPress** 5.0+
- **WooCommerce** 4.0+
- **PHP** 7.4+
- **Theme** Razzi (parent + child theme)
- **Plugin** Nav Template Switcher Pro+ (v1.0+)

---

## 📖 Documentation

- [Installation Guide](docs/INSTALLATION-GUIDE.md)
- [Project Overview](docs/PROJECT-OVERVIEW.md)
- [V3 Quick Start](docs/QUICKSTART-V3.md)
- [V3 Documentation](docs/README-V3.md)
- [V2 vs V3 Comparison](docs/V3-COMPARISON.md)

---

## 📋 Version History

| Version | Date | Changes |
|---------|------|---------|
| V2 (4.0.1) | Apr 2026 | `__DIR__` fix for Nav Template Switcher Pro+ plugin compatibility |
| V3 (5.0.1) | Apr 2026 | `__DIR__` fix for Nav Template Switcher Pro+ plugin compatibility |
| V2 (4.0) | Dec 2025 | Mobile flyout drawer, expanded desktop dropdowns, 30s cache |
| V3 (5.0) | Dec 2025 | Brand-first restructure, consolidated menus |
| V1 (3.1) | Dec 2025 | Original hybrid nav (archived in `archive/v1/`) |

See `archive/changelogs/` for detailed history.

## License

Use freely for your ADVapes site.
