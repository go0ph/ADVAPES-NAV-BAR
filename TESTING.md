# ADVapes Navigation v3.0 - Testing Guide

This document provides comprehensive testing instructions for validating the v3.0 dynamic navigation system before production deployment.

---

## 📋 Pre-Deployment Checklist

### Environment Setup
- [ ] Staging environment mirrors production
- [ ] WooCommerce is active and configured
- [ ] Products and categories exist
- [ ] Brand taxonomy configured (if applicable)
- [ ] Files uploaded correctly:
  - [ ] `advapes-nav.php` in child theme root
  - [ ] `header.php` modified version
  - [ ] `css.txt` (unchanged from v2.0)

---

## 🧪 Test Suite

### Test 1: Initial Load & Visual Verification

**Objective:** Verify navigation renders correctly on first load

**Steps:**
1. Clear all WordPress caches
2. Visit homepage: `https://www.advapes.co.za/`
3. Inspect navigation bar

**Expected Results:**
- [ ] Navigation renders without errors
- [ ] All categories display with names
- [ ] Dropdown menus open on hover (desktop)
- [ ] Product counts show (e.g., "1,405+" or "123+ products")
- [ ] Brands section appears (if brand taxonomy exists)
- [ ] Static sections (Deals, Support) intact
- [ ] Mobile toggle button visible on mobile
- [ ] CSS styling matches v2.0 appearance

**Failure Actions:**
- Check PHP error log: `/wp-content/debug.log`
- Verify `advapes-nav.php` loaded: View page source for functions
- Check browser console for JavaScript errors
- Verify WooCommerce is active

---

### Test 2: Cache Creation

**Objective:** Verify transient cache is created and persists

**Steps:**
1. Load homepage (fresh page load)
2. Check for transient in database:
   ```sql
   SELECT * FROM wp_options 
   WHERE option_name = '_transient_advapes_nav_structure' 
   OR option_name = '_transient_timeout_advapes_nav_structure';
   ```
3. Or use WP-CLI:
   ```bash
   wp transient get advapes_nav_structure
   ```

**Expected Results:**
- [ ] Transient `advapes_nav_structure` exists
- [ ] Timeout set to ~30 minutes in future
- [ ] Transient contains valid navigation array
- [ ] Second page load uses cached version (check Query Monitor)

**Failure Actions:**
- Check if transient API is working: `wp transient set test 123`
- Verify cache directory permissions
- Check if object cache is interfering

---

### Test 3: REST API Endpoint

**Objective:** Verify REST endpoint returns correct data

**Steps:**
1. Access endpoint:
   ```bash
   curl https://www.advapes.co.za/wp-json/advapes/v1/nav
   ```

**Expected Results:**
```json
{
  "success": true,
  "data": {
    "deals": { ... },
    "cat_disposables": { ... },
    "brands": { ... },
    "support": { ... }
  },
  "cached": true,
  "version": "3.0"
}
```

- [ ] HTTP 200 status code
- [ ] Valid JSON response
- [ ] `success: true`
- [ ] `data` object contains navigation structure
- [ ] `version: "3.0"`
- [ ] `cached` field present

**Test Force Refresh:**
```bash
curl https://www.advapes.co.za/wp-json/advapes/v1/nav?refresh=true
```

- [ ] Returns fresh data
- [ ] `cached: false` on first call after refresh

**Failure Actions:**
- Check REST API is enabled: `wp rest list`
- Verify permalink structure is set
- Check for conflicting REST API plugins

---

### Test 4: Dynamic Product Count Updates

**Objective:** Verify counts update when products change

**Steps:**
1. Note current count for a category (e.g., "Disposables (1,405+)")
2. Add a new product to that category in WooCommerce
3. Save/publish the product
4. Wait 5 seconds (for cache invalidation)
5. Refresh homepage

**Expected Results:**
- [ ] Product count increments by 1
- [ ] Change visible within 30 seconds
- [ ] Transient was deleted on product save
- [ ] New transient created on page load

**Test Variations:**
- [ ] Add product to new category → new category appears
- [ ] Delete product → count decrements
- [ ] Trash product → count decrements
- [ ] Untrash product → count increments

