# ADVapes Navigation v3.2.0 - Visual Structure Guide

This document provides a visual representation of the updated navigation structure.

---

## Before vs After Comparison

### BEFORE (v3.1.2)
```
┌─ DISPOSABLES ──────────────────────┐
│ Title: "One-use disposable vapes"  │
│                                     │
│ BY TYPE                             │
│ ├─ Subcategory 1                    │
│ ├─ Subcategory 2                    │
│ ├─ Subcategory 3                    │
│ ├─ Subcategory 4                    │
│ ├─ Subcategory 5                    │
│ └─ Subcategory 6                    │
│                                     │
│ Nic Options (0mg / 20mg / 50mg)     │
│                                     │
│ TOP BRANDS                          │
│ ├─ Brand 1                          │
│ ├─ Brand 2                          │
│ └─ Brand 3                          │
│                                     │
│ View All Disposables [Shop all]     │
└─────────────────────────────────────┘
```

### AFTER (v3.2.0)
```
┌─ DISPOSABLES ──────────────────────┐
│ Title: "One-use disposable vapes"  │
│                                     │
│ BY TYPE                             │
│ ├─ Subcategory 1                    │
│ ├─ Subcategory 2                    │
│ └─ Subcategory 3                    │ ← Max 3!
│                                     │
│ PUFF COUNT                          │ ← NEW!
│ [10k-15k] [20k-30k] [40k+]          │ ← Filter Chips
│                                     │
│ TOP BRANDS                          │
│ ├─ Brand 1                          │
│ ├─ Brand 2                          │
│ └─ Brand 3                          │ ← Max 3!
│                                     │
│ View All Disposables [Shop all]     │
└─────────────────────────────────────┘
```

---

## All Updated Dropdowns

### 1. DISPOSABLES ✨ NEW CHIPS
```
Title: "One-use disposable vapes (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

PUFF COUNT (NEW!)
[10k–15k] [20k–30k] [40k+]
    ↓         ↓         ↓
 filter_size filter_size filter_size

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All Disposables [Shop all]
```

### 2. POD DISPOSABLES ✨ NEW CHIPS
```
Title: "Pod-based systems (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

NIC SALT STRENGTHS (NEW!)
[10mg NS] [20mg NS] [50mg NS]
    ↓         ↓         ↓
filter_strength filter_strength filter_strength

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All Pod Disposables [Shop all]
```

### 3. POD SYSTEMS & KITS (No Chips)
```
Title: "Refillable pod systems (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

Shop All Pod Systems [Shop all]
```

### 4. VAPE HARDWARE (No Chips)
```
Title: "Mods, tanks & spares (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All Vape Hardware [Shop all]
```

### 5. DL E-LIQUIDS (No Chips)
```
Title: "Direct lung liquids (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All DL E-Liquids [Shop all]
```

### 6. MTL & NIC SALTS ✨ EXISTING CHIPS
```
Title: "Nic salts & MTL liquids (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

NIC SALT STRENGTHS (Already existed, now verified)
[10mg NS] [20mg NS] [50mg NS]
    ↓         ↓         ↓
filter_strength filter_strength filter_strength

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All Nic Salts [Shop all]
```

### 7. NIC ALTERNATIVES (No Chips)
```
Title: "Non-vape nicotine (XXX+)"

BY TYPE (max 3)
├─ Link 1
├─ Link 2
└─ Link 3

TOP BRANDS (max 3)
├─ Brand 1
├─ Brand 2
└─ Brand 3

View All Nic Alternatives [Shop all]
```

---

## Chip Visual Design

### Desktop View
```
┌──────────────────────────────────────────────────────┐
│ PUFF COUNT                                           │
│                                                      │
│  ╭─────────────╮  ╭─────────────╮  ╭─────────────╮  │
│  │  10k–15k  [5]│  │  20k–30k [12]│  │   40k+   [8]│  │
│  ╰─────────────╯  ╰─────────────╯  ╰─────────────╯  │
│     ↑  hover: darker background + red border  ↑     │
│                                                      │
└──────────────────────────────────────────────────────┘

Colors:
- Background: #111827 (dark gray)
- Border: #374151 (gray)
- Text: #f9fafb (white)
- Count badge: #374151 bg, #9ca3af text
- Hover: #1f2937 bg, #e11d2f border (red)
```

### Mobile View
```
┌──────────────────────────────┐
│ PUFF COUNT                   │
│                              │
│  ╭──────╮  ╭──────╮  ╭────╮  │
│  │10k-15k│  │20k-30k│  │40k+│  │
│  ╰──────╯  ╰──────╯  ╰────╯  │
│    ↑ Slightly smaller  ↑     │
│                              │
└──────────────────────────────┘

Responsive:
- Chips wrap if needed
- Padding reduced (5px 10px)
- Font size 10px
- Count badges optional (removable)
```

---

## URL Examples

### Puff Count Filters (Disposables)
```
User clicks: [10k–15k]
   ↓
https://www.advapes.co.za/product-category/disposables/?filter_size=10-000-puff&filter=1
   ↑                                                         ↑
   Base URL from category                        Term slug (dynamically looked up)
```

### Strength Filters (Pod Disposables, MTL)
```
User clicks: [20mg NS]
   ↓
https://www.advapes.co.za/product-category/pod-disposables/?filter_strength=20mg-nic-salt&filter=1
   ↑                                                            ↑
   Base URL from category                           Term slug (dynamically looked up)
```

