<?php
/**
 * The header for our Razzi child theme
 *
 * Adds a custom ADVapes nav bar under the default Razzi header
 * without touching the logo or existing header layout.
 *
 * Version 5.0 (V3): Structural refactor - Brand-first navigation
 *
 * @package Razzi Child
 */

// Load V3 dynamic navigation system
require_once get_stylesheet_directory() . '/advapes-nav.php';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'razzi_before_site' ); ?>

<div id="page" class="site">
    <?php do_action( 'razzi_before_open_site_header' ); ?>

    <?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>

        <header id="site-header" class="<?php \Razzi\Header::classes('site-header'); ?>">

            <?php
            // Original Razzi header (logo, icons, search, etc)
            do_action( 'razzi_after_open_site_header' );
            do_action( 'razzi_before_close_site_header' );
            ?>

            <!-- ADVapes custom nav bar start (V3 - Brand-first) -->
            <nav class="adv-main-nav">
                <div class="adv-nav-inner">

                    <!-- Mobile toggle -->
                    <input type="checkbox" id="adv-nav-toggle" class="adv-nav-toggle" />
                    <label for="adv-nav-toggle" class="adv-nav-toggle-btn">
                        <div class="adv-burger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="adv-nav-toggle-label">Menu</span>
                    </label>

                    <!-- V3: Desktop nav list (hidden on mobile ≤1024px) -->
                    <?php
                    // V3: Use brand-first navigation structure
                    if ( function_exists( 'advapes_v3_render_nav' ) ) {
                        advapes_v3_render_nav(); // Desktop navigation
                    } else {
                        // Fallback: static navigation
                        echo '<!-- ADVapes Nav V3: Fallback to static menu (dynamic nav not loaded) -->';
                        echo '<ul class="adv-nav-list">';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/" class="adv-nav-link">Home</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/on-sale/" class="adv-nav-link">Deals</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/brands/" class="adv-nav-link">Brands</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/faq/" class="adv-nav-link">Support</a></li>';
                        echo '</ul>';
                    }
                    ?>

                    <!-- V3: Mobile drawer (flyout from right) -->
                    <?php
                    if ( function_exists( 'advapes_v3_render_mobile_drawer' ) ) {
                        advapes_v3_render_mobile_drawer();
                    }
                    ?>

                </div>
            </nav>
            <!-- ADVapes custom nav bar end -->

        </header>

    <?php } ?>

    <?php do_action( 'razzi_after_close_site_header' ); ?>

    <?php
    \Razzi\Markup::instance()->open( 'site_content', [
        'tag'     => 'div',
        'attr'    => [
            'id'    => 'content',
            'class' => 'site-content'
        ],
        'actions' => true,
    ] );
    ?>
