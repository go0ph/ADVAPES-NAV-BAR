<?php
/**
 * The header for our Razzi child theme
 *
 * Adds a custom ADVapes nav bar under the default Razzi header
 * without touching the logo or existing header layout.
 *
 * Version 3.1: Hybrid navigation (fixed parent menus + dynamic subcategories)
 *
 * @package Razzi Child
 */

// Load dynamic navigation system
require_once __DIR__ . '/advapes-nav.php';
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

            <!-- ADVapes custom nav bar start -->
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

                    <!-- Main nav list (desktop + mobile) - Hybrid v3.1 -->
                    <?php
                    // Use hybrid navigation if available, otherwise fallback to static
                    if ( function_exists( 'advapes_render_nav' ) ) {
                        advapes_render_nav();
                    } else {
                        // Fallback: static navigation
                        echo '<!-- ADVapes Nav: Fallback to static menu (dynamic nav not loaded) -->';
                        echo '<ul class="adv-nav-list">';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/" class="adv-nav-link">Home</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/on-sale/" class="adv-nav-link">Deals</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/brands/" class="adv-nav-link">Brands</a></li>';
                        echo '<li class="adv-nav-item"><a href="https://www.advapes.co.za/faq/" class="adv-nav-link">Support</a></li>';
                        echo '</ul>';
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