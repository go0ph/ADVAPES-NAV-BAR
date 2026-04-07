# V3 Navigation - Quick Start Guide

## 🚀 How to Install V3 Navigation

This guide helps you quickly install and test the new V3 brand-first navigation structure.

---

## Option 1: Quick Installation (Recommended)

### Step 1: Download the V3 Files

Download these three files from the repository:
- `advapes-nav-v3.php` (navigation logic)
- `header-v3.php` (header template)
- `README-V3.md` (documentation)

### Step 2: Upload to Your WordPress Theme

Upload the files to your Razzi child theme directory:
```
/wp-content/themes/razzi-child/
```

### Step 3: Activate V3

**Option A: Rename files** (easiest)
```bash
# Backup existing files
mv header.php header-v2-backup.php
mv advapes-nav.php advapes-nav-v2-backup.php

# Activate V3
cp header-v3.php header.php
cp advapes-nav-v3.php advapes-nav.php
```

**Option B: Edit header.php**

Open your `header.php` and change line 14:
```php
// OLD (V2):
require_once __DIR__ . '/advapes-nav.php';

// NEW (V3):
require_once __DIR__ . '/advapes-nav-v3.php';
```

### Step 4: Clear Cache

```bash
# If using WordPress CLI
wp cache flush

# If using a caching plugin
# Go to your plugin settings and clear all caches
```

### Step 5: Test

1. Visit your site on **desktop** - you should see:
   - New menu order: Deals, **Brands**, Disposables, Devices, Liquids, Nic Alternatives, Support
   - Brands in 2nd position (not 9th)
   - Brands dropdown shows "Top Brands" section

2. Visit your site on **mobile** (or resize browser to ≤1024px):
   - Same flyout drawer as before
   - Brands accessible in 1 tap
   - New menu structure in drawer

---

## Option 2: Staging/Testing Installation

### For Testing on Staging Site First

1. **Upload V3 files** to staging site
2. **Follow Option 1 steps** above
3. **Test thoroughly** (see Testing Checklist below)
4. **Copy to production** once validated

---

## Option 3: Conditional Loading (Advanced)

### Load V3 Only for Specific Users

Add this to your `functions.php`:

```php
/**
 * Conditionally load V3 navigation for testing
 */
function advapes_conditional_v3_load() {
    // Load V3 only for admin users
    if ( current_user_can( 'manage_options' ) ) {
        require_once __DIR__ . '/advapes-nav-v3.php';
    } else {
        // Load V2 for everyone else
        require_once __DIR__ . '/advapes-nav.php';
    }
}
```

Then update `header.php` line 14:
```php
// Replace direct require with conditional function
// require_once __DIR__ . '/advapes-nav.php';
advapes_conditional_v3_load();
```

---

## 🧪 Testing Checklist

### Desktop Testing (5 minutes)

Open your site on desktop and verify:

- [ ] **Menu order is correct:**
  1. Deals
  2. **Brands** (NEW POSITION!)
  3. Disposables
  4. Devices (NEW!)
  5. Liquids (NEW!)
  6. Nic Alternatives
  7. Support

- [ ] **Brands menu:**
  - [ ] Shows "Top Brands" section at top
  - [ ] Lists: Bewolk, Nasty, Airscream, Oxbar, Oxva, Vuse
  - [ ] Shows "All Brands (A–Z)" link at bottom

- [ ] **Disposables menu:**
  - [ ] Shows "All Disposables"
  - [ ] Shows "One-Use Disposables" (if category exists)
  - [ ] Shows "High-Puff Disposables" (if category exists)
  - [ ] Shows "Popular Disposable Brands"
  - [ ] NO DL/MTL terminology

- [ ] **Devices menu (NEW):**
  - [ ] Shows "Pod Systems & Kits"
  - [ ] Shows "Refillable Pods"
  - [ ] Shows "Vape Mods"
  - [ ] Shows "Coils & Spares"
  - [ ] Shows "Top Brands"

- [ ] **Liquids menu (NEW):**
  - [ ] Shows "Nic Salts"
  - [ ] Shows "Freebase Liquid"
  - [ ] Shows "Longfills"
  - [ ] Shows "Additives & Boosters"
  - [ ] Shows "Top Brands"
  - [ ] NO DL/MTL terminology in nav

- [ ] **Visual design:**
  - [ ] Colors unchanged (dark background, red accent)
  - [ ] Fonts unchanged
  - [ ] Spacing unchanged
  - [ ] Hover effects work correctly

### Mobile Testing (5 minutes)

Resize browser to ≤1024px or use mobile device:

- [ ] **Hamburger menu:**
  - [ ] Opens drawer from right
  - [ ] Shows new menu order
  - [ ] **Brands is 2nd item** (easy to reach)

- [ ] **Navigation:**
  - [ ] Tap "Brands" → opens Brands panel
  - [ ] Tap "Top Brands" section → shows priority brands
  - [ ] Tap "Disposables" → opens Disposables panel
  - [ ] Tap "Devices" → opens Devices panel (consolidated)
  - [ ] Tap "Liquids" → opens Liquids panel (consolidated)
  - [ ] Back button works correctly
  - [ ] Overlay closes drawer

- [ ] **Usability:**
  - [ ] Can reach any brand in 2 taps max
  - [ ] Can reach any category in 2 taps max
  - [ ] No confusing DL/MTL terms

---

