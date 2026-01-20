<?php
/**
 * ADVapes Dynamic Navigation System
 * Version 5.0.0 (V3 - January 2026)
 * 
 * V3 Structural Refactor:
 * - NEW TOP-LEVEL ORDER: Deals, Brands, Disposables, Devices, Liquids, Nic Alternatives, Support
 * - Brands promoted to 2nd position with "Top Brands" section
 * - Disposables simplified (remove DL/MTL terminology)
 * - Devices consolidated (absorb DL Hardware)
 * - Liquids grouped by nicotine type (not vape theory)
 * - Mobile-first approach maintained (60% mobile traffic)
 * - Visual design unchanged (CSS preserved)
 * 
 * Key Features:
 * - Mobile: Slide-out drawer from right with back navigation
 * - Desktop: Expanded dropdowns with brand-first navigation
 * - Hierarchical mobile navigation with smooth transitions
 * - Dynamic content from WooCommerce
 * - Smart filter chips for quick filtering
 * - 30-second cache with auto-invalidation
 * 
 * @package Razzi Child / ADVapes
 * @version 5.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'ADVAPES_NAV_V3_TRANSIENT_KEY', 'advapes_nav_structure_v3' );
define( 'ADVAPES_NAV_V3_TTL', 30 ); // 30 seconds

/**
 * Enqueue ADVapes navigation CSS and JS
 * Only enqueues if the CSS file exists, otherwise expects inline CSS via theme customizer
 */
function advapes_v3_enqueue_nav_styles() {
    $css_file = get_stylesheet_directory() . '/advapes-nav.css';
    
    // Only enqueue if the file exists
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style( 
            'advapes-nav-v3', 
            get_stylesheet_directory_uri() . '/advapes-nav.css', 
            array(), 
            '5.0.0', 
            'all' 
        );
    }
}
add_action( 'wp_enqueue_scripts', 'advapes_v3_enqueue_nav_styles' );

/**
 * Add inline JavaScript for mobile menu functionality (V3 - Mobile-first)
 */
function advapes_v3_enqueue_nav_scripts() {
    // V3: Same mobile flyout drawer as V2 (no changes needed)
    $script = "
    document.addEventListener('DOMContentLoaded', function() {
        // Only apply on mobile (matching CSS breakpoint)
        function isMobile() {
            return window.innerWidth <= 1024;
        }

        // Track if handlers are already attached to prevent duplicates
        let handlersAttached = false;
        
        // V3: Flyout drawer state management
        let drawerStack = []; // Track navigation history for back button
        
        // Enhance hamburger menu toggle with drawer functionality
        function initHamburgerMenu() {
            const toggle = document.getElementById('adv-nav-toggle');
            const toggleBtn = document.querySelector('.adv-nav-toggle-btn');
            const drawer = document.querySelector('.adv-mobile-drawer');
            
            if (toggle && toggleBtn && drawer && isMobile()) {
                // Update aria-expanded and drawer state on change
                toggle.addEventListener('change', function() {
                    const isOpen = toggle.checked;
                    toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    drawer.setAttribute('data-open', isOpen ? 'true' : 'false');
                    
                    // Reset drawer to main menu when closed
                    if (!isOpen) {
                        resetDrawer();
                    }
                    
                    // Prevent body scroll when drawer is open
                    if (isOpen) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
                
                // Add touch handler for visual feedback
                toggleBtn.addEventListener('touchstart', function() {
                    toggleBtn.classList.add('touching');
                    setTimeout(function() {
                        toggleBtn.classList.remove('touching');
                    }, 150);
                }, { passive: true });
                
                // Initialize aria-expanded
                toggleBtn.setAttribute('aria-expanded', toggle.checked ? 'true' : 'false');
            }
        }
        
        // V3: Reset drawer to main menu state
        function resetDrawer() {
            drawerStack = [];
            const panels = document.querySelectorAll('.adv-drawer-panel');
            panels.forEach(function(panel) {
                panel.classList.remove('active', 'previous');
            });
            
            const mainPanel = document.querySelector('.adv-drawer-panel[data-level=\"main\"]');
            if (mainPanel) {
                mainPanel.classList.add('active');
            }
        }
        
        // V3: Navigate to sub-panel
        function navigateToPanel(targetPanelId, title) {
            const currentPanel = document.querySelector('.adv-drawer-panel.active');
            const targetPanel = document.getElementById(targetPanelId);
            
            if (!currentPanel || !targetPanel) return;
            
            // Add current panel to stack for back navigation
            drawerStack.push({
                panelId: currentPanel.id,
                title: currentPanel.dataset.title || 'Menu'
            });
            
            // Animate transition
            currentPanel.classList.remove('active');
            currentPanel.classList.add('previous');
            targetPanel.classList.add('active');
            
            // Update back button text
            updateBackButton();
        }
        
        // V3: Navigate back to previous panel
        function navigateBack() {
            if (drawerStack.length === 0) return;
            
            const previousState = drawerStack.pop();
            const currentPanel = document.querySelector('.adv-drawer-panel.active');
            const targetPanel = document.getElementById(previousState.panelId);
            
            if (!currentPanel || !targetPanel) return;
            
            // Animate transition
            currentPanel.classList.remove('active');
            targetPanel.classList.remove('previous');
            targetPanel.classList.add('active');
            
            // Update back button text
            updateBackButton();
        }
        
        // V3: Update back button visibility and text
        function updateBackButton() {
            const backButtons = document.querySelectorAll('.adv-drawer-back');
            backButtons.forEach(function(btn) {
                if (drawerStack.length > 0) {
                    btn.style.display = 'flex';
                    const backText = btn.querySelector('.adv-back-text');
                    if (backText) {
                        const prevTitle = drawerStack[drawerStack.length - 1].title;
                        backText.textContent = prevTitle;
                    }
                } else {
                    btn.style.display = 'none';
                }
            });
        }

        // V3: Initialize mobile flyout drawer
        function initMobileDrawer() {
            if (!isMobile()) {
                return;
            }

            // Prevent duplicate handler attachments
            if (handlersAttached) return;

            // Handle panel navigation links
            const navLinks = document.querySelectorAll('[data-panel-target]');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetPanelId = this.dataset.panelTarget;
                    const title = this.textContent.trim();
                    navigateToPanel(targetPanelId, title);
                });
            });
            
            // Handle back buttons
            const backButtons = document.querySelectorAll('.adv-drawer-back');
            backButtons.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    navigateBack();
                });
            });
            
            // Close drawer when clicking overlay
            const overlay = document.querySelector('.adv-drawer-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    const toggle = document.getElementById('adv-nav-toggle');
                    if (toggle) {
                        toggle.checked = false;
                        toggle.dispatchEvent(new Event('change'));
                    }
                });
            }

            handlersAttached = true;
        }

        // Initialize components
        initHamburgerMenu();
        initMobileDrawer();

        // Re-initialize on window resize
        let resizeTimer;
        let lastWidth = window.innerWidth;
        
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const currentWidth = window.innerWidth;
                if ((lastWidth <= 1024 && currentWidth > 1024) || (lastWidth > 1024 && currentWidth <= 1024)) {
                    handlersAttached = false;
                    initHamburgerMenu();
                    initMobileDrawer();
                }
                lastWidth = currentWidth;
            }, 250);
        });
    });
    ";
    
    wp_add_inline_script( 'jquery', $script );
}
add_action( 'wp_enqueue_scripts', 'advapes_v3_enqueue_nav_scripts' );

