# ADVAPES Navigation Bar - Version History & Backups

This directory contains backups of previous navigation bar versions to allow for easy rollback if needed.

## Version History

### v1.0 - Initial Custom Navigation Bar (Backed up: 2025-12-10)
**Location:** `/backup/v1.0/`

**Features:**
- 10 main navigation categories
- Dropdown menus with subcategories
- Responsive mobile design with hamburger menu
- ADVapes brand styling (dark theme with red accents)

**Files:**
- `header.php` - Main header template with navigation structure
- `css.txt` - Complete CSS styling for navigation
- `README.md` - Original documentation

**Categories included:**
1. Deals
2. Disposables
3. Pod Disposables
4. Pod Systems & Kits
5. DL Hardware
6. DL Liquids
7. MTL Devices & Liquid
8. Brands
9. Nic Alternatives
10. Support

---

## How to Restore a Previous Version

If you need to restore a previous version:

1. Navigate to the backup version folder (e.g., `/backup/v1.0/`)
2. Copy the files back to the root directory:
   ```bash
   cp backup/v1.0/header.php header.php
   cp backup/v1.0/css.txt css.txt
   ```
3. Clear any caching if using WordPress caching plugins
4. Test the navigation on your site

## Backup Best Practices

- Always create a backup before major changes
- Document the changes made in each version
- Keep at least 3 previous versions
- Test thoroughly before deploying to production

## Version Naming Convention

- **Major changes** (complete restructure): Increment major version (v1.0 → v2.0)
- **Significant updates** (new categories, major styling): Increment minor version (v1.0 → v1.1)
- **Small fixes** (bug fixes, link updates): Increment patch version (v1.0.0 → v1.0.1)

---

*Last updated: 2025-12-10*
