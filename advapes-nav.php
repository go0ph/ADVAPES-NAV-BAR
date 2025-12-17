<?php
/**
 * ADVapes Dynamic Navigation System
 * Version 4.0.0 (V2 - December 2025)
 * 
 * V2 Major Redesign:
 * - MOBILE: Right-side flyout drawer menu with hierarchical drill-down navigation
 * - DESKTOP: Expanded content with more subcategories for full catalogue browsing
 * - Enhanced user experience to browse entire product range
 * - Maintains high SEO scores while providing comprehensive navigation
 * 
 * Key Features:
 * - Mobile: Slide-out drawer from right with back navigation
 * - Desktop: Expanded dropdowns (6-8 items per group vs 3 in V1)
 * - Hierarchical mobile navigation with smooth transitions
 * - Dynamic content from WooCommerce
 * - Smart filter chips for quick filtering
 * - 30-second cache with auto-invalidation
 * 
 * @package Razzi Child / ADVapes
 * @version 4.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'ADVAPES_NAV_TRANSIENT_KEY', 'advapes_nav_structure' );
define( 'ADVAPES_NAV_TTL', 30 ); // 30 seconds

/**
 * Enqueue ADVapes navigation CSS and JS
 * Only enqueues if the CSS file exists, otherwise expects inline CSS via theme customizer
 */
function advapes_enqueue_nav_styles() {
    $css_file = get_stylesheet_directory() . '/advapes-nav.css';
    
    // Only enqueue if the file exists
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style( 
            'advapes-nav', 
            get_stylesheet_directory_uri() . '/advapes-nav.css', 
            array(), 
            '3.2.0', 
            'all' 
        );
    }
}
add_action( 'wp_enqueue_scripts', 'advapes_enqueue_nav_styles' );

/**
 * Add inline JavaScript for mobile menu functionality (V2 - Flyout Drawer)
 */
function advapes_enqueue_nav_scripts() {
    // V2: Mobile flyout drawer with hierarchical drill-down navigation
    $script = "
    document.addEventListener('DOMContentLoaded', function() {
        // Only apply on mobile (matching CSS breakpoint)
        function isMobile() {
            return window.innerWidth <= 1024;
        }

        // Track if handlers are already attached to prevent duplicates
        let handlersAttached = false;
        
        // V2: Flyout drawer state management
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
        
        // V2: Reset drawer to main menu state
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
        
        // V2: Navigate to sub-panel
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
        
        // V2: Navigate back to previous panel
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
        
        // V2: Update back button visibility and text
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

        // V2: Initialize mobile flyout drawer
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
add_action( 'wp_enqueue_scripts', 'advapes_enqueue_nav_scripts' );

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
function advapes_detect_brand_taxonomy() {
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
function advapes_find_category( $slug_or_name ) {
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
 * Detect strength taxonomy from common candidates or by searching
 * 
 * Checks for strength taxonomies in order:
 * - 'pa_strength' (most common WooCommerce product attribute)
 * - Any taxonomy containing "strength" with a term named '50mg NS'
 * 
 * @return string|false Strength taxonomy name or false if not found
 */
function advapes_detect_strength_taxonomy() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return false;
    }

    // Try pa_strength first (most common)
    if ( taxonomy_exists( 'pa_strength' ) ) {
        return 'pa_strength';
    }

    // Search through all WooCommerce attribute taxonomies for one containing "strength"
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( ! empty( $attribute_taxonomies ) ) {
        foreach ( $attribute_taxonomies as $tax ) {
            if ( strpos( strtolower( $tax->attribute_name ), 'strength' ) !== false ) {
                $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                // Verify this taxonomy has a term named '50mg NS' to confirm it's the right one
                $test_term = get_term_by( 'name', '50mg NS', $taxonomy_name );
                if ( $test_term && ! is_wp_error( $test_term ) ) {
                    return $taxonomy_name;
                }
            }
        }
    }

    return false;
}

/**
 * Detect size taxonomy from common candidates or by searching
 * 
 * Checks for size taxonomies in order:
 * - 'pa_size' (most common WooCommerce product attribute)
 * - Any taxonomy containing a term named '5000 Puff' or similar puff counts
 * 
 * @return string|false Size taxonomy name or false if not found
 */
function advapes_detect_size_taxonomy() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return false;
    }

    // Try pa_size first (most common)
    if ( taxonomy_exists( 'pa_size' ) ) {
        return 'pa_size';
    }

    // Search through all WooCommerce attribute taxonomies for one containing puff count terms
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( ! empty( $attribute_taxonomies ) ) {
        foreach ( $attribute_taxonomies as $tax ) {
            $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
            // Check if this taxonomy contains typical puff count terms
            $test_terms = array( '5000 Puff', '10 000 Puff', '10000 Puff', '20 000 Puff' );
            foreach ( $test_terms as $test_name ) {
                $test_term = get_term_by( 'name', $test_name, $taxonomy_name );
                if ( $test_term && ! is_wp_error( $test_term ) ) {
                    return $taxonomy_name;
                }
            }
        }
    }

    return false;
}

/**
 * Count products for a specific chip filter with context awareness and caching
 * 
 * This function provides accurate, context-aware product counts for filter chips.
 * Unlike term->count which gives global counts, this counts products that:
 * - Are published
 * - Are in the specified category context (e.g., Disposables)
 * - Have the specified attribute term (e.g., "20 000 Puff" for Size)
 * - Optionally: are in stock (configurable)
 * 
 * Results are cached with transients to optimize performance.
 * 
 * @param int $context_category_id Category ID for context (e.g., Disposables category ID)
 * @param string $filter_taxonomy Attribute taxonomy name (e.g., 'pa_size', 'pa_strength')
 * @param int $filter_term_id Term ID within the attribute taxonomy
 * @param bool $include_children Whether to include products from child categories (default: true)
 * @param bool $in_stock_only Whether to filter by stock status (default: false)
 * @return int Number of products matching the criteria
 */
function adv_count_products_for_chip( $context_category_id, $filter_taxonomy, $filter_term_id, $include_children = true, $in_stock_only = false ) {
    // Validate inputs - ensure IDs are positive integers
    $context_category_id = absint( $context_category_id );
    $filter_term_id = absint( $filter_term_id );
    
    if ( $context_category_id < 1 || empty( $filter_taxonomy ) || $filter_term_id < 1 ) {
        return 0;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return 0;
    }
    
    // Build cache key
    $cache_key = 'adv_chip_count_' . $context_category_id . '_' . $filter_taxonomy . '_' . $filter_term_id;
    if ( $include_children ) {
        $cache_key .= '_with_children';
    }
    if ( $in_stock_only ) {
        $cache_key .= '_in_stock';
    }
    
    // Try to get from cache
    $cached_count = get_transient( $cache_key );
    if ( false !== $cached_count ) {
        return (int) $cached_count;
    }
    
    // Build category IDs array (include children if requested)
    $category_ids = array( $context_category_id );
    if ( $include_children ) {
        $children = get_term_children( $context_category_id, 'product_cat' );
        if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
            $category_ids = array_merge( $category_ids, $children );
        }
    }
    
    // Build tax query for WP_Query
    $tax_query = array(
        'relation' => 'AND',
        // Category constraint
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $category_ids,
            'operator' => 'IN',
        ),
        // Attribute term constraint
        array(
            'taxonomy' => $filter_taxonomy,
            'field'    => 'term_id',
            'terms'    => $filter_term_id,
            'operator' => 'IN',
        ),
    );
    
    // Build meta query for stock status if needed
    $meta_query = array();
    if ( $in_stock_only ) {
        $meta_query[] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        );
    }
    
    // Query arguments
    // Note: Using WP_Query with 'fields' => 'ids' is the WordPress-recommended way to count
    // products with complex tax_query and meta_query constraints. While 'posts_per_page' => -1
    // retrieves all IDs, this is acceptable because:
    // 1. We're only fetching IDs (minimal memory footprint)
    // 2. Results are cached for 6 hours (query runs rarely)
    // 3. WooCommerce filter chips typically show <500 products per category/attribute combo
    // 4. Custom SQL would bypass WooCommerce's product visibility filters
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'no_found_rows'  => true, // Skip SQL_CALC_FOUND_ROWS for better performance
        'tax_query'      => $tax_query,
    );
    
    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }
    
    // Execute query and count results
    $query = new WP_Query( $args );
    $count = count( $query->posts );
    
    // Cache the result for 6 hours (21600 seconds)
    set_transient( $cache_key, $count, 6 * HOUR_IN_SECONDS );
    
    return (int) $count;
}