---

## Filter Chip Term Mapping

### Puff Count (Size)
```
Chip Label    Preferred Term    Fallbacks
──────────────────────────────────────────────────
10k–15k   →   10 000 Puff   →   12 000 Puff
                                 15 000 Puff
                                 10000 Puff (no space)

20k–30k   →   20 000 Puff   →   25 000 Puff
                                 30 000 Puff
                                 20000 Puff (no space)

40k+      →   40 000 Puff   →   50 000 Puff
                                 55 000 Puff
                                 40000 Puff (no space)
```

### Nic Salt Strengths
```
Chip Label    Term Name
─────────────────────────
10mg NS   →   10mg NS
20mg NS   →   20mg NS
50mg NS   →   50mg NS

Note: Exact match required, but slug is auto-detected
```

---

## Dropdown Height Comparison

### BEFORE (v3.1.2)
```
Disposables dropdown:
- 6 subcategories
- 1 hint label
- 3 brands
- 1 View All
= ~11 items tall

Typical height: 350-400px
```

### AFTER (v3.2.0)
```
Disposables dropdown:
- 3 subcategories     ← Reduced
- 3 puff chips (1 row) ← Added
- 3 brands
- 1 View All
= ~8 items + 1 chip row

Typical height: 300-350px (actually more compact!)
```

---

## Mobile Behavior

### Hamburger Menu
```
┌──────────────────────────────┐
│ ☰  MENU                      │ ← Tap to expand
└──────────────────────────────┘

Tap ↓

┌──────────────────────────────┐
│ ✕                            │
├──────────────────────────────┤
│ > Deals                      │
│ > Disposables            ▼   │ ← Tap to expand
│   Pod Disposables            │
│   Pod Systems & Kits         │
│   Vape Hardware              │
│   DL E-Liquids               │
│   MTL & Nic Salts            │
│   Nic Alternatives           │
│   Brands                     │
│   Support                    │
└──────────────────────────────┘

Tap Disposables ↓

┌──────────────────────────────┐
│ ✕                            │
├──────────────────────────────┤
│ > Deals                      │
│ v Disposables            ▲   │
│   ├─ BY TYPE                 │
│   │   └─ Link 1              │
│   │   └─ Link 2              │
│   │   └─ Link 3              │
│   ├─ PUFF COUNT              │
│   │   [10k-15k] [20k-30k]    │
│   │   [40k+]                 │
│   ├─ TOP BRANDS              │
│   │   └─ Brand 1             │
│   │   └─ Brand 2             │
│   │   └─ Brand 3             │
│   └─ View All Disposables    │
│   Pod Disposables            │
│   Pod Systems & Kits         │
└──────────────────────────────┘
```

---

## CSS Classes Reference

### Chips
```css
.adv-strength-pills-container  /* List item wrapper */
.adv-strength-pills            /* Flex row container */
.adv-strength-pill             /* Individual chip */
.adv-pill-label                /* Chip text */
.adv-pill-count                /* Optional count badge */
```

### Usage
```html
<li class="adv-strength-pills-container">
  <div class="adv-strength-pills">
    <a href="..." class="adv-strength-pill">
      <span class="adv-pill-label">10k–15k</span>
      <span class="adv-pill-count">5</span>
    </a>
    <!-- more chips -->
  </div>
</li>
```

---

## Summary of Changes

### What Changed
✅ Subcategories: 6-7 → 3 max per dropdown  
✅ Brands: Already 3, maintained  
✅ New chips: Puff Count (Disposables)  
✅ New chips: Nic Salt Strengths (Pod Disposables)  
✅ Verified chips: Nic Salt Strengths (MTL & Nic Salts)  

### What Stayed the Same
✅ All core links preserved (BY TYPE, TOP BRANDS, View All)  
✅ Mobile accordion behavior unchanged  
✅ CSS classes for existing elements  
✅ URL patterns for site navigation  
✅ Cache behavior (30 seconds)  

### Result
- **More compact** dropdowns (fewer items)
- **More useful** (smart filter chips)
- **Better UX** (direct access to popular filters)
- **Faster** (less visual clutter)
- **Consistent** (max 3 rule everywhere)

---

## Quick Reference Card

```
╔═══════════════════════════════════════════════════════╗
║  ADVapes Navigation v3.2.0 - Quick Reference          ║
╠═══════════════════════════════════════════════════════╣
║  Rule: Max 3 items per group (BY TYPE + TOP BRANDS)  ║
║                                                       ║
║  Chips Added:                                         ║
║  ├─ Disposables: Puff Count (10k-15k, 20k-30k, 40k+) ║
║  ├─ Pod Disposables: Nic Salts (10mg, 20mg, 50mg)    ║
║  └─ MTL & Nic Salts: Nic Salts (10mg, 20mg, 50mg)    ║
║                                                       ║
║  No Chips:                                            ║
║  ├─ Pod Systems & Kits                                ║
║  └─ DL E-Liquids                                      ║
║                                                       ║
║  Filter URL Pattern:                                  ║
║  {url}?filter_{taxonomy}={slug}&filter=1              ║
║                                                       ║
║  CSS Classes: .adv-strength-pill (reused)             ║
║  Mobile: Same accordion behavior                      ║
║  Cache: 30 seconds (auto-invalidates)                 ║
╚═══════════════════════════════════════════════════════╝
```

---

**Version:** 3.2.0  
**Created:** December 15, 2025  
**For:** ADVapes Navigation System