/**
 * Detect brand taxonomy from common candidates
 * 
 * Checks for brand taxonomies in order of likelihood:
 * - 'pwb-brand' (WordPress Perfect Brands plugin - used by advapes.co.za)
 * - 'product_brand' (WooCommerce Brands)
 * - 'brand', 'brands'
 * - Product attribute taxonomies: 'pa_brand', 'pa_brands'
 * - Any registered WooCommerce attribute taxonomy
 * 
 * @return string|false Brand taxonomy name or false if not found
 */
function advapes_v3_detect_brand_taxonomy() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return false;
    }

    // Common brand taxonomy names in order of likelihood
    // pwb-brand is prioritized as it's used by advapes.co.za
    $candidates = array( 'pwb-brand', 'product_brand', 'brand', 'brands', 'pa_brand', 'pa_brands' );

    // Check direct taxonomy existence
    foreach ( $candidates as $candidate ) {
        if ( taxonomy_exists( $candidate ) ) {
            return $candidate;
        }
    }

    // Check WooCommerce attribute taxonomies
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( ! empty( $attribute_taxonomies ) ) {
        foreach ( $attribute_taxonomies as $tax ) {
            $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
            if ( strpos( strtolower( $tax->attribute_name ), 'brand' ) !== false ) {
                return $taxonomy_name;
            }
        }
    }

    return false;
}

/**
 * Helper function to find a WooCommerce category by slug or name
 * 
 * @param string $slug_or_name Category slug or name to search for
 * @return WP_Term|false Category term object or false if not found
 */
function advapes_v3_find_category( $slug_or_name ) {
    // First try by slug
    $term = get_term_by( 'slug', $slug_or_name, 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) {
        return $term;
    }
    // Then try by name
    $term = get_term_by( 'name', $slug_or_name, 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) {
        return $term;
    }
    return false;
}

/**
 * Helper function to get child categories for a parent category
 * 
 * @param int $parent_id Parent category term ID
 * @param int $limit Maximum number of children to return (default 12)
 * @return array Array of child category data (name, url, count)
 */
