<?php
/**
 * ADVapes Dynamic Navigation System
 * Version 3.1 (Hybrid Approach)
 * 
 * Combines the best of v2 and v3:
 * - Fixed parent menu structure from v2 (for user familiarity)
 * - Dynamic subcategories from v3 (auto-updating from WooCommerce)
 * 
 * Parent menu order stays consistent, but content under each parent
 * updates automatically based on WooCommerce categories and products.
 * 
 * @package Razzi Child / ADVapes
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'ADVAPES_NAV_TRANSIENT_KEY', 'advapes_nav_structure' );
define( 'ADVAPES_NAV_TTL', 30 * MINUTE_IN_SECONDS ); // 30 minutes

/**
 * Enqueue ADVapes navigation CSS
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
            '3.1.1', 
            'all' 
        );
    }
}
add_action( 'wp_enqueue_scripts', 'advapes_enqueue_nav_styles' );

/**
 * Detect brand taxonomy from common candidates
 * 
 * Checks for brand taxonomies in order of likelihood:
 * - 'brand', 'brands', 'product_brand'
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
    $candidates = array( 'brand', 'brands', 'product_brand', 'pa_brand', 'pa_brands' );

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

    global $wpdb;
    
    // Use direct database query for better performance
    // Note: $wpdb->terms, $wpdb->posts etc. automatically include table prefix
    // Get all brand terms associated with products in this category
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
        AND tt2.term_id = %d
        AND p.post_type = 'product'
        AND p.post_status = 'publish'
        GROUP BY t.term_id, t.name
        ORDER BY product_count DESC
        LIMIT %d
    ";
    
    $brands = $wpdb->get_results(
        $wpdb->prepare( $query, $brand_taxonomy, $category_id, $limit )
    );

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

    // 1. Static Deals section (fully static - no WooCommerce dependency)
    $nav_structure['deals'] = array(
        'name' => 'Deals',
        'url' => 'https://www.advapes.co.za/on-sale/',
        'class' => 'adv-nav-link--primary',
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
                'tag' => 'Multi-pack',
            ),
        ),
    );

    // 2. Disposables (hybrid: specific subcategories + dynamic "View All")
    $disposables = advapes_find_category( 'disposables' );
    if ( $disposables ) {
        // Get key subcategories dynamically
        $children = advapes_get_category_children( $disposables->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $disposables->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All Disposables',
            'url'  => get_term_link( $disposables ),
            'tag'  => 'Browse all',
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
        // Get subcategories dynamically
        $children = advapes_get_category_children( $pod_disposables->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $pod_disposables->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All Pod Disposables',
            'url'  => get_term_link( $pod_disposables ),
            'tag'  => 'Browse all',
        );
        
        $nav_structure['pod-disposables'] = array(
            'name' => 'Pod Disposables',
            'url'  => get_term_link( $pod_disposables ),
            'dropdown_title' => 'Pod-based systems (' . $pod_disposables->count . '+)',
            'children' => $children,
        );
    }

    // 4. Pod Systems & Kits (hybrid: dynamic subcategories)
    $pod_systems = advapes_find_category( 'pod-systems-kits' );
    if ( $pod_systems ) {
        // Get subcategories dynamically
        $children = advapes_get_category_children( $pod_systems->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $pod_systems->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All Pod Systems &amp; Kits',
            'url'  => get_term_link( $pod_systems ),
            'tag'  => 'Browse all',
        );
        
        $nav_structure['pod-systems-kits'] = array(
            'name' => 'Pod Systems &amp; Kits',
            'url'  => get_term_link( $pod_systems ),
            'dropdown_title' => 'Refillable pod systems (' . $pod_systems->count . '+)',
            'children' => $children,
        );
    }

    // 5. Vape Hardware (hybrid: dynamic subcategories)
    // Try 'dl-hardware' first (v2 slug), then 'vape-hardware'
    $hardware = advapes_find_category( 'dl-hardware' );
    if ( ! $hardware ) {
        $hardware = advapes_find_category( 'vape-hardware' );
    }
    if ( $hardware ) {
        // Get subcategories dynamically
        $children = advapes_get_category_children( $hardware->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $hardware->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All Vape Hardware',
            'url'  => get_term_link( $hardware ),
            'tag'  => 'Browse all',
        );
        
        $nav_structure['vape-hardware'] = array(
            'name' => 'Vape Hardware',
            'url'  => get_term_link( $hardware ),
            'dropdown_title' => 'Mods, tanks &amp; spares (' . $hardware->count . '+)',
            'children' => $children,
        );
    }

    // 6. DL E-Liquids (hybrid: dynamic subcategories)
    $dl_liquids = advapes_find_category( 'dl-liquid' );
    if ( ! $dl_liquids ) {
        $dl_liquids = advapes_find_category( 'dl-liquids' );
    }
    if ( $dl_liquids ) {
        // Get subcategories dynamically
        $children = advapes_get_category_children( $dl_liquids->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $dl_liquids->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All DL E-Liquids',
            'url'  => get_term_link( $dl_liquids ),
            'tag'  => 'Browse all',
        );
        
        $nav_structure['dl-liquids'] = array(
            'name' => 'DL E-Liquids',
            'url'  => get_term_link( $dl_liquids ),
            'dropdown_title' => 'Direct lung liquids (' . $dl_liquids->count . '+)',
            'children' => $children,
        );
    }

    // 7. MTL & Nic Salts (hybrid: dynamic subcategories)
    $nic_salts = advapes_find_category( 'nic-salts' );
    if ( ! $nic_salts ) {
        $nic_salts = advapes_find_category( 'nic-salts-mtl-liquids' );
    }
    if ( $nic_salts ) {
        // Get subcategories dynamically
        $children = advapes_get_category_children( $nic_salts->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $nic_salts->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link at the end
        $children[] = array(
            'name' => 'All MTL &amp; Nic Salts',
            'url'  => get_term_link( $nic_salts ),
            'tag'  => 'Browse all',
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
        $children = advapes_get_category_children( $nic_alternatives->term_id, 10 );
        
        // Add top brands for this category
        $brands = advapes_get_category_brands( $nic_alternatives->term_id, 4 );
        if ( ! empty( $brands ) ) {
            foreach ( $brands as $brand ) {
                $children[] = $brand;
            }
        }
        
        // Add "View All" link
        $children[] = array(
            'name' => 'All Nicotine Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'tag'  => 'Browse all',
        );
        
        $nav_structure['nic-alternatives'] = array(
            'name' => 'Nic Alternatives',
            'url'  => get_term_link( $nic_alternatives ),
            'dropdown_title' => 'Non-vape nicotine (' . $nic_alternatives->count . '+)',
            'children' => $children,
        );
    }

    // 9. Brands (static parent, dynamic top brands)
    $brand_taxonomy = advapes_detect_brand_taxonomy();
    if ( $brand_taxonomy ) {
        $brands = get_terms( array(
            'taxonomy'   => $brand_taxonomy,
            'hide_empty' => true,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 10,
        ) );

        if ( ! is_wp_error( $brands ) && ! empty( $brands ) ) {
            // Get total brand count for dropdown title
            $total_brands = wp_count_terms( array(
                'taxonomy'   => $brand_taxonomy,
                'hide_empty' => true,
            ) );
            
            $brand_children = array();
            foreach ( $brands as $brand ) {
                $brand_children[] = array(
                    'name' => $brand->name,
                    'url'  => get_term_link( $brand ),
                    'count' => $brand->count,
                );
            }

            // Add "View All Brands" link
            $brand_children[] = array(
                'name' => 'View All Brands',
                'url'  => 'https://www.advapes.co.za/brands/',
                'tag'  => 'A–Z',
            );

            $nav_structure['brands'] = array(
                'name' => 'Brands',
                'url'  => 'https://www.advapes.co.za/brands/',
                'dropdown_title' => 'Shop by brand (' . $total_brands . '+)',
                'dropdown_class' => 'adv-dropdown--wide',
                'children' => $brand_children,
            );
        } else {
            // Show Brands menu even if no brands found yet
            $nav_structure['brands'] = array(
                'name' => 'Brands',
                'url'  => 'https://www.advapes.co.za/brands/',
                'dropdown_title' => 'Shop by brand',
                'dropdown_class' => 'adv-dropdown--wide',
                'children' => array(
                    array(
                        'name' => 'View All Brands',
                        'url'  => 'https://www.advapes.co.za/brands/',
                        'tag'  => 'Browse all',
                    ),
                ),
            );
        }
    } else {
        // No brand taxonomy detected - still show the menu but with just a link
        $nav_structure['brands'] = array(
            'name' => 'Brands',
            'url'  => 'https://www.advapes.co.za/brands/',
        );
    }

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
                echo '<li>' . "\n";
                echo '<a href="' . esc_url( $child['url'] ) . '">' . "\n";
                echo '<span>' . esc_html( $child['name'] ) . '</span>' . "\n";
                
                // Add tag or count
                if ( ! empty( $child['tag'] ) ) {
                    echo '<span class="adv-tag">' . esc_html( $child['tag'] ) . '</span>' . "\n";
                } elseif ( ! empty( $child['count'] ) ) {
                    echo '<span class="adv-tag">' . absint( $child['count'] ) . '+ products</span>' . "\n";
                }
                
                echo '</a>' . "\n";
                echo '</li>' . "\n";
            }
            
            echo '</ul>' . "\n";
            echo '</div>' . "\n";
        }

        echo '</li>' . "\n";
    }

    echo '</ul>' . "\n";
    
    // Mobile swipe indicator (shown only on mobile)
    echo '<div class="adv-mobile-swipe-hint">' . "\n";
    echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>' . "\n";
    echo '<span>Swipe to browse</span>' . "\n";
    echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>' . "\n";
    echo '</div>' . "\n";
}

/**
 * Invalidate navigation cache when products or terms change
 */
function advapes_invalidate_nav_cache() {
    delete_transient( ADVAPES_NAV_TRANSIENT_KEY );
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
 * Register custom cron schedule for 30-minute intervals
 */
function advapes_cron_schedules( $schedules ) {
    if ( ! isset( $schedules['advapes_30min'] ) ) {
        $schedules['advapes_30min'] = array(
            'interval' => 30 * MINUTE_IN_SECONDS,
            'display'  => __( 'Every 30 Minutes (ADVapes Nav)', 'razzi-child' ),
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
        wp_schedule_event( time(), 'advapes_30min', 'advapes_refresh_nav_cron' );
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
 * Register REST API endpoint for cache refresh
 */
function advapes_register_rest_route() {
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
 * REST API callback to force navigation cache refresh
 */
function advapes_rest_refresh_nav( $request ) {
    advapes_invalidate_nav_cache();
    return rest_ensure_response( array(
        'success' => true,
        'message' => 'Navigation cache cleared successfully',
    ) );
}
