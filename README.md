# ADVapes Dynamic Navigation Bar

![Version](https://img.shields.io/badge/version-3.1.2-blue.svg)
![Status](https://img.shields.io/badge/status-stable-green.svg)
[![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/woocommerce-4.0%2B-purple.svg)](https://woocommerce.com/)

A simple, dynamic navigation menu for WooCommerce that automatically updates based on your product categories and brands.

## ✨ Features

- **Automatically displays product categories** from your WooCommerce store
- **Shows top brands** in each category dropdown (if brands exist)
- **Updates dynamically** as you add/remove products and categories
- **Single line layout** that fits all menu items without wrapping on desktop
- **Mobile responsive** with collapsible hamburger menu and accordion dropdowns
- **Touch optimized** for excellent mobile user experience
- **Cached for performance** (30-second cache with auto-invalidation)

## 📦 Current Version: 4.0.0 - V2 (December 17, 2025)

**Status:** 🚧 In Development - Major Redesign

### V2 Major Updates:
- ✅ **Mobile**: Right-side flyout drawer menu with hierarchical navigation
- ✅ **Desktop**: Expanded content (6-8 items per section vs 3 in V1)
- ✅ **Catalogue Coverage**: Users can browse entire product range via navigation
- ✅ **Improved UX**: Better balance between neatness and comprehensive navigation
- ✅ **Brand Expansion**: Show 20 top brands (vs 10 in V1)
- ✅ **Better than competitors**: Exceeds vaperite.co.za and smokeorganic.co.za

### Previous Version (V1 - 3.1.2):
- Archived in `/V1` folder
- Collapsible mobile dropdowns (accordion style)
- 3 items per section
- Can be restored if needed

## 📁 Repository Structure

```
/
├── advapes-nav.php      # Main navigation system (functions and logic)
├── advapes-nav.css      # Navigation styling
├── header.php           # Header template that includes the navigation bar
├── README.md            # This file
├── archive/             # Historical documentation and changelogs
└── backups/             # Backup versions (if any)
```

## 🚀 Installation

1. Upload all three files to your WordPress child theme directory:
   ```
   /wp-content/themes/razzi-child/
   ```

2. The navigation will automatically appear below your header

3. That's it! The menu will populate automatically from your WooCommerce categories and products

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

### Navigation is empty or shows fallback menu

**Causes:** WooCommerce not active, no categories, or wrong category slugs

**Solution:**
1. Activate WooCommerce plugin
2. Create categories with correct slugs (see "Category Detection" section)
3. Add products to those categories

### Brands not showing in dropdowns

**Causes:** No brand taxonomy, unassigned brands, or stale cache

**Solution:**
1. Create a brand attribute in WooCommerce (Products → Attributes)
2. Assign brands to your products
3. Ensure products are published (not drafts)
4. Clear cache (edit any product and click Update)

### Navigation wrapping to multiple lines

**Fixed in v3.1.1+** - All 10 menu items now fit on a single line on desktop

### Mobile menu sluggish or unresponsive

**Fixed in v3.1.2** - Touch events optimized with instant feedback and GPU acceleration

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

- WordPress 5.0+
- WooCommerce 4.0+
- PHP 7.4+
- Razzi theme (or modify `header.php` for your theme)

## ⚡ Performance

- Optimized database queries with proper JOINs and sanitization
- 30-second cache for near real-time updates
- Automatic cache invalidation on content changes
- GPU-accelerated animations for smooth mobile experience
- Zero external dependencies

## 💬 Support

Having issues? Check these:

1. ✅ WooCommerce is active
2. ✅ Product categories exist with correct slugs
3. ✅ Products are published and categorized
4. ✅ Cache is cleared (edit any product → Update)
5. ✅ No JavaScript errors in browser console (F12)

For detailed documentation, see the `archive/` directory.

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