## ✅ What Should You See?

### Desktop View

```
┌─────────────────────────────────────────────────────┐
│  Deals  │  Brands  │  Disposables  │  Devices  │  Liquids  │  Nic Alternatives  │  Support  │
└─────────────────────────────────────────────────────┘
           ▼
      ┌────────────────┐
      │ Top Brands     │ ← NEW!
      ├────────────────┤
      │ • Bewolk       │
      │ • Nasty        │
      │ • Airscream    │
      │ • Oxbar        │
      │ • Oxva         │
      │ • Vuse         │
      ├────────────────┤
      │ All Brands (A–Z)│
      └────────────────┘
```

### Mobile View

```
┌─────────────────┐
│ ≡ Menu     [×]  │
├─────────────────┤
│ ▸ Deals         │
│ ▸ Brands        │ ← 2nd position!
│ ▸ Disposables   │
│ ▸ Devices       │ ← NEW!
│ ▸ Liquids       │ ← NEW!
│ ▸ Nic Altern... │
│ ▸ Support       │
└─────────────────┘
```

---

## 🔧 Troubleshooting

### Issue: Navigation looks the same (no changes visible)

**Cause:** Cache not cleared or V3 files not loaded

**Solution:**
1. Hard refresh browser (Ctrl+Shift+R / Cmd+Shift+R)
2. Clear WordPress cache
3. Check that `advapes-nav-v3.php` is being loaded (add `error_log('V3 loaded');` to line 10)
4. Check server error logs

### Issue: Brands menu doesn't show "Top Brands" section

**Cause:** Brand taxonomy not configured or priority brands don't exist

**Solution:**
1. Check that brands exist in WooCommerce (Products → Attributes → Brands)
2. Verify brand names match exactly: Bewolk, Nasty, Airscream, Oxbar, Oxva, Vuse
3. Check brand detection in `advapes_v3_detect_brand_taxonomy()` function

### Issue: Devices or Liquids menu is empty

**Cause:** Required categories don't exist with expected slugs

**Solution:**
1. Check WooCommerce categories (Products → Categories)
2. Verify slug names:
   - `pod-systems-kits` for Pod Systems
   - `refillable-pods` for Refillable Pods
   - `vape-mods` or `mods` for Vape Mods
   - `coils-spares` or `coils` for Coils & Spares
   - `nic-salts` for Nic Salts
   - `freebase-liquid`, `dl-liquid`, or `dl-liquids` for Freebase
3. Update category slugs in WooCommerce or modify V3 code to match your slugs

### Issue: Navigation breaks on mobile

**Cause:** JavaScript error or conflicting code

**Solution:**
1. Open browser console (F12) and check for errors
2. Verify `advapes_v3_enqueue_nav_scripts()` is running
3. Check for JavaScript conflicts with other plugins
4. Rollback to V2 (see below)

---

## 🔙 How to Rollback to V2

If V3 has issues, rollback in 30 seconds:

### Method 1: Restore Backup Files

```bash
# Restore V2 files from backup
cp header-v2-backup.php header.php
cp advapes-nav-v2-backup.php advapes-nav.php

# Clear cache
wp cache flush
```

### Method 2: Rename Files

```bash
# Deactivate V3
mv header.php header-v3-test.php
mv advapes-nav-v3.php advapes-nav-v3-test.php

# Restore V2 originals
# (they should still be in the directory)
```

### Method 3: Edit header.php

Change line 14 back to:
```php
// V2 (original):
require_once __DIR__ . '/advapes-nav.php';
```

---

## 📊 What to Monitor After Launch

### Week 1: Navigation Behavior

- **Brands menu clicks** (should increase significantly)
- **Devices vs old Pod Systems + Vape Hardware** (consolidation impact)
- **Liquids vs old DL + MTL menus** (consolidation impact)
- **Mobile vs desktop** navigation patterns

### Week 2: Search Behavior

- **Brand searches** (should decrease - people find brands in nav)
- **"DL" / "MTL" searches** (should decrease - simpler terms)
- **Category searches** (should stay similar or decrease)

### Week 3: Conversion Metrics

- **Category page → Product page rate** (should improve)
- **Navigation → Add to cart rate** (should improve)
- **Bounce rate on category pages** (should decrease)

---

## 📞 Need Help?

**Repository:** go0ph/ADVAPES-NAV-BAR  
**Version:** 5.0.0 (V3)  
**Documentation:** README-V3.md

**Before asking for help, check:**
1. ✅ V3 files uploaded correctly
2. ✅ Cache cleared
3. ✅ WooCommerce active
4. ✅ Required categories exist
5. ✅ Brand taxonomy configured

---

## 🎉 Success!

If all tests pass, V3 is working correctly. You now have:

- ✅ Brand-first navigation (Brands in 2nd position)
- ✅ Priority brands accessible (Bewolk, Nasty, etc.)
- ✅ Simplified structure (no DL/MTL jargon)
- ✅ Consolidated menus (Devices, Liquids)
- ✅ Mobile-optimized (1-2 taps to any item)
- ✅ Same visual design (no CSS changes)

**Next steps:**
1. Monitor analytics for 2-3 weeks
2. Collect user feedback
3. Adjust if needed (all V2 files preserved)

---

**Installation time:** ~10 minutes  
**Testing time:** ~10 minutes  
**Total time:** ~20 minutes

Let's ship it! 🚀
