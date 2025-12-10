# ADVAPES Custom Navigation Bar

A custom navigation bar implementation for the ADVapes WooCommerce/WordPress site, built as an enhancement to the Razzi child theme. This navigation bar provides a comprehensive, dropdown-enabled menu system that sits below the existing Razzi header without modifying the logo or original header layout.

## Overview

This repository contains the implementation files for a fully-responsive custom navigation bar designed specifically for ADVapes.co.za. The navigation bar features:

- **10+ main categories** including Deals, Disposables, Pod Systems, DL/MTL devices, Brands, and Support
- **Dropdown menus** for each category with organized subcategories
- **Responsive design** with mobile hamburger menu
- **Brand-consistent styling** using ADVapes color scheme (dark background with red accents)
- **Hover effects** and smooth transitions for enhanced user experience

## Features

### Desktop Experience
- Horizontal navigation bar with hover-activated dropdowns
- Visual tags and descriptions for subcategories
- Smooth transitions and hover effects
- Optimized spacing for easy navigation
- Red bottom border accent (#e11d2f) matching ADVapes branding

### Mobile Experience
- Hamburger menu toggle (hides the default Razzi mobile menu)
- Collapsible menu with vertical layout
- Inline dropdown expansion (no overlays)
- Streamlined view (hides tags/descriptions for space)
- Touch-friendly tap targets

## Files in This Repository

### `header.php`
The main header template file for the Razzi child theme. This file:
- Includes the standard WordPress/Razzi header hooks
- Adds the custom ADVapes navigation bar structure
- Contains all menu items with their links and dropdown content
- Uses semantic HTML5 with accessibility considerations

**Key sections:**
- Lines 1-26: Standard WordPress/Razzi header setup
- Lines 38-464: Custom ADVapes navigation bar implementation
- Lines 54-461: Navigation menu items with dropdowns

### `css.txt`
Complete CSS styling for the custom navigation bar. Includes:
- Base navigation styles (lines 5-94)
- Desktop dropdown styles (lines 96-154)
- Mobile responsive styles (lines 167-295)
- Hover effects and transitions
- Hamburger menu animations

**Responsive breakpoint:** 1024px (`@media (max-width: 1024px)`)

## Installation

1. **Copy `header.php`** to your Razzi child theme directory:
   ```
   /wp-content/themes/razzi-child/header.php
   ```

2. **Add CSS** from `css.txt` to your child theme's stylesheet. You can either:
   - Add to `style.css` in your Razzi child theme
   - Add via WordPress Customizer (Appearance → Customize → Additional CSS)
   - Enqueue as a separate stylesheet in `functions.php`

3. **Clear cache** if using any caching plugins

4. **Test** on both desktop and mobile devices

## Customization

### Modifying Menu Items
Edit `header.php` to add, remove, or modify navigation items. Each menu item follows this structure:

```php
<li class="adv-nav-item">
    <a href="YOUR-URL" class="adv-nav-link">
        Menu Label
    </a>
    <div class="adv-dropdown">
        <div class="adv-dropdown-title">Dropdown Title</div>
        <ul>
            <li>
                <a href="SUBITEM-URL">
                    <span>Subitem Name</span>
                    <span class="adv-tag">Tag text</span>
                </a>
            </li>
        </ul>
    </div>
</li>
```

### Styling Changes
Key CSS variables to customize in `css.txt`:

- **Background color:** `.adv-main-nav { background: #050507; }` (line 7)
- **Red accent:** `.adv-main-nav { border-bottom: 2px solid #e11d2f; }` (line 9)
- **Link hover color:** `.adv-nav-link:hover { background: #e11d2f; }` (line 87)
- **Dropdown background:** `.adv-dropdown { background: #07080c; }` (line 103)
- **Primary link color (Deals):** `.adv-nav-link--primary { color: #fbbf24; }` (line 91)

### Mobile Breakpoint
Adjust the responsive breakpoint by changing `@media (max-width: 1024px)` throughout `css.txt`.

## Menu Structure

The navigation bar includes these main categories:

1. **Deals** (highlighted in gold) - Seasonal promos, new products, sales, clearance
2. **Disposables** - One-use and DTL disposable vapes
3. **Pod Disposables** - Pod-based systems and kits
4. **Pod Systems & Kits** - Refillable pod kits (XROS, Orca, etc.)
5. **DL Hardware** - Direct lung devices, tanks, mods, and spares
6. **DL Liquids** - Freebase e-liquids and longfills
7. **MTL Devices & Liquid** - Mouth-to-lung gear and nic salts
8. **Brands** - Shop by manufacturer (Airscream, Nasty, Elf Bar, BLVK, etc.)
9. **Nic Alternatives** - Nicotine pouches and gum
10. **Support** - FAQ, contact, shipping, policies, tracking

Each category contains relevant subcategories with descriptive tags.

## Technical Details

### Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (may require polyfills for some CSS features)

### WordPress/WooCommerce Compatibility
- Built for Razzi theme (child theme approach)
- Compatible with WooCommerce product categories
- Uses WordPress hooks and actions
- Preserves existing theme functionality

### Performance Considerations
- Pure CSS dropdowns (no JavaScript required)
- Minimal DOM manipulation
- Efficient hover states with CSS transitions
- Mobile menu uses CSS checkbox hack (no JS needed)

### Accessibility
- Semantic HTML5 elements (`<nav>`, `<ul>`, `<li>`)
- Keyboard navigation support through native browser behavior
- High contrast color scheme for readability
- Touch-friendly mobile interface

## Maintenance

### Updating Links
All product category URLs in `header.php` point to `advapes.co.za`. Update these as needed when category slugs change or new categories are added.

### Seasonal Promotions
The "Deals" dropdown includes seasonal items (e.g., "Dezemba Dealz"). Update these periodically to reflect current promotions.

### Brand Updates
The "Brands" section includes popular manufacturers. Keep this list updated as new brands are added to the store.

## Debug Styles

Lines 260-268 in `css.txt` contain debug styles (red borders). These can be removed in production:

```css
/* Optional: your debug styles – keep or remove */
header#site-header {
  border-bottom: 1px solid red;
}
```

## Support

For issues or customization requests related to this navigation bar, refer to:
- The ADVapes website: https://www.advapes.co.za
- WordPress Razzi theme documentation
- WooCommerce documentation for product categories

## License

This code is developed for ADVapes.co.za. Modify and adapt as needed for your specific implementation.