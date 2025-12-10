# ADVAPES Navigation Bar - Development Dashboard

> **Version 3.1** | Last Updated: 2025-12-10 | Status: ✅ Hybrid Navigation

---

## 🎯 What's New in v3.1

### **Hybrid Navigation: Best of V2 + V3**

Version 3.1 implements a **hybrid approach** that combines:
- **Fixed parent menu structure** from v2.0 (for user familiarity) 
- **Dynamic subcategories** from v3.0 (for automatic updates)

This ensures users always see the same top-level navigation items in the same order, while subcategories automatically update based on your WooCommerce catalog.

**Key Features:**
- ✅ **Fixed parent menus** - Same top-level items as v2.0, always in the same order
- ✅ **Dynamic subcategories** - Auto-update from WooCommerce categories
- ✅ **No more unexpected menus** - "Dezemba Dealz" stays under Deals (not as top-level)
- ✅ Dynamic brand detection and listing (top 15 by product count)
- ✅ Real-time product counts in dropdowns
- ✅ Intelligent caching (30-minute transient + proactive invalidation)
- ✅ REST API endpoint for debugging (`/wp-json/advapes/v1/nav`)
- ✅ Graceful fallback if dynamic system unavailable
- ✅ No plugins required - pure WordPress/WooCommerce APIs

---

## 📊 Version Comparison

| Feature | v1.0 | v2.0 | v3.0 | v3.1 |
|---------|------|------|------|------|
| Navigation Type | Static HTML | Static HTML | Dynamic WooCommerce | **Hybrid** |
| Parent Menus | Static | Static | Dynamic | **Fixed from v2** |
| Subcategories | Static | Static | Dynamic | **Dynamic** |
| Product Counts | Manual | Manual | Auto-updated | **Auto-updated** |
| Category Changes | Manual edit | Manual edit | Automatic | **Automatic** |
| User Familiarity | ✅ High | ✅ High | ⚠️ Low | **✅ High** |
| Content Freshness | ❌ Manual | ❌ Manual | ✅ Auto | **✅ Auto** |
| Brand Detection | N/A | Manual list | Auto-detected | **Auto-detected** |
| Caching | None | None | 30min transient | **30min transient** |
| Cache Invalidation | N/A | N/A | Proactive hooks | **Proactive hooks** |
| REST API | No | No | Yes | **Yes** |
| Fallback Safety | No | No | Yes | **Yes** |

---

## 📊 Project Status

### Version History
| Version | Date | Status | Changes |
|---------|------|--------|---------|
| **v3.1** | 2025-12-10 | ✅ Complete | Hybrid navigation (fixed parents + dynamic children) |
| v3.0 | 2025-12-10 | ✅ Complete | Dynamic navigation with WooCommerce integration |
| v2.0 | 2025-12-10 | ✅ Complete | Comprehensive restructure based on product data |
| v1.0 | Previous | ✅ Complete | Initial custom navigation bar |

### Quick Stats
- **System Type:** Hybrid (Fixed Parents + Dynamic Subcategories)
- **Total Products:** 6,528+ published (auto-counted)
- **Parent Menus:** Fixed structure from v2.0
- **Subcategories:** Auto-detected from WooCommerce
- **Brands Detection:** Automatic (top 15 by product count)
- **Cache Strategy:** 30-minute transient with proactive invalidation
- **REST Endpoint:** `/wp-json/advapes/v1/nav`

---

## 🔧 Technical Architecture (v3.1)

### Core Files
```
ADVAPES-NAV-BAR/
├── advapes-nav.php         # NEW: Dynamic navigation engine
├── header.php              # Modified: Calls dynamic system
├── css.txt                 # Unchanged: Existing styles
├── backup/
│   ├── v2.0/              # NEW: v2.0 backup
│   │   ├── header.php
│   │   └── css.txt
│   └── v1.0/              # v1.0 backup
│       ├── header.php
│       └── css.txt
└── README.md              # Updated documentation
```

### How It Works

#### 1. Brand Detection (`advapes_detect_brand_taxonomy()`)
Automatically detects brand taxonomy from common candidates:
- Direct taxonomies: `brand`, `brands`, `product_brand`
- WooCommerce attributes: `pa_brand`, `pa_brands`
- Custom attribute taxonomies containing "brand"

#### 2. Navigation Structure Builder (`advapes_get_nav_structure()`)
- Queries WooCommerce for top-level product categories
- Gets child categories (limited to 12 per parent)
- Detects and lists top 15 brands by product count
- Includes static sections (Deals, Support)
- Caches result in transient for 30 minutes

