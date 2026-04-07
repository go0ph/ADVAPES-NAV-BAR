# ADVapes Navigation Bar - Version 1 (Archived)

**Version:** 3.1.2  
**Date Archived:** December 17, 2025  
**Status:** Stable - Production Version

## Overview

This folder contains the archived V1 of the ADVapes navigation system. This version was successfully deployed and working in production before the V2 redesign.

## What's Included

- `advapes-nav.php` - Main navigation logic and functions (v3.1.2)
- `advapes-nav.css` - Navigation styling
- `header.php` - Header template with navigation integration
- `README.md` - Original documentation

## Key Features of V1

### Desktop Navigation
- Single-line layout with 10 menu items
- Hover-based dropdown menus
- Dynamic content from WooCommerce
- Micro-grouped dropdowns (By Type, Top Brands)
- Filter chips for Disposables and Nic Salts
- 30-second caching for performance

### Mobile Navigation
- Hamburger menu toggle
- Collapsible accordion dropdowns
- Touch-optimized interactions
- GPU-accelerated animations
- Logo stability (no resize glitches)

## Why V2?

Boss feedback indicated that while V1 scored high on SEO and rankings, it lacked sufficient content for users to browse the full catalogue via the navigation bar. The balance between neatness and comprehensive product range needed improvement.

### V2 Goals:
1. **Expand menu content** - Allow users to browse entire store catalogue
2. **Mobile redesign** - Implement right-side flyout drawer menu (not dropdowns)
3. **Desktop expansion** - Keep style, add more navigation paths
4. **Better than competitors** - Exceed vaperite.co.za and smokeorganic.co.za

## Restoring V1

If you need to rollback to V1, copy these files back to the parent directory:

```bash
cp V1/advapes-nav.php ../
cp V1/advapes-nav.css ../
cp V1/header.php ../
```

Then clear WordPress cache and refresh.

## Version History

See the main `README.md` for complete version history leading up to v3.1.2.

---

**Note:** This is an archive for reference and rollback purposes. Active development continues in V2.
