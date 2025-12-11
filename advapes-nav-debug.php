<?php
/**
 * ADVapes Navigation Debug Page
 * 
 * Upload this file to your theme directory alongside header.php and advapes-nav.php
 * Then visit: https://www.advapes.co.za/wp-content/themes/razzi-child/advapes-nav-debug.php
 * 
 * This will show you exactly what's happening with your navigation.
 */

// Load WordPress
require_once( '../../../wp-load.php' );

// Must be admin to view
if ( ! current_user_can( 'manage_options' ) ) {
    die( 'Access denied. You must be logged in as an administrator.' );
}

// Load the navigation system
require_once get_stylesheet_directory() . '/advapes-nav.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>ADVapes Navigation Debug</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 { color: #e11d2f; }
        h2 { 
            color: #333; 
            border-bottom: 2px solid #e11d2f;
            padding-bottom: 10px;
        }
        .status { 
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .status.success { background: #d4edda; color: #155724; }
        .status.warning { background: #fff3cd; color: #856404; }
        .status.error { background: #f8d7da; color: #721c24; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th { background: #f8f9fa; font-weight: bold; }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
        }
        .menu-item {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-left: 3px solid #e11d2f;
        }
        .children {
            margin-left: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>🔍 ADVapes Navigation Debug Report</h1>
    
    <div class="card">
        <h2>System Status</h2>
        <table>
            <tr>
                <th>Check</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
            <?php
            $wc_active = class_exists( 'WooCommerce' );
            $brand_tax = advapes_detect_brand_taxonomy();
            $css_file = get_stylesheet_directory() . '/advapes-nav.css';
            $css_exists = file_exists( $css_file );
            $nav_structure = advapes_get_nav_structure();
            ?>
            <tr>
                <td><strong>WordPress</strong></td>
                <td><span class="status success">✅ Active</span></td>
                <td>Version <?php echo get_bloginfo( 'version' ); ?></td>
            </tr>
            <tr>
                <td><strong>WooCommerce</strong></td>
                <td>
                    <?php if ( $wc_active ): ?>
                        <span class="status success">✅ Active</span>
                    <?php else: ?>
                        <span class="status error">❌ Not Active</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                    if ( $wc_active ) {
                        echo 'Version ' . WC()->version;
                    } else {
                        echo '<strong>REQUIRED:</strong> Install WooCommerce plugin for dynamic navigation';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Brand Taxonomy</strong></td>
                <td>
                    <?php if ( $brand_tax ): ?>
                        <span class="status success">✅ Detected</span>
                    <?php else: ?>
                        <span class="status warning">⚠️ Not Found</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                    if ( $brand_tax ) {
                        echo "Using taxonomy: <code>$brand_tax</code>";
                        if ( $wc_active ) {
                            $brand_count = wp_count_terms( array(
                                'taxonomy' => $brand_tax,
                                'hide_empty' => true,
                            ) );
                            echo " ($brand_count brands found)";
                        }
                    } else {
                        echo 'Checked: brand, brands, product_brand, pa_brand, pa_brands';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>CSS File</strong></td>
                <td>
                    <?php if ( $css_exists ): ?>
                        <span class="status success">✅ Found</span>
                    <?php else: ?>
                        <span class="status warning">⚠️ Not Found</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                    if ( $css_exists ) {
                        echo "Located at: <code>" . basename( $css_file ) . "</code>";
                    } else {
                        echo "Expected at: <code>$css_file</code><br>";
                        echo "<em>You can add CSS via Theme Customizer instead</em>";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Navigation Cache</strong></td>
                <td>
                    <?php 
                    $cached = get_transient( ADVAPES_NAV_TRANSIENT_KEY );
                    if ( $cached !== false ): ?>
                        <span class="status success">✅ Cached</span>
                    <?php else: ?>
                        <span class="status warning">⚠️ Empty</span>
                    <?php endif; ?>
                </td>
                <td>
                    TTL: 30 minutes | 
                    <a href="?clear_cache=1">Clear Cache</a>
                    <?php
                    if ( isset( $_GET['clear_cache'] ) ) {
                        delete_transient( ADVAPES_NAV_TRANSIENT_KEY );
                        echo ' <span style="color: green;">✓ Cache cleared!</span>';
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="card">
        <h2>Navigation Structure (<?php echo count( $nav_structure ); ?> menu items)</h2>
        
        <?php if ( empty( $nav_structure ) ): ?>
            <div class="status error">
                ❌ No navigation structure found. Check WooCommerce is active and categories exist.
            </div>
        <?php else: ?>
            <?php foreach ( $nav_structure as $key => $item ): ?>
                <div class="menu-item">
                    <strong><?php echo esc_html( $item['name'] ); ?></strong>
                    <?php if ( ! empty( $item['children'] ) ): ?>
                        <div class="children">
                            <?php echo count( $item['children'] ); ?> dropdown items:
                            <ul>
                                <?php foreach ( $item['children'] as $child ): ?>
                                    <li>
                                        <?php echo esc_html( $child['name'] ); ?>
                                        <?php if ( isset( $child['count'] ) ): ?>
                                            <em>(<?php echo $child['count']; ?>+ products)</em>
                                        <?php elseif ( isset( $child['tag'] ) ): ?>
                                            <em>[<?php echo $child['tag']; ?>]</em>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="children"><em>No dropdown</em></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h2>Raw Navigation Data</h2>
        <pre><?php echo htmlspecialchars( print_r( $nav_structure, true ) ); ?></pre>
    </div>
    
    <div class="card">
        <h2>Product Categories</h2>
        <?php if ( $wc_active ): ?>
            <?php
            $categories = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'parent' => 0,
            ) );
            ?>
            <p>Found <?php echo count( $categories ); ?> top-level product categories:</p>
            <ul>
                <?php foreach ( $categories as $cat ): ?>
                    <li>
                        <strong><?php echo $cat->name; ?></strong> 
                        (slug: <code><?php echo $cat->slug; ?></code>, 
                        <?php echo $cat->count; ?> products)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="status error">WooCommerce not active - cannot check categories</p>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h2>Quick Actions</h2>
        <ul>
            <li><a href="?clear_cache=1">Clear Navigation Cache</a></li>
            <li><a href="<?php echo home_url( '/wp-json/advapes/v1/nav' ); ?>">View REST API Response</a></li>
            <li><a href="<?php echo home_url( '/wp-json/advapes/v1/nav?refresh=true' ); ?>">Force Refresh Cache</a></li>
            <li><a href="<?php echo admin_url(); ?>">Back to Admin Dashboard</a></li>
        </ul>
    </div>
    
    <div class="card">
        <h2>Recommendations</h2>
        <ul>
            <?php if ( ! $wc_active ): ?>
                <li>⚠️ <strong>Install WooCommerce:</strong> The navigation requires WooCommerce to be active</li>
            <?php endif; ?>
            
            <?php if ( empty( $nav_structure ) && $wc_active ): ?>
                <li>⚠️ <strong>Check Categories:</strong> Make sure your product categories match the expected slugs (disposables, pod-systems-kits, etc.)</li>
            <?php endif; ?>
            
            <?php if ( ! $brand_tax && $wc_active ): ?>
                <li>⚠️ <strong>Add Brand Taxonomy:</strong> Create a brand taxonomy or product attribute for brand filtering</li>
            <?php endif; ?>
            
            <?php if ( ! $css_exists ): ?>
                <li>ℹ️ <strong>CSS Location:</strong> Either upload <code>advapes-nav.css</code> to your theme directory, or add the CSS via Appearance > Customize > Additional CSS</li>
            <?php endif; ?>
            
            <?php if ( ! empty( $nav_structure ) && $wc_active && $css_exists ): ?>
                <li>✅ <strong>Everything looks good!</strong> Your navigation should be working properly.</li>
            <?php endif; ?>
        </ul>
    </div>
    
</body>
</html>
