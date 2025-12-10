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
                                <div class="adv-dropdown-title">Shop deals &amp; promotions</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/dezemba-dealz/">
                                            <span>Dezemba Dealz</span>
                                            <span class="adv-tag">Seasonal</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/new-products/">
                                            <span>New Products</span>
                                            <span class="adv-tag">Latest</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/on-sale/">
                                            <span>On Sale</span>
                                            <span class="adv-tag">Discounted</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/fire-sale/">
                                            <span>Fire Sale</span>
                                            <span class="adv-tag">Hot deals</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/buy-bulk-save/">
                                            <span>Buy Bulk &amp; Save</span>
                                            <span class="adv-tag">Multi-pack</span>
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
                            <a href="https://www.advapes.co.za/product-category/disposables/" class="adv-nav-link">
                                Disposables
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">One-use disposable vapes (1,400+)</div>
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
                                        <a href="https://www.advapes.co.za/product-category/disposables/puff-count/">
                                            <span>By Puff Count</span>
                                            <span class="adv-tag">20k-40k+</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/disposables/bewolk-25k-disposables/">
                                            <span>Bewolk 25k</span>
                                            <span class="adv-tag">High capacity</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/disposables/">
                                            <span>All Disposables</span>
                                            <span class="adv-tag">Browse all</span>
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
                                <div class="adv-dropdown-title">Pod-based systems (690+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/bewolk-pod-disposables/">
                                            <span>Bewolk Pods</span>
                                            <span class="adv-tag">XL &amp; Bar</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/wotofo-nexpod-pod-disposables/">
                                            <span>Wotofo NEXpod</span>
                                            <span class="adv-tag">5k-7k pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/upends-switch-replacement-pods-batteries/">
                                            <span>Upends Switch</span>
                                            <span class="adv-tag">Switch system</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/airscream-airspops-xl-pods/">
                                            <span>Airscream AirsPops XL</span>
                                            <span class="adv-tag">Premium pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/tugboat-super-16000-pods-batteries/">
                                            <span>Tugboat Pods</span>
                                            <span class="adv-tag">16k capacity</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-disposables/">
                                            <span>All Pod Disposables</span>
                                            <span class="adv-tag">Browse all</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Pod Systems & Kits -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/pod-systems-kits/" class="adv-nav-link">
                                Pod Systems &amp; Kits
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Refillable pod systems (340+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-systems-kits/">
                                            <span>All Pod Kits</span>
                                            <span class="adv-tag">Complete kits</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/pod-systems-kits/refillable-pods/">
                                            <span>Refillable Pods</span>
                                            <span class="adv-tag">Replacement pods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/vaporesso-xros-5-pod-kits/">
                                            <span>Vaporesso XROS 5</span>
                                            <span class="adv-tag">Popular kit</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/orca-vape-san-dynasty-device/">
                                            <span>Orca San Dynasty</span>
                                            <span class="adv-tag">Premium pod</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-devices/">
                                            <span>MTL Pod Devices</span>
                                            <span class="adv-tag">Mouth-to-lung</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Vape Hardware -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/dl-hardware/" class="adv-nav-link">
                                Vape Hardware
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Mods, tanks &amp; spares (420+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/vape-mods/">
                                            <span>Vape Mods</span>
                                            <span class="adv-tag">Box mods</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-hardware/">
                                            <span>DL Tanks &amp; Atomizers</span>
                                            <span class="adv-tag">Direct lung</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/coils/">
                                            <span>Coils &amp; Replacement Parts</span>
                                            <span class="adv-tag">137+ options</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-spares/">
                                            <span>DL Spares</span>
                                            <span class="adv-tag">Glass &amp; parts</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-spares/">
                                            <span>MTL Spares</span>
                                            <span class="adv-tag">Coils &amp; pods</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- DL Liquids -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/dl-liquid/" class="adv-nav-link">
                                DL E-Liquids
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Direct lung liquids (930+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-liquids/120ml-longfill-flavour-shots/">
                                            <span>120ml Longfill Shots</span>
                                            <span class="adv-tag">790+ flavours</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-liquid/">
                                            <span>Pre-mixed DL Liquids</span>
                                            <span class="adv-tag">Ready to vape</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/dl-longfills/">
                                            <span>DL Longfills</span>
                                            <span class="adv-tag">Mix your own</span>
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

                        <!-- MTL & Nic Salts -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/nic-salts/" class="adv-nav-link">
                                MTL &amp; Nic Salts
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Nic salts &amp; MTL liquids (1,730+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nic-salts/">
                                            <span>Nic Salts Pre-mixed</span>
                                            <span class="adv-tag">Ready to use</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nic-salts-mtl-liquids/30ml-longfill-flavour-shots/">
                                            <span>30ml Longfill Shots</span>
                                            <span class="adv-tag">840+ flavours</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nic-salts-mtl-liquids/60ml-longfill-flavour-shots/">
                                            <span>60ml Longfill Shots</span>
                                            <span class="adv-tag">300+ flavours</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/mtl-liquid/">
                                            <span>MTL E-Liquids</span>
                                            <span class="adv-tag">Freebase MTL</span>
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
                            <div class="adv-dropdown adv-dropdown--wide">
                                <div class="adv-dropdown-title">Shop by brand (150+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/nasty/">
                                            <span>Nasty</span>
                                            <span class="adv-tag">470+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/bewolk-industries/">
                                            <span>Bewolk Industries</span>
                                            <span class="adv-tag">390+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/cosmic-dropz/">
                                            <span>Cosmic Dropz</span>
                                            <span class="adv-tag">330+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/oxva/">
                                            <span>OXVA</span>
                                            <span class="adv-tag">250+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/the-hidden-cloud/">
                                            <span>The Hidden Cloud</span>
                                            <span class="adv-tag">250+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/elf-bar/">
                                            <span>Elf Bar</span>
                                            <span class="adv-tag">200+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/airscream/">
                                            <span>Airscream</span>
                                            <span class="adv-tag">190+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/vuse/">
                                            <span>Vuse</span>
                                            <span class="adv-tag">180+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/vaporesso/">
                                            <span>Vaporesso</span>
                                            <span class="adv-tag">170+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/upends/">
                                            <span>Upends</span>
                                            <span class="adv-tag">150+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/pod-salt/">
                                            <span>Pod Salt</span>
                                            <span class="adv-tag">140+ products</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/blvk/">
                                            <span>BLVK</span>
                                            <span class="adv-tag">Bars &amp; liquids</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/wotofo/">
                                            <span>Wotofo</span>
                                            <span class="adv-tag">NEXpod &amp; more</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brand/vozol-tech/">
                                            <span>Vozol Tech</span>
                                            <span class="adv-tag">Gear &amp; Star</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/brands/">
                                            <span>View All Brands</span>
                                            <span class="adv-tag">A–Z</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <!-- Nic Alternatives -->
                        <li class="adv-nav-item">
                            <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/" class="adv-nav-link">
                                Nic Alternatives
                            </a>
                            <div class="adv-dropdown">
                                <div class="adv-dropdown-title">Non-vape nicotine (78+)</div>
                                <ul>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/">
                                            <span>All Nicotine Alternatives</span>
                                            <span class="adv-tag">Browse all</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/product-category/nicotine-alternatives/nicotine-pouches/">
                                            <span>Nicotine Pouches</span>
                                            <span class="adv-tag">Discreet option</span>
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
                                <div class="adv-dropdown-title">Help &amp; information</div>
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
                                        <a href="https://www.advapes.co.za/track/">
                                            <span>Track my Order</span>
                                            <span class="adv-tag">Shipment tracking</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.advapes.co.za/about-us/">
                                            <span>About Us</span>
                                            <span class="adv-tag">Our story</span>
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