#### 3. HTML Renderer (`advapes_render_nav()`)
- Renders navigation using existing CSS classes
- Maintains full compatibility with v2.0 styling
- Outputs semantic HTML with proper escaping

#### 4. Cache Management
**Transient Caching:**
- Key: `ADVAPES_NAV_TRANSIENT_KEY`
- TTL: 30 minutes (`ADVAPES_NAV_TTL`)

**Proactive Invalidation:** Cache cleared on:
- Product save/trash/untrash
- Product category create/edit/delete
- Brand taxonomy create/edit/delete

**Scheduled Refresh:**
- WP-Cron job every 30 minutes
- Custom schedule: `advapes_30min`
- Hook: `advapes_refresh_nav_cron`

#### 5. REST API Endpoint
```
GET /wp-json/advapes/v1/nav
GET /wp-json/advapes/v1/nav?refresh=true  # Force refresh
```

Returns:
```json
{
  "success": true,
  "data": { /* navigation structure */ },
  "cached": true/false,
  "version": "3.1"
}
```

---

## 🗂️ Navigation Structure (v3.1 - Hybrid)

### Fixed Parent Menu Structure

v3.1 uses a **fixed parent menu order** from v2.0 for user familiarity, with **dynamic subcategories** that auto-update:

1. **🔥 Deals** (Static Parent + Static Children)
   - Fixed items: Dezemba Dealz, New Products, On Sale, Fire Sale, Buy Bulk & Save, Clearance
   - Manually update seasonal items (e.g., change "Dezemba Dealz" to "Summer Sale")

2. **📱 Disposables** (Fixed Parent + Dynamic Children)
   - Subcategories auto-detected from WooCommerce `disposables` category
   - Up to 12 subcategories ordered by product count
   - Automatic product counts in dropdown title

3. **🔌 Pod Disposables** (Fixed Parent + Dynamic Children)
   - Subcategories from WooCommerce `pod-disposables` category
   - Brand-specific pod systems appear automatically

4. **💨 Pod Systems & Kits** (Fixed Parent + Dynamic Children)
   - Subcategories from WooCommerce `pod-systems-kits` category
   - Refillable systems and replacement pods

5. **🔧 Vape Hardware** (Fixed Parent + Dynamic Children)
   - Maps to `dl-hardware` or `vape-hardware` WooCommerce category
   - Mods, tanks, coils, and spares subcategories

6. **💧 DL E-Liquids** (Fixed Parent + Dynamic Children)
   - Maps to `dl-liquid` or `dl-liquids` WooCommerce category
   - Longfills, pre-mixed, and additives

7. **🍃 MTL & Nic Salts** (Fixed Parent + Dynamic Children)
   - Maps to `nic-salts` or `nic-salts-mtl-liquids` category
   - Nic salts, MTL liquids, and longfills

8. **🏷️ Brands** (Fixed Parent + Dynamic Top 15)
   - Top 15 brands by product count (auto-detected)
   - Auto-updates when brand products change
   - "View All Brands" link
   - Wide dropdown layout (`.adv-dropdown--wide`)

9. **🌿 Nic Alternatives** (Fixed Parent + Dynamic Children)
   - Maps to `nicotine-alternatives` WooCommerce category
   - Pouches, gum, and other alternatives

10. **💼 Support** (Static Parent + Static Children)
    - Fixed items: FAQ, Contact, Track Order, About, Shipping, Returns, T&Cs, Privacy, Refer

### Navigation Logic

**Hybrid Approach:**
- **Parent menus:** Fixed order from v2.0 structure
- **Subcategories:** Dynamic from WooCommerce
- **Mapping:** Function finds WooCommerce categories by slug/name
- **Fallback:** Graceful handling if category doesn't exist

**Category Mapping:**
- Each parent menu maps to a specific WooCommerce category slug
- System tries multiple slug variations (e.g., 'dl-hardware', 'vape-hardware')
- If category not found, that menu section is skipped
- Children automatically populate from WooCommerce hierarchy

**Dynamic Subcategories:**
- Uses `product_cat` taxonomy
- Filters: `hide_empty=true`, `parent=[parent_id]`
- Order: By product count (descending)
- Limit: 12 subcategories per parent
- Automatic "View All" link appended

**Brand Detection:**
- Checks: `brand`, `brands`, `product_brand`, `pa_brand`, `pa_brands`
- Also scans WooCommerce attribute taxonomies
- Top 15 by product count
- Gracefully handles missing brand taxonomy

