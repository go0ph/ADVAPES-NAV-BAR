# ADVapes Dynamic Navigation Bar

A simple, dynamic navigation menu for WooCommerce that automatically updates based on your product categories and brands.

## What It Does

- **Automatically displays product categories** from your WooCommerce store
- **Shows top brands** in each category dropdown (if brands exist)
- **Updates dynamically** as you add/remove products and categories
- **Single line layout** that fits all menu items without wrapping on desktop
- **Mobile responsive** with collapsible hamburger menu and accordion dropdowns
- **Touch optimized** for excellent mobile user experience
- **Cached for performance** (30-second cache with auto-invalidation)

## Files

- `header.php` - Header template that includes the navigation bar
- `advapes-nav.php` - Main navigation system (functions and logic)
- `advapes-nav.css` - Navigation styling

## Installation

1. Upload all three files to your WordPress child theme directory:
   ```
   /wp-content/themes/razzi-child/
   ```

2. The navigation will automatically appear below your header

3. That's it! The menu will populate automatically from your WooCommerce categories and products

## How It Works

### Menu Structure

The navigation shows these menu items in order:

1. **Deals** - Static links to promotions
2. **Disposables** - Dynamic category with subcategories and top brands
3. **Pod Disposables** - Dynamic category with subcategories and top brands
4. **Pod Systems & Kits** - Dynamic category with subcategories and top brands
5. **Vape Hardware** - Dynamic category with subcategories and top brands
6. **DL E-Liquids** - Dynamic category with subcategories and top brands
7. **MTL & Nic Salts** - Dynamic category with subcategories and top brands
8. **Nic Alternatives** - Dynamic category with subcategories and top brands
9. **Brands** - Top 10 brands across all products
10. **Support** - Static links to help pages

### Dynamic Updates

Each category dropdown automatically shows:
- Up to 10 subcategories (ordered by product count)
- Up to 4 top brands for that category (ordered by product count)
- "View All" link at the bottom

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

### Mobile Menu Features

The navigation includes an enhanced mobile experience:
- **Hamburger Toggle**: Clean burger icon on mobile (≤1024px width)
- **Collapsible Dropdowns**: All menu items with children collapse/expand on tap
- **Accordion Behavior**: Opening one section automatically closes others
- **Touch Optimized**: Instant response with `touchstart` events
- **Visual Feedback**: Smooth animations with rotating arrows
- **Smooth Scrolling**: GPU-accelerated scrolling with touch optimization
- **Logo Stability**: Fixed logo resize glitch during menu interactions
- **No Menu Overlap**: Desktop menu hidden on mobile, mobile menu hidden on desktop

## Cache Management

Navigation data is cached for 30 seconds to improve performance.

### Automatic Cache Invalidation

Cache is automatically cleared when you:
- Save/update/trash any product
- Create/edit/delete a category
- Create/edit/delete a brand

### Manual Cache Clear

If you need to force a cache refresh:
1. Edit any product
2. Click "Update" (even without changes)
3. Navigation will rebuild immediately

Or call the REST API endpoint (admin only):
```
POST /wp-json/advapes/v1/nav/refresh
```

## Troubleshooting

### Navigation is empty or shows fallback menu

**Causes:**
- WooCommerce is not active
- No product categories exist
- Category slugs don't match expected names

**Solution:**
1. Activate WooCommerce plugin
2. Create categories with the correct slugs (see "Category Detection" above)
3. Add products to those categories

### Brands not showing in dropdowns

**Causes:**
- Brand taxonomy doesn't exist
- Products don't have brands assigned
- Cache is stale

**Solution:**
1. Create a brand attribute in WooCommerce (Products → Attributes)
2. Assign brands to your products
3. Make sure products are published (not drafts)
4. Clear the cache (edit any product and click Update)

### Navigation wrapping to multiple lines