**Failure Actions:**
- Check if `save_post_product` hook fires
- Verify cache invalidation function called
- Check error logs for hook failures

---

### Test 5: Category Changes

**Objective:** Verify navigation updates when categories change

**Test 5a: Create New Category**

**Steps:**
1. Create new top-level product category: "Test Category"
2. Add 5 products to it
3. Refresh homepage within 30 seconds

**Expected Results:**
- [ ] New category appears in navigation
- [ ] Shows correct product count
- [ ] Link goes to correct category page
- [ ] Child categories appear if created

**Test 5b: Edit Category**

**Steps:**
1. Edit existing category name
2. Refresh homepage

**Expected Results:**
- [ ] Category name updates in navigation
- [ ] Link still works

**Test 5c: Delete Category**

**Steps:**
1. Delete a category (move products elsewhere)
2. Refresh homepage

**Expected Results:**
- [ ] Category removed from navigation
- [ ] No broken links
- [ ] No PHP errors

---

### Test 6: Brand Detection & Display

**Objective:** Verify brand taxonomy detection works

**Test 6a: Brand Taxonomy Present**

**Steps:**
1. Verify brand taxonomy exists (check WooCommerce > Attributes)
2. Add products to brands
3. Check navigation

**Expected Results:**
- [ ] Brands section displays
- [ ] Top 15 brands by product count shown
- [ ] Brand names correct
- [ ] Product counts accurate
- [ ] Links go to brand pages
- [ ] "View All Brands" link present

**Test 6b: No Brand Taxonomy**

**Steps:**
1. Temporarily disable brand taxonomy
2. Refresh homepage

**Expected Results:**
- [ ] Navigation still works
- [ ] Brands section omitted gracefully
- [ ] No PHP errors or warnings
- [ ] Other sections unaffected

---

### Test 7: Cache Invalidation Hooks

**Objective:** Verify cache invalidates on all trigger events

**Test Each Hook:**

1. **Product Save:**
   ```
   Edit any product > Save
   Expected: Cache deleted, rebuilt on next load
   ```

2. **Product Trash:**
   ```
   Trash a product
   Expected: Cache deleted, counts update
   ```

3. **Product Untrash:**
   ```
   Restore trashed product
   Expected: Cache deleted, counts update
   ```

4. **Category Create:**
   ```
   Create new product_cat term
   Expected: Cache deleted
   ```

5. **Category Edit:**
   ```
   Edit category name/slug
   Expected: Cache deleted
   ```

6. **Category Delete:**
   ```
   Delete category
   Expected: Cache deleted
   ```

7. **Brand Term Create/Edit:**
   ```
   Add/edit brand term
   Expected: Cache deleted (if brand taxonomy detected)
   ```

**Verification Method:**
```bash
# Before trigger
wp transient get advapes_nav_structure

# Perform trigger action

# After trigger (should be empty)
wp transient get advapes_nav_structure
```

---

### Test 8: WP-Cron Scheduled Refresh

**Objective:** Verify cron job schedules and runs correctly

**Steps:**

1. **Check Schedule:**
   ```bash
   wp cron event list | grep advapes
   ```

   **Expected Output:**
   ```
   advapes_refresh_nav_cron    30min    <timestamp>
   ```

2. **Manually Trigger:**
   ```bash
   wp cron event run advapes_refresh_nav_cron
   ```

   **Expected:**
   - [ ] Command succeeds
   - [ ] Transient refreshed
   - [ ] No errors

3. **Check Custom Schedule:**
   ```bash
   wp cron schedule list | grep advapes
   ```

   **Expected:**
   ```
   advapes_30min    1800 seconds
   ```

**Verify After 30 Minutes:**
- [ ] Cron job runs automatically
- [ ] Cache refreshes
- [ ] Next run scheduled

---

### Test 9: Fallback Mechanism

**Objective:** Verify graceful fallback if dynamic system fails

**Test 9a: Missing File**

**Steps:**
1. Temporarily rename `advapes-nav.php` to `advapes-nav.php.bak`
2. Refresh homepage

