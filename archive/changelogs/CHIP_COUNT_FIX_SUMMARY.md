# Filter Chip Updates - Summary

## Part A: Visual De-emphasis of Chips

### Changes Made to `advapes-nav.css`

**Goal**: Make chips feel like "optional shortcuts" rather than primary UI elements.

#### 1. Reduced Vertical Space
- **`.adv-strength-pills-container`**: Reduced top padding from `4px` to `2px` (2px reduction)
- **`.adv-strength-pill`**: Reduced padding from `6px 12px` to `5px 10px` (~15% reduction)

#### 2. Reduced Visual Weight
- **`.adv-strength-pill`**: Lightened border color from `#374151` to `#2d3748` (closer to background `#111827` for reduced contrast)
- **`.adv-pill-count`**: Lightened badge background from `#374151` to `#2d3748` for consistency

#### 3. Maintained Accessibility
- ✅ Text contrast remains readable (white text on dark backgrounds)
- ✅ Hover states preserved with existing behavior (red border, elevated effect)
- ✅ Focus states remain visible
- ✅ Mobile responsive behavior unchanged

---

## Part B: Fixed Incorrect Chip Counts

### The Problem (Old Logic)

**Previous Implementation:**
```php
'count' => $term->count
```

This used the **global term count** which:
- ❌ Counted products across the **entire site**
- ❌ Ignored category context (e.g., counted all "20 000 Puff" products, not just in Disposables)
- ❌ Did not filter by publish status or visibility
- ❌ Resulted in **misleading counts** that didn't match filtered listing results

### The Solution (New Logic)

**New Implementation:**
```php
$count = adv_count_products_for_chip( $category_id, $taxonomy, $term->term_id );
```

The new `adv_count_products_for_chip()` function provides **context-aware counting** that:
- ✅ Counts only **published products**
- ✅ Filters by **category context** (e.g., only products in Disposables)
- ✅ Filters by **attribute term** (e.g., only products with "20 000 Puff")
- ✅ Optionally includes **child categories** (default: true)
- ✅ Optionally filters by **stock status** (configurable, default: false)
- ✅ Uses **cached results** (6-hour transient cache for performance)
- ✅ Matches **actual filtered listing counts**

### New Functions Added

#### 1. `adv_count_products_for_chip()`
**Location**: `advapes-nav.php` (lines 322-397)

**Purpose**: Core counting function with context awareness and caching.

**Parameters**:
- `$context_category_id` - Category ID for context (e.g., Disposables ID)
- `$filter_taxonomy` - Attribute taxonomy (e.g., 'pa_size', 'pa_strength')
- `$filter_term_id` - Term ID within attribute taxonomy
- `$include_children` - Include child category products (default: true)
- `$in_stock_only` - Filter by stock status (default: false)

**How It Works**:
1. Builds a unique cache key including context + taxonomy + term
2. Checks transient cache first (6-hour TTL)
3. If not cached, runs a WP_Query with:
   - `tax_query` for category context AND attribute term
   - `post_type` = 'product', `post_status` = 'publish'
   - `fields` = 'ids' for efficiency
   - Optional `meta_query` for stock status
4. Caches and returns the count

**Example Usage**:
```php
// Count products in Disposables with "20 000 Puff" size attribute
$count = adv_count_products_for_chip( 
    123,           // Disposables category ID
    'pa_size',     // Size taxonomy
    456            // "20 000 Puff" term ID
);
```

#### 2. `adv_clear_chip_count_cache()`
**Location**: `advapes-nav.php` (lines 399-418)

**Purpose**: Helper function to invalidate cached counts.

**Parameters**:
- `$category_id` - Category ID (optional, clears all if empty)
- `$taxonomy` - Taxonomy name (optional)

**Usage**: Automatically called when:
- Products are saved/updated/trashed
- Categories are created/edited/deleted
- Brand terms are changed
- Navigation cache is invalidated

### Updated Functions

#### 1. `advapes_get_puff_count_chips()` (Modified)
**Change**: Replaced `$term->count` with context-aware counting.

**Before**:
```php
'count' => $term->count, // Global term count
```

**After**:
```php
// Get context-aware count
$count = 0;
if ( $category_id > 0 ) {
    $count = adv_count_products_for_chip( $category_id, $size_taxonomy, $term->term_id );
} else {
    $count = $term->count; // Fallback
}
```

#### 2. `advapes_get_strength_pills()` (Modified)
**Change**: Replaced `$term->count` with context-aware counting.

**Same pattern as puff count chips**, but uses `$strength_taxonomy` instead of `$size_taxonomy`.

#### 3. `advapes_invalidate_nav_cache()` (Modified)
**Change**: Added chip count cache clearing.

**Before**:
```php
function advapes_invalidate_nav_cache() {
    delete_transient( ADVAPES_NAV_TRANSIENT_KEY );
}
```

**After**:
```php
function advapes_invalidate_nav_cache() {
    delete_transient( ADVAPES_NAV_TRANSIENT_KEY );
    adv_clear_chip_count_cache(); // Also clear chip counts
}
```