/**
 * Clear chip count cache for a specific category and taxonomy
 * Helper function to invalidate cached counts when products change
 * 
 * Note: Uses direct SQL queries for bulk transient deletion. This is necessary because:
 * 1. WordPress doesn't provide a native API for wildcard transient deletion
 * 2. Looping through delete_transient() would require knowing all cache keys in advance
 * 3. This approach is used by WordPress core and major plugins (e.g., WooCommerce)
 * 4. All values are properly escaped with $wpdb->esc_like() and absint()
 * 
 * @param int $category_id Category ID (0 clears all)
 * @param string $taxonomy Attribute taxonomy (optional, clears all if not provided)
 */
function adv_clear_chip_count_cache( $category_id = 0, $taxonomy = '' ) {
    global $wpdb;
    
    if ( empty( $category_id ) && empty( $taxonomy ) ) {
        // Clear all chip count caches using prepared statements for safety
        $wpdb->query( $wpdb->prepare( 
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", 
            $wpdb->esc_like( '_transient_adv_chip_count_' ) . '%' 
        ) );
        $wpdb->query( $wpdb->prepare( 
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", 
            $wpdb->esc_like( '_transient_timeout_adv_chip_count_' ) . '%' 
        ) );
    } elseif ( $category_id > 0 ) {
        // Clear caches for specific category using prepared statements
        $like_pattern = $wpdb->esc_like( '_transient_adv_chip_count_' . absint( $category_id ) . '_' ) . '%';
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_pattern ) );
        
        $like_pattern = $wpdb->esc_like( '_transient_timeout_adv_chip_count_' . absint( $category_id ) . '_' ) . '%';
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_pattern ) );
    }
}

/**
 * Get puff count chip links for the Disposables dropdown
 * 
 * Returns an array of chip link data with dynamic URLs based on term slugs.
 * Uses term name lookup with fallbacks for each puff count range.
 * 
 * @param int $category_id Optional category ID to use as base URL context
 * @return array Array of chip data (name, url, count) or empty if taxonomy not found
 */
