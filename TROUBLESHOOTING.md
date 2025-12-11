# ADVapes Navigation Troubleshooting Guide

## Problem: "Brands not showing, nothing is displaying"

This guide will help you diagnose and fix issues with the ADVapes navigation bar.

---

## Quick Checklist

Before diving deep, check these essentials:

- [ ] **WooCommerce is installed and active**
- [ ] **Files are in the correct location** (`/wp-content/themes/razzi-child/`)
- [ ] **CSS is loaded** (either as file or via Theme Customizer)
- [ ] **WordPress debug log is enabled** (to see error messages)

---

## Step 1: Verify File Installation

### Required Files

Your theme directory should have these files:

```
/wp-content/themes/razzi-child/
├── header.php              ← Main header file
├── advapes-nav.php         ← Navigation engine
└── advapes-nav.css         ← Styles (optional if using Customizer)
```

### How to Check

1. Log into your WordPress admin
2. Go to **Appearance > Theme File Editor**
3. Select **Razzi Child** theme
4. Look for `header.php` and `advapes-nav.php` in the file list

**If files are missing:** Upload them via FTP or cPanel File Manager to the theme directory.

---

## Step 2: Check WooCommerce Status

The navigation **requires WooCommerce** to be active.

### How to Check

1. Go to **Plugins > Installed Plugins**
2. Find "WooCommerce" in the list
3. Make sure it shows "Active" (not "Inactive")

**If WooCommerce is not installed:**
1. Go to **Plugins > Add New**
2. Search for "WooCommerce"
3. Click "Install Now" then "Activate"

### What if WooCommerce is Active but Navigation Still Doesn't Show?

If WooCommerce is active but you see a basic fallback menu (just Home, Deals, Brands, Support), then the issue is with category detection. Skip to **Step 4**.

---

## Step 3: Check CSS Loading

The navigation needs CSS to display properly.

### Option A: CSS File Method

If you uploaded `advapes-nav.css` to your theme folder:

1. The file should be at: `/wp-content/themes/razzi-child/advapes-nav.css`
2. It will automatically load if it exists
3. Check browser Developer Tools (F12) > Network tab for `advapes-nav.css`

### Option B: Theme Customizer Method

If you're adding CSS manually via Customizer:

1. Go to **Appearance > Customize**
2. Find **Additional CSS** section
3. Paste the entire contents of `advapes-nav.css` there
4. Click **Publish**

**Which method should you use?**
- **File method** is cleaner and loads faster
- **Customizer method** works if you can't upload files

---

## Step 4: Use the Debug Tool

We've included a debug page to help diagnose issues.

### How to Access

1. Upload `advapes-nav-debug.php` to your theme directory
2. Visit in your browser: `https://www.advapes.co.za/wp-content/themes/razzi-child/advapes-nav-debug.php`
3. Log in as admin if prompted

### What the Debug Page Shows

- ✅ WooCommerce status
- ✅ Brand taxonomy detection
- ✅ CSS file location
- ✅ Navigation structure
- ✅ All product categories
- ✅ Recommendations for fixes

**Screenshot what you see and this will help identify the exact problem.**

---

## Step 5: Check WordPress Debug Log

Enable WordPress debugging to see error messages.

### Enable Debug Mode

Edit `wp-config.php` and add:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

### View Debug Log

1. Connect via FTP/cPanel
2. Go to `/wp-content/debug.log`
3. Look for lines containing "ADVapes Nav"

Common log messages:
- `ADVapes Nav: WooCommerce is not active` → Install WooCommerce
- `ADVapes Nav: No brand taxonomy detected` → Normal if you don't have brands set up yet
- `ADVapes Nav: Successfully found categories: ...` → Good! Categories detected

---

## Common Issues & Solutions

### Issue 1: Nothing Displays At All

**Symptoms:**
- No navigation bar visible
- Just see empty space where nav should be

**Causes & Fixes:**

1. **Files not loaded**
   - Check `header.php` contains: `require_once get_stylesheet_directory() . '/advapes-nav.php';`
   - Make sure both files are in the correct directory

2. **PHP error preventing execution**
   - Check debug.log for PHP errors
   - Make sure PHP version is 7.4 or higher

3. **CSS not loaded**
   - Navigation exists but is invisible without CSS
   - Add CSS via Customizer or upload CSS file

### Issue 2: Only Fallback Menu Shows (Home, Deals, Brands, Support)

**Symptoms:**
- Navigation shows but only 4 basic links
- No dropdowns or detailed categories
- HTML comment in page source: `ADVapes Nav Debug: No structure available`

**Causes & Fixes:**

