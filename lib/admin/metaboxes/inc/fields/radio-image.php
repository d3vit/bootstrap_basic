<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Radio_Image_Field' ) ) 
{
	class RWMB_Radio_Image_Field 
	{
		/**
		 * Enqueue scripts and styles
		 * 
		 * @return void
		 */
		static function admin_enqueue_scripts() 
		{
			wp_enqueue_script( 'rwmb-radio-image', RWMB_JS_URL . 'radio-image.js', null, RWMB_VER, true );
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
			$html = '';
			foreach ( $field['options'] as $key => $value ) 
			{
				$checked  = checked( $meta, $key, false );
				$selected = $checked ? ' selected' : '';
				$id		  = strstr( $field['id'], '[]' ) ? str_replace( '[]', "-{$key}[]", $field['id'] ) : $field['id'];
				$id		  = " id='{$id}'";
				$name     = "name='{$field['field_name']}'";
				$val      = " value='{$key}'";
				$html    .= "<label class='rwmb-label-radio-image{$selected}'><input type='radio' class='rwmb-radio-image'{$name}{$id}{$val}{$checked} /> {$value}</label> ";
			}

			return $html;
		}
	}
}