This issue is fixed in v3.1.1+:
- Optimized padding in `.adv-nav-link` (14px 8px)
- Reduced font size to 11px
- Container max-width increased to 1400px
- All 10 menu items fit on a single line on desktop

### Mobile menu not responding or feels sluggish

Fixed in v3.1.2:
- Implemented `touchstart` events for instant feedback
- Added proper touch-action and tap-highlight CSS
- GPU acceleration for smooth animations
- Debounced resize handlers for better performance

### Brands showing but indistinguishable from categories

This is intentional - both use the same format:
```
Item Name ──────── XX+ products
```

Brands and subcategories appear mixed in the dropdown, ordered by product count (highest first).

## Customization

### Change Number of Items Shown

In `advapes-nav.php`, adjust these values:

```php
// Line 420 (Disposables - currently 7 subcategories)
$children = advapes_get_category_children( $disposables->term_id, 7 ); // Change 7 to any number

// Line 429 (Disposables - currently 3 brands)
$brands = advapes_get_category_brands( $disposables->term_id, 3 ); // Change 3 to any number

// Similar for other categories (lines 455+)
// Most categories use: 6 subcategories + 3 brands = 9 items + "Browse all" = 10 total

// Line 646 (main Brands menu - top brands)
'number' => 10, // Change 10 to show more/fewer top brands
```

### Change Colors and Styling

Edit `advapes-nav.css`:
- Background: Line 7 (`background: #050507;`)
- Border: Line 9 (`border-bottom: 2px solid #e11d2f;`)
- Link color: Line 81 (`color: #d1d5db;`)
- Hover color: Line 94 (`background: #e11d2f;`)
- Deals color: Line 98 (`color: #fbbf24;`)

### Change Cache Duration

In `advapes-nav.php` line 28:
```php
define( 'ADVAPES_NAV_TTL', 30 ); // Currently 30 seconds, change to any number
```

Note: Cache duration is set to 30 seconds (not 30 minutes) for near real-time updates while maintaining performance.

### Add/Remove Menu Items

Edit the navigation structure in `advapes_get_nav_structure()` function (lines 376-743).

## Requirements

- WordPress 5.0+
- WooCommerce 4.0+
- PHP 7.4+
- Razzi theme (or modify `header.php` for your theme)

## Performance

- Database queries are optimized with proper JOINs and sanitization
- Results are cached for 30 seconds for near real-time updates
- Cache invalidates automatically on product/category/brand changes
- Mobile-first responsive design with GPU-accelerated animations
- Touch events optimized for instant mobile response
- No external dependencies or heavy libraries

## Support

If something isn't working:

1. Make sure WooCommerce is active
2. Check that you have product categories with correct slugs
3. Verify products are published and in categories
4. Clear the navigation cache
5. Check browser console for JavaScript errors (F12)

## Version History

### Current Version: 3.1.2 (December 2025)

**Latest Updates:**
- ✅ **Mobile Menu Enhancements**: Fixed mobile menu unresponsiveness and touch interaction issues
- ✅ **Logo Stability**: Resolved logo resize glitch when mobile menu opens/closes
- ✅ **Collapsible Mobile Dropdowns**: All menu items collapse/expand smoothly on mobile with accordion behavior
- ✅ **Touch Optimization**: Improved touch responsiveness with proper event handling
- ✅ **GPU Acceleration**: Smooth animations using CSS transforms and hardware acceleration

### Version 3.1.1 (December 2025)
- Fixed navigation bar wrapping to single line on all screen sizes
- Enhanced brand detection to prioritize `pwb-brand` taxonomy (WordPress Perfect Brands)
- Improved brand queries to include products from subcategories
- Security improvements with proper SQL sanitization

### Version 3.1 (December 2025)
- Hybrid approach: Fixed parent menu structure with dynamic subcategories
- Maintains v2.0 user familiarity while providing v3.0 automatic updates
- 10 consistent parent menus with auto-updating content

## License

Use freely for your ADVapes site.