function advapes_get_puff_count_chips( $category_id = 0 ) {
    $size_taxonomy = advapes_detect_size_taxonomy();
    if ( ! $size_taxonomy ) {
        return array();
    }

    // Define puff count ranges with preferred terms and fallbacks
    $puff_ranges = array(
        array(
            'label' => '10k–15k',
            'preferred' => '10 000 Puff',
            'fallbacks' => array( '12 000 Puff', '15 000 Puff', '10000 Puff', '12000 Puff', '15000 Puff' ),
        ),
        array(
            'label' => '20k–30k',
            'preferred' => '20 000 Puff',
            'fallbacks' => array( '25 000 Puff', '30 000 Puff', '20000 Puff', '25000 Puff', '30000 Puff' ),
        ),
        array(
            'label' => '40k+',
            'preferred' => '40 000 Puff',
            'fallbacks' => array( '50 000 Puff', '55 000 Puff', '40000 Puff', '50000 Puff', '55000 Puff' ),
        ),
    );
    
    // Determine base URL - prefer category context, fallback to WooCommerce shop page
    $base_url = wc_get_page_permalink( 'shop' );
    if ( $category_id > 0 ) {
        $category = get_term( $category_id, 'product_cat' );
        if ( $category && ! is_wp_error( $category ) ) {
            $base_url = get_term_link( $category );
        }
    }
    
    $chips = array();
    foreach ( $puff_ranges as $range ) {
        // Try preferred term first
        $term = get_term_by( 'name', $range['preferred'], $size_taxonomy );
        
        // If preferred not found, try fallbacks
        if ( ! $term || is_wp_error( $term ) ) {
            foreach ( $range['fallbacks'] as $fallback ) {
                $term = get_term_by( 'name', $fallback, $size_taxonomy );
                if ( $term && ! is_wp_error( $term ) ) {
                    break; // Found a fallback, stop searching
                }
            }
        }
        
        // If we found a valid term, add the chip
        if ( $term && ! is_wp_error( $term ) ) {
            // Build filter URL using site's existing WooCommerce filter pattern:
            // - filter_size: the taxonomy term slug for filtering
            // - filter=1: activates the filtering system
            $url = add_query_arg( 
                array( 
                    'filter_size' => $term->slug, 
                    'filter' => 1 
                ), 
                $base_url 
            );
            
            // Get context-aware count (products in this category with this size attribute)
            $count = 0;
            if ( $category_id > 0 ) {
                $count = adv_count_products_for_chip( $category_id, $size_taxonomy, $term->term_id );
            } else {
                // Fallback to term count if no category context
                $count = $term->count;
            }
            
            $chips[] = array(
                'name' => $range['label'],
                'url' => $url,
                'count' => $count,
            );
        }
    }
    
    return $chips;
}

/**
 * Helper function to strip "NS" or "Nic Salt" from strength labels for display
 * 
 * This is a presentation-only change - removes trailing "NS" or "Nic Salt" variations
 * from labels while keeping internal logic unchanged.
 * 
 * @param string $label Original label (e.g., "10mg NS", "20mg Nic Salt")
 * @return string Cleaned label (e.g., "10mg", "20mg")
 */
function advapes_strip_ns_from_label( $label ) {
    // Trim any extra spaces first
    $label = trim( $label );
    
    // Remove trailing "NS" and any preceding spaces
    $label = preg_replace( '/\s+NS$/i', '', $label );
    
    // Remove trailing "Nic Salt" and any preceding spaces
    $label = preg_replace( '/\s+Nic\s+Salt$/i', '', $label );
    
    // Trim again to ensure no trailing spaces
    return trim( $label );
}

/**
 * Get strength pill links for the MTL & Nic Salts dropdown
 * 
 * Returns an array of pill link data with dynamic URLs based on term slugs.
 * Uses the base URL from the nic salts category or falls back to /shop/.
 * 
 * @param int $category_id Optional category ID to use as base URL context
 * @return array Array of pill data (name, url, count) or empty if taxonomy not found
 */
function advapes_get_strength_pills( $category_id = 0 ) {
    $strength_taxonomy = advapes_detect_strength_taxonomy();
    if ( ! $strength_taxonomy ) {
        return array();
    }

    // Define the strength names we want to display
    // These are the internal term names - we look up terms using these full names
    $strength_names = array( '10mg NS', '20mg NS', '50mg NS' );
    
    // Determine base URL - prefer category context, fallback to WooCommerce shop page
    $base_url = wc_get_page_permalink( 'shop' );
    if ( $category_id > 0 ) {
        $category = get_term( $category_id, 'product_cat' );
        if ( $category && ! is_wp_error( $category ) ) {
            $base_url = get_term_link( $category );
        }
    }
    
    $pills = array();
    foreach ( $strength_names as $strength_name ) {
        // Continue matching the full term name internally (e.g., "20mg NS")
        $term = get_term_by( 'name', $strength_name, $strength_taxonomy );
        if ( $term && ! is_wp_error( $term ) ) {
            // Build filter URL using site's existing WooCommerce filter pattern:
            // - filter_strength: the taxonomy term slug for filtering
            // - filter=1: activates the filtering system
            $url = add_query_arg( 
                array( 
                    'filter_strength' => $term->slug, 
                    'filter' => 1 
                ), 
                $base_url 
            );
            
            // Get context-aware count (products in this category with this strength attribute)
            $count = 0;
            if ( $category_id > 0 ) {
                $count = adv_count_products_for_chip( $category_id, $strength_taxonomy, $term->term_id );
            } else {
                // Fallback to term count if no category context
                $count = $term->count;
            }
            
            // Strip "NS" or "Nic Salt" only from the rendered label
            // Internal term name: "10mg NS" → Display label: "10mg"
            $display_label = advapes_strip_ns_from_label( $strength_name );
            
            $pills[] = array(
                'name' => $display_label,  // Display label without NS
                'url' => $url,             // URL unchanged - uses term slug
                'count' => $count,
            );
        }
    }
    
    return $pills;
}

/**
 * Helper function to get child categories for a parent category
 * 
 * @param int $parent_id Parent category term ID
 * @param int $limit Maximum number of children to return (default 12)
 * @return array Array of child category data (name, url, count)
 */
