<?php
//define('lrl_OPTIONS_URL', site_url('path the options folder'));
if(!class_exists('lrl_Options')){
	require_once( BOOTSTRAP_ADMIN_DIR. '/options/options.php' );
}


/*
 * This is the meat of creating the options page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_lrl_framework_options(){

$args = array();

//google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
$args['google_api_key'] = 'AIzaSyAXpS28j-eNGn1Ph_cUMeWqc28jyTlKtJ0';

//Remove the default stylesheet? make sure you enqueue another one or the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
$args['intro_text'] = __('', 'bootstrap');
 
//Choose to disable the import/export feature
$args['show_import_export'] = true;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = 'bootstrap_theme';

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __('Theme Options', 'bootstrap');

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Bootstrap Basic Options Panel', 'bootstrap');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "bootstrap_options"
$args['page_slug'] = 'bootstrap_options';

//Custom page capability - default is set to "manage_options"
$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
$args['page_type'] = 'submenu';

//parent menu - default is set to "themes.php" (Appearance)
//the list of available parent menus is available here: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
$args['page_parent'] = 'themes.php';

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 100;

//Custom page icon class (used to override the page icon next to heading)
//$args['page_icon'] = 'icon-themes';

//Want to disable the sections showing as a submenu in the admin? uncomment this line
$args['allow_sub_menu'] = false;
		
/*//Set ANY custom page help tabs - displayed using the new help tab API, show in order of definition		
$args['help_tabs'][] = array(
							'id' => 'lrl-opts-1',
							'title' => __('Theme Information 1', 'bootstrap'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'bootstrap')
							);
$args['help_tabs'][] = array(
							'id' => 'lrl-opts-2',
							'title' => __('Theme Information 2', 'bootstrap'),
							'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'bootstrap')
							);

//Set the Help Sidebar for the options page - no sidebar by default										
$args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'bootstrap');

*/

$sections = array();			
			
$tabs = array();

if( DEV_MODE ) {
	 		
	$theme_data = wp_get_theme();
	$theme_uri = $theme_data->get('ThemeURI');
	$description = $theme_data->get('Description');
	$author = $theme_data->get('Author');
	$version = $theme_data->get('Version');
 	global $wp_version;
 	
 	
	$theme_info = '<div class="lrl-opts-section-desc">';
	$theme_info .= '<p class="lrl-opts-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'bootstrap').'<a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="lrl-opts-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'bootstrap').$author.'</p>';
	$theme_info .= '<p class="lrl-opts-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'bootstrap').$version.'</p>';
	$theme_info .= '<p class="lrl-opts-theme-data description theme-description">'.$description.'</p>';
  	$theme_info .= '<p class="lrl-opts-theme-data description php-version"> PHP Version: '.PHP_VERSION.'</b></p>';
 	$theme_info .= '<p class="lrl-opts-theme-data description wp-version">WordPress Version: '.$wp_version.'</p>';
 	$theme_info .= '</div>';
}

global $lrl_Options;
$lrl_Options = new lrl_Options($sections, $args, $tabs);


}
add_action('init', 'setup_lrl_framework_options', 0);