**Expected Results:**
- [ ] Page loads without fatal errors
- [ ] Fallback menu displays:
  - Home
  - Deals
  - Brands
  - Support
- [ ] HTML comment: "Fallback to static menu"
- [ ] Site remains functional

**Test 9b: Function Error**

**Steps:**
1. Temporarily break `advapes_render_nav()` function
2. Refresh homepage

**Expected Results:**
- [ ] PHP error logged but caught
- [ ] Fallback menu displays
- [ ] No white screen of death

**Restore:**
```bash
mv advapes-nav.php.bak advapes-nav.php
```

---

### Test 10: Performance Testing

**Objective:** Measure performance impact

**Test 10a: First Load (Uncached)**

**Steps:**
1. Clear all caches
2. Use Query Monitor plugin or similar
3. Load homepage
4. Note metrics

**Expected Metrics:**
- [ ] Total queries: +3-5 vs baseline
- [ ] Page load time: < 2 seconds
- [ ] Memory usage: < 5MB increase
- [ ] No slow queries (> 0.05s)

**Test 10b: Cached Load**

**Steps:**
1. Load homepage (cache exists)
2. Note metrics

**Expected Metrics:**
- [ ] Total queries: Same as baseline (0 extra)
- [ ] Page load time: No increase
- [ ] Memory usage: Minimal increase
- [ ] Transient read only (no queries)

**Test 10c: Large Catalog**

If site has 5,000+ products:
- [ ] First load completes in < 3s
- [ ] Cache build time acceptable
- [ ] No timeout errors
- [ ] No memory exhaustion

---

### Test 11: Responsive & Mobile Testing

**Objective:** Verify mobile functionality unchanged

**Devices to Test:**
- [ ] iPhone (iOS Safari)
- [ ] Android (Chrome)
- [ ] iPad (Safari)
- [ ] Desktop (1920px)
- [ ] Laptop (1440px)
- [ ] Tablet (768px)

**Test Each:**
1. **Mobile Menu Toggle:**
   - [ ] Hamburger icon visible
   - [ ] Click opens menu
   - [ ] Menu slides in smoothly
   - [ ] Close button works
   - [ ] Dropdowns expand on tap

2. **Desktop Hover:**
   - [ ] Dropdowns open on hover
   - [ ] Dropdowns close when mouse leaves
   - [ ] Wide dropdown (brands) displays correctly
   - [ ] No layout shift

3. **Touch/Pointer:**
   - [ ] Links tappable/clickable
   - [ ] No double-tap required
   - [ ] Proper hit targets (44px minimum)

---

### Test 12: Cross-Browser Testing

**Browsers to Test:**
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

**For Each Browser:**
- [ ] Navigation renders correctly
- [ ] Dropdowns work
- [ ] CSS styles apply
- [ ] No console errors
- [ ] Links functional

---

### Test 13: SEO & Accessibility

**Objective:** Verify SEO and a11y not impacted

**SEO Tests:**
1. **Internal Links:**
   - [ ] All nav links crawlable (not JavaScript)
   - [ ] Proper anchor text
   - [ ] No broken links
   - [ ] Canonical URLs

2. **Structured Data:**
   - [ ] Validate with Google Rich Results Test
   - [ ] No errors introduced

**Accessibility Tests:**
1. **Keyboard Navigation:**
   - [ ] Tab through menu items
   - [ ] Enter/Space activates links
   - [ ] Focus visible
   - [ ] Escape closes dropdowns

2. **Screen Reader:**
   - [ ] NVDA/JAWS announces menu items
   - [ ] Proper ARIA labels (if any)
   - [ ] Semantic HTML maintained

3. **Color Contrast:**
   - [ ] WCAG AA compliance
   - [ ] Text readable on backgrounds

---

### Test 14: Edge Cases

**Test 14a: Empty Category**

**Steps:**
1. Create category with 0 products
2. Check navigation

**Expected:**
- [ ] Category does not appear (`hide_empty=true`)
- [ ] No errors

**Test 14b: Very Long Names**

