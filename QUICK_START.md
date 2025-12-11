# Quick Start Guide - ADVapes Navigation

## 🎯 Your navigation isn't showing? Follow these steps!

---

## Step 1: Check Your Setup (2 minutes)

### Upload the Debug Tool

1. Download `advapes-nav-debug.php` from this repository
2. Upload it to: `/wp-content/themes/razzi-child/advapes-nav-debug.php`
3. Visit in your browser: `https://www.advapes.co.za/wp-content/themes/razzi-child/advapes-nav-debug.php`
4. **Take a screenshot** of what you see

The debug page will instantly show you:
- ✅ What's working
- ❌ What's broken
- 🔧 How to fix it

---

## Step 2: Common Quick Fixes

### Fix A: WooCommerce Not Active

**If debug page shows: "❌ WooCommerce: Not Active"**

1. Go to WordPress Admin > **Plugins**
2. Find **WooCommerce** and click **Activate**
3. If not installed, click **Add New** > Search "WooCommerce" > **Install** > **Activate**
4. Refresh your site - navigation should appear!

---

### Fix B: CSS Not Loading

**If you see navigation links but they look broken/unstyled**

**Option 1: Upload CSS File (Recommended)**
1. Download `advapes-nav.css` from repository
2. Upload to: `/wp-content/themes/razzi-child/advapes-nav.css`
3. Refresh page

**Option 2: Add CSS via Customizer**
1. Copy entire contents of `advapes-nav.css`
2. Go to **Appearance > Customize > Additional CSS**
3. Paste the CSS
4. Click **Publish**

---

### Fix C: Files in Wrong Location

**Your files MUST be here:**

```
/wp-content/themes/razzi-child/
├── header.php              ← Main header
├── advapes-nav.php         ← Navigation code
└── advapes-nav.css         ← Styles (or in Customizer)
```

**NOT here:**
- ❌ `/wp-content/plugins/`
- ❌ `/wp-content/uploads/`
- ❌ Main Razzi theme folder (must be in `razzi-child`)

**How to check:**
1. Go to **Appearance > Theme File Editor**
2. Select **Razzi Child** theme (NOT main Razzi theme)
3. Look for `header.php` and `advapes-nav.php` in file list

---

### Fix D: No Product Categories

**If debug page shows: "0 menu items"**

1. Go to **Products > Categories**
2. Create categories with these exact slugs:
   - `disposables` (name: Disposables)
   - `pod-systems-kits` (name: Pod Systems & Kits)
   - `dl-hardware` (name: Vape Hardware)
   - `dl-liquid` (name: DL E-Liquids)
   - `nic-salts` (name: MTL & Nic Salts)
3. Add some products to these categories
4. Visit `/wp-json/advapes/v1/nav?refresh=true` to refresh cache
5. Check your site again

---

## Step 3: Verify It's Working

### What You Should See

**Desktop:**
- Navigation bar below main header
- Black background with red bottom border
- Menu items: Deals, Disposables, Pod Disposables, etc.
- Hover over items to see dropdowns
- Brands menu between "Nic Alternatives" and "Support"

**Mobile:**
- Hamburger menu icon
- Tap to expand full menu
- Nested subcategories visible

### How to Test Brands

1. **Main Brands Menu:**
   - Hover over "Brands" in navigation
   - Should see dropdown with top brands
   - Each brand shows product count (e.g., "20+ products")

2. **Brands in Category Dropdowns:**
   - Hover over "Pod Systems & Kits"
   - Should see subcategories THEN brands at bottom
   - Example: "Caliburn - 27+ products"
   - Same for "DL E-Liquids" and "MTL & Nic Salts"

---

## Step 4: Still Not Working?

### Enable Debug Mode

1. Edit `wp-config.php`
2. Add before "That's all, stop editing!":

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

3. Check `/wp-content/debug.log` for errors mentioning "ADVapes Nav"

### Check REST API

Visit: `https://www.advapes.co.za/wp-json/advapes/v1/nav`

This shows:
- Navigation structure (JSON)
- Debug information
- Whether cache is working

---

## Common Error Messages

### "ADVapes Nav: WooCommerce is not active"
→ **Solution:** Install and activate WooCommerce plugin

### "ADVapes Nav: No brand taxonomy detected"
→ **Normal:** This is OK if you haven't set up brands yet
→ **To add brands:** Products > Attributes > Create "Brand" attribute

### "ADVapes Nav: No WooCommerce categories found"
→ **Solution:** Create product categories (see Fix D above)

---

## What Each File Does

| File | Purpose | Required? |
|------|---------|-----------|
| `header.php` | Main header template, loads navigation | ✅ Yes |
| `advapes-nav.php` | Navigation engine and logic | ✅ Yes |
| `advapes-nav.css` | Styling for navigation bar | ⚠️ Yes (or add to Customizer) |
| `advapes-nav-debug.php` | Diagnostic tool | ⚠️ Helpful for troubleshooting |
| `TROUBLESHOOTING.md` | Detailed troubleshooting guide | ℹ️ Reference only |

---

## Screenshots to Help You

### 1. Where to Upload Files
Using **cPanel File Manager** or **FTP**:
```
public_html/
└── wp-content/
    └── themes/
        └── razzi-child/   ← Upload here
            ├── header.php
            ├── advapes-nav.php
            └── advapes-nav.css
```

### 2. Theme File Editor Location
```
WordPress Admin
└── Appearance
    └── Theme File Editor
        └── Select theme: "Razzi Child"
            └── Look for files in right sidebar
```

### 3. Where to Add CSS (Customizer Method)
```
WordPress Admin
└── Appearance
    └── Customize
        └── Additional CSS
            └── Paste CSS here
                └── Click "Publish"
```

---

## Success Checklist

After following this guide, you should have:

- [x] Debug page showing all green checkmarks
- [x] Navigation bar visible on your site
- [x] Dropdowns working on hover
- [x] "Brands" menu showing between "Nic Alternatives" and "Support"
- [x] Brand product counts displaying (if brands set up)
- [x] Mobile menu working (if testing on phone)

---

## Need More Help?

1. **Run debug page** and screenshot the results
2. **Check debug.log** for error messages
3. **Visit REST API** to see navigation data
4. **Read TROUBLESHOOTING.md** for detailed solutions

---

## Pro Tips

- **Clear cache** after making changes: `/wp-json/advapes/v1/nav?refresh=true`
- **Test on different devices** - Desktop and mobile may show different issues
- **Check browser console** (F12) for JavaScript errors
- **Disable other plugins temporarily** to rule out conflicts

---

## Version Info

- **Navigation System:** v3.1.1
- **Last Updated:** 2025-12-11
- **Requires:** WordPress 5.0+, WooCommerce 4.0+, PHP 7.4+

---

**🎉 Once you see the navigation working, you're done! The brands will automatically update as you add products.**