---

## 📋 Development Roadmap

### ✅ Completed
- [x] Excel product data analysis (6,528 products)
- [x] Category hierarchy mapping
- [x] Brand analysis and ranking
- [x] Backup system creation (v1.0)
- [x] Navigation structure redesign
- [x] header.php updates with new structure
- [x] CSS updates for wider dropdowns
- [x] Split E-Liquids category for better UX

### 🚧 In Progress
- [ ] Testing and validation
- [ ] README roadmap documentation
- [ ] URL verification

### 📅 Backlog
- [ ] Add category icons/emojis
- [ ] Implement mega-menu for Brands
- [ ] Add "Popular Products" quick links
- [ ] Mobile menu UX improvements
- [ ] A/B testing framework
- [ ] Analytics integration
- [ ] SEO optimization
- [ ] Accessibility audit (WCAG 2.1)

---

## 🔧 Technical Details

### Files Structure
```
ADVAPES-NAV-BAR/
├── header.php              # Main header template
├── css.txt                 # Navigation styles
├── README.md               # This file
├── backup/                 # Version backups
│   ├── v1.0/
│   │   ├── header.php
│   │   ├── css.txt
│   │   └── README.md
│   └── README.md
├── nav_structure_analysis.json
└── Products-Export-2025-December-10-0816.xlsx
```

### Technology Stack
- **Frontend:** HTML5, CSS3 (No JavaScript required)
- **CMS:** WordPress with Razzi Child Theme
- **E-Commerce:** WooCommerce
- **Responsive:** Mobile-first approach with 1024px breakpoint

### Browser Support
- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🚀 Installation & Deployment

### Installation Steps (v3.0)

1. **Upload Files to Child Theme**
   ```bash
   # Upload to: /wp-content/themes/razzi-child/
   - advapes-nav.php (new)
   - header.php (modified)
   - css.txt (existing styles, no changes needed)
   ```

2. **Verify File Loading**
   - The `header.php` automatically loads `advapes-nav.php`
   - No additional includes needed in `functions.php`

3. **Clear WordPress Cache**
   ```php
   // Via WordPress admin or WP-CLI
   wp cache flush
   ```

4. **Test Dynamic Navigation**
   - Visit your site homepage
   - Navigation should render automatically
   - Check browser console for errors

5. **Verify REST Endpoint**
   ```bash
   curl https://www.advapes.co.za/wp-json/advapes/v1/nav
   ```

### First-Time Setup

On first load, the system will:
1. Detect your brand taxonomy automatically
2. Query WooCommerce categories
3. Build navigation structure
4. Cache for 30 minutes
5. Schedule cron job for refresh

**No configuration required!** The system is plug-and-play.

---

## 🧪 Testing Checklist

### Pre-Deployment Testing

#### 1. Visual Verification
- [ ] Navigation renders on homepage
- [ ] All dropdowns open on hover (desktop)
- [ ] Mobile menu toggle works
- [ ] Category names display correctly
- [ ] Product counts show (e.g., "123+ products")
- [ ] Brand links are functional
- [ ] Static sections (Deals, Support) intact

#### 2. Dynamic Behavior Testing

**Test Product Count Updates:**
```bash
# 1. Note current count for a category
# 2. Add new product to that category
# 3. Wait up to 30 seconds (cache invalidation)
# 4. Refresh page - count should increment
```

**Test Category Addition:**
```bash
# 1. Create new top-level product category
# 2. Add products to it
# 3. Refresh page - new category should appear
```

**Test Brand Detection:**
```bash
# 1. Add product with brand taxonomy
# 2. Refresh page within 30s
# 3. Brand should appear in top 15 (if count is high enough)
```

#### 3. Cache Testing

**Verify Cache Creation:**
```php
// In WordPress admin > Tools > Site Health > Info > Transients
// Look for: advapes_nav_structure
```

**Force Cache Refresh:**
```bash
# Method 1: Via REST API
curl https://www.advapes.co.za/wp-json/advapes/v1/nav?refresh=true

# Method 2: Delete transient manually
# WP Admin > Tools > Delete Transient: advapes_nav_structure
```

**Verify Cache Invalidation:**
1. Edit a product (change category)
2. Check transient is deleted immediately
3. Next page load rebuilds cache

#### 4. Fallback Testing

**Test Graceful Degradation:**
```php
// Temporarily rename advapes-nav.php
// Page should show fallback menu:
// - Home, Deals, Brands, Support
// No PHP errors should appear
```

