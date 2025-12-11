# Collapsible Mobile Menu Implementation

## Overview
Implemented collapsible/expandable headers for the mobile menu navigation. All main menu headers now start collapsed and expand when clicked, providing a cleaner and more organized mobile experience.

## Changes Made

### 1. CSS Updates (advapes-nav.css)
- Added dropdown arrow icons to mobile menu items with children
- Implemented collapse/expand animations with smooth transitions
- Added `.has-dropdown` class styling for items with sub-menus
- Added `.is-expanded` class for expanded state
- Arrow rotates 180° when expanded
- Smooth max-height transition for dropdown content

### 2. JavaScript Functionality (advapes-nav.php)
- Added `advapes_enqueue_nav_scripts()` function to inject mobile menu JavaScript
- Implements accordion behavior: clicking one header closes others
- Only activates on mobile (viewport <= 1024px)
- Prevents default link navigation for items with dropdowns on mobile
- Responsive to window resize events

### 3. Backup Files
Created backup copies in `archive/backups-collapsible-mobile/`:
- `advapes-nav.php.backup`
- `advapes-nav.css.backup`
- `header.php.backup`

## Features

### Mobile Menu Behavior
1. **Collapsed by Default**: All menu sections start collapsed
2. **Click to Expand**: Tap any header to expand its dropdown
3. **Accordion Style**: Opening one section automatically closes others
4. **Visual Indicator**: Dropdown arrow shows expand/collapse state
5. **Smooth Animation**: Transitions are smooth and professional

### Desktop Unchanged
- Desktop navigation remains exactly as before
- Hover dropdowns work normally
- No changes to desktop functionality

## Menu Headers Affected
All these headers now have collapsible dropdowns on mobile:
- Deals
- Disposables
- Pod Disposables
- Pod Systems & Kits
- Vape Hardware
- DL E-Liquids
- MTL & Nic Salts
- Nic Alternatives
- Brands
- Support

## Technical Details

### CSS Classes Added
- `.has-dropdown`: Applied to nav items with dropdown content
- `.is-expanded`: Applied when dropdown is expanded
- Arrow indicator via `::after` pseudo-element

### JavaScript Implementation
- Pure vanilla JavaScript (no jQuery dependency for this feature)
- Mobile detection via `window.innerWidth <= 1024`
- Event listeners on all nav links with dropdowns
- Debounced resize handler for viewport changes

## Testing
Tested successfully with:
- Mobile viewport (375px width)
- Tablet viewport (768px width)
- Desktop viewport (1440px width)
- Accordion behavior (only one section open at a time)
- Arrow rotation animation
- Smooth transitions

## Version
Updated CSS version to 3.1.2

## Backwards Compatibility
- Fully backwards compatible
- No breaking changes
- Desktop navigation unaffected
- Mobile users get improved UX

## Performance
- Minimal JavaScript footprint
- CSS transitions use GPU acceleration
- No external dependencies added
- Efficient event handling with debouncing
