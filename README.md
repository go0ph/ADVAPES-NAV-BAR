# ADVapes Dynamic Navigation Bar

A simple, dynamic navigation menu for WooCommerce that automatically updates based on your product categories and brands.

## What It Does

- **Automatically displays product categories** from your WooCommerce store
- **Shows top brands** in each category dropdown (if brands exist)
- **Updates dynamically** as you add/remove products and categories
- **Single line layout** that fits all menu items without wrapping
- **Mobile responsive** with hamburger menu
- **Cached for performance** (30-minute cache with auto-invalidation)

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

**Important:** If your categories use different slugs, update them in WooCommerce or adjust the slugs in `advapes-nav.php` (lines 257-467).

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

If you use a different brand taxonomy, the system won't find it. Either rename your taxonomy or update the detection code in `advapes_detect_brand_taxonomy()` (lines 55-85).

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

This should be fixed now with `flex-wrap: nowrap` in the CSS. If it still wraps:
1. Reduce padding in `.adv-nav-link` (currently 14px 10px)
2. Reduce font size in `.adv-nav-link` (currently 12px)
3. Shorten menu item names

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
// Line 261 (and similar for other categories)
$children = advapes_get_category_children( $disposables->term_id, 10 ); // Change 10 to any number

// Line 264 (and similar for other categories)  
$brands = advapes_get_category_brands( $disposables->term_id, 4 ); // Change 4 to any number

// Line 478 (main Brands menu)
'number' => 10, // Change 10 to any number
```

### Change Colors and Styling

Edit `advapes-nav.css`:
- Background: Line 7 (`background: #050507;`)
- Border: Line 9 (`border-bottom: 2px solid #e11d2f;`)
- Link color: Line 76 (`color: #d1d5db;`)
- Hover color: Line 89 (`background: #e11d2f;`)
- Deals color: Line 93 (`color: #fbbf24;`)

### Change Cache Duration

In `advapes-nav.php` line 23:
```php
define( 'ADVAPES_NAV_TTL', 30 ); // Change 30 to any number of seconds
```

### Add/Remove Menu Items

Edit the navigation structure in `advapes_get_nav_structure()` function (lines 210-580).

## Requirements

- WordPress 5.0+
- WooCommerce 4.0+
- PHP 7.4+
- Razzi theme (or modify `header.php` for your theme)

## Performance

- Database queries are optimized with proper JOINs
- Results are cached for 30 seconds
- Cache invalidates automatically on data changes
- Mobile-first responsive design
- No external dependencies

## Support

If something isn't working:

1. Make sure WooCommerce is active
2. Check that you have product categories with correct slugs
3. Verify products are published and in categories
4. Clear the navigation cache
5. Check browser console for JavaScript errors (F12)

## Version

Current version: 3.1.2 (Simplified)

## License

Use freely for your ADVapes site.