#### 5. REST API Testing

```bash
# Basic test
curl https://www.advapes.co.za/wp-json/advapes/v1/nav

# Expected response:
{
  "success": true,
  "data": { ... },
  "cached": true,
  "version": "3.0"
}

# Force refresh test
curl https://www.advapes.co.za/wp-json/advapes/v1/nav?refresh=true
```

#### 6. Performance Testing

**Check Page Load Time:**
- First load (no cache): Should complete in < 2s
- Cached loads: Should be instant (no DB queries)
- Monitor via Query Monitor plugin (optional)

**Check Database Queries:**
```php
// With Query Monitor active:
// - Cached page load: 0 extra queries for nav
// - Uncached: ~3-5 queries (categories, brands)
```

---

## 🐛 Troubleshooting

### Common Issues

#### Navigation Not Showing
**Symptoms:** Blank nav or only fallback menu
**Solutions:**
1. Check `advapes-nav.php` uploaded correctly
2. Verify WordPress/WooCommerce active
3. Check PHP error log: `/wp-content/debug.log`
4. Ensure file permissions: 644

#### Product Counts Not Updating
**Symptoms:** Counts stay static after changes
**Solutions:**
1. Verify cache invalidation hooks are running:
   ```php
   // Check if hooks registered
   has_action('save_post_product', 'advapes_invalidate_nav_cache')
   ```
2. Manually delete transient to test
3. Check WP-Cron is running (not disabled)

#### Brand Taxonomy Not Detected
**Symptoms:** Brands section missing or empty
**Solutions:**
1. Run detection test via REST API:
   ```bash
   curl https://www.advapes.co.za/wp-json/advapes/v1/nav | jq .data.brands
   ```
2. Check taxonomy exists:
   ```php
   // WP Admin > Products > Attributes
   // Or check registered taxonomies
   ```
3. If using custom brand taxonomy, add to detection list

#### Cron Job Not Running
**Symptoms:** Cache never refreshes automatically
**Solutions:**
1. Verify cron scheduled:
   ```php
   wp_next_scheduled('advapes_refresh_nav_cron')
   ```
2. Check WP-Cron not disabled in `wp-config.php`
3. Manually trigger via WP-CLI:
   ```bash
   wp cron event run advapes_refresh_nav_cron
   ```

---

## 🔄 Maintenance

### Manual Cache Clear

**Via WordPress Admin:**
1. Use plugin like "Transients Manager"
2. Find and delete: `advapes_nav_transient`

**Via WP-CLI:**
```bash
wp transient delete advapes_nav_structure
```

**Via REST API:**
```bash
curl "https://www.advapes.co.za/wp-json/advapes/v1/nav?refresh=true"
```

### Updating Static Sections

To modify Deals or Support sections:
1. Edit `advapes-nav.php`
2. Find `$nav_structure['deals']` or `$nav_structure['support']`
3. Update URLs/names/tags
4. Clear cache to see changes

### Customizing Category Limits

To show more/fewer child categories:
```php
// In advapes-nav.php, line ~150
'number' => 12,  // Change to desired limit
```

To show more/fewer brands:
```php
// In advapes-nav.php, line ~190
'number' => 15,  // Change to desired limit
```

---

## 🔙 Rollback Procedures

### Quick Rollback to v2.0 (Static Nav)

If you need to revert to v2.0 static navigation:

```bash
# 1. Restore backup files
cp backup/v2.0/header.php header.php

# 2. Remove dynamic navigation file (optional but recommended)
rm advapes-nav.php

# 3. Clear WordPress cache
wp cache flush

# 4. Clear scheduled cron (optional cleanup)
wp cron event delete advapes_refresh_nav_cron
```

**Result:** Navigation returns to v2.0 static menu, fully functional.

### Rollback to v1.0 (Original Static Nav)

```bash
cp backup/v1.0/header.php header.php
cp backup/v1.0/css.txt css.txt
rm advapes-nav.php
```

### Emergency Fallback

If `advapes-nav.php` is missing or broken, the header.php automatically shows a minimal fallback menu:
- Home
- Deals  
- Brands
- Support

**No site breakage will occur** - the fallback is built into header.php.

---

## 📈 Key Improvements

