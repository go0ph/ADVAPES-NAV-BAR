<?php
/**
 * The header for our Razzi child theme
 *
 * Adds a custom ADVapes nav bar under the default Razzi header
 * without touching the logo or existing header layout.
 *
 * @package Razzi Child
 */
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

                    <!-- Main nav list (desktop + mobile) -->
                    <ul class="adv-nav-list">

                        <!-- Deals -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/on-sale/" class="adv-nav-link adv-nav-link--primary">
                                Deals
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Shop deals</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/dezemba-dealz/">
                                            <span>Dezemba Dealz</span>
                                            <span class="adv-tag">Seasonal promos</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/new-products/">
                                            <span>New Products</span>
                                            <span class="adv-tag">Latest arrivals</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/on-sale/">
                                            <span>On Sale</span>
                                            <span class="adv-tag">Discounted items</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/fire-sale/">
                                            <span>Fire Sale</span>
                                            <span class="adv-tag">Hot clearance</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/buy-bulk-save/">
                                            <span>Buy Bulk &amp; Save</span>
                                            <span class="adv-tag">Multi-pack deals</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/clearance/">
                                            <span>Clearance</span>
                                            <span class="adv-tag">End of line</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Disposables -->
                        <li class="adv-nav-item">
                            <a
                                href="https://www.advapes.co.za/product-category/disposables/one-use-disposable/"
                                class="adv-nav-link"
                            >
                                Disposables
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">One-use &amp; DTL disposable vapes</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/disposables/one-use-disposable/">
                                            <span>One-Use Disposables</span>
                                            <span class="adv-tag">All puff counts</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/disposables/dtl-disposables/">
                                            <span>DTL Disposables</span>
                                            <span class="adv-tag">Direct lung</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/disposables/">
                                            <span>All Disposables</span>
                                            <span class="adv-tag">Browse everything</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Pod Disposables -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/pod-disposables/" class="adv-nav-link">
                                Pod Disposables
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Pod-based disposables &amp; kits</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/">
                                            <span>All Pod Disposables</span>
                                            <span class="adv-tag">Kits &amp; pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/bewolk-xl-pod-disposables/">
                                            <span>Bewolk XL Pods</span>
                                            <span class="adv-tag">High puff pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/airscream-airspops-xl-pods/">
                                            <span>Airscream AirsPops XL</span>
                                            <span class="adv-tag">Airscream ecosystem</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Pod Systems & Kits -->
                        <li class="adv-nav-item">
                            <a
                                href="https://www.advapes.co.za/product-category/pod-systems-kits/"
                                class="adv-nav-link"
                            >
                                Pod Systems &amp; Kits
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Refillable pod kits</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-systems-kits/">
                                            <span>All Pod Systems &amp; Kits</span>
                                            <span class="adv-tag">Starter to advanced</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-systems-kits/refillable-pods/">
                                            <span>Refillable Pods</span>
                                            <span class="adv-tag">XROS &amp; more</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/vaporesso-xros-5-pod-kits/">
                                            <span>Vaporesso XROS 5 Kits</span>
                                            <span class="adv-tag">Popular pod kit</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/orca-vape-san-dynasty-device/">
                                            <span>Orca San Dynasty Kits</span>
                                            <span class="adv-tag">Stylish pod system</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- DL Hardware -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/dl-hardware/" class="adv-nav-link">
                                DL Hardware
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Direct lung devices &amp; spares</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-hardware/">
                                            <span>DL Hardware</span>
                                            <span class="adv-tag">Tanks &amp; coils</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-spares/">
                                            <span>DL Spares</span>
                                            <span class="adv-tag">Glass &amp; coils</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/vape-mods/">
                                            <span>Vape Mods</span>
                                            <span class="adv-tag">Mods only</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- DL Liquids -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/dl-liquid/" class="adv-nav-link">
                                DL Liquids
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Freebase &amp; longfill</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-liquid/">
                                            <span>DL Liquid</span>
                                            <span class="adv-tag">Main range</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-longfills/">
                                            <span>DL Longfills</span>
                                            <span class="adv-tag">Mix-your-own</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/longfill-additives/">
                                            <span>Longfill Additives</span>
                                            <span class="adv-tag">Boosters &amp; shots</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- MTL Devices & Liquid -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/mtl-devices/" class="adv-nav-link">
                                MTL Devices &amp; Liquid
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Tight-draw gear &amp; juice</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-devices/">
                                            <span>MTL Devices</span>
                                            <span class="adv-tag">Pods &amp; starter kits</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-liquid/">
                                            <span>MTL Liquid</span>
                                            <span class="adv-tag">Freebase MTL</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-spares/">
                                            <span>MTL Spares</span>
                                            <span class="adv-tag">Coils &amp; pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nic-salts/">
                                            <span>Nic Salts</span>
                                            <span class="adv-tag">Salt nic juice</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Brands -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/brands/" class="adv-nav-link">
                                Brands
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Shop by brand</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/brands/">
                                            <span>All Brands</span>
                                            <span class="adv-tag">A–Z listing</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/airscream/">
                                            <span>Airscream</span>
                                            <span class="adv-tag">Devices &amp; juice</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/nasty/">
                                            <span>Nasty</span>
                                            <span class="adv-tag">Disposables &amp; salts</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/elf-bar/">
                                            <span>Elf Bar</span>
                                            <span class="adv-tag">BC, EW &amp; more</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/blvk/">
                                            <span>BLVK</span>
                                            <span class="adv-tag">Bars &amp; liquids</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/bewolk-industries/">
                                            <span>Bewolk</span>
                                            <span class="adv-tag">XL &amp; Smart Shot</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/vapengin/">
                                            <span>Vapengin</span>
                                            <span class="adv-tag">Ceres &amp; more</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/vozol-tech/">
                                            <span>Vozol Tech</span>
                                            <span class="adv-tag">Gear &amp; Star</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/okk/">
                                            <span>OKK</span>
                                            <span class="adv-tag">Pod &amp; disposables</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Nic Alternatives -->
                        <li class="adv-nav-item">
                            <a
                                href="https://www.advapes.co.za/product-category/nicotine-alternatives/"
                                class="adv-nav-link"
                            >
                                Nic Alternatives
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Pouches &amp; gum</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/">
                                            <span>All Nicotine Alternatives</span>
                                            <span class="adv-tag">Non-vape</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/nicotine-pouches/">
                                            <span>Nicotine Pouches</span>
                                            <span class="adv-tag">Discreet</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/nicotine-gum/">
                                            <span>Nicotine Gum</span>
                                            <span class="adv-tag">Yalla Gum</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Support -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/faq/" class="adv-nav-link">
                                Support
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Support &amp; policies</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/faq/">
                                            <span>FAQ</span>
                                            <span class="adv-tag">Answers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/contact-us/">
                                            <span>Contact Us</span>
                                            <span class="adv-tag">Get in touch</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/about-us/">
                                            <span>About Us</span>
                                            <span class="adv-tag">Our story</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/track/">
                                            <span>Track my Order</span>
                                            <span class="adv-tag">Shipment tracking</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/shipping-rates-2/">
                                            <span>Shipping Rates</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/refunds-returns/">
                                            <span>Refunds &amp; Returns</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/terms-conditions/">
                                            <span>Terms &amp; Conditions</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/privacy-policy/">
                                            <span>Privacy Policy</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/refer-a-friend/">
                                            <span>Refer a Friend</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>
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