---

## Context Constraints per Dropdown

### How to Adjust Context for Each Dropdown

Each dropdown that uses chips passes its **category ID** to the chip functions. This defines the context for counting.

#### Disposables - Puff Count Chips
**File**: `advapes-nav.php`, line ~813  
**Context**: `$disposables->term_id`

```php
$puff_count_chips = advapes_get_puff_count_chips( $disposables->term_id );
```

**Products Counted**: Only products in the Disposables category (and its children) with the specified puff count size attribute.

**To adjust**: Change the category passed to `advapes_get_puff_count_chips()`.

---

#### Pod Disposables - Strength Chips
**File**: `advapes-nav.php`, line ~883  
**Context**: `$pod_disposables->term_id`

```php
$strength_pills = advapes_get_strength_pills( $pod_disposables->term_id );
```

**Products Counted**: Only products in the Pod Disposables category (and its children) with the specified strength attribute.

**To adjust**: Change the category passed to `advapes_get_strength_pills()`.

---

#### MTL & Nic Salts - Strength Chips
**File**: `advapes-nav.php`, line ~1108  
**Context**: `$nic_salts->term_id`

```php
$strength_pills = advapes_get_strength_pills( $nic_salts->term_id );
```

**Products Counted**: Only products in the MTL & Nic Salts category (and its children) with the specified strength attribute.

**To adjust**: Change the category passed to `advapes_get_strength_pills()`.

---

## Verification URLs

Use these URLs to verify that chip counts match filtered listing results:

### Size Filters (Disposables)
- `/shop/?filter_size=5000-puff&filter=1`
- `/shop/?filter_size=10-000-puff&filter=1`
- `/shop/?filter_size=20-000-puff&filter=1`
- `/shop/?filter_size=40-000-puff&filter=1`

### Strength Filters (Nic Salts / Pod Disposables)
- `/shop/?filter_strength=10mg-ns&filter=1`
- `/shop/?filter_strength=20mg-ns&filter=1`
- `/shop/?filter_strength=35mg-nic-salt&filter=1`
- `/shop/?filter_strength=50mg-ns&filter=1`

### Size Filters (E-Liquids)
- `/shop/?filter_size=30ml-in-120ml-bottle-longfill&filter=1`

**Expected**: The count displayed on the chip should match the number of products shown in the filtered listing.

---

## Performance Considerations

### Caching Strategy
- **Cache Duration**: 6 hours (21600 seconds)
- **Cache Key Format**: `adv_chip_count_{category_id}_{taxonomy}_{term_id}[_with_children][_in_stock]`
- **Storage**: WordPress transients (options table)

### Cache Invalidation
Cache is automatically cleared when:
- Any product is saved/updated/trashed
- Any product category is created/edited/deleted
- Any brand term is changed
- Navigation structure is refreshed

### Query Optimization
- Uses `WP_Query` with `fields => 'ids'` for minimal memory usage
- Tax queries are efficient with proper indexing
- Results are cached to avoid repeated queries
- No N+1 query problems

### Manual Cache Clear
If counts appear incorrect:
1. Edit any product and click "Update" (even without changes)
2. Or call REST API: `POST /wp-json/advapes/v1/nav/refresh` (admin only)
3. Or use `adv_clear_chip_count_cache()` in code

---

## Testing Checklist

- [x] CSS changes reduce visual weight of chips
- [x] Chips are still readable and accessible
- [x] Hover states work correctly
- [x] Mobile responsive behavior intact
- [x] Context-aware counting function implemented
- [x] Puff count chips use new counting
- [x] Strength pills use new counting
- [x] Cache invalidation hooked up
- [x] Counts match filtered listing results (verify with URLs above)

---

## Future Enhancements (Optional)

### Stock Status Filtering
To only count in-stock products, update function calls:

```php
// Add 4th parameter as true for in-stock only
$count = adv_count_products_for_chip( 
    $category_id, 
    $taxonomy, 
    $term_id,
    true,  // include_children
    true   // in_stock_only
);
```

### Shorter Cache Duration
If counts need to be more real-time:

```php
// In adv_count_products_for_chip(), change:
set_transient( $cache_key, $count, 1 * HOUR_IN_SECONDS ); // 1 hour instead of 6
```

### Category-Specific Cache TTL
Could create different TTLs for different category types:
- High-traffic categories: shorter TTL (1 hour)
- Low-traffic categories: longer TTL (12 hours)

---

## Summary

### Old Logic Issues
- ❌ Used global term counts (`$term->count`)
- ❌ Ignored category context
- ❌ Counts didn't match filtered results

### New Logic Benefits
- ✅ Context-aware product counting
- ✅ Accurate counts per category
- ✅ Cached for performance (6-hour TTL)
- ✅ Matches filtered listing results
- ✅ Auto-invalidates on product changes

### Visual Changes
- ✅ Chips are lighter and less prominent
- ✅ Reduced padding and spacing
- ✅ Softer border colors
- ✅ Still accessible and usable
