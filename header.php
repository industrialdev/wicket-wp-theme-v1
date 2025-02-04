<?php
$lang = 'en';
$locale = '&locale=en';
if( defined( 'ICL_LANGUAGE_CODE' ) ) {
    $locale = ICL_LANGUAGE_CODE == 'fr' ? '&locale=fr' : '&locale=en';
    $lang = ICL_LANGUAGE_CODE;
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <title>
        <?php if ( is_front_page() ): ?>
            <?php wp_title( '' ); ?>
        <?php elseif ( is_404() ) : ?>
        404 - <?php echo get_bloginfo( 'name' ); ?>
        <?php else: ?>
            <?php wp_title( '' ); ?>
        <?php endif; ?>
    </title>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="profile" href="http://gmpg.org/xfn/11">

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon.ico">

    <!-- Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo get_template_directory_uri(); ?>/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-16x16.png"
        sizes="16x16">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-32x32.png"
        sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/icons/mstile-150x150.png"
        sizes="150x150">
    <link rel="icon" type="image/png"
        href="<?php echo get_template_directory_uri(); ?>/assets/icons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png"
        href="<?php echo get_template_directory_uri(); ?>/assets/icons/android-chrome-512x512.png" sizes="512x512">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/icons/site.webmanifest">

    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <?php
        wp_head();

        echo get_field('tracking_codes_in_head', 'options');
    ?>
</head>

<body <?php body_class(); ?>>
    <?php echo get_field('tracking_codes_right_after_body', 'options'); ?>
    <a class="transition left-0 bg-primary text-primary-content absolute p-3 m-3 -translate-y-32 z-10 bg-white focus:translate-y-0" href="#main-content">
        <?php echo __( 'Skip to main content', 'wicket' ); ?>
    </a>

    <?php
        // Set PHP variables needed for secondary nav
        $logo = get_field( 'site_logo', 'options' );
        $logo_url = $logo['url'] ?? get_template_directory_uri() . "/assets/images/logo-" . $lang . ".svg" ?? '';
        $bam_path = $lang == 'fr' ? '/fr/create-account' : '/create-account';

        $acc_index_url = get_post_type_archive_link('my-account');
        if ($acc_index_url) {
            $account_center_landing = $acc_index_url;
            $acc_index_url          = $acc_index_url . $locale;
        } else {
            $account_center_slug_locale = get_field('ac_localization', 'option');
            if (empty($account_center_slug_locale['value'])) {
                $account_center_slug_locale['value'] = 'account-center';
            }
            $account_center_landing = $lang == 'fr' ? '/fr/mon-compte' : '/' . $account_center_slug_locale['value'];

            $acc_index_url = $account_center_landing . $locale;
        }
        $referrer = isset($_GET['referrer']) ? wicket_get_site_root_url() . $_GET['referrer'] . $locale : $acc_index_url;

        $cart_path = $lang == 'fr' ? '/fr/cart' : '/cart';
        $nav_state = 'logged_out'; // Will be one of logged_out, logged_in_user, logged_in_member
        if( is_user_logged_in() ) {
            $nav_state = 'logged_in_user';

            $user = wp_get_current_user();
            if (in_array('member', (array) $user->roles)) {
                $nav_state = 'logged_in_member';
            }

            if (in_array('administrator', (array) $user->roles)) {
                $nav_state = 'logged_in_member';
            }
        }

        // Grab Menus
        $theme_locations = get_nav_menu_locations();
        if( isset( $theme_locations['header-utility'] ) ) {
            $utility_nav_items = wp_get_nav_menu_items( $theme_locations['header-utility'] );
        } else {
            $utility_nav_items = [];
        }
        if( isset( $theme_locations['header-secondary'] ) ) {
            $secondary_nav_items = wp_get_nav_menu_items( $theme_locations['header-secondary'] );
        } else {
            $secondary_nav_items = [];
        }
        if( isset( $theme_locations['header'] ) ) {
            $primary_nav_items = wp_get_nav_menu_items( $theme_locations['header'] );
        } else {
            $primary_nav_items = [];
        }
        // Create better primary nav array
        $primary_nav_items_structured = wicket_generate_structured_menu( $primary_nav_items );

        // Data formatted for Alpine
        $show_search        = get_field( 'secondary_nav_search_enabled', 'options' ) ? 'true' : 'false';
        $show_lang_toggle   = get_field( 'secondary_nav_language_toggle_enabled', 'options' ) ? 'true' : 'false';
        $show_cart          = get_field( 'secondary_nav_cart_enabled', 'options' ) ? 'true' : 'false';
        $show_utility       = !empty( $utility_nav_items ) ? 'true' : 'false';
        $sub_menu_dropdowns = get_field( 'mobile_menu_sub_menu_dropdowns', 'options' ) ? 'true' : 'false';
    ?>
    <header
        x-data="{
            searchOpen:     false,
            showSearch:     <?php echo $show_search; ?>,
            showLangToggle: <?php echo $show_lang_toggle; ?>,
            showCart:     <?php echo $show_cart; ?>,
            showUtilityNav: <?php echo $show_utility; ?>,
            mobileMenuOpen: false,
            mobileMenuMegaSubDropdowns: <?php echo $sub_menu_dropdowns; ?>,
        }"
        class="w-full flex flex-col bg-white text-primary-100"
    >
        <!-- Utility Nav -->
        <div
            x-show="showUtilityNav"
            x-cloak
            class="utility-nav hidden lg:block w-full bg-dark-100 text-white font-bold"
            >
            <div class="container flex justify-end">
                <?php
                    $utility_loop_index = 0;
                    foreach( $utility_nav_items as $utility_nav_item ) {
                        $is_last_element = $utility_loop_index == ( count( $utility_nav_items ) - 1 );
                        $margin_r = '';
                        if( $is_last_element ) {
                            $margin_r = '-mr-2';
                        }

                        $target = $utility_nav_item->target ?? '';
                        get_component( 'link', [
                            'text'     => $utility_nav_item->title,
                            'classes'  => ['ml-4', $margin_r, 'items-center', 'hidden', 'lg:inline-flex', 'px-2', 'py-1', 'hover:bg-light-040'],
                            'target'   => $target,
                            'url'    => $utility_nav_item->url,
                        ] );
                        $utility_loop_index++;
                    }
                ?>
            </div>
        </div> <!-- End Utility Nav -->

        <!-- Secondary Nav (Logo, login button, language switcher, etc.) -->
        <div x-ref="secondary-nav" class="secondary-nav w-full px-2 xl:px-0 py-3">
            <div class="container flex items-center justify-between relative ">
                <!-- Left-aligned hamburger menu button that only shows on Base/SM breakpoints -->
                <button
                    x-show="! mobileMenuOpen"
                    x-cloak
                    x-on:click="mobileMenuOpen = ! mobileMenuOpen; if(mobileMenuOpen){$dispatch('close-mobile-search')};"
                    x-on:close-mobile-menu.window="mobileMenuOpen = false"
                    class="left-hamburger-button inline-flex md:hidden mr-20 items-center"
                    >
                    <?php
                    get_component( 'icon', [
                        'classes' => [ 'text-x-large', 'lg:text-large' ],
                        'icon'    => 'fa-solid fa-bars',
                        'text'    => '',
                    ] );
                    ?>
                </button>
                <!-- Left-aligned hamburger menu CLOSE button that only shows on Base/SM breakpoints -->
                <button
                    x-show="mobileMenuOpen"
                    x-cloak
                    x-on:click="mobileMenuOpen = ! mobileMenuOpen"
                    class="right-hamburger-close-button inline-flex md:hidden mr-16 items-center"
                    >
                    <?php
                    get_component( 'icon', [
                        'classes' => [ 'text-x-large', 'lg:text-large', 'bg-white', 'rounded-base', 'px-2', 'py-0' ],
                        'icon'    => 'fa-solid fa-x',
                        'text'    => '',
                    ] );
                    ?>
                </button>

                <!-- Logo -->
                <a href="/" class="logo w-60"><img src="<?php echo $logo_url; ?>" /></a>

                <!-- Right panel -->
                <div
                    class="right-panel flex justify-between align-center font-bold"
                    x-bind:class=" searchOpen ? 'lg:flex-grow' : '' "
                    >
                    <?php
                        foreach( $secondary_nav_items as $secondary_nav_item ) {
                            $target = $secondary_nav_item->target ?? '';
                            get_component( 'link', [
                                'text'     => $secondary_nav_item->title,
                                'classes'  => ['secondary-nav-item', 'mr-4', 'items-center', 'hidden', 'lg:inline-flex'],
                                'target'   => $target,
                                'url'      => $secondary_nav_item->url,
                                'atts'     => ['x-show="! searchOpen"', 'x-cloak'],
                            ] );
                        }
                    ?>
                    <?php
                    if( $nav_state == 'logged_out' ) {
                        get_component( 'button', [
                            'variant'   => 'primary',
                            'a_tag'     => true,
                            'link'      => $bam_path,
                            'classes'   => ['create-account-button', 'hidden', 'md:inline-flex'],
                            'label'     => __( 'Create Account', 'wicket' ),
                            'atts'      => ['x-show="! searchOpen"', 'x-cloak'],
                        ] );
                    } else if ( $nav_state == 'logged_in_user' ) {
                        get_component( 'button', [
                            'variant'     => 'primary',
                            'a_tag'     => true,
                            'link'      => $bam_path,
                            'classes'     => ['become-a-member-button', 'mr-4'],
                            'label'     => __( 'Become a member', 'wicket' ),
                            'atts'      => [' x-bind:class=" searchOpen ? \'hidden md:inline-flex lg:hidden\' : \'hidden md:inline-flex\' " '],
                        ] );
                        get_component( 'button', [
                            'variant'     => 'secondary',
                            'a_tag'     => true,
                            'link'      => $account_center_landing,
                            'classes'     => ['my-account-button', 'hidden', 'lg:inline-flex'],
                            'label'     => __( 'My Account', 'wicket' ),
                            'atts'      => ['x-show="! searchOpen"', 'x-cloak'],
                        ] );
                    } else if ( $nav_state == 'logged_in_member' ) {
                        get_component( 'button', [
                            'variant'     => 'secondary',
                            'a_tag'     => true,
                            'link'      => $account_center_landing,
                            'classes'     => ['member-portal-button', 'hidden', 'md:inline-flex'],
                            'label'     => __( 'Member Portal', 'wicket' ),
                            'atts'      => ['x-show="! searchOpen"', 'x-cloak'],
                        ] );
                    }
                    ?>
                    <?php if( $nav_state == 'logged_out' ): ?>
                        <?php
                            get_component( 'link', [
                                'text'     => __( 'Login', 'wicket' ),
                                'classes'  => ['login-button', 'mx-4', 'items-center', 'hidden', 'lg:inline-flex'],
                                'url'      => get_option('wp_cassify_base_url').'login?service='.$referrer,
                                'atts'     => ['x-show="! searchOpen"', 'x-cloak'],
                            ] );
                        ?>
                    <?php else: ?>
                        <?php
                            get_component( 'link', [
                                'text'     => __( 'Logout', 'wicket' ),
                                'classes'  => ['logout-button', 'mx-4', 'items-center', 'hidden', 'lg:inline-flex'],
                                'url'      => wp_logout_url(),
                                'atts'     => ['x-show="! searchOpen"', 'x-cloak'],
                            ] );
                        ?>
                    <?php endif; ?>
                    <!-- Start search field -->
                    <div x-show="searchOpen" x-cloak class="hidden lg:block flex-grow px-2 py-3 ml-20 bg-white">
                        <form class="flex" action="/" method="get">
                            <label for="search" class="hidden"><?php _e('Search the website', 'wicket'); ?></label>
                            <input class="w-full p-1" type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="<?php _e('Search by Keyword', 'wicket'); ?>" />
                            <?php get_component( 'button', [
                                'variant'     => 'primary',
                                'type'      => 'submit',
                                'label'     => __( 'Search', 'wicket' ),
                                'classes'     => ['search-submit-button', 'ml-2', 'border-0']
                            ] ) ?>
                        </form>
                    </div>
                    <!-- End search field -->
                    <!-- Start search button states -->
                    <button
                        x-on:click="searchOpen = ! searchOpen; if(searchOpen){$dispatch('close-mobile-menu')};"
                        x-on:close-mobile-search.window="searchOpen = false"
                        x-show="showSearch"
                        x-cloak
                        class="search-toggle-button mx-4 inline-flex items-center"
                    >
                        <span x-show="! searchOpen" x-cloak>
                        <?php
                        get_component( 'icon', [
                            'classes' => [ 'text-x-large', 'lg:text-large' ],
                            'icon'    => 'fa-solid fa-magnifying-glass',
                            'text'    => '',
                        ] );
                        ?>
                        </span>
                        <span x-show="searchOpen" x-cloak>
                        <?php
                        get_component( 'icon', [
                            'classes' => [ 'text-x-large', 'lg:text-large', 'bg-white', 'rounded-base', 'px-2', 'py-0' ],
                            'icon'    => 'fa-solid fa-x',
                            'text'    => '',
                        ] );
                        ?>
                        </span>
                    </button>
                    <!-- End search button states -->

                    <a x-show="showCart" x-cloak href="<?php echo $cart_path; ?>" class="cart-button ml-4 md:mx-4 inline-flex items-center">
                        <?php
                        get_component( 'icon', [
                            'classes' => [ 'text-x-large', 'lg:text-large' ],
                            'icon'    => 'fa-regular fa-cart-shopping',
                            'text'    => '',
                        ] );
                        ?>
                    </a>

                    <?php do_action( 'wpml_language_switcher' ); ?>

                    <!-- Right-aligned hamburger menu button that only shows on MD breakpoint -->
                    <button
                        x-on:click="mobileMenuOpen = ! mobileMenuOpen; if(mobileMenuOpen){$dispatch('close-mobile-search')};"
                        x-on:close-mobile-menu.window="mobileMenuOpen = false"
                        class="right-hamburger-button hidden md:inline-flex lg:hidden ml-4 items-center"
                    >
                        <span x-show="! mobileMenuOpen" x-cloak>
                        <?php
                        get_component( 'icon', [
                            'classes' => [ 'text-x-large', 'lg:text-large', 'px-2' ],
                            'icon'    => 'fa-solid fa-bars',
                            'text'    => '',
                        ] );
                        ?>
                        </span>
                        <span x-show="mobileMenuOpen" x-cloak>
                        <?php
                        get_component( 'icon', [
                            'classes' => [ 'text-x-large', 'lg:text-large', 'bg-dark-040', 'rounded-base', 'px-2', 'py-0' ],
                            'icon'    => 'fa-solid fa-x',
                            'text'    => '',
                        ] );
                        ?>
                        </span>
                    </button> <!-- End hamburger button -->
                </div>
            </div>
        </div> <!-- End secondary nav -->

        <!-- Main Nav -->
        <nav x-ref="main-nav" class="main-nav w-full hidden lg:flex border-t-base border-b-base border-dark-040">
            <ul class="container w-full gap-4 flex relative <?php if( count($primary_nav_items_structured) > 4 ) { echo 'justify-between'; } else { echo 'justify-evenly'; } ?>">
            <?php
            foreach( $primary_nav_items_structured as $primary_nav_item ):
                $is_primary_nav_item_last_item = ($primary_nav_item == end($primary_nav_items_structured)) ? true : false;
                // If this is a single menu item link
                if( $primary_nav_item['child_count'] == 0 ):
                ?>
                  <li
                        class="single-menu-item block py-3 px-2 relative hover:cursor-pointer border-b-large border-light-000 hover:border-primary-100 transition"
                  >
                        <?php
                        $target = $primary_nav_item['target'] ?? '';
                        get_component( 'link', [
                              'text'     => $primary_nav_item['title'],
                              'classes'  => [ '!items-baseline' ],
                              'target'   => $target,
                              'url'    => $primary_nav_item['url'],
                        ] );
                        ?>
                  </li>

                <?php
                // If this menu item only has immediate children AND has item count <= 8
                elseif ( $primary_nav_item['child_count'] > 0 && $primary_nav_item['child_count'] <= 8 && $primary_nav_item['grand_child_count'] == 0 ):
                ?>
                    <!-- Start Nav Item -->
                    <li
                        class="dropdown-menu-item block py-3 px-2 relative hover:cursor-pointer border-b-large border-light-000 hover:border-primary-100 transition"
                        x-data="{ navDropdownOpen: false }"
                        x-on:click="navDropdownOpen = ! navDropdownOpen; $dispatch('close-nav-dropdowns')"
                        <?php // If the sending element isn't this element, close dropdown ?>
                        x-on:close-nav-dropdowns.window="
                            if( $event.target !== $el ) {navDropdownOpen = false}"
                        >
                        <?php echo $primary_nav_item['title']; ?><i class="fa-solid fa-caret-down ml-2"></i>

                        <ul
                            x-show="navDropdownOpen"
                            x-cloak
                            x-transition
                            class="nav-dropdown absolute z-20 mt-3 w-56 bg-white p-3 lg:p-7 shadow-lg <?php echo $is_primary_nav_item_last_item ? 'right-0' : 'left-0' ?>"
                        >
                            <?php
                            $child_loop_index = 0;
                            foreach( $primary_nav_item['children'] as $child ):
                                // If this is the last element in the list
                                $is_last_element = $child_loop_index == ( count( $primary_nav_item['children'] ) - 1 );
                                ?>
                                <li class="<?php if(!$is_last_element){echo 'mb-4';}?>">
                                    <?php
                                    $target = $child['target'] ?? '';
                                    get_component( 'link', [
                                          'text'     => $child['title'],
                                          'classes'  => [],
                                          'target'   => $target,
                                          'url'    => $child['url'],
                                    ] );
                                    ?>
                                </li>
                                <?php
                                $child_loop_index++;
                            endforeach; ?>
                        </ul>
                    </li> <!-- End Nav Item -->


                <?php
                // MEGA MENU If this menu item has grand children OR has more than 8 items
                elseif( $primary_nav_item['child_count'] > 8 || $primary_nav_item['grand_child_count'] > 0 ):
                ?>
                    <!-- Start Nav Item -->
                    <li
                        class="mega-menu-item block py-3 px-2 hover:cursor-pointer border-b-large border-light-000 hover:border-primary-100 transition"
                        x-data="{ navDropdownOpen: false }"
                        x-on:click="navDropdownOpen = ! navDropdownOpen; $dispatch('close-nav-dropdowns')"
                        <?php // If the sending element isn't this element, close dropdown ?>
                        x-on:close-nav-dropdowns.window="
                            if( $event.target !== $el ) {navDropdownOpen = false}"
                        >
                        <?php echo $primary_nav_item['title']; ?><i class="fa-solid fa-caret-down ml-2"></i>


                        <div
                            x-show="navDropdownOpen"
                            x-cloak
                            x-transition
                            class="nav-mega-dropdown container absolute z-20 flex gap-2 left-0 mt-3 w-full bg-white p-3 lg:p-7 shadow-lg flex-wrap"
                            >
                            <?php
                            foreach ( $primary_nav_item['children'] as $child ):
                                // Just print these as a header if there are no children
                                if( $child['child_count'] == 0 ):
                                ?>
                                    <div class="w-1/4">
                                        <?php
                                        $target = $child['target'] ?? '';
                                        get_component( 'link', [
                                            'text'     => $child['title'],
                                            'classes'  => ['block', 'pb-2', 'mb-2', 'mr-2', 'text-primary-100', 'font-bold', 'border-b-medium', 'border-dark-100'],
                                            'target'   => $target,
                                            'url'    => $child['url'],
                                        ] );
                                        ?>
                                    </div>
                                <?php
                                else:
                                ?>
                                    <div class="w-1/4">
                                        <?php
                                        $target = $child['target'] ?? '';
                                        get_component( 'link', [
                                            'text'     => $child['title'],
                                            'classes'  => ['block', 'pb-2', 'mb-2', 'mr-2', 'text-primary-100', 'font-bold', 'border-b-medium', 'border-dark-100'],
                                            'target'   => $target,
                                            'url'    => $child['url'],
                                        ] );
                                        ?>
                                        <ul>
                                            <?php foreach( $child['children'] as $grand_child ): ?>
                                                <li class="mb-4">
                                                      <?php
                                                      $target = $grand_child['target'] ?? '';
                                                      get_component( 'link', [
                                                            'text'     => $grand_child['title'],
                                                            'classes'  => [],
                                                            'target'   => $target,
                                                            'url'    => $grand_child['url'],
                                                      ] );
                                                      ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div> <!-- End Mega Menu -->
                    </li> <!-- End Nav Item -->
                <?php
                endif;
            endforeach; // End main nav looping?>

            </ul>
        </nav> <!-- End main nav -->

        <!-- Mobile Menu -->
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition
            x-anchor.bottom-start="$refs.secondary-nav"
            class="w-full bg-white border-t-base border-b-base font-bold"
            >
            <div class="pt-3 pb-2 px-3">
                <?php
                // Conditional Account Buttons
                if( $nav_state == 'logged_out' ) {
                    get_component( 'button', [
                        'variant'     => 'primary',
                        'a_tag'     => true,
                        'link'      => $bam_path,
                        'classes'     => ['create-account-button-mobile', 'w-full', 'mb-2', 'justify-center', 'md:hidden'],
                        'label'     => __( 'Create Account', 'wicket' ),
                    ] );
                } else if ( $nav_state == 'logged_in_user' ) {
                    get_component( 'button', [
                        'variant'     => 'primary',
                        'a_tag'     => true,
                        'link'      => $bam_path,
                        'classes'     => ['become-a-member-button-mobile', 'w-full', 'mb-2', 'justify-center', 'md:hidden'],
                        'label'     => __( 'Become a member', 'wicket' ),
                    ] );
                    get_component( 'button', [
                        'variant'     => 'secondary',
                        'a_tag'     => true,
                        'link'      => $account_center_landing,
                        'classes'     => ['my-account-button-mobile', 'w-full', 'mb-2', 'justify-center'],
                        'label'     => __( 'My Account', 'wicket' ),
                    ] );
                } else if ( $nav_state == 'logged_in_member' ) {
                    get_component( 'button', [
                        'variant'     => 'secondary',
                        'a_tag'     => true,
                        'link'      => $account_center_landing,
                        'classes'     => ['member-portal-button-mobile', 'w-full', 'mb-2', 'justify-center'],
                        'label'     => __( 'Member Portal', 'wicket' ),
                    ] );
                }
                ?>
                <?php
                // Conditional login/logout buttons
                if( $nav_state == 'logged_out' ) {
                    get_component( 'link', [
                        'text'     => __( 'Login', 'wicket' ),
                        'classes'  => ['login-button-mobile', 'block', 'text-center', 'mb-2'],
                        'url'    => get_option('wp_cassify_base_url').'login?service='.$referrer,
                    ] );
                } else {
                    get_component( 'link', [
                        'text'     => __( 'Logout', 'wicket' ),
                        'classes'  => ['logout-button-mobile', 'block', 'text-center', 'mb-2'],
                        'url'    => wp_logout_url(),
                    ] );
                }
                ?>
                <?php
                // Secondary Nav Items
                foreach( $secondary_nav_items as $secondary_nav_item ) {
                    $target = $secondary_nav_item->target ?? '';
                    get_component( 'link', [
                        'text'     => $secondary_nav_item->title,
                        'classes'  => ['secondary-nav-item-mobile', 'block', 'mb-2'],
                        'target'   => $target,
                        'url'    => $secondary_nav_item->url,
                    ] );
                }
                ?>
                <?php if( $lang == 'en' ): ?>
                    <a x-show="showLangToggle" x-cloak href="<?php echo wicket_get_lang_url( 'fr' ); ?>" class="lang-button ml-4 hidden lg:inline-flex items-center">Fr</a>
                <?php else: ?>
                    <a x-show="showLangToggle" x-cloak href="<?php echo wicket_get_lang_url( 'en' ); ?>" class="lang-button ml-4 hidden lg:inline-flex items-center">En</a>
                <?php endif; ?>
            </div> <!-- End items above main nav items and utility nav -->

            <div class="font-normal">
            <?php
            // Loop main nav items
                  foreach( $primary_nav_items_structured as $primary_nav_item ):
                  // If this is a single menu item link
                  if( $primary_nav_item['child_count'] == 0 ):
                  ?>
                        <?php
                        $target = $primary_nav_item['target'] ?? '';
                        get_component( 'link', [
                              'text'     => $primary_nav_item['title'],
                              'classes'  => ['single-nav-item-mobile', 'block', 'p-3', 'border-t-base', 'border-dark-060'],
                              'target'   => $target,
                              'url'    => $primary_nav_item['url'],
                        ] );
                        ?>

                  <?php
                  // If this menu item only has immediate children AND has item count <= 8
                  elseif ( $primary_nav_item['child_count'] > 0 && $primary_nav_item['child_count'] <= 8 && $primary_nav_item['grand_child_count'] == 0 ):
                  ?>
                        <!-- Start Nav Item -->
                        <div
                              class="nav-parent-item-mobile flex flex-col w-full border-t-base border-dark-060 relative hover:cursor-pointer"
                              x-data="{ navDropdownOpen: false }"
                              x-on:click="navDropdownOpen = ! navDropdownOpen; $dispatch('close-nav-dropdowns')"
                              <?php // If the sending element isn't this element, close dropdown ?>
                              x-on:close-nav-dropdowns.window="
                              if( $event.target !== $el ) {navDropdownOpen = false}"
                              >
                              <div
                                    class="inline-flex justify-between items-center w-full p-3"
                                    x-bind:class=" navDropdownOpen ? 'bg-dark-040' : '' "
                              >
                                    <span><?php echo $primary_nav_item['title']; ?></span>
                                    <i x-show="! navDropdownOpen" x-cloak class="fa-solid fa-caret-down"></i>
                                    <i x-show="navDropdownOpen" x-cloak class="fa-solid fa-caret-up"></i>
                              </div>

                              <ul
                              x-show="navDropdownOpen"
                              x-cloak
                              x-transition
                              class="nav-dropdown-mobile block w-full bg-white p-3"
                              >
                              <?php
                              $child_loop_index = 0;
                              foreach( $primary_nav_item['children'] as $child ):
                                    // If this is the last element in the list
                                    $is_last_element = $child_loop_index == ( count( $primary_nav_item['children'] ) - 1 );
                                    ?>
                                    <li class="<?php if(!$is_last_element){echo 'mb-4';}?>">
                                          <?php
                                          $target = $child['target'] ?? '';
                                          get_component( 'link', [
                                                'text'     => $child['title'],
                                                'classes'  => [],
                                                'target'   => $target,
                                                'url'    => $child['url'],
                                          ] );
                                          ?>
                                    </li>
                                    <?php
                                    $child_loop_index++;
                              endforeach; ?>
                              </ul>
                        </div> <!-- End Nav Item -->


                  <?php
                  // MEGA MENU If this menu item has grand children OR has more than 8 items
                  elseif( $primary_nav_item['child_count'] > 8 || $primary_nav_item['grand_child_count'] > 0 ):
                  ?>

                        <!-- Start Nav Item -->
                        <div
                              class="nav-mega-parent-item-mobile flex flex-col w-full border-t-base border-dark-060 relative hover:cursor-pointer"
                              x-data="{ navDropdownOpen: false }"
                              x-on:click="navDropdownOpen = ! navDropdownOpen; $dispatch('close-nav-dropdowns')"
                              <?php // If the sending element isn't this element, close dropdown ?>
                              x-on:close-nav-dropdowns.window="
                              if( $event.target !== $el ) {navDropdownOpen = false}"
                              >
                              <div
                                    class="inline-flex justify-between items-center w-full p-3"
                                    x-bind:class=" navDropdownOpen ? 'bg-dark-040' : '' "
                              >
                                    <span><?php echo $primary_nav_item['title']; ?></span>
                                    <i x-show="! navDropdownOpen" x-cloak class="fa-solid fa-caret-down"></i>
                                    <i x-show="navDropdownOpen" x-cloak class="fa-solid fa-caret-up"></i>
                              </div>

                              <ul
                              x-show="navDropdownOpen"
                              x-cloak
                              x-transition
                              class="nav-dropdown-mobile block w-full bg-white"
                              >
                              <?php
                              foreach( $primary_nav_item['children'] as $child ):


                                    // Just print these as a header if there are no children
                                    if( $child['child_count'] == 0 ):

                                    $target = $child['target'] ?? '';
                                    get_component( 'link', [
                                          'text'     => $child['title'],
                                          'classes'  => ['block', 'p-3', 'border-dark-060', 'font-bold', 'border-b-medium', 'border-dark-100'],
                                          'target'   => $target,
                                          'url'      => $child['url'],
                                    ] );
                                    ?>

                                    <?php
                                    else:
                                    ?>
                                          <div
                                                class="flex flex-col w-full border-dark-060 relative hover:cursor-pointer"
                                                x-data="{ subNavDropdownOpen: false }"
                                                <?php // Note: .stop prevents this click event from propagating/bubbling up to the parent dropdown; see https://alpinejs.dev/directives/on#stop ?>
                                                x-on:click.stop="if(mobileMenuMegaSubDropdowns) {subNavDropdownOpen = ! subNavDropdownOpen;} $dispatch('close-sub-nav-dropdowns')"
                                                <?php // If the sending element isn't this element, close dropdown ?>
                                                x-on:close-sub-nav-dropdowns.window="
                                                if( $event.target !== $el ) {subNavDropdownOpen = false}"
                                                >
                                                <div
                                                      class="inline-flex justify-between items-center w-full p-3 font-bold border-b-medium border-dark-100"
                                                      x-bind:class=" subNavDropdownOpen ? 'bg-dark-040' : '' "
                                                >
                                                      <span><?php echo $child['title']; ?></span>
                                                      <i x-show="! subNavDropdownOpen && mobileMenuMegaSubDropdowns" x-cloak class="fa-solid fa-caret-down"></i>
                                                      <i x-show="subNavDropdownOpen && mobileMenuMegaSubDropdowns" x-cloak class="fa-solid fa-caret-up"></i>
                                                </div>

                                          <ul
                                                x-show="subNavDropdownOpen || !mobileMenuMegaSubDropdowns"
                                                x-cloak
                                                x-transition
                                                class="block w-full bg-white p-3"
                                          >
                                                <?php
                                                $child_loop_index = 0;
                                                foreach( $child['children'] as $grand_child ):
                                                      // If this is the last element in the list
                                                      $is_last_element = $child_loop_index == ( count( $child['children'] ) - 1 );
                                                      ?>
                                                      <li class="<?php if(!$is_last_element){echo 'mb-4';}?>">
                                                            <?php
                                                            $target = $grand_child['target'] ?? '';
                                                            get_component( 'link', [
                                                                  'text'     => $grand_child['title'],
                                                                  'classes'  => [],
                                                                  'target'   => $target,
                                                                  'url'    => $grand_child['url'],
                                                            ] );
                                                            ?>
                                                      </li>
                                                      <?php
                                                      $child_loop_index++;
                                                endforeach; ?>
                                          </ul>
                                          </div>
                                    <?php endif;
                              endforeach; ?>
                              </ul>
                        </div> <!-- End Nav Item -->
                  <?php
                  endif;
                  endforeach; // End main nav looping ?>
            </div>

            <?php if( !empty( $utility_nav_items ) ): ?>
            <div class="utility-nav-mobile bg-dark-100 text-light-000 px-3 py-2 pb-3">
                <?php
                // Utility Nav Items
                foreach( $utility_nav_items as $utility_nav_item ) {
                    $target = $utility_nav_item->target ?? '';
                    get_component( 'link', [
                        'text'     => $utility_nav_item->title,
                        'classes'  => ['block', 'px-2', 'py-2', 'hover:bg-light-040'],
                        'target'   => $target,
                        'url'    => $utility_nav_item->url,
                    ] );
                }
                ?>
            </div>
            <?php endif; ?>

        </div>

        <!-- Mobile Search -->
        <div
            x-show="searchOpen"
            x-cloak
            x-transition
            x-anchor.bottom-start="$refs.secondary-nav"
            class="search-bar-mobile lg:hidden w-full p-3 bg-white shadow-lg z-30"
            >
            <form class="flex" action="/" method="get">
                <label for="search" class="hidden"><?php _e('Search the website', 'wicket'); ?></label>
                <input class="w-full p-1" type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="<?php _e('Search by Keyword', 'wicket'); ?>" />
                <?php get_component( 'button', [
                    'variant'     => 'primary',
                    'type'      => 'submit',
                    'label'     => __( 'Search', 'wicket' ),
                    'classes'     => ['ml-2', 'border-0']
                ] ) ?>
            </form>
        </div>

    </header>

    <?php
    /* Conditionally-rendered helpers: */

    $wicket_helpers_current_user = wp_get_current_user();
    $wicket_is_administrator = false;
    if ( in_array( 'administrator', (array) $wicket_helpers_current_user->roles ) ) {
        $wicket_is_administrator = true;
    }
    ?>

    <?php if( isset( $_GET['wp-info'] ) && $wicket_is_administrator ): ?>
        <div class="fixed block bottom-0 left-0 bg-white border text-dark-100 p-2">
            <div><strong>Post type:</strong> <?php echo get_post_type(); ?></div>
            <?php
                $wicket_helper_current_template = get_page_template_slug();
                if(empty($wicket_helper_current_template)) { $wicket_helper_current_template = 'Default'; }
            ?>
            <div><strong>Current template:</strong> <?php echo $wicket_helper_current_template; ?></div>
        </div>
    <?php endif; ?>

    <?php if( isset( $_GET['breakpoints'] ) && $wicket_is_administrator ): ?>
        <div class="fixed block sm:hidden bottom-0 right-0 bg-dark-080 text-light-000 p-2">Base</div>
        <div class="fixed hidden sm:block md:hidden bottom-0 right-0 bg-dark-080 text-light-000 p-2">SM</div>
        <div class="fixed hidden md:block lg:hidden bottom-0 right-0 bg-dark-080 text-light-000 p-2">MD</div>
        <div class="fixed hidden lg:block xl:hidden bottom-0 right-0 bg-dark-080 text-light-000 p-2">LG</div>
        <div class="fixed hidden xl:block 2xl:hidden bottom-0 right-0 bg-dark-080 text-light-000 p-2">XL</div>
        <div class="fixed hidden 2xl:block bottom-0 right-0 bg-dark-080 text-light-000 p-2">2XL</div>
    <?php endif; ?>

    <?php
    // Tailwind Colour Variance View
    if( isset( $_GET['colours'] ) && $wicket_is_administrator ):
        // NOTE: If you need to update the below array, use ?generate_colours_array, which will generate a fresh associative array that you can paste in as the value of $all_possible_colours
        $all_possible_colours = ['primary' => ['bg-primary-000', 'bg-primary-010', 'bg-primary-020', 'bg-primary-030', 'bg-primary-040', 'bg-primary-050', 'bg-primary-060', 'bg-primary-070', 'bg-primary-080', 'bg-primary-090', 'bg-primary-100', 'bg-primary-110', 'bg-primary-120', 'bg-primary-130', 'bg-primary-140', 'bg-primary-150', 'bg-primary-160', 'bg-primary-170', 'bg-primary-180', 'bg-primary-190', 'bg-primary-200', ], 'secondary' => ['bg-secondary-000', 'bg-secondary-010', 'bg-secondary-020', 'bg-secondary-030', 'bg-secondary-040', 'bg-secondary-050', 'bg-secondary-060', 'bg-secondary-070', 'bg-secondary-080', 'bg-secondary-090', 'bg-secondary-100', 'bg-secondary-110', 'bg-secondary-120', 'bg-secondary-130', 'bg-secondary-140', 'bg-secondary-150', 'bg-secondary-160', 'bg-secondary-170', 'bg-secondary-180', 'bg-secondary-190', 'bg-secondary-200', ], 'tertiary' => ['bg-tertiary-000', 'bg-tertiary-010', 'bg-tertiary-020', 'bg-tertiary-030', 'bg-tertiary-040', 'bg-tertiary-050', 'bg-tertiary-060', 'bg-tertiary-070', 'bg-tertiary-080', 'bg-tertiary-090', 'bg-tertiary-100', 'bg-tertiary-110', 'bg-tertiary-120', 'bg-tertiary-130', 'bg-tertiary-140', 'bg-tertiary-150', 'bg-tertiary-160', 'bg-tertiary-170', 'bg-tertiary-180', 'bg-tertiary-190', 'bg-tertiary-200', ], 'dark' => ['bg-dark-000', 'bg-dark-010', 'bg-light-050', 'bg-dark-030', 'bg-dark-040', 'bg-dark-050', 'bg-dark-060', 'bg-dark-070', 'bg-dark-080', 'bg-dark-090', 'bg-dark-100', 'bg-dark-110', 'bg-dark-120', 'bg-dark-130', 'bg-dark-140', 'bg-dark-150', 'bg-dark-160', 'bg-dark-170', 'bg-dark-180', 'bg-dark-190', 'bg-dark-200', ], 'light' => ['bg-light-000', 'bg-light-010', 'bg-light-020', 'bg-light-030', 'bg-light-040', 'bg-light-050', 'bg-light-060', 'bg-light-070', 'bg-light-080', 'bg-light-090', 'bg-light-100', 'bg-light-110', 'bg-light-120', 'bg-light-130', 'bg-light-140', 'bg-light-150', 'bg-light-160', 'bg-light-170', 'bg-light-180', 'bg-light-190', 'bg-light-200', ], 'accent' => ['bg-accent-000', 'bg-accent-010', 'bg-accent-020', 'bg-accent-030', 'bg-accent-040', 'bg-accent-050', 'bg-accent-060', 'bg-accent-070', 'bg-accent-080', 'bg-accent-090', 'bg-accent-100', 'bg-accent-110', 'bg-accent-120', 'bg-accent-130', 'bg-accent-140', 'bg-accent-150', 'bg-accent-160', 'bg-accent-170', 'bg-accent-180', 'bg-accent-190', 'bg-accent-200', ], 'info-a' => ['bg-info-a-000', 'bg-info-a-010', 'bg-info-a-020', 'bg-info-a-030', 'bg-info-a-040', 'bg-info-a-050', 'bg-info-a-060', 'bg-info-a-070', 'bg-info-a-080', 'bg-info-a-090', 'bg-info-a-100', 'bg-info-a-110', 'bg-info-a-120', 'bg-info-a-130', 'bg-info-a-140', 'bg-info-a-150', 'bg-info-a-160', 'bg-info-a-170', 'bg-info-a-180', 'bg-info-a-190', 'bg-info-a-200', ], 'info-b' => ['bg-info-b-000', 'bg-info-b-010', 'bg-info-b-020', 'bg-info-b-030', 'bg-info-b-040', 'bg-info-b-050', 'bg-info-b-060', 'bg-info-b-070', 'bg-info-b-080', 'bg-info-b-090', 'bg-info-b-100', 'bg-info-b-110', 'bg-info-b-120', 'bg-info-b-130', 'bg-info-b-140', 'bg-info-b-150', 'bg-info-b-160', 'bg-info-b-170', 'bg-info-b-180', 'bg-info-b-190', 'bg-info-b-200', ], 'danger' => ['bg-danger-000', 'bg-danger-010', 'bg-danger-020', 'bg-danger-030', 'bg-danger-040', 'bg-danger-050', 'bg-danger-060', 'bg-danger-070', 'bg-danger-080', 'bg-danger-090', 'bg-danger-100', 'bg-danger-110', 'bg-danger-120', 'bg-danger-130', 'bg-danger-140', 'bg-danger-150', 'bg-danger-160', 'bg-danger-170', 'bg-danger-180', 'bg-danger-190', 'bg-danger-200', ], 'warning' => ['bg-warning-000', 'bg-warning-010', 'bg-warning-020', 'bg-warning-030', 'bg-warning-040', 'bg-warning-050', 'bg-warning-060', 'bg-warning-070', 'bg-warning-080', 'bg-warning-090', 'bg-warning-100', 'bg-warning-110', 'bg-warning-120', 'bg-warning-130', 'bg-warning-140', 'bg-warning-150', 'bg-warning-160', 'bg-warning-170', 'bg-warning-180', 'bg-warning-190', 'bg-warning-200', ], 'success' => ['bg-success-000', 'bg-success-010', 'bg-success-020', 'bg-success-030', 'bg-success-040', 'bg-success-050', 'bg-success-060', 'bg-success-070', 'bg-success-080', 'bg-success-090', 'bg-success-100', 'bg-success-110', 'bg-success-120', 'bg-success-130', 'bg-success-140', 'bg-success-150', 'bg-success-160', 'bg-success-170', 'bg-success-180', 'bg-success-190', 'bg-success-200', ], 'white' => ['bg-white'], ];
        ?>
        <?php foreach( $all_possible_colours as $colour => $colours ):?>
        <h2 class="font-bold text-x-large my-2 font-sans"><?php echo $colour; ?>:</h2>
        <div class="flex flex-wrap w-full">
            <?php foreach( $colours as $colour ): ?>
            <div class="flex justify-center items-center p-5 <?php echo "$colour"; ?> <?php if( !str_contains( $colour, 'light' ) ) { echo 'text-light-000'; } ?>"><?php echo "$colour"; ?></div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if( isset( $_GET['font_sizes'] ) ){
        $theme_json = file_get_contents( get_template_directory() . '/theme.json' );
        $theme_json = json_decode( $theme_json, true );
        $font_sizes = $theme_json['settings']['typography']['fontSizes'];
        foreach( $font_sizes as $font_size ): ?>
            <div class="text-<?php echo $font_size['slug']; ?> font-bold"><?php echo $font_size['name']; ?></div>
        <?php endforeach;
    } ?>

<?php
    // For generating colours array used for ?colours view
    if( isset( $_GET['generate_colours_array'] ) && $wicket_is_administrator ):
        $colour_names = [
            'primary',
            'secondary',
            'tertiary',
            'dark',
            'light',
            'accent',
            'info-a',
            'info-b',
            'danger',
            'warning',
            'success'
        ];
        $colour_varieties = [
            '000',
            '010',
            '020',
            '030',
            '040',
            '050',
            '060',
            '070',
            '080',
            '090',
            '100',
            '110',
            '120',
            '130',
            '140',
            '150',
            '160',
            '170',
            '180',
            '190',
            '200'
        ];
        echo '[';
        foreach( $colour_names as $colour ) {
            echo "'" . "$colour" . "' => [";
            foreach( $colour_varieties as $variety ) {
                echo "'" . "bg-$colour-$variety" . "', ";
            };
            echo "], ";
        };
        echo ']';
    endif;

do_action('wicket_header_end');