**Steps:**
1. Create category: "Super Long Category Name That Might Break Layout"
2. Check navigation

**Expected:**
- [ ] Name truncates or wraps gracefully
- [ ] Layout not broken
- [ ] Dropdown still functions

**Test 14c: Special Characters**

**Steps:**
1. Create category: "Vapes & E-Liquids (50%+ Off)"
2. Check navigation

**Expected:**
- [ ] Name displays correctly
- [ ] HTML entities escaped (`&amp;`)
- [ ] Link works
- [ ] No XSS vulnerability

**Test 14d: 100+ Brands**

If site has many brands:
- [ ] Only top 15 shown
- [ ] "View All Brands" link present
- [ ] No performance degradation

**Test 14e: No Products**

**Steps:**
1. Test on fresh install with no products
2. Check navigation

**Expected:**
- [ ] Deals and Support sections show
- [ ] No product categories (expected)
- [ ] No PHP errors
- [ ] Fallback or empty state graceful

---

### Test 15: Security Testing

**Objective:** Verify no security vulnerabilities introduced

**XSS Tests:**
1. Create category with name: `<script>alert('xss')</script>`
   - [ ] Script does not execute
   - [ ] HTML escaped in output

2. Create brand with name: `" onclick="alert('xss')"`
   - [ ] Attribute properly escaped
   - [ ] No injection possible

**SQL Injection:**
- [ ] All queries use WP functions (no raw SQL)
- [ ] No user input processed directly

**Privilege Escalation:**
- [ ] REST endpoint read-only
- [ ] No admin functions exposed
- [ ] Public access appropriate

**Data Exposure:**
- [ ] REST endpoint doesn't leak sensitive data
- [ ] Only public category/product data shown

---

## 📊 Test Results Template

```markdown
## Test Run: [Date] [Time]

**Environment:** Staging / Production
**Tested By:** [Name]
**WP Version:** [x.x.x]
**WC Version:** [x.x.x]
**PHP Version:** [x.x]

### Results Summary
- Total Tests: 15
- Passed: __
- Failed: __
- Skipped: __

### Failed Tests
1. [Test Name] - [Reason] - [Severity: Critical/High/Medium/Low]
   - Steps to reproduce: ...
   - Expected: ...
   - Actual: ...
   - Screenshots: ...

### Performance Metrics
- Uncached load: __s
- Cached load: __s
- Extra queries: __
- Memory usage: __MB

### Issues Found
1. [Issue description] - [Severity] - [Status: Open/Fixed]

### Recommendations
- [ ] Safe to deploy
- [ ] Deploy with monitoring
- [ ] Do not deploy - critical issues

### Notes
[Any additional observations]
```

---

## 🚨 Issue Severity Levels

**Critical (P0):**
- Site breaks / white screen
- Navigation doesn't render
- Fatal PHP errors
- Security vulnerabilities

**High (P1):**
- Incorrect product counts
- Broken links
- Cache not invalidating
- Performance degradation > 1s

**Medium (P2):**
- Minor display issues
- Non-critical console warnings
- Edge case failures
- Missing fallback

**Low (P3):**
- Cosmetic issues
- Optimization opportunities
- Documentation updates needed

---

## ✅ Sign-Off Checklist

Before production deployment:

- [ ] All critical tests passed
- [ ] No P0 or P1 issues remain
- [ ] Performance acceptable
- [ ] Staging tested for 24+ hours
- [ ] Rollback plan documented
- [ ] Stakeholders notified
- [ ] Monitoring in place

**Approved By:**
- [ ] Developer: _______________ Date: _____
- [ ] QA: _______________ Date: _____
- [ ] Product Owner: _______________ Date: _____

---

## 📞 Support

If tests fail or issues arise:
1. Check `/wp-content/debug.log`
2. Review REST API: `/wp-json/advapes/v1/nav`
3. Verify transient: `wp transient get advapes_nav_structure`
4. Check hooks: `has_action('save_post_product')`
5. Contact developer with test results

---

**Testing Duration Estimate:** 2-4 hours for full suite
**Recommended Re-test:** After any code changes or WooCommerce updates