### v1.0 → v2.0
- ✅ Split E-Liquids into DL and MTL/Nic Salts (better findability)
- ✅ Renamed "DL Hardware" to "Vape Hardware" (clearer)
- ✅ Expanded Pod Disposables with specific brands
- ✅ Added product counts to dropdown titles
- ✅ Increased brand visibility (9 → 15 brands)
- ✅ Better subcategory organization

### v2.0 → v3.0
- ✅ **Dynamic WooCommerce integration** - no more manual updates
- ✅ **Auto-updating product counts** - always accurate
- ✅ **Automatic brand detection** - smart taxonomy detection
- ✅ **Intelligent caching** - 30min transient + proactive invalidation
- ✅ **REST API for debugging** - `/wp-json/advapes/v1/nav`
- ✅ **Scheduled refresh** - WP-Cron every 30 minutes
- ✅ **Graceful fallback** - site never breaks
- ✅ **Zero plugins required** - pure WP/WC APIs

### v3.0 → v3.1 (Current)
- ✅ **Fixed parent menu structure** - consistent top-level navigation from v2.0
- ✅ **User familiarity preserved** - same parent menus in same order
- ✅ **Dynamic subcategories maintained** - content stays fresh
- ✅ **No unexpected menu items** - "Dezemba Dealz" stays under Deals
- ✅ **Best of both worlds** - stability + automation

### v3.1 Benefits

**For Site Admins:**
- No manual nav updates when products change
- Real-time count accuracy  
- Reduced maintenance overhead
- Better scalability as catalog grows
- **Fixed parent menus** - no surprises in navigation structure
- **Predictable behavior** - seasonal items stay in Deals section

**For Users:**
- **Consistent navigation** - parent menus never move
- Always up-to-date subcategories
- Accurate product counts
- **Familiar structure** - easy to remember where things are
- Discover new products automatically in expected locations

**For Performance:**
- Cached navigation (30-minute TTL)
- Minimal database queries
- Proactive cache invalidation
- No impact on page load speed

---

## 🐛 Known Issues

### Current
- None reported

### Resolved
- **(v3.0)** Dynamic categories could show unexpected top-level menus - Fixed in v3.1 with hybrid approach
- (v1.0) Mobile menu overlapping with Razzi menu - Fixed by hiding Razzi mobile burger

---

## 📚 Documentation

### For Developers (v3.0)
- **Dynamic System:** All navigation logic in `advapes-nav.php`
- **Modifying Static Sections:** Edit `$nav_structure['deals']` or `$nav_structure['support']` in `advapes-nav.php`
- **Customizing Limits:** Change `'number' => 12` (categories) or `'number' => 15` (brands) in `advapes_get_nav_structure()`
- **Styling Changes:** Update `css.txt` (no changes needed for v3.0)
- **Cache Management:** Use `advapes_invalidate_nav_cache()` or REST API
- **Debugging:** Visit `/wp-json/advapes/v1/nav` for structure JSON

### For Content Managers (v3.0)
- **No Manual Updates Required!** Categories and counts update automatically
- **Adding Products:** Just add products in WooCommerce - navigation updates within 30 seconds
- **New Categories:** Create in WooCommerce - appears in nav automatically (if top-level)
- **Brands:** Add brand taxonomy to products - top 15 shown automatically
- **Seasonal Updates:** Edit "Dezemba Dealz" link in `advapes-nav.php` (static section)

---

## 🔗 Resources

### Internal Links
- [Backup System](/backup/README.md)
- [Version 1.0 Files](/backup/v1.0/)
- [Product Data Analysis](nav_structure_analysis.json)

### External References
- [ADVapes Website](https://www.advapes.co.za)
- [Razzi Theme Docs](https://razzi.co)
- [WooCommerce Documentation](https://woocommerce.com/documentation/)

---

## 👥 Contributors

**Development Team**
- Navigation Design & Implementation
- Product Data Analysis
- UX/UI Optimization

---

## 📝 Change Log

### v2.0 (2025-12-10)
- Complete navigation restructure based on product data analysis
- Split E-Liquids into DL and MTL/Nic Salts categories
- Expanded Brands section with 15 top brands
- Added product counts to navigation labels
- Improved subcategory organization
- Created comprehensive backup system
- Implemented Trello-style development dashboard

### v1.0 (Previous)
- Initial custom navigation bar implementation
- 10 main categories with dropdowns
- Responsive mobile menu
- ADVapes brand styling

---

## 📞 Support

For issues, questions, or feature requests:
- Create an issue in the repository
- Contact: [ADVapes Support](https://www.advapes.co.za/contact-us/)

---

*This is a living document. Updates are made as development progresses.*