function advapes_get_category_children( $parent_id, $limit = 12 ) {
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
function advapes_get_category_brands( $category_id, $limit = 4 ) {
    $brand_taxonomy = advapes_detect_brand_taxonomy();
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
    // WordPress $wpdb->prepare() requires all values to use placeholders, but for IN clauses
    // we need to build the placeholder string dynamically
    $placeholders_str = implode( ',', array_fill( 0, count( $category_ids ), '%d' ) );
    
    // Build the query with placeholders
    // This query finds brands associated with products in the specified category and its children:
    // 1. Join brand terms (t) with their taxonomy relationships (tt, tr)
    // 2. Join to products (p) that have those brand terms
    // 3. Join products to their category relationships (tr2, tt2)
    // 4. Filter by brand taxonomy, product categories, and published status
    // 5. Count distinct products per brand and order by count
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
function advapes_has_active_promo() {
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
    return apply_filters( 'advapes_has_active_promo', $has_promo );
}

/**
 * Get navigation structure from WooCommerce data
 * 
 * Hybrid approach (v3.1):
 * - Static parent menu structure from v2 (for user familiarity)
 * - Dynamic subcategories from WooCommerce (for fresh content)
 * 
 * Parent menu order is fixed, but subcategories update automatically
 * based on actual WooCommerce category structure and product counts.
 * 
 * @param bool $force_refresh Force rebuild even if cache exists
 * @return array Navigation structure
 */
function advapes_get_nav_structure( $force_refresh = false ) {
    // Try to get from cache first
    if ( ! $force_refresh ) {
        $cached = get_transient( ADVAPES_NAV_TRANSIENT_KEY );
        if ( false !== $cached ) {
            return $cached;
        }
    }

    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array();
    }

    $nav_structure = array();

    // 1. Static Deals section (state-aware for active promotions)
    $has_active_promo = advapes_has_active_promo();
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

    // 2. Disposables (hybrid: specific subcategories + dynamic "View All")
    $disposables = advapes_find_category( 'disposables' );
    if ( $disposables ) {
        // V2: Get max 8 subcategories for better catalogue coverage
        $subcategories = advapes_get_category_children( $disposables->term_id, 8 );
        
        // Build children array with micro-grouping
        $children = array();
        
        // Add "By Type" group label (non-clickable)
        if ( ! empty( $subcategories ) ) {
            $children[] = array(
                'type' => 'group_label',
                'name' => 'By Type',
            );
            // Add subcategories (max 3)
            foreach ( $subcategories as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // Add PUFF COUNT chips section (Goal B)
        $puff_count_chips = advapes_get_puff_count_chips( $disposables->term_id );
        if ( ! empty( $puff_count_chips ) ) {
            // Add "PUFF COUNT" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'PUFF COUNT',
            );
            // Add puff count chips as a special type that will be rendered differently
            $children[] = array(
                'type' => 'puff_count_chips',
                'chips' => $puff_count_chips,
            );
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $disposables->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end with improved CTA copy
        $children[] = array(
            'name' => 'View All Disposables',
            'url'  => get_term_link( $disposables ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['disposables'] = array(
            'name' => 'Disposables',
            'url'  => get_term_link( $disposables ),
            'dropdown_title' => 'One-use disposable vapes (' . $disposables->count . '+)',
            'children' => $children,
        );
    }

    // 3. Pod Disposables (hybrid: dynamic subcategories)
    $pod_disposables = advapes_find_category( 'pod-disposables' );
    if ( $pod_disposables ) {
        // V2: Get max 8 subcategories for better catalogue coverage
        $subcategories = advapes_get_category_children( $pod_disposables->term_id, 8 );
        
        // Build children array with micro-grouping
        $children = array();
        
        // Add "By Type" group label (non-clickable)
        if ( ! empty( $subcategories ) ) {
            $children[] = array(
                'type' => 'group_label',
                'name' => 'By Type',
            );
            // V2: Add subcategories (max 8 for better coverage)
            foreach ( $subcategories as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // Add NIC SALT STRENGTHS chips section (Goal C)
        $strength_pills = advapes_get_strength_pills( $pod_disposables->term_id );
        if ( ! empty( $strength_pills ) ) {
            // Add "NIC SALT STRENGTHS" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'NIC SALT STRENGTHS',
            );
            // Add strength pills as a special type that will be rendered differently
            $children[] = array(
                'type' => 'strength_pills',
                'pills' => $strength_pills,
            );
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $pod_disposables->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end with improved CTA copy
        $children[] = array(
            'name' => 'View All Pod Disposables',
            'url'  => get_term_link( $pod_disposables ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['pod-disposables'] = array(
            'name' => 'Pod Disposables',
            'url'  => get_term_link( $pod_disposables ),
            'dropdown_title' => 'Pod-based systems (' . $pod_disposables->count . '+)',
            'children' => $children,
        );
    }

    // 4. Pod Systems & Kits (hybrid: dynamic subcategories)
// NOTE: Do NOT add chips to this dropdown per requirements
$pod_systems = advapes_find_category( 'pod-systems-kits' );
if ( $pod_systems ) {
    // V2: Get max 8 subcategories for better catalogue coverage
    $subcategories = advapes_get_category_children( $pod_systems->term_id, 8 );

    // Build children array with micro-grouping
    $children = array();

    // NEW REQUIREMENT: Only show "By Type" if we have 2+ subcategories
    // Otherwise replace with "POPULAR PICKS" guidance group
    if ( ! empty( $subcategories ) && count( $subcategories ) >= 2 ) {
        $children[] = array(
            'type' => 'group_label',
            'name' => 'By Type',
        );

        // Add subcategories (max 3) with NAV label override
        foreach ( array_slice( $subcategories, 0, 3 ) as $subcat ) {

            // NAV DISPLAY OVERRIDE ONLY:
            // If the "refillable-pods" subcategory is present, show it as "Pod Kits" in the menu.
            // URL remains /pod-systems-kits/refillable-pods/ and the actual category name stays unchanged in WooCommerce.
            if ( ! empty( $subcat['url'] ) && strpos( $subcat['url'], '/pod-systems-kits/refillable-pods/' ) !== false ) {
                $subcat['name'] = 'Pod Kits';
            }

            $children[] = $subcat;
        }

    } else {
        // Add "POPULAR PICKS" group with guidance links
        $children[] = array(
            'type' => 'group_label',
            'name' => 'POPULAR PICKS',
        );

        // Try to find preferred guidance categories (max 3)
        $guidance_categories = array(
            array( 'slug' => 'beginner-friendly-pods', 'fallback_name' => 'Beginner-Friendly Pods' ),
            array( 'slug' => 'compact-pod-kits', 'fallback_name' => 'Compact Pod Kits' ),
            array( 'slug' => 'advanced-pod-kits', 'fallback_name' => 'Advanced Pod Kits' ),
        );

        $guidance_count = 0;
        foreach ( $guidance_categories as $guidance ) {
            if ( $guidance_count >= 3 ) break;

            $cat = advapes_find_category( $guidance['slug'] );
            if ( $cat && ! is_wp_error( $cat ) ) {
                $children[] = array(
                    'name'  => $cat->name,
                    'url'   => get_term_link( $cat ),
                    'count' => $cat->count,
                );
                $guidance_count++;
            }
        }

        // If no specific guidance categories found, use available subcategories with friendly labels
        if ( $guidance_count === 0 && ! empty( $subcategories ) ) {
            foreach ( array_slice( $subcategories, 0, 8 ) as $subcat ) {

                // Apply the same NAV label override here too (in case fallback uses subcategories)
                if ( ! empty( $subcat['url'] ) && strpos( $subcat['url'], '/pod-systems-kits/refillable-pods/' ) !== false ) {
                    $subcat['name'] = 'Pod Kits';
                }

                $children[] = $subcat;
                $guidance_count++;
                if ( $guidance_count >= 8 ) break;
            }
        }

        // Fallback: if still no items, show the parent category itself
        if ( $guidance_count === 0 ) {
            $children[] = array(
                'name'  => 'Pod Systems & Kits',
                'url'   => get_term_link( $pod_systems ),
                'count' => $pod_systems->count,
            );
        }
    }

    // V2: Add top brands for this category - increased to 6 for better coverage
    $brands = advapes_get_category_brands( $pod_systems->term_id, 6 );
    if ( ! empty( $brands ) ) {
        // Add "Top Brands" group label (non-clickable)
        $children[] = array(
            'type' => 'group_label',
            'name' => 'Top Brands',
        );
        foreach ( $brands as $brand ) {
            $children[] = $brand;
        }
    }

    // Add "View All" link at the end with improved CTA copy
    $children[] = array(
        'name' => 'Shop All Pod Systems',
        'url'  => get_term_link( $pod_systems ),
        'tag'  => 'Shop all',
    );

    $nav_structure['pod-systems-kits'] = array(
        'name'           => 'Pod Systems &amp; Kits',
        'url'            => get_term_link( $pod_systems ),
        'dropdown_title' => 'Refillable pod systems (' . $pod_systems->count . '+)',
        'children'       => $children,
    );
}


    // 5. Vape Hardware (hybrid: dynamic subcategories)
    // Try 'dl-hardware' first (v2 slug), then 'vape-hardware'
    $hardware = advapes_find_category( 'dl-hardware' );
    if ( ! $hardware ) {
        $hardware = advapes_find_category( 'vape-hardware' );
    }
    if ( $hardware ) {
        // Build children array with micro-grouping
        $children = array();
        
        // V2: Replace "By Type" with "BY HARDWARE TYPE"
        // Show up to 6 proper hardware type links (not brands)
        $children[] = array(
            'type' => 'group_label',
            'name' => 'BY HARDWARE TYPE',
        );
        
        // Try to find these specific hardware type categories (max 3)
        $hardware_types = array(
            array( 'slug' => 'vape-mods', 'alt_slugs' => array( 'mods' ), 'fallback_name' => 'Vape Mods' ),
            array( 'slug' => 'tanks-rtas', 'alt_slugs' => array( 'tanks', 'rtas', 'tanks-and-rtas' ), 'fallback_name' => 'Tanks & RTAs' ),
            array( 'slug' => 'coils-spares', 'alt_slugs' => array( 'coils', 'coils-pods-spares', 'coils-and-spares' ), 'fallback_name' => 'Coils & Spares' ),
        );
        
        $types_added = 0;
        foreach ( $hardware_types as $type ) {
            if ( $types_added >= 6 ) break;
            
            // Try main slug first
            $cat = advapes_find_category( $type['slug'] );
            
            // Try alternative slugs if main not found
            if ( ! $cat || is_wp_error( $cat ) ) {
                foreach ( $type['alt_slugs'] as $alt_slug ) {
                    $cat = advapes_find_category( $alt_slug );
                    if ( $cat && ! is_wp_error( $cat ) ) {
                        break;
                    }
                }
            }
            
            if ( $cat && ! is_wp_error( $cat ) ) {
                $children[] = array(
                    'name' => $cat->name,
                    'url'  => get_term_link( $cat ),
                    'count' => $cat->count,
                );
                $types_added++;
            }
        }
        
        // V2: Fallback - if no specific types found, use top 6 subcategories but filter out brand-like names
        if ( $types_added === 0 ) {
            $subcategories = advapes_get_category_children( $hardware->term_id, 12 );
            // Filter out items that look like brand coil makers (e.g., "Bearded Viking Coils", "White Collar Coils")
            // Known hardware type words that should NOT be filtered
            $hardware_type_words = array( 'vape', 'tank', 'mod', 'coil', 'spare', 'kit', 'pod', 'rta', 'rdta', 'rda', 'atomizer' );
            $filtered_subcats = array();
            
            foreach ( $subcategories as $subcat ) {
                $subcat_name_lower = strtolower( $subcat['name'] );
                $is_brand = false;
                
                // Check if it looks like a brand-specific coil/accessory (e.g., "Bearded Viking Coils")
                // Pattern: Two or more capitalized words followed by "Coils" or similar
                if ( preg_match( '/^[A-Z][a-z]+\s+[A-Z][a-z]+\s+(Coils|Accessories|Parts)$/i', $subcat['name'] ) ) {
                    // Only mark as brand if it doesn't contain hardware type words
                    $contains_hardware_word = false;
                    foreach ( $hardware_type_words as $hw_word ) {
                        if ( strpos( $subcat_name_lower, $hw_word ) !== false ) {
                            $contains_hardware_word = true;
                            break;
                        }
                    }
                    
                    if ( ! $contains_hardware_word ) {
                        $is_brand = true; // Likely "Brand Name Coils"
                    }
                }
                
                if ( ! $is_brand ) {
                    $filtered_subcats[] = $subcat;
                    if ( count( $filtered_subcats ) >= 6 ) break;
                }
            }
            
            foreach ( array_slice( $filtered_subcats, 0, 6 ) as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $hardware->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end with improved CTA copy
        $children[] = array(
            'name' => 'View All Vape Hardware',
            'url'  => get_term_link( $hardware ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['vape-hardware'] = array(
            'name' => 'Vape Hardware',
            'url'  => get_term_link( $hardware ),
            'dropdown_title' => 'Mods, tanks &amp; spares (' . $hardware->count . '+)',
            'children' => $children,
        );
    }

    // 6. DL E-Liquids (hybrid: dynamic subcategories)
    // NOTE: Do NOT add strength chips to this dropdown per requirements
    $dl_liquids = advapes_find_category( 'dl-liquid' );
    if ( ! $dl_liquids ) {
        $dl_liquids = advapes_find_category( 'dl-liquids' );
    }
    if ( $dl_liquids ) {
        // Build children array with micro-grouping
        $children = array();
        
        // NEW REQUIREMENT: Add "BY FORMAT" group before Top Brands
        $children[] = array(
            'type' => 'group_label',
            'name' => 'BY FORMAT',
        );
        
        // Try to find format-specific categories (max 3)
        $format_categories = array(
            array( 'slug' => 'premixed-dl-liquids', 'alt_slugs' => array( 'dl-premixed', 'pre-mixed-freebase', 'premixed-freebase' ), 'fallback_name' => 'Premixed DL Liquids' ),
            array( 'slug' => 'dl-longfills', 'alt_slugs' => array( 'dl-long-fill-kits', 'longfills', 'dl-long-fills' ), 'fallback_name' => 'DL Longfills' ),
            array( 'slug' => 'flavour-shots', 'alt_slugs' => array( 'dl-long-fill-flavour-shots', 'flavor-shots', 'dl-flavour-shots' ), 'fallback_name' => 'Flavour Shots' ),
        );
        
        $formats_added = 0;
        foreach ( $format_categories as $format ) {
            if ( $formats_added >= 6 ) break;
            
            // Try main slug first
            $cat = advapes_find_category( $format['slug'] );
            
            // Try alternative slugs if main not found
            if ( ! $cat || is_wp_error( $cat ) ) {
                foreach ( $format['alt_slugs'] as $alt_slug ) {
                    $cat = advapes_find_category( $alt_slug );
                    if ( $cat && ! is_wp_error( $cat ) ) {
                        break;
                    }
                }
            }
            
            if ( $cat && ! is_wp_error( $cat ) ) {
                $children[] = array(
                    'name' => $cat->name,
                    'url'  => get_term_link( $cat ),
                    'count' => $cat->count,
                );
                $formats_added++;
            }
        }
        
        // V2: Fallback - if no specific format categories found, use top 6 subcategories
        if ( $formats_added === 0 ) {
            $subcategories = advapes_get_category_children( $dl_liquids->term_id, 6 );
            foreach ( array_slice( $subcategories, 0, 6 ) as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $dl_liquids->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end with improved CTA copy
        $children[] = array(
            'name' => 'View All DL E-Liquids',
            'url'  => get_term_link( $dl_liquids ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['dl-liquids'] = array(
            'name' => 'DL E-Liquids',
            'url'  => get_term_link( $dl_liquids ),
            'dropdown_title' => 'Direct lung liquids (' . $dl_liquids->count . '+)',
            'children' => $children,
        );
    }

    // 7. MTL & Nic Salts (hybrid: dynamic subcategories)
    // Already has strength pills
    $nic_salts = advapes_find_category( 'nic-salts' );
    if ( ! $nic_salts ) {
        $nic_salts = advapes_find_category( 'nic-salts-mtl-liquids' );
    }
    if ( $nic_salts ) {
        // V2: Get max 8 subcategories for better catalogue coverage
        $subcategories = advapes_get_category_children( $nic_salts->term_id, 8 );
        
        // Build children array with micro-grouping
        $children = array();
        
        // Add "By Type" group label (non-clickable)
        if ( ! empty( $subcategories ) ) {
            $children[] = array(
                'type' => 'group_label',
                'name' => 'By Type',
            );
            // V2: Add subcategories (max 8 for better coverage)
            foreach ( $subcategories as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // Add strength pills section (clickable filters) - Goal D
        $strength_pills = advapes_get_strength_pills( $nic_salts->term_id );
        if ( ! empty( $strength_pills ) ) {
            // Add "NIC SALT STRENGTHS" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'NIC SALT STRENGTHS',
            );
            // Add strength pills as a special type that will be rendered differently
            $children[] = array(
                'type' => 'strength_pills',
                'pills' => $strength_pills,
            );
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $nic_salts->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end with improved CTA copy
        $children[] = array(
            'name' => 'View All Nic Salts',
            'url'  => get_term_link( $nic_salts ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['mtl-nic-salts'] = array(
            'name' => 'MTL &amp; Nic Salts',
            'url'  => get_term_link( $nic_salts ),
            'dropdown_title' => 'Nic salts &amp; MTL liquids (' . $nic_salts->count . '+)',
            'children' => $children,
        );
    }

    // 8. Nic Alternatives (static parent, dynamic children)
    $nic_alternatives = advapes_find_category( 'nicotine-alternatives' );
    if ( $nic_alternatives ) {
        // V2: Get max 8 subcategories for better catalogue coverage
        $subcategories = advapes_get_category_children( $nic_alternatives->term_id, 8 );
        
        // Build children array with micro-grouping
        $children = array();
        
        // Add "By Type" group label (non-clickable)
        if ( ! empty( $subcategories ) ) {
            $children[] = array(
                'type' => 'group_label',
                'name' => 'By Type',
            );
            // V2: Add subcategories (max 8 for better coverage)
            foreach ( $subcategories as $subcat ) {
                $children[] = $subcat;
            }
        }
        
        // V2: Add top brands for this category - increased to 6 for better coverage
        $brands = advapes_get_category_brands( $nic_alternatives->term_id, 6 );
        if ( ! empty( $brands ) ) {
            // Add "Top Brands" group label (non-clickable)
            $children[] = array(
                'type' => 'group_label',
                'name' => 'Top Brands',
            );
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link with improved CTA copy
        $children[] = array(
            'name' => 'View All Nic Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'tag'  => 'Shop all',
        );
        
        $nav_structure['nic-alternatives'] = array(
            'name' => 'Nic Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'dropdown_title' => 'Non-vape nicotine (' . $nic_alternatives->count . '+)',
            'children' => $children,
        );
    }

    // 9. Brands (static parent, dynamic top brands)
    // Ensure Brands menu is included in navigation structure
    $brand_taxonomy = advapes_detect_brand_taxonomy();
    $brand_children = array();
    
    if ( $brand_taxonomy ) {
        // V2: Show more brands for better catalogue coverage (20 instead of 10)
        $brands = get_terms( array(
            'taxonomy'   => $brand_taxonomy,
            'hide_empty' => true,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 20,
        ) );

        if ( ! is_wp_error( $brands ) && ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $brand_children[] = array(
                    'name' => $brand->name,
                    'url'  => get_term_link( $brand ),
                    'count' => $brand->count,
                );
            }
        }
    }
    
    // Calculate brand count (excluding the "View All" link we're about to add)
    $brand_count = count( $brand_children );
    
    // Add "View All Brands" link (always present)
    $brand_children[] = array(
        'name' => 'View All Brands',
        'url'  => 'https://www.advapes.co.za/brands/',
        'tag'  => 'Browse all',
    );
    
    // Build dropdown title with count if brands exist
    $dropdown_title = 'Shop by brand';
    if ( $brand_count > 0 ) {
        $dropdown_title .= ' (' . $brand_count . '+)';
    }
    
    // Always add Brands to navigation structure
    $nav_structure['brands'] = array(
        'name' => 'Brands',
        'url'  => 'https://www.advapes.co.za/brands/',
        'dropdown_title' => $dropdown_title,
        'dropdown_class' => 'adv-dropdown--wide',
        'children' => $brand_children,
    );

    // 10. Static Support section (fully static)
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
                'name' => 'Track my Order',
                'url' => 'https://www.advapes.co.za/track/',
                'tag' => 'Shipment tracking',
            ),
            array(
                'name' => 'About Us',
                'url' => 'https://www.advapes.co.za/about-us/',
                'tag' => 'Our story',
            ),
            array(
                'name' => 'Shipping Rates',
                'url' => 'https://www.advapes.co.za/shipping-rates-2/',
                'tag' => 'Delivery info',
            ),
            array(
                'name' => 'Refunds &amp; Returns',
                'url' => 'https://www.advapes.co.za/refunds-returns/',
                'tag' => 'Policy',
            ),
            array(
                'name' => 'Terms &amp; Conditions',
                'url' => 'https://www.advapes.co.za/terms-conditions/',
                'tag' => 'Legal',
            ),
            array(
                'name' => 'Privacy Policy',
                'url' => 'https://www.advapes.co.za/privacy-policy/',
                'tag' => 'Legal',
            ),
            array(
                'name' => 'Refer a Friend',
                'url' => 'https://www.advapes.co.za/refer-a-friend/',
                'tag' => 'Rewards',
            ),
        ),
    );

    // Cache the structure
    set_transient( ADVAPES_NAV_TRANSIENT_KEY, $nav_structure, ADVAPES_NAV_TTL );

    return $nav_structure;
}

/**
 * Render mobile drawer navigation (V2 - Flyout style)
 * 
 * Generates hierarchical panel-based navigation for mobile devices
 */
function advapes_render_mobile_drawer() {
    $nav_structure = advapes_get_nav_structure();
    
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
            } elseif ( isset( $child['type'] ) && $child['type'] === 'strength_pills' ) {
                // Render strength pills in mobile drawer
                echo '<li class="adv-drawer-item adv-drawer-pills">' . "\n";
                foreach ( $child['pills'] as $pill ) {
                    echo '<a href="' . esc_url( $pill['url'] ) . '" class="adv-drawer-pill">' . "\n";
                    echo esc_html( $pill['name'] ) . "\n";
                    echo '</a>' . "\n";
                }
                echo '</li>' . "\n";
            } elseif ( isset( $child['type'] ) && $child['type'] === 'puff_count_chips' ) {
                // Render puff count chips in mobile drawer
                echo '<li class="adv-drawer-item adv-drawer-pills">' . "\n";
                foreach ( $child['chips'] as $chip ) {
                    echo '<a href="' . esc_url( $chip['url'] ) . '" class="adv-drawer-pill">' . "\n";
                    echo esc_html( $chip['name'] ) . "\n";
                    echo '</a>' . "\n";
                }
                echo '</li>' . "\n";
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
 * Render navigation HTML
 * 
 * Outputs navigation using the same class names and markup structure
 * as the existing static navigation for CSS compatibility.
 */
function advapes_render_nav() {
    $nav_structure = advapes_get_nav_structure();

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
                } elseif ( isset( $child['type'] ) && $child['type'] === 'strength_pills' ) {
                    // Render strength pills as a special list item with flex layout
                    echo '<li class="adv-strength-pills-container">' . "\n";
                    echo '<div class="adv-strength-pills">' . "\n";
                    foreach ( $child['pills'] as $pill ) {
                        echo '<a href="' . esc_url( $pill['url'] ) . '" class="adv-strength-pill">' . "\n";
                        echo '<span class="adv-pill-label">' . esc_html( $pill['name'] ) . '</span>' . "\n";
                        echo '</a>' . "\n";
                    }
                    echo '</div>' . "\n";
                    echo '</li>' . "\n";
                } elseif ( isset( $child['type'] ) && $child['type'] === 'puff_count_chips' ) {
                    // Render puff count chips using same styling as strength pills
                    echo '<li class="adv-strength-pills-container">' . "\n";
                    echo '<div class="adv-strength-pills">' . "\n";
                    foreach ( $child['chips'] as $chip ) {
                        echo '<a href="' . esc_url( $chip['url'] ) . '" class="adv-strength-pill">' . "\n";
                        echo '<span class="adv-pill-label">' . esc_html( $chip['name'] ) . '</span>' . "\n";
                        echo '</a>' . "\n";
                    }
                    echo '</div>' . "\n";
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
function advapes_invalidate_nav_cache() {
    delete_transient( ADVAPES_NAV_TRANSIENT_KEY );
    // Also clear chip count caches when navigation cache is invalidated
    adv_clear_chip_count_cache();
}

/**
 * Hook invalidation to product and term changes
 */
function advapes_setup_cache_invalidation() {
    // Product save/trash
    add_action( 'save_post_product', 'advapes_invalidate_nav_cache' );
    add_action( 'wp_trash_post', 'advapes_invalidate_nav_cache' );
    add_action( 'untrash_post', 'advapes_invalidate_nav_cache' );
    
    // Product category changes
    add_action( 'created_product_cat', 'advapes_invalidate_nav_cache' );
    add_action( 'edited_product_cat', 'advapes_invalidate_nav_cache' );
    add_action( 'delete_product_cat', 'advapes_invalidate_nav_cache' );
    
    // Brand taxonomy changes (detect and hook)
    $brand_taxonomy = advapes_detect_brand_taxonomy();
    if ( $brand_taxonomy ) {
        add_action( 'created_' . $brand_taxonomy, 'advapes_invalidate_nav_cache' );
        add_action( 'edited_' . $brand_taxonomy, 'advapes_invalidate_nav_cache' );
        add_action( 'delete_' . $brand_taxonomy, 'advapes_invalidate_nav_cache' );
    }
}
add_action( 'init', 'advapes_setup_cache_invalidation' );

/**
 * Scheduled refresh of navigation cache via WP-Cron
 */
function advapes_refresh_nav_cron() {
    advapes_get_nav_structure( true ); // Force refresh
}

/**
 * Register custom cron schedule for 30-second intervals
 */
function advapes_cron_schedules( $schedules ) {
    if ( ! isset( $schedules['advapes_30sec'] ) ) {
        $schedules['advapes_30sec'] = array(
            'interval' => 30,
            'display'  => __( 'Every 30 Seconds (ADVapes Nav)', 'razzi-child' ),
        );
    }
    return $schedules;
}
add_filter( 'cron_schedules', 'advapes_cron_schedules' );

/**
 * Schedule the cron job if not already scheduled
 */
function advapes_schedule_cron() {
    if ( ! wp_next_scheduled( 'advapes_refresh_nav_cron' ) ) {
        wp_schedule_event( time(), 'advapes_30sec', 'advapes_refresh_nav_cron' );
    }
}
add_action( 'wp', 'advapes_schedule_cron' );

/**
 * Clear scheduled cron on theme deactivation
 */
function advapes_clear_scheduled_cron() {
    $timestamp = wp_next_scheduled( 'advapes_refresh_nav_cron' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'advapes_refresh_nav_cron' );
    }
}
register_deactivation_hook( __FILE__, 'advapes_clear_scheduled_cron' );



/**
 * Register REST API endpoints
 */
function advapes_register_rest_route() {
    // GET endpoint - view navigation structure
    register_rest_route( 'advapes/v1', '/nav', array(
        'methods'  => 'GET',
        'callback' => 'advapes_rest_get_nav',
        'permission_callback' => '__return_true',
    ) );
    
    // POST endpoint - refresh cache (admin only)
    register_rest_route( 'advapes/v1', '/nav/refresh', array(
        'methods'  => 'POST',
        'callback' => 'advapes_rest_refresh_nav',
        'permission_callback' => function() {
            return current_user_can( 'manage_options' );
        },
    ) );
}
add_action( 'rest_api_init', 'advapes_register_rest_route' );

/**
 * REST API callback - get navigation structure
 */
function advapes_rest_get_nav( $request ) {
    $force_refresh = $request->get_param( 'refresh' ) === 'true';
    $nav_structure = advapes_get_nav_structure( $force_refresh );
    
    return rest_ensure_response( array(
        'success' => true,
        'data'    => $nav_structure,
    ) );
}

/**
 * REST API callback - force cache refresh (admin only)
 */
function advapes_rest_refresh_nav( $request ) {
    advapes_invalidate_nav_cache();
    return rest_ensure_response( array(
        'success' => true,
        'message' => 'Navigation cache cleared successfully',
    ) );
}
