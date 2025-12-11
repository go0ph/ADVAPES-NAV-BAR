# Brand Display Enhancement Guide - v3.1.1

## Overview

Version 3.1.1 introduces enhanced brand visibility in category dropdowns with accurate product counts. This guide shows the expected output for each category dropdown that includes brand listings.

---

## Expected Navigation Structure Order

The navigation menu items appear in this order:

1. 🔥 **Deals**
2. 📱 **Disposables**
3. 🔌 **Pod Disposables**
4. 💨 **Pod Systems & Kits** ← Includes brands
5. 🔧 **Vape Hardware**
6. 💧 **DL E-Liquids** ← Includes brands
7. 🍃 **MTL & Nic Salts** ← Includes brands
8. 🌿 **Nic Alternatives**
9. 🏷️ **Brands** ← Main Brands menu (always shows between Nic Alternatives and Support)
10. 💼 **Support**

---

## Category Dropdowns with Brand Display

### 1. Pod Systems & Kits

**Expected Output:**
```
Pod Systems & Kits
├── Dropdown Title: "Refillable pod systems (230+)"
├── Refillable pod systems ──────── 230+ products
├── Refillable Pods ───────────────── 20+ products
├── [Subcategory 3] ───────────────── XX+ products
├── [Subcategory 4] ───────────────── XX+ products
│
├── 🏷️ Airscream ────────────────── 20+ products
├── 🏷️ Bewolk ──────────────────────  5+ products
├── 🏷️ Caliburn ────────────────── 27+ products
├── 🏷️ Uwell ───────────────────── 15+ products
│
└── All Pod Systems & Kits ────── Browse all
```

**Key Features:**
- Shows up to 10 subcategories first
- Then shows top 4 brands with product counts
- Finally shows "View All" link
- Brands display actual product counts from database query
- Brands are ordered by product count (highest first)

---

### 2. DL E-Liquids

**Expected Output:**
```
DL E-Liquids
├── Dropdown Title: "Direct lung liquids (XXX+)"
├── [Subcategory 1] ───────────────── XX+ products
├── [Subcategory 2] ───────────────── XX+ products
├── [Subcategory 3] ───────────────── XX+ products
│
├── 🏷️ [Brand 1] ──────────────────── XX+ products
├── 🏷️ [Brand 2] ──────────────────── XX+ products
├── 🏷️ [Brand 3] ──────────────────── XX+ products
├── 🏷️ [Brand 4] ──────────────────── XX+ products
│
└── All DL E-Liquids ───────────── Browse all
```

**Key Features:**
- Dynamic subcategories from WooCommerce
- Top 4 brands by product count within this category
- Product counts update automatically
- Brands only show if products exist in this category

---

### 3. MTL & Nic Salts

**Expected Output:**
```
MTL & Nic Salts
├── Dropdown Title: "Nic salts & MTL liquids (XXX+)"
├── [Subcategory 1] ───────────────── XX+ products
├── [Subcategory 2] ───────────────── XX+ products
├── [Subcategory 3] ───────────────── XX+ products
│
├── 🏷️ [Brand 1] ──────────────────── XX+ products
├── 🏷️ [Brand 2] ──────────────────── XX+ products
├── 🏷️ [Brand 3] ──────────────────── XX+ products
├── 🏷️ [Brand 4] ──────────────────── XX+ products
│
└── All MTL & Nic Salts ────────── Browse all
```

**Key Features:**
- Nic salt and MTL-specific brands
- Shows brands popular in this category
- Helps users find their preferred nic salt brands quickly
- Updates automatically as products are added/removed

---

## Main Brands Menu (Always Present)

**Expected Output:**
```
Brands
├── Dropdown Title: "Shop by brand (XX+)"
├── [Top Brand 1] ────────────────── XXX+ products
├── [Top Brand 2] ────────────────── XXX+ products
├── [Top Brand 3] ────────────────── XXX+ products
├── [Top Brand 4] ────────────────── XXX+ products
├── [Top Brand 5] ────────────────── XXX+ products
├── [Top Brand 6] ────────────────── XXX+ products
├── [Top Brand 7] ────────────────── XXX+ products
├── [Top Brand 8] ────────────────── XXX+ products
├── [Top Brand 9] ────────────────── XXX+ products
├── [Top Brand 10] ───────────────── XXX+ products
│
└── View All Brands ────────────── A–Z
```

**Key Features:**
- Shows top 10 brands across ALL categories
- Ordered by total product count
- Always positioned between "Nic Alternatives" and "Support"
- Wide dropdown layout for better visibility

---

## Technical Implementation Details

### Brand Detection

