<?php

add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );

//WooCommerce
//add_theme_support( 'woocommerce' );
//add_theme_support( 'wc-product-gallery-zoom' );
//add_theme_support( 'wc-product-gallery-lightbox' );
//add_theme_support( 'wc-product-gallery-slider' );

if ( function_exists('register_sidebar') ) {

	register_sidebar(array(
		'name'			=> 'Main Sidebar',
        'id'            => 'main-sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));

	register_sidebar(array(
		'name'			=> 'Footer Left',
        'id'            => 'footer-left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name'			=> 'Footer Mid',
        'id'            => 'footer-mid',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
	register_sidebar(array(
		'name'			=> 'Footer Right',
        'id'            => 'footer-right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	));
	
}
/* 
SET UP CUSTOM NAVIGATION
*/
add_theme_support( 'menus' );


/* 
 * Set up Custom scripts loading
 */
function custom_scripts_loading() {
    // Deregister the included library
    wp_deregister_script( 'jquery' );
     
    // Register the library again from Google's CDN
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-1.11.2.min.js', array(), null, false );

    // Register custom scripts
    wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array( 'jquery' ) );
	wp_register_script( 'jquery-easing', get_template_directory_uri() . '/js/vendor/jquery.easing.js', array( 'jquery' ) );
	wp_register_script( 'jquery-scroll-to', get_template_directory_uri() . '/js/vendor/jquery.scrollTo.min.js', array( 'jquery' ) );
	wp_register_script( 'custom-script', get_template_directory_uri() . '/js/site-wide.js', array( 'jquery' ) );
	
	//Enqueue scripts
	wp_enqueue_script( 'bootstrap' );
	wp_enqueue_script( 'jquery-easing' );
	wp_enqueue_script( 'jquery-scroll-to' );
	wp_enqueue_script( 'custom-script' );

	/*
	 * Custom Google Fonts. Simply follow the format below.
	 * Use pipes "|" when adding multiple fonts
	 */
	$query_args = array(
		'family' => 'Open+Sans:400,400i,700'//'Open+Sans:400,700|Oswald:700'
	);
	wp_register_style('google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
	wp_enqueue_style('google_fonts');
}
add_action( 'wp_enqueue_scripts', 'custom_scripts_loading' );

/* 
 * Add first and last class to WP Nav Menu
 */
function add_first_and_last($output) {
	$output = preg_replace('/class="menu-item/', 'class="first-menu-item menu-item', $output, 1);
	$output = substr_replace($output, 'class="last-menu-item menu-item', strripos($output, 'class="menu-item'), strlen('class="menu-item'));
	return $output;
}
add_filter('wp_nav_menu', 'add_first_and_last');


register_nav_menu( 'primary', 'Primary Menu' );
register_nav_menu( 'secondary', 'Secondary Menu' );

?>
