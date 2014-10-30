<?php

function bootstrap_load_framework_theme_options() {

$sections[] = array(
				'title' => __('General Options', 'bootstrap'),
				'desc' => __('<p class="description">This page contains general theme options.</p>', 'bootstrap'),
				'fields' => array(
				
					array(
						'id' => 'logo',
						'type' => 'upload', 
						'title' => __('Upload Logo', 'bootstrap'),
						'sub_desc' => __('Upload your custom logo here. If left empty, the site title will be displayed instead.', 'bootstrap'),
						),							
					
					array(
						'id' => 'favicon',
						'type' => 'upload', 
						'title' => __('Upload Favicon', 'bootstrap'),
						'sub_desc' => __('Upload a favicon here that will override the default favicon. (16px by 16px)', 'bootstrap'),
						),
					
					array(
						'id' => 'appleicon',
						'type' => 'upload', 
						'title' => __('Upload Apple Touch Icon', 'bootstrap'),
						'sub_desc' => __('Upload you custom icon which will be displayed when your website is saved to an iOS device homescreen. (114px by 114px)', 'bootstrap'),
						),
				
					array(
						'id' => 'branding_tagline',
						'type' => 'text',
						'title' => __('Branding Tagline', 'bootstrap'),
						'sub_desc' => __('Customize the bold title lettering located at the top of the blog sidebar.', 'bootstrap'),
						'std' => 'I create mousemade pixel perfection stuffs for the interwebs.'
						),		
					
					array(
						'id' => 'sidebar_menu',
						'type' => 'checkbox_hide_below',
						'title' => __( 'Display Sidebar Menu', 'bootstrap'), 
						'sub_desc' => __('Elect to display the sidebar menu system (or use the WordPress menu widget) in the sidebar.', 'bootstrap'),
						'desc' => __('Yes do it', 'bootstrap'),
						'std' => 1 
						),	
						
					array(
						'id' => 'sidebar_menu_text',
						'type' => 'text',
						'title' => __('Menu Title', 'bootstrap'),
						'sub_desc' => __('The title of your sidebar menu (if enabled).', 'bootstrap'),
						'std' => 'Menu'
						),		
							
									
					array(
						'id' => 'header_layout',
						'type' => 'radio_img',
						'title' => __('Header Layout', 'bootstrap'), 
						'sub_desc' => __('Please select a style for your header. Choose to display a full width persistent header, split (with navigation & a widget area) or simply hide it.', 'bootstrap'),
							'options' => array(
								'full' => array(
									'title' => 'Full', 
									'img' => lrl_OPTIONS_URL.'assets/images/full.png'
								),
								'split' => array(
									'title' => 'Split', 
									'img' => lrl_OPTIONS_URL.'assets/images/split.png'
								),
								'none' => array(
									'title' => 'None', 
									'img' => lrl_OPTIONS_URL.'assets/images/none.png'
								),
							),
							
						'std' => 'full'
						),
					
					array(
						'id' => 'copyright_text',
						'type' => 'textarea',
						'title' => __('Footer Copyright Text', 'bootstrap'),
						'sub_desc' => __('This text overrides the default copyright message located in the theme footer.', 'bootstrap'),
						'std' => '© 2013 Bootstrap Basic WordPress Theme.'
						),
						
					array(
						'id' => 'header_analytics',
						'type' => 'textarea',
						'title' => __('Header Analytics', 'bootstrap'),
						'sub_desc' => __('Paste any analytics code that belongs in the head element of your site here.', 'bootstrap'),
						'std' => ''
						),	
						
					array(
						'id' => 'other_analytics',
						'type' => 'textarea',
						'title' => __('Footer Analytics', 'bootstrap'),
						'sub_desc' => __('Paste any analytics code that belongs before the closing body tag here.', 'bootstrap'),
						'std' => ''
						),	
								
 					)
				);
  				
  								
$sections[] = array(
				'title' => __('Blog Settings', 'bootstrap'),
				'desc' => __('<p class="description">Manage multiple general page & blog view settings.</p>', 'bootstrap'),
				'icon' => '',
				'fields' => array(	
								
					array(
						'id' => 'post_options',
						'type' => 'multi_checkbox',
						'title' => __( 'Post Meta Options', 'bootstrap'), 
						'sub_desc' => __('Select which post meta you would like to display under the each post title.', 'bootstrap'),
						'options' => array(
							'post_author'      => 'Author',
							'post_new_tag'     => 'New Post Tag',
							'post_category'    => 'Category',
							'post_comments'    => 'Comments',
							'post_tags'    	   => 'Tags',
							'post_views'       => 'View Count',
							'post_words'       => 'Word Count',
							'post_likes'       => 'Post Likes',
						),
						
						'std' => array(
							'post_author'      => '1',
							'post_new_tag'     => '1',
							'post_category'    => '1',
							'post_comments'    => '1',
							'post_tags'        => '1',
							'post_views'       => '1',
							'post_words'       => '1',
							'post_likes'       => '1',
						)
					),	
					
					array(
						'id' => 'blog_pagination',
						'type' => 'checkbox',
						'title' => __( 'Display Blog Pagination', 'bootstrap'), 
						'sub_desc' => __('Elect to display the arrows to the next / previous posts.', 'bootstrap'),
						'desc' => __('Yes do it', 'bootstrap'),
						'std' => 1 
						),	
					
					array(
						'id' => 'social_sharing',
						'type' => 'checkbox',
						'title' => __( 'Display Social Sharing', 'bootstrap'), 
						'sub_desc' => __('Elect to display the twitter and facebook sharing buttons.', 'bootstrap'),
						'desc' => __('Yes do it', 'bootstrap'),
						'std' => 1 
						),	
						
					array(
						'id' => 'profile_twitter',
						'type' => 'text',
						'title' => __('Twitter Username', 'bootstrap'),
						'sub_desc' => __('Your username to be paired with the Twitter social sharing button.', 'bootstrap'),
						'std' => 'ryankallen'
						),															
				)
			);							
					

$sections[] = array(
				'title' => __('Site Archives', 'bootstrap'),
				'desc' => __('<p class="description">Customize the headers and content displayed on the Archives Template.</p>', 'bootstrap'),
 				'fields' => array(
 				
						array(
							'id' => 'archives_content',
							'type' => 'multi_checkbox',
							'title' => __( 'Archive Page Content', 'bootstrap'), 
							'sub_desc' => __('Select which contexts are displayed on the Archives page.', 'bootstrap'),
							'options' => array(
								'posts'    => 'All Posts',
								'latest'   => 'Latest Posts', 
								'month'    => 'Archives by Month',
								'category' => 'Archives by Category', 
								'pages'    => 'Site Map',
							),
							
							'std' => array(
								'posts'    => '1', 
								'latest'   => '1', 
								'category' => '1', 
								'month'    => '1',
								'pages'    => '0', 
							)
						),	
						
						array(
							'id' => 'archive_all_text',
							'type' => 'text',
							'title' => __('All Published Posts', 'bootstrap'),
							'sub_desc' => __('Replace the text above the all posts archive content.', 'bootstrap'),
							'std' => 'All Published Posts'
							),	
							
						array(
							'id' => 'archive_latest',
							'type' => 'text',
							'title' => __('Last 30 Posts Header', 'bootstrap'),
							'sub_desc' => __('Replace the text above the latest 30 posts archive content.', 'bootstrap'),
							'std' => 'Last 30 Posts'
							),
															
						array(
							'id' => 'archive_monthly',
							'type' => 'text',
							'title' => __('Monthly Archive Header', 'bootstrap'),
							'sub_desc' => __('Replace the text above the monthly posts archive content.', 'bootstrap'),
							'std' => 'Monthly Archives'
							),	
							
						array(
							'id' => 'archive_category',
							'type' => 'text',
							'title' => __('Category Archive Header', 'bootstrap'),
							'sub_desc' => __('Replace the text above the all category archive content.', 'bootstrap'),
							'std' => 'Category Archives'
							),	
							
						array(
							'id' => 'archive_sitemap',
							'type' => 'text',
							'title' => __('Site Map Header', 'bootstrap'),
							'sub_desc' => __('Replace the text above the site map content.', 'bootstrap'),
							'std' => 'Site Map'
							),	
																							
					)
  				); 			
  				

$sections[] = array(
				'title' => __('404 Error Page', 'bootstrap'),
				'desc' => __('<p class="description">Manage & customize your theme 404 error page.</p>', 'bootstrap'),
 				'fields' => array(
 				
 						array(
 							'id' => 'error_404_bg',
 							'type' => 'upload', 
 							'title' => __('Upload 404 Background Image', 'bootstrap'),
 							'sub_desc' => __('Upload a custom background image to be displayed full screen on your 404 page.', 'bootstrap'),
 							),	
 							
						array(
							'id' => '404_error_text',
							'type' => 'text',
							'title' => __('404 Page Header', 'bootstrap'),
							'sub_desc' => __('Customize the bold header text on the 404 Page.', 'bootstrap'),
							'std' => '404 Error... Ain&#39;t nobody got time for that!'
							),	
						
						array(
							'id' => '404_error_p_text',
							'type' => 'textarea',
							'title' => __('404 Page Paragraph', 'bootstrap'),
							'sub_desc' => __('Customize the paragraph text displayed on the 404 page to say anything you would liket.', 'bootstrap'),
							'std' => 'We hate these little bugs just as much as you do. Really. Customize this 404 via the Theme Options Panel.'
							),
						
						array(
							'id' => '404_error_btn_text',
							'type' => 'text',
							'title' => __('Back Button', 'bootstrap'),
							'sub_desc' => __('Customize the text that is displayed on the 404 Page "Back" button.', 'bootstrap'),
							'std' => 'Head on Back'
							),								
					)
  				);
  				
  				  				  				  				
$sections[] = array(
				'title' => __('Theme Colors', 'bootstrap'),
				'desc' => __('<p class="description">Easily manipulate various CSS elements throughout the theme. <b>Note:</b> These colors will override all other theme stylesheets. Deleting the color values inputted here will allow the stylesheets display.
				</p>', 'bootstrap'),
				'fields' => array(
				
					array(
						'id' => 'accent_color',
						'type' => 'color',
						'title' => __('Theme Accent Color', 'bootstrap'), 
						'sub_desc' => __('Default color of this element: #FF4229', 'bootstrap'),
						'std' => ''
						),

					array(
						'id' => 'sidebar_color',
						'type' => 'color',
						'title' => __('Sidebar Background', 'bootstrap'), 
						'sub_desc' => __('Default color of this element: #21282E', 'bootstrap'),
						'std' => ''
						),
				
					array(
						'id' => 'selection_bg_color',
						'type' => 'color',
						'title' => __('Selection Background', 'bootstrap'), 
						'sub_desc' => __('Default color of this element: #F4F4F6', 'bootstrap'),
						'std' => ''
						),							

					array(
						'id' => 'selection_text_color',
						'type' => 'color',
						'title' => __('Selection Text Color', 'bootstrap'), 
						'sub_desc' => __('Default color of this element: #21282E', 'bootstrap'),
						'std' => ''
						),	

					)
				);

$sections[] = array(
				'title' => __('Custom CSS', 'bootstrap'),
				'desc' => __('<p class="description">Overwrite or customize various CSS elements throughout the theme by implementing your styles in this textarea.
				</p>', 'bootstrap'),
				'fields' => array(
				
					array(
						'id' => 'bootstrap_custom_css_input',
						'type' => 'textarea',
						'title' => __('Custom StyleSheet', 'bootstrap'), 
						'sub_desc' => __('The CSS entered here will override all other CSS elements thoughout the theme.', 'bootstrap'),
						'std' => ''
						),
					
					)
				);
				
								
  return $sections;
  
}

add_filter('lrl-opts-sections-bootstrap_theme', 'bootstrap_load_framework_theme_options');

?>