The system automatically detects brand taxonomy from:
- Direct taxonomies: `brand`, `brands`, `product_brand`
- WooCommerce attributes: `pa_brand`, `pa_brands`
- Any custom attribute containing "brand" in the name

### Brand Query (Per Category)

```php
// Query finds brands that have products in a specific category
SELECT t.term_id, t.name, COUNT(DISTINCT p.ID) as product_count
FROM terms t
INNER JOIN term_taxonomy tt ON t.term_id = tt.term_id
INNER JOIN term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
INNER JOIN posts p ON tr.object_id = p.ID
INNER JOIN term_relationships tr2 ON p.ID = tr2.object_id
INNER JOIN term_taxonomy tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
WHERE tt.taxonomy = 'brand' (or detected brand taxonomy)
AND tt2.taxonomy = 'product_cat'
AND tt2.term_id = [category_id]
AND p.post_type = 'product'
AND p.post_status = 'publish'
GROUP BY t.term_id, t.name
ORDER BY product_count DESC
LIMIT 4
```

### Brand Data Structure

Each brand in the array contains:
```php
array(
    'name'  => 'Airscream',        // Brand name
    'url'   => '/brand/airscream/', // Brand archive URL
    'count' => 20,                  // Product count (integer)
)
```

### Rendering Logic

```php
// Display logic in advapes_render_nav()
if ( ! empty( $child['count'] ) ) {
    echo '<span class="adv-tag">' . absint( $child['count'] ) . '+ products</span>';
}
```

---

## Benefits

### For Customers
- ✅ **Quick brand discovery** - See popular brands without leaving navigation
- ✅ **Informed decisions** - Product counts show brand popularity
- ✅ **Faster navigation** - Direct links to favorite brands from category dropdowns
- ✅ **Better UX** - Brands appear where users expect them (in relevant categories)

### For Store Owners
- ✅ **Automatic updates** - Brands appear/disappear based on inventory
- ✅ **Zero maintenance** - No manual brand list updates required
- ✅ **Increased discoverability** - Top brands get more visibility
- ✅ **Better conversion** - Users find brands faster = more sales

### For SEO
- ✅ **More internal links** - Brand pages get more link equity
- ✅ **Better crawlability** - Search engines find brand pages easier
- ✅ **Improved site structure** - Clear category-brand relationships

---

## Cache Behavior

- **Cache Duration:** 30 minutes
- **Auto-invalidation:** When products, categories, or brands are updated
- **Manual refresh:** Via REST API `/wp-json/advapes/v1/nav?refresh=true`

Brand counts update within 30 seconds of product changes due to proactive cache invalidation.

---

## Testing Checklist

- [ ] Verify "Brands" menu appears between "Nic Alternatives" and "Support"
- [ ] Check Pod Systems & Kits shows top brands with counts
- [ ] Check DL E-Liquids shows top brands with counts
- [ ] Check MTL & Nic Salts shows top brands with counts
- [ ] Verify brand counts are accurate
- [ ] Test brand links navigate correctly
- [ ] Verify brands ordered by product count (highest first)
- [ ] Check "View All" links appear after brands
- [ ] Test cache invalidation (add product, verify count updates within 30s)
- [ ] Verify responsive design (mobile and desktop)

---

## Troubleshooting

### Brands not showing in category dropdowns

**Possible causes:**
1. Brand taxonomy not detected
   - Check if brand taxonomy exists in WooCommerce
   - Verify taxonomy name matches detection candidates
   
2. No brands have products in that category
   - Add products with brands to the category
   - Verify products are published (not draft)

3. Cache not refreshed
   - Force refresh via REST API: `/wp-json/advapes/v1/nav?refresh=true`
   - Or wait 30 minutes for automatic refresh

### Main Brands menu not showing

**Possible causes:**
1. Brand taxonomy not detected
   - Verify brand taxonomy exists
   - Check WooCommerce > Products > Attributes
   
2. No brands exist in system
   - Add brand taxonomy to products
   - Ensure products are published

3. Conditional logic prevents display
   - Check if `advapes_detect_brand_taxonomy()` returns valid taxonomy
   - Verify brands array is not empty

---

## Version Information

- **Version:** 3.1.1
- **Release Date:** 2025-12-11
- **Previous Version:** 3.1 (generic brand tags)
- **Next Planned:** Mobile menu improvements

---

## Related Documentation

- [README.md](README.md) - Full project documentation
- [advapes-nav.php](advapes-nav.php) - Source code with inline documentation
- [advapes-nav.css](advapes-nav.css) - Navigation styles

---

*This guide is part of the ADVAPES Navigation Bar project. For questions or issues, see the main README.md file.*
