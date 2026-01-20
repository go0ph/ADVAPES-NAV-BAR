# ADVapes Dynamic Navigation Bar

![Version](https://img.shields.io/badge/version-4.0.0-blue.svg)
![Status](https://img.shields.io/badge/status-stable-green.svg)
[![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/woocommerce-4.0%2B-purple.svg)](https://woocommerce.com/)

A dynamic, professional navigation menu for WooCommerce that automatically updates based on your product categories and brands. Designed for vape shops and e-commerce stores.

## ✨ Features

- **🎯 Automatically displays product categories** from your WooCommerce store
- **🏷️ Shows top brands** in each category dropdown (if brands exist)
- **🔄 Updates dynamically** as you add/remove products and categories
- **📏 Single line layout** that fits all menu items without wrapping on desktop
- **📱 Mobile responsive** with right-side flyout drawer and hierarchical navigation
- **👆 Touch optimized** for excellent mobile user experience
- **⚡ Cached for performance** (30-second cache with auto-invalidation)
- **🎨 Preview before installing** with standalone HTML demo

## 📦 Current Version: 4.0.0 (V2)

**Status:** ✅ Stable - Production Ready

### What's New in V2:
- ✅ **Mobile**: Right-side flyout drawer menu with hierarchical navigation
- ✅ **Desktop**: Expanded dropdowns (6-8 items per section vs 3 in V1)
- ✅ **Full Catalogue**: Browse entire product range via navigation
- ✅ **Improved UX**: Better balance between neatness and comprehensive navigation
- ✅ **Enhanced Performance**: 30-second cache with auto-invalidation
- ✅ **Preview Demo**: Test before installing with `preview.html`

### V3 Available (Optional):
- Brand-first navigation structure
- See `QUICKSTART-V3.md` and `README-V3.md` for details
- Files: `advapes-nav-v3.php`, `header-v3.php`

## 📁 Repository Structure

```
/
├── advapes-nav.php        # Main navigation system (V2 - Current)
├── advapes-nav-v3.php     # V3 navigation system (Brand-first approach)
├── advapes-nav.css        # Navigation styling (works with V2 and V3)
├── header.php             # Header template for V2
├── header-v3.php          # Header template for V3
├── preview.html           # 🆕 Standalone HTML demo - Preview before installing!
├── INSTALLATION-GUIDE.md  # 🆕 Complete step-by-step installation guide
├── README.md              # This file - Overview and features
├── QUICKSTART-V3.md       # Quick start guide for V3 installation
├── README-V3.md           # V3 documentation
└── archive/               # Historical documentation and changelogs
```

## 🎨 Preview the Navigation

**NEW!** You can now preview the navigation bar before installing it on your site:

1. Download `preview.html` from this repository
2. Open it in any web browser
3. See exactly how it will look on desktop and mobile
4. No WordPress or server needed!

**Screenshot:**

![ADVapes Navigation Preview](https://github.com/user-attachments/assets/1d5b0c63-8e60-4757-a1e1-ad7d37077a98)

This is perfect for:
- Testing the design before committing
- Showing stakeholders what it will look like
- Understanding the mobile drawer behavior
- Verifying it matches your brand

## 🚀 Quick Start

### Step 1: Preview First (Recommended)

1. Download `preview.html`
2. Open in browser
3. See exactly how it will look!

### Step 2: Install on Your Site

**For detailed instructions, see [INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md)**

Quick summary:
1. Download 3 files: `advapes-nav.php`, `advapes-nav.css`, `header.php`
2. Upload to `/wp-content/themes/razzi-child/`
3. Clear all caches (WordPress, browser, CDN)
4. Visit your site - navigation appears below header!

**Common Issues:**
- **Navigation doesn't appear?** → Clear cache and refresh with Ctrl+Shift+R
- **Shows fallback menu?** → Check WooCommerce is active and categories exist
- **Brands not showing?** → Assign brands to products in WooCommerce

See the [Installation Guide](INSTALLATION-GUIDE.md) for complete troubleshooting.

## 🎯 How It Works

### Menu Structure

The navigation displays **10 fixed parent menu items** in consistent order:

1. **Deals** - Static promotion links
2. **Disposables** - Dynamic category dropdown
3. **Pod Disposables** - Dynamic category dropdown
4. **Pod Systems & Kits** - Dynamic category dropdown
5. **Vape Hardware** - Dynamic category dropdown
6. **DL E-Liquids** - Dynamic category dropdown
7. **MTL & Nic Salts** - Dynamic category dropdown
8. **Nic Alternatives** - Dynamic category dropdown
9. **Brands** - Top 10 brands across all products
10. **Support** - Static help links

### Dynamic Dropdowns

Each category dropdown automatically displays:
- Up to 10 subcategories (sorted by product count)
- Up to 4 top brands for that category (sorted by product count)
- "View All" link at the bottom

Dropdowns update automatically when products or categories change!

### Category Detection

The system looks for WooCommerce categories with these slugs:
- `disposables`
- `pod-disposables`
- `pod-systems-kits`
- `dl-hardware` or `vape-hardware`
- `dl-liquid` or `dl-liquids`
- `nic-salts` or `nic-salts-mtl-liquids`
- `nicotine-alternatives`

**Important:** If your categories use different slugs, update them in WooCommerce or adjust the slugs in `advapes-nav.php` (lines 412-633).

### Brand Detection

The system automatically detects your brand taxonomy from these common names (in priority order):
- `pwb-brand` (WordPress Perfect Brands plugin - **used by advapes.co.za**)
- `product_brand` (WooCommerce Brands)
- `brand`
- `brands`
- `pa_brand`
- `pa_brands`
- Any WooCommerce attribute containing "brand" in the name

The system prioritizes `pwb-brand` as this is the taxonomy used by advapes.co.za. Brands are now fetched from both the parent category and all its subcategories to ensure complete brand coverage.

If you use a different brand taxonomy, the system won't find it. Either rename your taxonomy or update the detection code in `advapes_detect_brand_taxonomy()` (lines 191-220).

### 📱 Mobile Experience

Enhanced mobile navigation (≤1024px width):
- **Hamburger Toggle**: Clean burger icon
- **Collapsible Dropdowns**: Tap to expand/collapse menu items
- **Accordion Behavior**: Only one section open at a time
- **Touch Optimized**: Instant response with `touchstart` events
- **Smooth Animations**: GPU-accelerated with rotating arrows
- **Logo Stability**: No resize glitches during menu interactions

## ⚡ Cache Management

Navigation data is cached for **30 seconds** for optimal performance.

### Automatic Cache Invalidation

Cache clears automatically when you:
- Save/update/trash any product
- Create/edit/delete a category
- Create/edit/delete a brand

### Manual Cache Refresh

**Option 1:** Edit any product and click "Update" (no changes needed)

**Option 2:** Call the REST API endpoint (admin only):
```bash
POST /wp-json/advapes/v1/nav/refresh
```

## 🔧 Troubleshooting

### Navigation doesn't appear after installation

**Most common cause:** Cache not cleared

**Solution:**
1. Clear WordPress cache (if using cache plugin)
2. Hard refresh browser: `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
3. Clear CDN cache (if using Cloudflare, etc.)
4. Check files are in `/wp-content/themes/razzi-child/` (not `razzi`)

### Navigation shows "Coming Soon" or fallback menu

**Cause:** WooCommerce not active or no categories found

**Solution:**
1. Activate WooCommerce plugin (Plugins → Installed Plugins)
2. Create categories with correct slugs (Products → Categories):
   - `disposables`
   - `pod-disposables`  
   - `pod-systems-kits`
   - `vape-hardware` or `dl-hardware`
   - `dl-liquids` or `nic-salts`
3. Add products to those categories
4. Clear cache (see above)

### Brands not showing in dropdowns

**Cause:** No brand taxonomy or unassigned brands

**Solution:**
1. Install a brand plugin (Perfect WooCommerce Brands or WooCommerce Brands)
2. Create brands (Products → Brands)
3. Assign brands to products
4. Ensure products are published (not drafts)
5. Clear cache

### Header looks wrong or doubled

**Cause:** Multiple header files or wrong theme active

**Solution:**
1. Check Razzi **Child** theme is active (not just "Razzi")
2. Check header.php is in `/wp-content/themes/razzi-child/` (not `/razzi/`)
3. If you had custom header, restore from backup

**For more troubleshooting, see [INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md#troubleshooting)**

## 🎨 Customization

### Change Number of Items in Dropdowns

In `advapes-nav.php`, modify the category-specific functions:

```php
// Example: Change Disposables to show 10 subcategories and 5 brands
$children = advapes_get_category_children( $disposables->term_id, 10 );
$brands = advapes_get_category_brands( $disposables->term_id, 5 );

// Main Brands menu (default: 10 top brands)
'number' => 15, // Show 15 top brands instead
```

### Change Colors and Styling

Edit `advapes-nav.css`:

```css
background: #050507;           /* Navigation background */
border-bottom: 2px solid #e11d2f;  /* Bottom border */
color: #d1d5db;                /* Link color */
background: #e11d2f;           /* Hover color */
color: #fbbf24;                /* Deals highlight color */
```

### Change Cache Duration

In `advapes-nav.php`:

```php
define( 'ADVAPES_NAV_TTL', 30 ); // Seconds (30 = near real-time updates)
```

### Modify Menu Structure

Edit `advapes_get_nav_structure()` function in `advapes-nav.php` to add/remove menu items.

## 📋 Requirements

- **WordPress:** 5.0 or higher
- **WooCommerce:** 4.0 or higher  
- **PHP:** 7.4 or higher
- **Theme:** Razzi (parent theme + child theme installed)
- **Access:** FTP/SFTP or WordPress File Manager

## ⚡ Performance & Features

- ✅ **Optimized database queries** with proper JOINs and sanitization
- ✅ **30-second cache** for near real-time updates
- ✅ **Automatic cache invalidation** on content changes
- ✅ **GPU-accelerated animations** for smooth mobile experience
- ✅ **Zero external dependencies** - Pure PHP, CSS, and vanilla JavaScript
- ✅ **SEO friendly** with semantic HTML structure
- ✅ **Accessibility** optimized with ARIA labels

## 💬 Support & Documentation

### Quick Links

- 📖 **[Complete Installation Guide](INSTALLATION-GUIDE.md)** - Step-by-step instructions
- 🎨 **[Live Preview Demo](preview.html)** - See it before installing
- 📋 **[Project Overview](PROJECT-OVERVIEW.md)** - Quick start guide
- 📥 **[Download Checklist](FILES-TO-DOWNLOAD.txt)** - What files you need

### Before Asking for Help

Check these:

1. ✅ WooCommerce is active
2. ✅ Product categories exist with correct slugs
3. ✅ Products are published and categorized
4. ✅ Cache is cleared (WordPress + Browser + CDN)
5. ✅ Files uploaded to `/wp-content/themes/razzi-child/` (not `/razzi/`)
6. ✅ No JavaScript errors in browser console (F12)

For troubleshooting, see **[INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md#troubleshooting)**

## 📋 Version History

### Version 3.1.2 (December 15, 2025) - Current
- ✅ Mobile menu enhancements with instant touch response
- ✅ Fixed logo resize glitch on mobile  
- ✅ Collapsible mobile dropdowns with accordion behavior
- ✅ GPU-accelerated animations
- ✅ Touch event optimization

### Version 3.1.1 (December 11, 2025)
- Fixed navigation bar wrapping to single line
- Enhanced brand detection (prioritizes `pwb-brand` taxonomy)
- Improved brand queries to include subcategories
- Security improvements with proper SQL sanitization

### Version 3.1 (December 10, 2025)
- Hybrid approach: Fixed parent menu structure with dynamic subcategories
- 10 consistent parent menus with auto-updating content

For detailed version history and changelogs, see the `archive/` directory.

## License

Use freely for your ADVapes site.
