# Plugin Header Override Architecture

How **Nav Template Switcher Pro+** correctly replaces the theme header using WordPress template hooks.

---

## The Problem

When a nav profile is activated, the theme's original header/navbar must be **completely replaced** — not appended, not hidden, not regex-replaced. The profile's `header.php` must fully override the theme header.

Previous approaches (output buffering, regex removal, footer injection) are fragile and break across themes. The correct method uses WordPress's built-in template system.

---

## The Correct Method — `get_header_template` Filter

WordPress loads headers through `get_header()`, which internally calls `locate_template('header.php')`. The plugin intercepts this lookup and returns the profile's `header.php` instead.

### Implementation

```php
/* ============================================================
   HARD HEADER OVERRIDE
============================================================ */
add_filter('template_include', function($template) {

    $active = get_option('nsp_active_profile');
    if (!$active) return $template;

    $profile_path = NSP_DIR . $active;

    $header_file = null;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($profile_path)
    );

    foreach ($iterator as $fileinfo) {
        if ($fileinfo->getFilename() === 'header.php') {
            $header_file = $fileinfo->getPathname();
            break;
        }
    }

    if (!$header_file) {
        return $template;
    }

    // Hook into get_header
    add_filter('get_header_template', function($located) use ($header_file) {
        return $header_file;
    });

    return $template;

}, 20);
```

### How It Works

1. User activates a nav profile in the admin panel.
2. WordPress loads a page and reaches the theme's `get_header()` call.
3. The `template_include` filter fires first. The plugin checks for an active profile.
4. If found, it registers a `get_header_template` filter pointing to the profile's `header.php`.
5. When the theme calls `get_header()`, WordPress asks "which file should I use for the header?"
6. The plugin returns the profile's `header.php` — the theme header is never loaded.

### Why This Is Correct

| Approach | Problem |
|----------|---------|
| Regex replacement | Fragile, breaks on theme updates or markup changes |
| Output buffering | Performance cost, unreliable with page builders |
| CSS hiding | Original header still loads, causes flash of unstyled content |
| DOM injection | Duplicates content, breaks accessibility |
| **`get_header_template` filter** | **Clean WordPress API, no performance cost, works across themes** |

---

## Critical Requirement — `__DIR__` in Profile Templates

Inside every profile `header.php`, the `require_once` path **must** use `__DIR__`:

```php
// ✅ CORRECT — resolves to wherever the file actually lives
require_once __DIR__ . '/advapes-nav.php';

// ❌ WRONG — always resolves to theme folder, fails when loaded from plugin
require_once get_stylesheet_directory() . '/advapes-nav.php';
```

**Why?** When the plugin overrides the header, the profile's `header.php` runs from the plugin directory (`wp-content/plugins/...`), not the theme folder. `get_stylesheet_directory()` always points to the theme, causing a silent failure. `__DIR__` resolves to the actual file location.

All profiles in `nav-profiles/` already use `__DIR__`.

---

## What Happens at Runtime

```
1. User visits page
2. WordPress starts template loading
3. template_include filter fires → plugin registers get_header_template override
4. Theme calls get_header()
5. WordPress asks: "What file for header?"
6. Plugin answers: "/path/to/plugin/profiles/active-profile/header.php"
7. Profile header.php loads instead of theme header.php
8. Profile header.php uses __DIR__ to load advapes-nav.php from same directory
9. Navigation renders from profile files — theme header never loads
```

---

## Failsafes

- ZIP extraction validates structure on upload
- `header.php` existence is checked before activation
- PHP token validation ensures syntax is valid
- No `eval()` or dynamic code execution during upload
- If a profile breaks the page after activation, deactivate from the admin panel

---

## Theme Compatibility

This method works on all standard WordPress themes that use `get_header()`.

Some page builders (Elementor Header Builder, Divi, etc.) may bypass `get_header()` entirely. If the original navbar still shows after activation, the theme likely uses a custom header mechanism that requires a different hook.

---

## See Also

- [Installation Guide](INSTALLATION-GUIDE.md) — how to install profiles
- [README](../README.md) — repository overview and profile structure