function advapes_v3_get_category_children( $parent_id, $limit = 12 ) {
    $children = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => $parent_id,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => $limit,
    ) );

    $category_children = array();
    if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
        foreach ( $children as $child ) {
            $category_children[] = array(
                'name' => $child->name,
                'url'  => get_term_link( $child ),
                'count' => $child->count,
            );
        }
    }
    
    return $category_children;
}

/**
 * Helper function to get top brands for a specific category
 * 
 * @param int $category_id Category term ID
 * @param int $limit Maximum number of brands to return (default 4)
 * @return array Array of brand data (name, url, count)
 */
function advapes_v3_get_category_brands( $category_id, $limit = 4 ) {
    $brand_taxonomy = advapes_v3_detect_brand_taxonomy();
    if ( ! $brand_taxonomy ) {
        return array();
    }

    // Get all child category IDs to include products from subcategories
    $category_ids = array( $category_id );
    $children = get_term_children( $category_id, 'product_cat' );
    if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
        $category_ids = array_merge( $category_ids, $children );
    }

    // Validate that we have category IDs to query
    if ( empty( $category_ids ) ) {
        return array();
    }

    global $wpdb;
    
    // Sanitize category IDs to ensure they're integers - absint() guarantees positive integers
    $category_ids = array_map( 'absint', $category_ids );
    
    // Validate we have at least one category ID after sanitization
    if ( empty( $category_ids ) ) {
        return array();
    }
    
    // Create placeholders for IN clause
    $placeholders_str = implode( ',', array_fill( 0, count( $category_ids ), '%d' ) );
    
    // Build the query with placeholders
    $query = "
        SELECT t.term_id, t.name, COUNT(DISTINCT p.ID) as product_count
        FROM {$wpdb->terms} t
        INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
        INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
        INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
        INNER JOIN {$wpdb->term_relationships} tr2 ON p.ID = tr2.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt2 ON tr2.term_taxonomy_id = tt2.term_taxonomy_id
        WHERE tt.taxonomy = %s
        AND tt2.taxonomy = 'product_cat'
        AND tt2.term_id IN ($placeholders_str)
        AND p.post_type = 'product'
        AND p.post_status = 'publish'
        GROUP BY t.term_id, t.name
        ORDER BY product_count DESC
        LIMIT %d
    ";
    
    // Prepare the query with all arguments: taxonomy, category IDs (spread), and limit
    $prepare_args = array_merge( array( $brand_taxonomy ), $category_ids, array( $limit ) );
    $brands = $wpdb->get_results( $wpdb->prepare( $query, ...$prepare_args ) );

    if ( empty( $brands ) ) {
        return array();
    }

    // Format for output with product counts
    $result = array();
    
    foreach ( $brands as $brand ) {
        $term = get_term( $brand->term_id, $brand_taxonomy );
        if ( $term && ! is_wp_error( $term ) ) {
            $result[] = array(
                'name' => $brand->name,
                'url'  => get_term_link( $term ),
                'count' => $brand->product_count,
            );
        }
    }

    return $result;
}

/**
 * Check if there's an active major promotion
 * 
 * This function checks for active promotional categories/pages to determine
 * if the Deals menu should be highlighted. Uses get_posts() to check for active
 * promo pages without hardcoding dates.
 * 
 * @return bool True if a major promo is active
 */
function advapes_v3_has_active_promo() {
    // Define major promo page slugs to check
    $promo_slugs = array( 'dezemba-dealz', 'fire-sale', 'black-friday', 'cyber-monday' );
    
    // Check if any promo pages are published
    $args = array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'post_name__in'  => $promo_slugs,
        'posts_per_page' => 1,
        'fields'         => 'ids',
    );
    
    $promo_pages = get_posts( $args );
    $has_promo = ! empty( $promo_pages );
    
    // Allow filtering for custom promo logic
    return apply_filters( 'advapes_v3_has_active_promo', $has_promo );
}

/**
 * Get V3 navigation structure from WooCommerce data
 * 
 * V3 Major Structural Changes:
 * - NEW ORDER: Deals, Brands, Disposables, Devices, Liquids, Nic Alternatives, Support
 * - Brands promoted to 2nd position (after Deals)
 * - Brands menu gets "Top Brands" priority section
 * - Disposables simplified (no DL/MTL language)
 * - Devices consolidated (absorbs DL Hardware, Pod Systems, Vape Hardware)
 * - Liquids grouped by nicotine type (Nic Salts, Freebase, Longfills)
 * - Mobile-first: 1-2 taps to reach any item
 * 
 * @param bool $force_refresh Force rebuild even if cache exists
 * @return array Navigation structure
 */
