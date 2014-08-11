<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Gallery_Field' ) )
{
	class RWMB_Gallery_Field extends RWMB_File_Field
	{
		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts()
		{
  
			wp_enqueue_style( 'rwmb-gallery', RWMB_CSS_URL.'gallery.css', array(), RWMB_VER );
			wp_enqueue_script( 'rwmb-gallery', RWMB_JS_URL.'gallery.js', array(), RWMB_VER, true );

		}

		/**
		 * Get field HTML
		 *
		 * @param string $html
		 * @param mixed  $meta
		 * @param array  $field
		 *
		 * @return string
		 */
		static function html( $html, $meta, $field )
		{
			global $wpdb, $post;
			
			$post_id = $post->ID;
			$thumb_ID = get_post_thumbnail_id( $post_id );
			
			$arrImages = get_children(
				array(
					'post_type' => 'attachment',
					'post_mime_type' => 'image', 
					'post_parent' => $post_id, 
					'order' => 'DESC', 
					'orderby' => 'ID',
					'exclude' => $thumb_ID //Exclude featured image from list
				) 
			);
			
			$i18n_title    = _x( '', 'image upload', 'bootstrap' );
				
			$html  = wp_nonce_field( "rwmb-delete-file_{$field['id']}", "nonce-delete-file_{$field['id']}", false, false );
			$html .= wp_nonce_field( "rwmb-reorder-galleries_{$field['id']}", "nonce-reorder-galleries_{$field['id']}", false, false );
			$html .= "<input type='hidden' class='field-id' value='{$field['id']}' />";

			// Show form upload
			$html .= "
			
			<h4>{$i18n_title}</h4>
			
			<a href='#' class='rw-upload-button button clear' rel='{$field['id']}'>" . __('Add Images', 'bootstrap') . "</a> </br></br>";
			
			//TOD - Add image drag and drop ordering
			foreach($arrImages as $image){
				$html .= "<img class='eachthumbs' src='".$image->guid."' title='".$image->post_title."' style='cursor:pointer;height:50px;width:50px;margin:5px 5px 0 0;'/>";
			}
					
			return $html;
		}

		}
}