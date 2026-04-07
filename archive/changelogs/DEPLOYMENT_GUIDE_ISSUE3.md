# Deployment Guide - Issue 3 Fixes

## Quick Overview

This update fixes three critical issues with the ADVapes navigation bar:
1. ✅ Navigation wrapping to two lines → Now fits on single line
2. ✅ Missing brands in category dropdowns → Now shows brands from all subcategories
3. ✅ Wrong brand taxonomy → Now uses `pwb-brand` (WordPress Perfect Brands)

## What Was Changed

### 1. CSS Styling (`advapes-nav.css`)
- Reduced spacing to fit all menu items on one line
- Increased container width for better layout

### 2. PHP Logic (`advapes-nav.php`)
- Added support for `pwb-brand` taxonomy (your WordPress Perfect Brands plugin)
- Enhanced brand queries to include products from subcategories
- Improved security and code quality

### 3. Documentation (`README.md`)
- Updated to reflect new taxonomy support

## Deployment Steps

### Step 1: Upload Files to Your Server

Upload these 3 files to `/wp-content/themes/razzi-child/`:
1. `advapes-nav.css`
2. `advapes-nav.php`
3. `header.php` (if not already using latest version)

### Step 2: Clear Navigation Cache

After uploading, clear the navigation cache:

**Option A - Easy Way:**
1. Go to WordPress Admin → Products
2. Click on any product
3. Click "Update" (no changes needed)
4. This clears the cache automatically

**Option B - REST API (Admin Only):**
```bash
curl -X POST https://www.advapes.co.za/wp-json/advapes/v1/nav/refresh \
  --user "admin:your-password"
```

### Step 3: Verify Changes

Visit https://www.advapes.co.za and check:

**✅ Navigation Layout:**
- All menu items on a single line
- No wrapping at different screen widths
- Mobile hamburger menu works correctly

**✅ Brand Display:**
Test these dropdowns specifically:
- Pod Systems & Kits → Should show brands
- DL E-Liquids → Should show brands
- MTL & Nic Salts → Should show brands

**✅ Brand Data:**
- Brand names display correctly
- Product counts show next to brand names
- Clicking brands leads to correct brand pages

## Testing Checklist

```
Desktop (1024px+):
[ ] All 10 menu items visible on one line
[ ] "Deals" menu shows promotions
[ ] "Disposables" shows subcategories + brands
[ ] "Pod Disposables" shows subcategories + brands
[ ] "Pod Systems & Kits" shows subcategories + brands ⭐
[ ] "Vape Hardware" shows subcategories + brands
[ ] "DL E-Liquids" shows subcategories + brands ⭐
[ ] "MTL & Nic Salts" shows subcategories + brands ⭐
[ ] "Nic Alternatives" shows subcategories + brands
[ ] "Brands" shows top 10 brands
[ ] "Support" shows help links

Mobile (<1024px):
[ ] Hamburger menu icon shows
[ ] Tapping icon opens menu
[ ] All items accessible
[ ] Dropdowns work inline
[ ] Can close menu

Brand-Specific:
[ ] Brands have product counts (e.g., "15+ products")
[ ] Brand links work correctly
[ ] Brands are from pwb-brand taxonomy
[ ] Brands appear even when products are in subcategories
```

## Troubleshooting

### Issue: Navigation still wraps to two lines

**Cause:** Browser cache showing old CSS

**Solution:**
1. Hard refresh browser: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
2. Clear WordPress cache (if using caching plugin)
3. Verify `advapes-nav.css` was uploaded correctly

### Issue: Still no brands showing

**Possible Causes & Solutions:**

**A. Cache not cleared:**
- Edit any product and click "Update"
- Wait 30 seconds and refresh page

**B. Products not assigned to brands:**
1. Go to Products → Edit any product in that category
2. Check if "Brand" field is set (should be pwb-brand taxonomy)
3. If missing, assign brands to your products
4. Save and clear cache

**C. Brands in wrong taxonomy:**
1. Go to Products → Brands in WordPress admin
2. Verify you see your brands listed
3. Check the URL - should contain `taxonomy=pwb-brand`
4. If URL is different, update line 62 in `advapes-nav.php`

**D. Products in nested subcategories:**
- This is now supported! Products can be in any subcategory
- Make sure products are published (not drafts)
- Verify category hierarchy is correct

### Issue: Wrong brands showing

**Cause:** Cache needs refresh

**Solution:**
1. Edit a product in the affected category
2. Click "Update"
3. Clear WordPress cache
4. Hard refresh browser

## Technical Details

### Brand Taxonomy Priority
The system checks for brands in this order:
1. `pwb-brand` ⭐ (WordPress Perfect Brands - your site)
2. `product_brand` (WooCommerce Brands)
3. `brand`, `brands` (generic)
4. `pa_brand`, `pa_brands` (attributes)

### Category Hierarchy
Brands are now pulled from parent categories AND all subcategories:
```
Pod Systems & Kits (parent)
  ├── Refillable Pods (child)
  ├── Pod Mods (child)
  └── All-in-One Kits (child)

Brands shown = Brands from ALL 4 categories combined
```

### Cache Behavior
- Cache duration: 30 minutes
- Auto-refresh on product/category/brand changes
- Manual refresh: Edit any product → Update

### CSS Constraints
- Container width: 1400px max
- Item padding: 8px horizontal
- Font size: 11px
- Can fit 10 menu items comfortably
- Adding more items may require further adjustment

## Rollback Instructions

If you need to revert to the previous version:

### Option 1: Revert in WordPress
1. Use your theme backup (if available)
2. Restore previous versions of the 3 files

### Option 2: Git Revert (if using version control)
```bash
git revert 5d5cdd0
git revert dbfa53c
git revert 536f870
git revert ee6877a
git revert ee36580
git push
```

### Option 3: Manual Revert
Contact support with commit hash `9945e0b` to restore previous version

## Support Information

**Last Updated:** December 11, 2025  
**Version:** 3.1.3  
**Branch:** `copilot/fix-nav-bar-issues`  
**Commits:** `ee36580` → `5d5cdd0`  

**Files Changed:**
- `advapes-nav.css` (styling)
- `advapes-nav.php` (functionality)
- `README.md` (documentation)

**Documentation:**
- Full changes: `archive/ISSUE3_CHANGES.md`
- Integration guide: `README.md`

## Expected Results After Deployment

**Before:**
- Navigation wrapped to 2 lines
- Pod Systems & Kits: No brands shown
- DL E-Liquids: No brands shown
- MTL & Nic Salts: No brands shown

**After:**
- Navigation on 1 line ✅
- Pod Systems & Kits: Shows top 4 brands ✅
- DL E-Liquids: Shows top 4 brands ✅
- MTL & Nic Salts: Shows top 4 brands ✅
- All brands from `pwb-brand` taxonomy ✅
- Includes brands from subcategories ✅

## Questions?

If you encounter any issues not covered in this guide:
1. Check browser console for JavaScript errors (F12)
2. Verify all files uploaded correctly
3. Confirm cache was cleared
4. Check WordPress error log
5. Test in incognito/private browsing mode

---

**Ready to Deploy!** Follow the steps above and your navigation will be fixed. 🚀
