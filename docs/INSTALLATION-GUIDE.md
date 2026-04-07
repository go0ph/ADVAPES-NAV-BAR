# Complete Installation Guide for WooCommerce & Razzi Theme

This guide will walk you through installing the ADVapes navigation bar on your WooCommerce website using the Razzi theme.

---

## 📋 Table of Contents

1. [Prerequisites](#prerequisites)
2. [Quick Preview](#quick-preview)
3. [Installation Steps](#installation-steps)
4. [What Happens When You Install](#what-happens-when-you-install)
5. [Troubleshooting](#troubleshooting)
6. [Removing or Reverting](#removing-or-reverting)

---

## Prerequisites

Before you begin, make sure you have:

- ✅ WordPress 5.0 or higher
- ✅ WooCommerce 4.0 or higher installed and activated
- ✅ Razzi theme installed (parent theme)
- ✅ Razzi Child theme installed and activated
- ✅ FTP/SFTP access to your server OR access to WordPress File Manager
- ✅ Admin access to WordPress dashboard

### Where is My Razzi Child Theme?

Your Razzi child theme folder is located at:
```
/wp-content/themes/razzi-child/
```

You can access it via:
- **FTP/SFTP** - Connect to your server and navigate to this path
- **cPanel File Manager** - Navigate to public_html/wp-content/themes/razzi-child/
- **WordPress Plugin** - Use a file manager plugin like "WP File Manager"

---

## Quick Preview

**Before installing**, you can preview exactly how the navigation will look:

1. Download `../preview/preview.html` from this repository
2. Open it in your web browser
3. Try hovering over menu items (desktop) and clicking the hamburger menu (mobile)

This shows you the exact design without making any changes to your site!

---

## Installation Steps

### Step 1: Download the Required Files

Download these **3 files** from the repository:

1. **`advapes-nav.php`** - Main navigation system (functions and logic)
2. **`advapes-nav.css`** - Navigation styling
3. **`header.php`** - Header template that includes the navigation

**Where to download from:**
- Go to: https://github.com/go0ph/ADVAPES-NAV-BAR
- Click on each file
- Click "Raw" button
- Right-click and "Save As"

### Step 2: Backup Your Current Files

**IMPORTANT:** Before making any changes, backup your current header file!

```bash
# Connect via FTP/SFTP and navigate to:
/wp-content/themes/razzi-child/

# If header.php already exists, rename it to:
header-backup-YYYY-MM-DD.php
```

### Step 3: Upload Files to Razzi Child Theme

Upload all 3 files to your Razzi Child theme directory:

```
/wp-content/themes/razzi-child/advapes-nav.php
/wp-content/themes/razzi-child/advapes-nav.css
/wp-content/themes/razzi-child/header.php
```

**How to upload:**

**Option A: Using FTP/SFTP (Recommended)**
1. Open FileZilla or your FTP client
2. Connect to your server
3. Navigate to `/wp-content/themes/razzi-child/`
4. Upload the 3 files
5. Set file permissions to 644 if needed

**Option B: Using cPanel File Manager**
1. Log in to cPanel
2. Open File Manager
3. Navigate to `public_html/wp-content/themes/razzi-child/`
4. Click "Upload" button
5. Select and upload all 3 files

**Option C: Using WordPress File Manager Plugin**
1. Install "WP File Manager" plugin
2. Navigate to `wp-content/themes/razzi-child/`
3. Upload the 3 files

### Step 4: Clear All Caches

This is **crucial** - the navigation won't appear without clearing caches!

1. **WordPress Cache:**
   - If using WP Super Cache: Settings → WP Super Cache → "Delete Cache"
   - If using W3 Total Cache: Performance → Dashboard → "Empty all caches"
   - If using WP Rocket: Settings → WP Rocket → "Clear cache"

2. **Browser Cache:**
   - Press `Ctrl + Shift + R` (Windows/Linux)
   - Press `Cmd + Shift + R` (Mac)

3. **CDN Cache (if using Cloudflare, etc.):**
   - Log in to your CDN dashboard
   - Purge all caches

### Step 5: Test the Navigation

Visit your website and check:

**Desktop View:**
- ✅ Navigation bar appears below the main header
- ✅ Dark background with red bottom border
- ✅ Menu items: Deals, Disposables, Pod Disposables, etc.
- ✅ Hover over items to see dropdown menus

**Mobile View:**
- ✅ Hamburger menu icon (☰) appears
- ✅ Clicking it opens a right-side drawer
- ✅ Menu items are tap-friendly
- ✅ Dropdowns expand when clicked

---

## What Happens When You Install

### Files Explained

**1. advapes-nav.php** (Main Logic)
- Fetches your WooCommerce categories
- Detects product brands automatically  
- Builds navigation menu structure
- Handles caching for performance
- Provides REST API for cache refresh

**2. advapes-nav.css** (Styling)
- Dark theme design (#050507 background)
- Red accent color (#e11d2f)
- Responsive mobile drawer
- Hover effects and animations

**3. header.php** (Theme Template)
- Replaces Razzi's default header
- Includes the navigation bar
- Maintains Razzi's header structure
- Child theme safe (won't be overwritten by theme updates)

### How It Integrates with Razzi Theme

The navigation bar is inserted **below** your existing Razzi header:

```
┌─────────────────────────────────┐
│   Razzi Header (Logo, Search)   │  ← Stays the same
├─────────────────────────────────┤
│   ADVapes Navigation Bar         │  ← NEW! Added here
├─────────────────────────────────┤
│   Page Content                   │
└─────────────────────────────────┘
```

### What Gets Replaced?

**Nothing is removed!** The navigation is **added**, not replaced:

- ❌ Your Razzi header stays intact
- ❌ Your logo, search bar, cart icon stay the same
- ❌ Your existing menus are not touched
- ✅ The navigation bar is inserted as an additional element

### Dynamic Content

The navigation automatically populates with:

- **Categories** from WooCommerce → Products → Categories
- **Brands** from your brand taxonomy (Perfect Brands, WooCommerce Brands, etc.)
- **Product counts** to show top brands in each category

**Example:**
If you have these categories:
- Disposables (50 products)
  - One-Use (30 products)
  - High-Puff (20 products)

The navigation will automatically show:
```
Disposables ▼
  ├─ All Disposables (50)
  ├─ One-Use Disposables (30)
  ├─ High-Puff Disposables (20)
  └─ Popular Brands
```

---

## Troubleshooting

### Problem 1: Navigation Bar Doesn't Appear

**Possible Causes:**
- Cache not cleared
- Files uploaded to wrong directory
- WooCommerce not active

**Solutions:**

1. **Check file location:**
   ```
   /wp-content/themes/razzi-child/advapes-nav.php ✅
   /wp-content/themes/razzi-child/advapes-nav.css ✅
   /wp-content/themes/razzi-child/header.php ✅
   ```

2. **Clear ALL caches** (see Step 4 above)

3. **Check WooCommerce is active:**
   - Go to: Plugins → Installed Plugins
   - Ensure "WooCommerce" is Active

4. **Check Razzi Child is active:**
   - Go to: Appearance → Themes
   - "Razzi Child" should be the active theme (not just "Razzi")

5. **Check browser console for errors:**
   - Press F12 in your browser
   - Click "Console" tab
   - Look for red error messages
   - Share errors with support if found

### Problem 2: Navigation Shows "Coming Soon" or Fallback Menu

**Cause:** WooCommerce categories not found or wrong category slugs

**Solution:**

1. **Create required categories:**
   - Go to: Products → Categories
   - Create categories with these slugs:
     - `disposables`
     - `pod-disposables`
     - `pod-systems-kits`
     - `vape-hardware` or `dl-hardware`
     - `dl-liquids` or `nic-salts`

2. **Assign products to categories:**
   - Go to: Products → All Products
   - Edit each product
   - Select appropriate category
   - Click "Update"

3. **Clear cache** (see Step 4)

### Problem 3: Brands Don't Show in Dropdowns

**Cause:** Brand taxonomy not detected or no brands assigned

**Solution:**

1. **Check brand plugin:**
   - If using "Perfect WooCommerce Brands" → Should work automatically
   - If using "WooCommerce Brands" → Should work automatically
   - If using custom attribute → See below

2. **If using custom brand attribute:**
   - Go to: Products → Attributes
   - Create attribute named "Brand" or "Brands"
   - Edit `advapes-nav.php` line 191-220 if using different name

3. **Assign brands to products:**
   - Go to: Products → All Products
   - Edit each product
   - Assign brand in the Brand field
   - Click "Update"

4. **Clear cache** (see Step 4)

### Problem 4: Navigation Wraps to Multiple Lines

**Cause:** Too many menu items or long category names

**Solution:**

This is fixed in version 3.1.1+. If you're still seeing wrapping:

1. **Update to latest version** from repository
2. **Check CSS file is loaded:**
   - View page source (Ctrl+U)
   - Search for "advapes-nav.css"
   - If not found, check file upload

3. **Reduce menu item padding** in `advapes-nav.css`:
   ```css
   .adv-nav-list > li > a {
     padding: 12px 12px; /* Reduce from 16px to 12px */
   }
   ```

### Problem 5: Mobile Menu Not Working

**Cause:** JavaScript conflict or CSS not loaded

**Solution:**

1. **Check browser console** (F12 → Console)
   - Look for JavaScript errors
   - Note any conflicting plugins

2. **Disable cache plugins temporarily:**
   - WP Super Cache → Settings → "Cache Off"
   - Test if mobile menu works
   - If it works, configure cache to exclude CSS/JS

3. **Check for theme conflicts:**
   - Switch to Razzi parent theme temporarily
   - Test mobile menu
   - If it works, there's a child theme conflict

### Problem 6: Wrong Header Appears

**Cause:** Multiple header.php files or theme override

**Solution:**

1. **Check active theme:**
   - Appearance → Themes
   - Must be "Razzi Child" (not "Razzi")

2. **Check header.php location:**
   ```
   /wp-content/themes/razzi-child/header.php ✅ (Your uploaded file)
   /wp-content/themes/razzi/header.php ❌ (Parent theme - ignored)
   ```

3. **Verify header.php content:**
   - Open `/wp-content/themes/razzi-child/header.php`
   - Line 14 should contain: `require_once __DIR__ . '/advapes-nav.php';`

---

## Removing or Reverting

### To Remove the Navigation

**Option 1: Delete Files**
1. Connect via FTP/SFTP
2. Navigate to `/wp-content/themes/razzi-child/`
3. Delete these files:
   - `advapes-nav.php`
   - `advapes-nav.css`
   - `header.php`
4. Restore your backup: `header-backup-YYYY-MM-DD.php` → `header.php`
5. Clear cache

**Option 2: Deactivate (Keep Files)**
1. Rename `header.php` to `header-advapes-disabled.php`
2. Restore your backup: `header-backup-YYYY-MM-DD.php` → `header.php`
3. Clear cache

### To Revert to Default Razzi Header

If you didn't have a custom header before:

1. Delete `/wp-content/themes/razzi-child/header.php`
2. WordPress will automatically use the parent theme's header
3. Clear cache

---

## Advanced Configuration

### Change Cache Duration

Edit `advapes-nav.php` line 31:
```php
define( 'ADVAPES_NAV_TTL', 30 ); // Change to 60, 120, etc. (seconds)
```

### Customize Colors

Edit `advapes-nav.css`:
```css
/* Line 7 - Background color */
background: #050507;

/* Line 9 - Border color */
border-bottom: 2px solid #e11d2f;

/* Line 72 - Link color */
color: #d1d5db;

/* Line 80 - Hover background */
background: #e11d2f;
```

### Add or Remove Menu Items

Edit `advapes-nav.php` function `advapes_get_nav_structure()` (around line 400-650)

---

## Support Checklist

Before asking for help, verify:

- [ ] All 3 files uploaded to `/wp-content/themes/razzi-child/`
- [ ] Razzi Child theme is active (not just Razzi)
- [ ] WooCommerce plugin is active
- [ ] All caches cleared (WordPress, browser, CDN)
- [ ] Categories exist in WooCommerce
- [ ] Products assigned to categories
- [ ] Browser console shows no errors (F12)
- [ ] Tried in different browser or incognito mode

---

## Quick Reference

**File Locations:**
```
/wp-content/themes/razzi-child/advapes-nav.php
/wp-content/themes/razzi-child/advapes-nav.css
/wp-content/themes/razzi-child/header.php
```

**Cache Clear Commands:**
```bash
# WordPress CLI (if available)
wp cache flush

# Delete transients manually
# In phpMyAdmin or database tool:
DELETE FROM wp_options WHERE option_name LIKE '%advapes_nav%';
```

**Useful Links:**
- Repository: https://github.com/go0ph/ADVAPES-NAV-BAR
- Preview Demo: Open `../preview/preview.html` in browser
- Documentation: See `README.md`

---

## Success!

If you can see the navigation bar with dropdowns that populate from your WooCommerce categories, you've successfully installed the ADVapes navigation system! 🎉

The navigation will automatically update as you:
- Add/remove products
- Create/delete categories
- Add/remove brands

No manual updates needed!

---

**Installation Time:** ~15 minutes  
**Skill Level:** Beginner-friendly  
**Reversible:** 100% (just delete/rename files)

Need help? Check the [Troubleshooting](#troubleshooting) section or create an issue on GitHub.
