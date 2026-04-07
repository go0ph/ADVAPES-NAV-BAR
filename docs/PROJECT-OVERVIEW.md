# Project Overview - ADVapes Navigation Bar

## 📌 Quick Links

- **🎨 [Preview Demo](../preview/preview.html)** - See it in action (no installation needed)
- **📖 [Installation Guide](INSTALLATION-GUIDE.md)** - Complete step-by-step instructions
- **📄 [Main Documentation](README.md)** - Features, how it works, troubleshooting
- **📥 [Files to Download](FILES-TO-DOWNLOAD.txt)** - What you need to install

## 🎯 What Is This?

ADVapes Navigation Bar is a **dynamic, responsive navigation menu** for WooCommerce stores that:

- ✅ Automatically populates from your product categories and brands
- ✅ Updates itself when you add/remove products
- ✅ Works perfectly on desktop and mobile
- ✅ Caches for fast performance
- ✅ Integrates seamlessly with Razzi theme

**Perfect for:** Vape shops, e-commerce stores with complex product hierarchies

## 🚀 Getting Started

### Option 1: Preview First (Recommended)

1. Download `../preview/preview.html`
2. Open it in your browser
3. See exactly what it looks like!

**Screenshot:**
![Navigation Preview](https://github.com/user-attachments/assets/1d5b0c63-8e60-4757-a1e1-ad7d37077a98)

### Option 2: Jump to Installation

See **[INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md)** for complete instructions.

**Quick Summary:**
1. Download 3 files: `advapes-nav.php`, `advapes-nav.css`, `header.php`
2. Upload to `/wp-content/themes/razzi-child/`
3. Clear cache
4. Done!

## 📁 Repository Structure

```
ADVAPES-NAV-BAR/
│
├── nav-profiles/                  ← READY-TO-ZIP PLUGIN PROFILES
│   ├── advapes-nav-v2/            ← V2: Current production nav
│   │   ├── header.php
│   │   ├── advapes-nav.php
│   │   └── advapes-nav.css
│   └── advapes-nav-v3/            ← V3: Brand-first nav
│       ├── header.php
│       ├── advapes-nav.php
│       └── advapes-nav.css
│
├── preview/
│   └── preview.html               ← open in browser to preview
│
├── docs/                          ← documentation
│   ├── INSTALLATION-GUIDE.md
│   ├── PROJECT-OVERVIEW.md        ← this file
│   ├── README-V3.md
│   ├── QUICKSTART-V3.md
│   ├── V3-COMPARISON.md
│   └── FILES-TO-DOWNLOAD.txt
│
└── archive/                       ← old versions & changelogs
    ├── v1/
    └── changelogs/
```

## 🤔 Which Version Should I Use?

### V2 (Current - Recommended)

**Best for:** Most users, stable, well-tested

**Features:**
- ✅ Desktop: Horizontal menu with dropdowns
- ✅ Mobile: Right-side drawer with hierarchical navigation
- ✅ Menu order: Deals → Disposables → Pod Disposables → Devices → Liquids → Brands → Support
- ✅ Fully dynamic categories and brands

**Files:** `advapes-nav.php`, `advapes-nav.css`, `header.php`

### V3 (Brand-First Approach)

**Best for:** Stores that want to emphasize brands

**Features:**
- ✅ Everything from V2, plus:
- ✅ Brands promoted to 2nd position (not 9th)
- ✅ "Top Brands" section with priority brands
- ✅ Simplified Disposables (no DL/MTL jargon)
- ✅ Consolidated Devices and Liquids menus

**Files:** `advapes-nav-v3.php`, `advapes-nav.css`, `header-v3.php`

**See:** [V3-COMPARISON.md](V3-COMPARISON.md) for detailed differences

## 📋 Common Questions

### Can I preview it before installing?

**Yes!** Download `../preview/preview.html` and open it in your browser. No server or WordPress needed.

### Will this replace my existing header?

**No!** It adds the navigation bar **below** your existing Razzi header. Your logo, search, and cart stay the same.

### What if I don't like it?

**Easy to remove!** Just delete the 3 files from `/wp-content/themes/razzi-child/` and clear cache. Your site returns to normal.

### Do I need to manually update the menu?

**No!** The navigation automatically updates when you:
- Add/remove products
- Create/delete categories
- Add/remove brands

### How do I switch from V2 to V3?

See **[QUICKSTART-V3.md](QUICKSTART-V3.md)** - it's a 10-minute process and fully reversible.

### Will this slow down my site?

**No!** The navigation uses a 30-second cache. After the first load, it's extremely fast.

## 🛠️ Technical Details

**Requirements:**
- WordPress 5.0+
- WooCommerce 4.0+
- Razzi theme (parent + child)
- PHP 7.4+

**Performance:**
- 30-second cache with auto-invalidation
- Optimized database queries
- GPU-accelerated mobile animations
- No external dependencies

**Browser Support:**
- Chrome, Firefox, Safari, Edge (latest)
- Mobile: iOS Safari, Chrome Mobile, Samsung Internet

## 📞 Support

### Before Asking for Help

Check these first:

- [ ] Read [INSTALLATION-GUIDE.md](INSTALLATION-GUIDE.md)
- [ ] All 3 files uploaded to `/wp-content/themes/razzi-child/`
- [ ] All caches cleared (WordPress + Browser + CDN)
- [ ] WooCommerce is active
- [ ] Razzi Child theme is active
- [ ] Categories exist in WooCommerce
- [ ] Browser console shows no errors (F12)

### Still Need Help?

1. Check [Troubleshooting Section](INSTALLATION-GUIDE.md#troubleshooting) in Installation Guide
2. Check [Common Issues](README.md#troubleshooting) in README
3. Create an issue on GitHub with:
   - What you tried
   - What happened
   - Screenshots if relevant
   - Browser console errors

## 📊 Version History

### V2 (4.0.0) - Current
- ✅ Stable and production-ready
- ✅ Right-side mobile drawer
- ✅ Expanded dropdowns (6-8 items)
- ✅ Full catalogue coverage

### V3 (5.0.0) - Optional
- ✅ Brand-first navigation structure
- ✅ Simplified terminology (no DL/MTL)
- ✅ Consolidated menus

### V1 (3.1.2) - Archived
- Archived in `V1/` folder
- Can be restored if needed

For detailed changelog, see `archive/CHANGELOG.md`

## 🎉 Success Stories

Once installed, you should see:

- **Desktop:** Clean horizontal menu with all items on one line
- **Mobile:** Touch-friendly drawer that slides from the right
- **Dynamic:** Categories and brands populate automatically
- **Fast:** Instant navigation with smart caching

## 📄 License

Free to use for ADVapes and related projects.

---

**Ready to install?** Start with the [preview](../preview/preview.html), then follow the [installation guide](INSTALLATION-GUIDE.md)!