1. **WooCommerce not active**
   - Install and activate WooCommerce plugin

2. **No product categories**
   - Go to **Products > Categories**
   - Create categories with these slugs:
     - `disposables`
     - `pod-disposables`
     - `pod-systems-kits`
     - `dl-hardware` or `vape-hardware`
     - `dl-liquid` or `dl-liquids`
     - `nic-salts` or `nic-salts-mtl-liquids`
     - `nicotine-alternatives`

3. **Cache issue**
   - Clear navigation cache via debug page
   - Or visit: `/wp-json/advapes/v1/nav?refresh=true`

### Issue 3: Brands Menu Missing

**Symptoms:**
- Most menus show but "Brands" missing
- Or Brands shows but no dropdown

**Causes & Fixes:**

1. **No brand taxonomy**
   - This is normal if you haven't set up brands yet
   - The Brands menu will still show, just without the dropdown
   - To add brands:
     - Go to **Products > Attributes**
     - Create attribute named "Brand" or "Brands"
     - Add brand terms
     - Assign brands to products

2. **No products with brands**
   - Even if brand taxonomy exists, if no products have brands assigned, the dropdown will be empty
   - Assign brands to at least a few products to test

### Issue 4: Brands Not Showing in Category Dropdowns

**Symptoms:**
- Main Brands menu works
- But brands don't show in Pod Systems, DL E-Liquids, or MTL & Nic Salts dropdowns

**Causes & Fixes:**

1. **No brands for that category**
   - Brands only show if products in that category have brand assigned
   - Example: Pod Systems dropdown only shows brands that have products in the "pod-systems-kits" category

2. **Products not in correct category**
   - Make sure products with brands are assigned to the right categories
   - Check **Products > All Products** and verify categories

3. **Cache not refreshed**
   - Cache refreshes automatically when products change
   - But you can force refresh: `/wp-json/advapes/v1/nav?refresh=true`

### Issue 5: CSS Styling Issues

**Symptoms:**
- Navigation shows but looks broken
- Text overlapping or wrong colors
- Dropdowns don't appear on hover

**Causes & Fixes:**

1. **CSS not loaded**
   - Check browser Developer Tools (F12) > Console for CSS errors
   - Verify CSS file exists or Customizer CSS is saved

2. **CSS conflicts with theme**
   - Theme CSS may override navigation styles
   - Inspect element (F12) to see which styles are applied
   - May need to add `!important` to some rules

3. **Old cached CSS**
   - Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
   - Clear WordPress cache if using cache plugin

---

## Advanced Debugging

### View Navigation Data via REST API

Visit this URL in your browser:

```
https://www.advapes.co.za/wp-json/advapes/v1/nav
```

This returns JSON with:
- Full navigation structure
- Debug information
- WooCommerce status
- Brand taxonomy status

**Force refresh cache:**
```
https://www.advapes.co.za/wp-json/advapes/v1/nav?refresh=true
```

### Check Admin Notice

When logged in as admin, check the top of WordPress admin pages for an "ADVapes Navigation Status" notice. This will show:
- WooCommerce status
- Brand taxonomy detection
- Number of menu items
- CSS file status

---

## Getting Help

If you've tried everything and still have issues:

1. **Take a screenshot** of the debug page (`advapes-nav-debug.php`)
2. **Check debug.log** for error messages starting with "ADVapes Nav"
3. **Note your setup:**
   - WordPress version
   - WooCommerce version
   - PHP version
   - How CSS is loaded (file or Customizer)
4. **Create an issue** with all the above information

---

## Quick Reference: File Locations

```
WordPress Root/
├── wp-content/
│   ├── themes/
│   │   └── razzi-child/
│   │       ├── header.php              ← Upload here
│   │       ├── advapes-nav.php         ← Upload here
│   │       ├── advapes-nav.css         ← Upload here (optional)
│   │       └── advapes-nav-debug.php   ← Debug tool (optional)
│   └── debug.log                        ← Check for errors
└── wp-config.php                        ← Enable WP_DEBUG here
```

---

## Prevention Tips

To avoid issues in the future:

1. **Keep WooCommerce updated** - Navigation depends on it
2. **Use cache invalidation** - Cache clears automatically when products change
3. **Test after changes** - Clear cache and refresh page after product/category changes
4. **Keep backups** - Before making theme changes
5. **Monitor debug.log** - Check occasionally for errors

---

## Version Information

- **Navigation Version:** 3.1.1
- **Requires:** WordPress 5.0+, WooCommerce 4.0+, PHP 7.4+
- **Last Updated:** 2025-12-11

---

**Still stuck? The debug page (`advapes-nav-debug.php`) is your best friend!**