function advapes_v3_get_nav_structure( $force_refresh = false ) {
    // Try to get from cache first
    if ( ! $force_refresh ) {
        $cached = get_transient( ADVAPES_NAV_V3_TRANSIENT_KEY );
        if ( false !== $cached ) {
            return $cached;
        }
    }

    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $nav_structure = array();

    // =================================================================
    // 1. DEALS (Position 1 - Unchanged, state-aware for active promotions)
    // =================================================================
    $has_active_promo = advapes_v3_has_active_promo();
    $deals_class = $has_active_promo ? 'adv-nav-link--primary adv-nav-link--promo-active' : 'adv-nav-link--primary';
    
    $nav_structure['deals'] = array(
        'name' => 'Deals',
        'url' => 'https://www.advapes.co.za/on-sale/',
        'class' => $deals_class,
        'dropdown_title' => 'Shop deals &amp; promotions',
        'children' => array(
            array(
                'name' => 'Dezemba Dealz',
                'url' => 'https://www.advapes.co.za/dezemba-dealz/',
                'tag' => 'Seasonal',
            ),
            array(
                'name' => 'New Products',
                'url' => 'https://www.advapes.co.za/new-products/',
                'tag' => 'Latest',
            ),
            array(
                'name' => 'Buy Bulk &amp; Save',
                'url' => 'https://www.advapes.co.za/product-category/buy-bulk-save/',
            ),
        ),
    );

    // =================================================================
    // 2. BRANDS (Position 2 - PROMOTED from position 9)
    // =================================================================
    // V3 NEW: Brands promoted to position 2 with "Top Brands" priority section
    $brand_taxonomy = advapes_v3_detect_brand_taxonomy();
    $brand_children = array();
    
    if ( $brand_taxonomy ) {
        // V3 REQUIREMENT: Add "Top Brands" section at the top
        $priority_brands = array( 'Bewolk', 'Nasty', 'Airscream', 'Oxbar', 'Oxva', 'Vuse' );
        
        // Add "Top Brands" group label
        $brand_children[] = array(
            'type' => 'group_label',
            'name' => 'Top Brands',
        );
        
        // Add priority brands
        foreach ( $priority_brands as $brand_name ) {
            $brand_term = get_term_by( 'name', $brand_name, $brand_taxonomy );
            if ( $brand_term && ! is_wp_error( $brand_term ) ) {
                $brand_children[] = array(
                    'name' => $brand_term->name,
                    'url'  => get_term_link( $brand_term ),
                    'count' => $brand_term->count,
                );
            }
        }
        
        // Add "All Brands (A–Z)" link
        $brand_children[] = array(
            'name' => 'All Brands (A–Z)',
            'url'  => 'https://www.advapes.co.za/brands/',
            'tag'  => 'Browse all',
        );
    }
    
    // Build dropdown title with count if brands exist
    $dropdown_title = 'Shop by brand';
    
    // Always add Brands to navigation structure
    $nav_structure['brands'] = array(
        'name' => 'Brands',
        'url'  => 'https://www.advapes.co.za/brands/',
        'dropdown_title' => $dropdown_title,
        'dropdown_class' => 'adv-dropdown--wide',
        'children' => $brand_children,
    );

    // =================================================================
    // 3. DISPOSABLES (Position 3 - Simplified, no DL/MTL terminology)
    // =================================================================
    // V3 REQUIREMENT: Remove DL/MTL language, simplify structure
    $disposables = advapes_v3_find_category( 'disposables' );
    if ( $disposables ) {
        $children = array();
        
        // Add "By Type" group
        $children[] = array(
            'type' => 'group_label',
            'name' => 'By Type',
        );
        
        // V3 REQUIRED STRUCTURE: All, One-Use, High-Puff
        $children[] = array(
            'name' => 'All Disposables',
            'url'  => get_term_link( $disposables ),
        );
        
        // Try to find One-Use and High-Puff subcategories
        $one_use = advapes_v3_find_category( 'one-use-disposables' );
        if ( $one_use ) {
            $children[] = array(
                'name' => 'One-Use Disposables',
                'url'  => get_term_link( $one_use ),
                'count' => $one_use->count,
            );
        }
        
        $high_puff = advapes_v3_find_category( 'high-puff-disposables' );
        if ( $high_puff ) {
            $children[] = array(
                'name' => 'High-Puff Disposables',
                'url'  => get_term_link( $high_puff ),
                'count' => $high_puff->count,
            );
        }
        
        // Add top brands for disposables
        $brands = advapes_v3_get_category_brands( $disposables->term_id, 6 );
        if ( ! empty( $brands ) ) {
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Popular Disposable Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        $nav_structure['disposables'] = array(
            'name' => 'Disposables',
            'url'  => get_term_link( $disposables ),
            'dropdown_title' => 'One-use disposable vapes',
            'children' => $children,
        );
    }

    // =================================================================
    // 4. DEVICES (Position 4 - NEW: Consolidates Pod Systems, Vape Hardware, DL Hardware)
    // =================================================================
    // V3 REQUIREMENT: Everything reusable/owned in one menu
    $children = array();
    
    // Add "By Type" group
    $children[] = array(
        'type' => 'group_label',
        'name' => 'By Type',
    );
    
    // V3 REQUIRED STRUCTURE: Pod Systems & Kits, Refillable Pods, Vape Mods, Coils & Spares
    $pod_systems = advapes_v3_find_category( 'pod-systems-kits' );
    if ( $pod_systems ) {
        $children[] = array(
            'name' => 'Pod Systems &amp; Kits',
            'url'  => get_term_link( $pod_systems ),
            'count' => $pod_systems->count,
        );
    }
    
    $refillable = advapes_v3_find_category( 'refillable-pods' );
    if ( $refillable ) {
        $children[] = array(
            'name' => 'Refillable Pods',
            'url'  => get_term_link( $refillable ),
            'count' => $refillable->count,
        );
    }
    
    $mods = advapes_v3_find_category( 'vape-mods' );
    if ( ! $mods ) {
        $mods = advapes_v3_find_category( 'mods' );
    }
    if ( $mods ) {
        $children[] = array(
            'name' => 'Vape Mods',
            'url'  => get_term_link( $mods ),
            'count' => $mods->count,
        );
    }
    
    $coils = advapes_v3_find_category( 'coils-spares' );
    if ( ! $coils ) {
        $coils = advapes_v3_find_category( 'coils' );
    }
    if ( $coils ) {
        $children[] = array(
            'name' => 'Coils &amp; Spares',
            'url'  => get_term_link( $coils ),
            'count' => $coils->count,
        );
    }
    
    // Add brands across all device categories
    // Collect brands from multiple categories
    $all_device_brands = array();
    $device_categories = array( $pod_systems, $refillable, $mods, $coils );
    foreach ( $device_categories as $cat ) {
        if ( $cat ) {
            $cat_brands = advapes_v3_get_category_brands( $cat->term_id, 10 );
            foreach ( $cat_brands as $brand ) {
                // Use brand name as key to avoid duplicates
                $all_device_brands[ $brand['name'] ] = $brand;
            }
        }
    }
    
    // Add top brands if found
    if ( ! empty( $all_device_brands ) ) {
        $children[] = array(
            'type' => 'group_label',
            'name' => 'Top Brands',
        );
        // Limit to top 6 brands
        $count = 0;
        foreach ( array_slice( $all_device_brands, 0, 6 ) as $brand ) {
            $children[] = $brand;
            $count++;
            if ( $count >= 6 ) break;
        }
    }
    
    $nav_structure['devices'] = array(
        'name' => 'Devices',
        'url'  => $pod_systems ? get_term_link( $pod_systems ) : 'https://www.advapes.co.za/product-category/devices/',
        'dropdown_title' => 'Reusable vape devices',
        'children' => $children,
    );

    // =================================================================
    // 5. LIQUIDS (Position 5 - NEW: Grouped by nicotine type, not vape theory)
    // =================================================================
    // V3 REQUIREMENT: Nic Salts, Freebase Liquid, Longfills, Additives & Boosters
    $children = array();
    
    // Add "By Type" group
    $children[] = array(
        'type' => 'group_label',
        'name' => 'By Type',
    );
    
    // V3 REQUIRED STRUCTURE: Nic Salts, Freebase, Longfills, Additives
    $nic_salts = advapes_v3_find_category( 'nic-salts' );
    if ( ! $nic_salts ) {
        $nic_salts = advapes_v3_find_category( 'nic-salts-mtl-liquids' );
    }
    if ( $nic_salts ) {
        $children[] = array(
            'name' => 'Nic Salts',
            'url'  => get_term_link( $nic_salts ),
            'count' => $nic_salts->count,
        );
    }
    
    $freebase = advapes_v3_find_category( 'freebase-liquid' );
    if ( ! $freebase ) {
        $freebase = advapes_v3_find_category( 'dl-liquid' );
    }
    if ( ! $freebase ) {
        $freebase = advapes_v3_find_category( 'dl-liquids' );
    }
    if ( $freebase ) {
        $children[] = array(
            'name' => 'Freebase Liquid',
            'url'  => get_term_link( $freebase ),
            'count' => $freebase->count,
        );
    }
    
    $longfills = advapes_v3_find_category( 'longfills' );
    if ( ! $longfills ) {
        $longfills = advapes_v3_find_category( 'dl-longfills' );
    }
    if ( $longfills ) {
        $children[] = array(
            'name' => 'Longfills',
            'url'  => get_term_link( $longfills ),
            'count' => $longfills->count,
        );
    }
    
    $additives = advapes_v3_find_category( 'additives-boosters' );
    if ( ! $additives ) {
        $additives = advapes_v3_find_category( 'flavour-shots' );
    }
    if ( $additives ) {
        $children[] = array(
            'name' => 'Additives &amp; Boosters',
            'url'  => get_term_link( $additives ),
            'count' => $additives->count,
        );
    }
    
    // Add brands across all liquid categories
    $all_liquid_brands = array();
    $liquid_categories = array( $nic_salts, $freebase, $longfills, $additives );
    foreach ( $liquid_categories as $cat ) {
        if ( $cat ) {
            $cat_brands = advapes_v3_get_category_brands( $cat->term_id, 10 );
            foreach ( $cat_brands as $brand ) {
                $all_liquid_brands[ $brand['name'] ] = $brand;
            }
        }
    }
    
    if ( ! empty( $all_liquid_brands ) ) {
        $children[] = array(
            'type' => 'group_label',
            'name' => 'Top Brands',
        );
        $count = 0;
        foreach ( array_slice( $all_liquid_brands, 0, 6 ) as $brand ) {
            $children[] = $brand;
            $count++;
            if ( $count >= 6 ) break;
        }
    }
    
    $nav_structure['liquids'] = array(
        'name' => 'Liquids',
        'url'  => $nic_salts ? get_term_link( $nic_salts ) : 'https://www.advapes.co.za/product-category/liquids/',
        'dropdown_title' => 'E-liquids by nicotine type',
        'children' => $children,
    );

    // =================================================================
    // 6. NIC ALTERNATIVES (Position 6 - Minimal change)
    // =================================================================
    $nic_alternatives = advapes_v3_find_category( 'nicotine-alternatives' );
    if ( $nic_alternatives ) {
        $children = array();
        
        // Add "By Type" group
        $children[] = array(
            'type' => 'group_label',
            'name' => 'By Type',
        );
        
        // V3 REQUIRED: Nicotine Pouches, Nicotine Gum, All Nic Alternatives
        $pouches = advapes_v3_find_category( 'nicotine-pouches' );
        if ( $pouches ) {
            $children[] = array(
                'name' => 'Nicotine Pouches',
                'url'  => get_term_link( $pouches ),
                'count' => $pouches->count,
            );
        }
        
        $gum = advapes_v3_find_category( 'nicotine-gum' );
        if ( $gum ) {
            $children[] = array(
                'name' => 'Nicotine Gum',
                'url'  => get_term_link( $gum ),
                'count' => $gum->count,
            );
        }
        
        $children[] = array(
            'name' => 'All Nic Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['nic-alternatives'] = array(
            'name' => 'Nic Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'dropdown_title' => 'Non-vape nicotine',
            'children' => $children,
        );
    }

    // =================================================================
    // 7. SUPPORT (Position 7 - Unchanged, non-commercial)
    // =================================================================
    $nav_structure['support'] = array(
        'name' => 'Support',
        'url' => 'https://www.advapes.co.za/faq/',
        'dropdown_title' => 'Help &amp; information',
        'children' => array(
            array(
                'name' => 'FAQ',
                'url' => 'https://www.advapes.co.za/faq/',
                'tag' => 'Answers',
            ),
            array(
                'name' => 'Contact Us',
                'url' => 'https://www.advapes.co.za/contact-us/',
                'tag' => 'Get in touch',
            ),
            array(
                'name' => 'Track My Order',
                'url' => 'https://www.advapes.co.za/track/',
                'tag' => 'Shipment tracking',
            ),
            array(
                'name' => 'Shipping',
                'url' => 'https://www.advapes.co.za/shipping-rates-2/',
            ),
            array(
                'name' => 'Returns',
                'url' => 'https://www.advapes.co.za/refunds-returns/',
            ),
            array(
                'name' => 'Policies',
                'url' => 'https://www.advapes.co.za/terms-conditions/',
            ),
        ),
    );

    // Cache the structure
    set_transient( ADVAPES_NAV_V3_TRANSIENT_KEY, $nav_structure, ADVAPES_NAV_V3_TTL );

    return $nav_structure;
}

/**
 * Render mobile drawer navigation (V3 - Same as V2, flyout style)
 * 
 * Generates hierarchical panel-based navigation for mobile devices
 */
function advapes_v3_render_mobile_drawer() {
    $nav_structure = advapes_v3_get_nav_structure();
    
    if ( empty( $nav_structure ) ) {
        return;
    }
    
    echo '<div class="adv-drawer-overlay"></div>' . "\n";
    echo '<div class="adv-mobile-drawer">' . "\n";
    
    // Drawer header
    echo '<div class="adv-drawer-header">' . "\n";
    echo '<span class="adv-drawer-title">Menu</span>' . "\n";
    echo '<label for="adv-nav-toggle" class="adv-drawer-close">&times;</label>' . "\n";
    echo '</div>' . "\n";
    
    // Drawer content with panels
    echo '<div class="adv-drawer-content">' . "\n";
    
    // Main panel
    echo '<div class="adv-drawer-panel active" id="panel-main" data-level="main" data-title="Menu">' . "\n";
    echo '<ul class="adv-drawer-menu">' . "\n";
    
    foreach ( $nav_structure as $key => $item ) {
        $has_children = ! empty( $item['children'] );
        $link_class = isset( $item['class'] ) && strpos( $item['class'], 'adv-nav-link--primary' ) !== false ? 'adv-drawer-link adv-drawer-link--primary' : 'adv-drawer-link';
        
        // Check if this is a promo item
        if ( isset( $item['class'] ) && strpos( $item['class'], 'promo-active' ) !== false ) {
            $link_class .= ' adv-drawer-link--promo';
        }
        
        echo '<li class="adv-drawer-item">' . "\n";
        
        if ( $has_children ) {
            // Item with sub-menu - navigate to panel
            echo '<a href="#" class="' . $link_class . '" data-panel-target="panel-' . esc_attr( $key ) . '">' . "\n";
            echo '<span>' . esc_html( $item['name'] ) . '</span>' . "\n";
            echo '<span class="adv-drawer-arrow"></span>' . "\n";
            echo '</a>' . "\n";
        } else {
            // Direct link
            echo '<a href="' . esc_url( $item['url'] ) . '" class="' . $link_class . '">' . "\n";
            echo '<span>' . esc_html( $item['name'] ) . '</span>' . "\n";
            echo '</a>' . "\n";
        }
        
        echo '</li>' . "\n";
    }
    
    echo '</ul>' . "\n";
    echo '</div>' . "\n";
    
    // Sub-panels for each menu item with children
    foreach ( $nav_structure as $key => $item ) {
        if ( empty( $item['children'] ) ) {
            continue;
        }
        
        echo '<div class="adv-drawer-panel" id="panel-' . esc_attr( $key ) . '" data-level="sub" data-title="' . esc_attr( $item['name'] ) . '">' . "\n";
        
        // Back button
        echo '<div class="adv-drawer-back">' . "\n";
        echo '<span class="adv-back-arrow"></span>' . "\n";
        echo '<span class="adv-back-text">Back to Menu</span>' . "\n";
        echo '</div>' . "\n";
        
        echo '<ul class="adv-drawer-menu">' . "\n";
        
        // Add "View All" link at top
        echo '<li class="adv-drawer-item">' . "\n";
        echo '<a href="' . esc_url( $item['url'] ) . '" class="adv-drawer-link adv-drawer-link--primary">' . "\n";
        echo '<span>View All ' . esc_html( $item['name'] ) . '</span>' . "\n";
        echo '</a>' . "\n";
        echo '</li>' . "\n";
        
        // Render children with section headers
        $current_section = '';
        foreach ( $item['children'] as $child ) {
            // Check if this is a group label
            if ( isset( $child['type'] ) && $child['type'] === 'group_label' ) {
                $current_section = $child['name'];
                echo '<li class="adv-drawer-section-header">' . esc_html( $child['name'] ) . '</li>' . "\n";
            } else {
                // Regular link
                echo '<li class="adv-drawer-item">' . "\n";
                echo '<a href="' . esc_url( $child['url'] ) . '" class="adv-drawer-link">' . "\n";
                echo '<span>' . esc_html( $child['name'] ) . '</span>' . "\n";
                echo '</a>' . "\n";
                echo '</li>' . "\n";
            }
        }
        
        echo '</ul>' . "\n";
        echo '</div>' . "\n";
    }
    
    echo '</div>' . "\n";
    echo '</div>' . "\n";
}

/**
 * Render navigation HTML (V3 - Desktop navigation)
 * 
 * Outputs navigation using the same class names and markup structure
 * as the existing static navigation for CSS compatibility.
 */
function advapes_v3_render_nav() {
    $nav_structure = advapes_v3_get_nav_structure();

    if ( empty( $nav_structure ) ) {
        // Show minimal fallback menu
        echo '<ul class="adv-nav-list">' . "\n";
        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/" class="adv-nav-link">Home</a></li>' . "\n";
        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/on-sale/" class="adv-nav-link adv-nav-link--primary">Deals</a></li>' . "\n";
        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/brands/" class="adv-nav-link">Brands</a></li>' . "\n";
        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/faq/" class="adv-nav-link">Support</a></li>' . "\n";
        echo '</ul>' . "\n";
        return;
    }

    echo '<ul class="adv-nav-list">' . "\n";

    foreach ( $nav_structure as $key => $item ) {
        echo '<li class="adv-nav-item">' . "\n";
        
        // Main link
        $link_class = isset( $item['class'] ) ? 'adv-nav-link ' . esc_attr( $item['class'] ) : 'adv-nav-link';
        echo '<a href="' . esc_url( $item['url'] ) . '" class="' . $link_class . '">' . "\n";
        echo esc_html( $item['name'] ) . "\n";
        echo '</a>' . "\n";

        // Dropdown if children exist
        if ( ! empty( $item['children'] ) ) {
            $dropdown_class = isset( $item['dropdown_class'] ) ? 'adv-dropdown ' . esc_attr( $item['dropdown_class'] ) : 'adv-dropdown';
            echo '<div class="' . $dropdown_class . '">' . "\n";
            
            if ( ! empty( $item['dropdown_title'] ) ) {
                echo '<div class="adv-dropdown-title">' . wp_kses_post( $item['dropdown_title'] ) . '</div>' . "\n";
            }
            
            echo '<ul>' . "\n";
            
            foreach ( $item['children'] as $child ) {
                // Check if this is a group label (non-clickable)
                if ( isset( $child['type'] ) && $child['type'] === 'group_label' ) {
                    echo '<li class="adv-dropdown-group-label">' . "\n";
                    echo '<span>' . esc_html( $child['name'] ) . '</span>' . "\n";
                    echo '</li>' . "\n";
                } else {
                    // Regular clickable item
                    echo '<li>' . "\n";
                    echo '<a href="' . esc_url( $child['url'] ) . '">' . "\n";
                    echo '<span>' . esc_html( $child['name'] ) . '</span>' . "\n";
                    
                    // Add tag if present
                    if ( ! empty( $child['tag'] ) ) {
                        echo '<span class="adv-tag">' . esc_html( $child['tag'] ) . '</span>' . "\n";
                    }
                    
                    echo '</a>' . "\n";
                    echo '</li>' . "\n";
                }
            }
            
            echo '</ul>' . "\n";
            echo '</div>' . "\n";
        }

        echo '</li>' . "\n";
    }

    echo '</ul>' . "\n";
}

/**
 * Invalidate navigation cache when products or terms change
 */
function advapes_v3_invalidate_nav_cache() {
    delete_transient( ADVAPES_NAV_V3_TRANSIENT_KEY );
}

/**
 * Hook invalidation to product and term changes
 */
function advapes_v3_setup_cache_invalidation() {
    // Product save/trash
    add_action( 'save_post_product', 'advapes_v3_invalidate_nav_cache' );
    add_action( 'wp_trash_post', 'advapes_v3_invalidate_nav_cache' );
    add_action( 'untrash_post', 'advapes_v3_invalidate_nav_cache' );
    
    // Product category changes
    add_action( 'created_product_cat', 'advapes_v3_invalidate_nav_cache' );
    add_action( 'edited_product_cat', 'advapes_v3_invalidate_nav_cache' );
    add_action( 'delete_product_cat', 'advapes_v3_invalidate_nav_cache' );
    
    // Brand taxonomy changes (detect and hook)
    $brand_taxonomy = advapes_v3_detect_brand_taxonomy();
    if ( $brand_taxonomy ) {
        add_action( 'created_' . $brand_taxonomy, 'advapes_v3_invalidate_nav_cache' );
        add_action( 'edited_' . $brand_taxonomy, 'advapes_v3_invalidate_nav_cache' );
        add_action( 'delete_' . $brand_taxonomy, 'advapes_v3_invalidate_nav_cache' );
    }
}
add_action( 'init', 'advapes_v3_setup_cache_invalidation' );

/**
 * Register REST API endpoints
 */
function advapes_v3_register_rest_route() {
    // GET endpoint - view navigation structure
    register_rest_route( 'advapes/v3', '/nav', array(
        'methods'  => 'GET',
        'callback' => 'advapes_v3_rest_get_nav',
        'permission_callback' => '__return_true',
    ) );
    
    // POST endpoint - refresh cache (admin only)
    register_rest_route( 'advapes/v3', '/nav/refresh', array(
        'methods'  => 'POST',
        'callback' => 'advapes_v3_rest_refresh_nav',
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
    ) );
}
add_action( 'rest_api_init', 'advapes_v3_register_rest_route' );

/**
 * REST API callback - get navigation structure
 */
function advapes_v3_rest_get_nav( $request ) {
    $force_refresh = $request->get_param( 'refresh' ) === 'true';
    $nav_structure = advapes_v3_get_nav_structure( $force_refresh );
    
    return rest_ensure_response( array(
        'success' => true,
        'data'    => $nav_structure,
    ) );
}

/**
 * REST API callback - force cache refresh (admin only)
 */
function advapes_v3_rest_refresh_nav( $request ) {
    advapes_v3_invalidate_nav_cache();
    return rest_ensure_response( array(
        'success' => true,
        'message' => 'V3 Navigation cache cleared successfully',
    ) );
}
