<?php

add_theme_support( 'automatic-feed-links' );

if ( function_exists('register_sidebar') ) {

	register_sidebar(array(
		'name'			=> 'Main Sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '',
		'after_title' => '',
	));

	register_sidebar(array(
		'name'			=> 'Footer Left',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
	
	register_sidebar(array(
		'name'			=> 'Footer Mid',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
	
	register_sidebar(array(
		'name'			=> 'Footer Right',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
	
}
/* 
SET UP CUSTOM NAVIGATION
*/
add_theme_support( 'menus' );

 /*
 add first and last classes to nav menus
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
