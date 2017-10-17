<?php

// take the content of a contact-form shortcode and parse it into a list of field types
function lrl_contact_form_parse( $content ) {
	// first parse all the contact-field shortcodes into an array
	global $lrl_contact_form_fields, $lrl_form_form;
	$lrl_contact_form_fields = array();

	$out = do_shortcode( $content );
	
	if ( empty($lrl_contact_form_fields) || !is_array($lrl_contact_form_fields) ) {
		// default form: same as the original Grunion form
		$default_form = '
		[contact-field label="'.__( 'Name', 'bootstrap' ).'" type="name" required="true" /]
		[contact-field label="'.__( 'Email', 'bootstrap' ).'" type="email" required="true" /]
		[contact-field label="'.__( 'Website', 'bootstrap' ).'" type="url" /]';
		if ( 'yes' == strtolower($lrl_form_form->show_subject) ) {
			$default_form .= '
			[contact-field label="'.__( 'Subject', 'bootstrap' ).'" type="subject" /]';
		}
		$default_form .= '
		[contact-field label="'.__( 'Message', 'bootstrap' ).'" type="textarea" /]';

		$out = do_shortcode( $default_form );
	}

	return $out;
}

function lrl_contact_form_render_field( $field ) {
	global $contact_form_last_id, $contact_form_errors, $lrl_contact_form_fields, $current_user, $user_identity;
	
	$r = '';
	
	$field_id = $field['id'];
	if ( isset($_POST[ $field_id ]) ) {
		$field_value = stripslashes( $_POST[ $field_id ] );
	} elseif ( is_user_logged_in() ) {
		// Special defaults for logged-in users
		if ( $field['type'] == 'email' )
			$field_value = $current_user->data->user_email;
		elseif ( $field['type'] == 'name' )
			$field_value = $user_identity;
		elseif ( $field['type'] == 'url' )
			$field_value = $current_user->data->user_url;
		else
			$field_value = $field['default'];
	} else {
		$field_value = $field['default'];
	}
	
	$field_value = wp_kses($field_value, array());

	$field['label'] = html_entity_decode( $field['label'] );
	$field['label'] = wp_kses( $field['label'], array() );

	if ( $field['type'] == 'email' ) {
		$r .= "\n<div class='input-text-block'>\n";
		$r .= "\t\t<label for='".esc_attr($field_id)."' class='lrl_form-field-label ".esc_attr($field['type']) . ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "'>" . htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		$r .= "\t\t<input type='text' name='".esc_attr($field_id)."' id='".esc_attr($field_id)."' value='".esc_attr($field_value)."' class='".esc_attr($field['type'])."'/>\n";
		$r .= "\t</div>\n";
	} elseif ( $field['type'] == 'textarea' ) {
		$r .= "\n<div class='input-textarea-block'>\n";
		$r .= "\t\t<label class='".esc_attr($field['type']) . ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "' for='contact-form-comment-" . esc_attr( $field_id ) . "'>" . htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		$r .= "\t\t<textarea name='".esc_attr($field_id)."' id='contact-form-comment-".esc_attr($field_id)."' rows='20' class='auto-height'>".htmlspecialchars($field_value)."</textarea>\n";
		$r .= "\t</div>\n";
	} elseif ( $field['type'] == 'radio' ) {
		$r .= "\t<div class='input-radio-block'><label class='". ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "'>" . htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		foreach ( $field['options'] as $option ) {
			$r .= "\t\t<label class='" . esc_attr( $field['type'] ) . ( contact_form_is_error( $field_id ) ? ' form-error' : '' ) . "'>";
			$r .= "<input type='radio' name='".esc_attr($field_id)."' value='".esc_attr($option)."' class='".esc_attr($field['type'])."' ".( $option == $field_value ? "checked='checked' " : "")." /> ";
 			$r .= htmlspecialchars( $option ) . "</label>\n";
			$r .= "\t\t<div class='clear-form'></div>\n";
		}
		$r .= "\t\t</div>\n";
	} elseif ( $field['type'] == 'checkbox' ) {
		$r .= "\t<div class='input-checkbox-block'>\n";
		$r .= "\t\t<label class='".esc_attr($field['type']) . ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "'>\n";
		$r .= "\t\t<input type='checkbox' name='".esc_attr($field_id)."' value='".__( 'Yes', 'bootstrap' )."' class='".esc_attr($field['type'])."' ".( $field_value ? "checked='checked' " : "")." /> \n";
		$r .= "\t\t". htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		$r .= "\t\t<div class='clear-form'></div>\n";
		$r .= "\t</div>\n";
	} elseif ( $field['type'] == 'select' ) {
		$r .= "\n<div class='input-select-block'>\n";
		$r .= "\t\t<label for='".esc_attr($field_id)."' class='".esc_attr($field['type']) . ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "'>" . htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		$r .= "\t<select name='".esc_attr($field_id)."' id='".esc_attr($field_id)."' value='".esc_attr($field_value)."' class='".esc_attr($field['type'])."'/>\n";
		foreach ( $field['options'] as $option ) {
			$option = html_entity_decode( $option );
			$option = wp_kses( $option, array() );
			$r .= "\t\t<option".( $option == $field_value ? " selected='selected'" : "").">". esc_html( $option ) ."</option>\n";
		}
		$r .= "\t</select>\n";
		$r .= "\t</div>\n";
	} else {
		// default: text field
		// note that any unknown types will produce a text input, so we can use arbitrary type names to handle
		// input fields like name, email, url that require special validation or handling at POST
		$r .= "\n<div class='input-text-block'>\n";
		$r .= "\t\t<label for='".esc_attr($field_id)."' class='".esc_attr($field['type']) . ( contact_form_is_error($field_id) ? ' form-error' : '' ) . "'>" . htmlspecialchars( $field['label'] ) . ( $field['required'] ? '<span class="required">&nbsp;*</span>' : '' ) . "</label>\n";
		$r .= "\t\t<input type='text' name='".esc_attr($field_id)."' id='".esc_attr($field_id)."' value='".esc_attr($field_value)."' class='".esc_attr($field['type'])."'/>\n";
		$r .= "\t</div>\n";
	}
	
	return $r;
}

function lrl_contact_form_validate_field( $field ) {
	global $contact_form_last_id, $contact_form_errors, $contact_form_values;

	$field_id = $field['id'];
	$field_value = isset($_POST[ $field_id ]) ? stripslashes($_POST[ $field_id ]) : '';

	# pay special attention to required email fields
	if ( $field['required'] && $field['type'] == 'email' ) {
		if ( !is_email( $field_value ) ) {
			if ( !is_wp_error( $contact_form_errors ) ) {
				$contact_form_errors = new WP_Error();
			}

			$contact_form_errors->add( $field_id, sprintf( __( 'A valid email address is required!', 'bootstrap' ), $field['label'] ) );
		}
	} elseif ( $field['required'] && !trim($field_value) ) {
		if ( !is_wp_error($contact_form_errors) ) {
			$contact_form_errors = new WP_Error();
		}

		$contact_form_errors->add( $field_id, sprintf( __( 'The "%s" field is required!', 'bootstrap' ), $field['label'] ) );
	}
	
	$contact_form_values[ $field_id ] = $field_value;
}

function contact_form_is_error( $field_id ) {
	global $contact_form_errors;
	
	return ( is_wp_error( $contact_form_errors ) && $contact_form_errors->get_error_message( $field_id ) );
}

// generic shortcode that handles all of the major input types
// this parses the field attributes into an array that is used by other functions for rendering, validation etc
function lrl_contact_form_field( $atts, $content, $tag ) {
	global $lrl_contact_form_fields, $contact_form_last_id, $lrl_form_form;
	
	$field = shortcode_atts( array(
		'label' => null,
		'type' => 'text',
		'required' => false,
		'options' => array(),
		'id' => null,
		'default' => null,
	), $atts);
	
	// special default for subject field
	if ( $field['type'] == 'subject' && is_null($field['default']) )
		$field['default'] = $lrl_form_form->subject;
	
	// allow required=1 or required=true
	if ( $field['required'] == '1' || strtolower($field['required']) == 'true' )
		$field['required'] = true;
	else
		$field['required'] = false;
		
	// parse out comma-separated options list
	if ( !empty($field['options']) && is_string($field['options']) )
		$field['options'] = array_map('trim', explode(',', $field['options']));

	// make a unique field ID based on the label, with an incrementing number if needed to avoid clashes
	$id = $field['id'];
	if ( empty($id) ) {
		$id = sanitize_title_with_dashes( $contact_form_last_id . '-' . $field['label'] );
		$i = 0;
		$max_tries = 12;
		while ( isset( $lrl_contact_form_fields[ $id ] ) ) {
			$i++;
			$id = sanitize_title_with_dashes( $contact_form_last_id . '-' . $field['label'] . '-' . $i );

			if ( $i > $max_tries ) {
				break;
			}
		}
		$field['id'] = $id;
	}
	
	$lrl_contact_form_fields[ $id ] = $field;
	
	if ( isset( $_POST['contact-form-id'] ) && $_POST['contact-form-id'] == $contact_form_last_id )
		lrl_contact_form_validate_field( $field );
	
	return lrl_contact_form_render_field( $field );
}

add_shortcode('contact-field', 'lrl_contact_form_field');


function lrl_contact_form_shortcode( $atts, $content ) {
	global $post;

	$default_to = get_option( 'admin_email' );
	$default_subject = "[" . get_option( 'blogname' ) . "]";

	if ( !empty( $atts['widget'] ) && $atts['widget'] ) {
		$default_subject .=  " Sidebar";
	} elseif ( $post->ID ) {
		$default_subject .= " ". wp_kses( $post->post_title, array() );
		$post_author = get_userdata( $post->post_author );
		$default_to = $post_author->user_email;
	}

	extract( shortcode_atts( array(
		'to' => $default_to,
		'subject' => $default_subject,
		'show_subject' => 'no', // only used in back-compat mode
		'widget' => 0 //This is not exposed to the user. Works with lrl_contact_form_widget_atts
	), $atts ) );

	$widget = esc_attr( $widget );

	if ( ( function_exists( 'faux_faux' ) && faux_faux() ) || is_feed() )
		return '[contact-form]';

	global $wp_query, $lrl_form_form, $contact_form_errors, $contact_form_values, $user_identity, $contact_form_last_id, $contact_form_message;
	
	// used to store attributes, configuration etc for access by contact-field shortcodes
	$lrl_form_form = new stdClass();
	$lrl_form_form->to = $to;
	$lrl_form_form->subject = $subject;
	$lrl_form_form->show_subject = $show_subject;

	if ( $widget )
		$id = 'widget-' . $widget;
	elseif ( is_singular() )
		$id = $wp_query->get_queried_object_id();
	else
		$id = $GLOBALS['post']->ID;
	if ( !$id ) // something terrible has happened
		return '[contact-form]';

	if ( $id == $contact_form_last_id )
		return;
	else
		$contact_form_last_id = $id;

	ob_start();
		wp_nonce_field( 'contact-form_' . $id );
		$nonce = ob_get_contents();
	ob_end_clean();


	$body = lrl_contact_form_parse( $content );

	$r = "<div id='contact-form-$id'>\n";
	
	
	$errors = array();
	if ( is_wp_error( $contact_form_errors ) && $errors = (array) $contact_form_errors->get_error_codes() ) {
		$r .= "<div class='form-error'><h3>Please correct the following errors:</h3><ul class='form-errors'>\n";
		foreach ( $contact_form_errors->get_error_messages() as $message )
			$r .= "\t<li class='form-error-message animated BeanFadeIn'>$message</li>\n";
		$r .= "</ul>\n</div>\n\n";
	}
		
	$action = apply_filters( 'lrl_form_contact_form_form_action', get_permalink( $post->ID ) . "#contact-form-$id", $post, $id );
	$r .= "<form action='" . esc_url( $action ) . "' method='post' class='contact-form commentsblock'>\n";
	$r .= $body;
	$r .= "\t<p class='contact-submit'>\n";
	$r .= "\t\t<button class='button left' type='submit' name='submit'><span>" . __( "Submit", 'bootstrap' ) . "</span></button>\n";
	$r .= "\t\t$nonce\n";
	$r .= "\t\t<input type='hidden' name='contact-form-id' value='$id' />\n";
	$r .= "\t</p>\n";
	$r .= "</form>\n</div>";
	

	
	
	if ( !isset( $_POST['contact-form-id'] ) || $_POST['contact-form-id'] != $contact_form_last_id )
		return $r;


	if ( is_wp_error($contact_form_errors) )
		return $r;

	
	$emails = str_replace( ' ', '', $to );
	$emails = explode( ',', $emails );
	foreach ( (array) $emails as $email ) {
		if ( is_email( $email ) && ( !function_exists( 'is_email_address_unsafe' ) || !is_email_address_unsafe( $email ) ) )
			$valid_emails[] = $email;
	}

	$to = ( $valid_emails ) ? $valid_emails : $default_to;

	$message_sent = lrl_contact_form_send_message( $to, $subject, $widget );

	if ( is_array( $contact_form_values ) )
		extract( $contact_form_values );

	if ( !isset( $comment_content ) )
		$comment_content = '';
	else
		$comment_content = wp_kses( $comment_content, array() );


	$r = "<div id='contact-form-$id'>\n";

	$errors = array();
	if ( is_wp_error( $contact_form_errors ) && $errors = (array) $contact_form_errors->get_error_codes() ) :
		$r .= "<div class='form-error'>\n<h3>" . __( 'Error!', 'bootstrap' ) . "</h3>\n<p>\n";
		foreach ( $contact_form_errors->get_error_messages() as $message )
			$r .= "\t$message<br />\n";
		$r .= "</p>\n</div>\n\n";
	else :
		$r_success_message = "<h3>" . __( 'Message Sent', 'bootstrap' ) . "</h3>\n\n";
		$r_success_message .= wp_kses($contact_form_message, array('br' => array(), 'blockquote' => array()));

		$r .= apply_filters( 'lrl_form_contact_form_success_message', $r_success_message );

		$r .= "</div>";
		
		// Reset for multiple contact forms. Hacky
		$contact_form_values['comment_content'] = '';

		return $r;
	endif;

	return $r;
}
add_shortcode( 'contact-form', 'lrl_contact_form_shortcode' );

function lrl_contact_form_send_message( $to, $subject, $widget ) {
	global $post;
	
 	if ( !isset( $_POST['contact-form-id'] ) )
		return;
		
	if ( ( $widget && 'widget-' . $widget != $_POST['contact-form-id'] ) || ( !$widget && $post->ID != $_POST['contact-form-id'] ) )
		return;

	if ( $widget )
		check_admin_referer( 'contact-form_widget-' . $widget );
	else
		check_admin_referer( 'contact-form_' . $post->ID );

	global $contact_form_values, $contact_form_errors, $current_user, $user_identity;
	global $lrl_contact_form_fields, $contact_form_message;
	
	// compact the fields and values into an array of Label => Value pairs
	// also find values for comment_author_email and other significant fields
	$all_values = $extra_values = array();
	
	foreach ( $lrl_contact_form_fields as $id => $field ) {
		if ( $field['type'] == 'email' && !isset( $comment_author_email ) ) {
			$comment_author_email = $contact_form_values[ $id ];
			$comment_author_email_label = $field['label'];
		} elseif  ( $field['type'] == 'name' && !isset( $comment_author ) ) {
			$comment_author = $contact_form_values[ $id ];
			$comment_author_label = $field['label'];
		} elseif ( $field['type'] == 'url' && !isset( $comment_author_url ) ) {
			$comment_author_url = $contact_form_values[ $id ];
			$comment_author_url_label = $field['label'];
	} elseif ( $field['type'] == 'subject' && !isset( $contact_form_subject ) ) {
			$contact_form_subject = $contact_form_values[$id];
			$contact_form_subject_label = $field['label'];
		} elseif ( $field['type'] == 'textarea' && !isset( $comment_content ) ) {
			$comment_content = $contact_form_values[ $id ];
			$comment_content_label = $field['label'];
		} else {
			$extra_values[ $field['label'] ] = $contact_form_values[ $id ];
		}
		
		$all_values[ $field['label'] ] = $contact_form_values[ $id ];
	}

/*
	$contact_form_values = array();
	$contact_form_errors = new WP_Error();

	list($comment_author, $comment_author_email, $comment_author_url) = is_user_logged_in() ?
		add_magic_quotes( array( $user_identity, $current_user->data->user_email, $current_user->data->user_url ) ) :
		array( $_POST['comment_author'], $_POST['comment_author_email'], $_POST['comment_author_url'] );
*/

	$comment_author = stripslashes( apply_filters( 'pre_comment_author_name', $comment_author ) );

	if ( !empty( $comment_author_email ) ) {
		$comment_author_email = stripslashes( apply_filters( 'pre_comment_author_email', $comment_author_email ) );
	} else {
		$comment_author_email = '';
		$comment_author_email_label = '';
	}

	if ( !empty( $comment_author_url ) ) {
		$comment_author_url = stripslashes( apply_filters( 'pre_comment_author_url', $comment_author_url ) );
		if ( 'http://' == $comment_author_url ) {
			$comment_author_url = '';
		}
	} else {
		$comment_author_url = '';
		$comment_author_url_label = '';
	}

	$comment_content = stripslashes( $comment_content );
	$comment_content = trim( wp_kses( $comment_content, array() ) );

	if ( empty( $contact_form_subject ) )
		$contact_form_subject = trim( wp_kses( $subject, array() ) );
	else
		$contact_form_subject = trim( wp_kses( $contact_form_subject, array() ) );
		
	$comment_author_IP = $_SERVER['REMOTE_ADDR'];

	$vars = array( 'comment_author', 'comment_author_email', 'comment_author_url', 'contact_form_subject', 'comment_author_IP' );
	foreach ( $vars as $var )
		$$var = str_replace( array("\n", "\r" ), '', $$var ); // I don't know if it's possible to inject this
	$vars[] = 'comment_content';

	$contact_form_values = compact( $vars );

	$spam = '';
	$akismet_values = lrl_contact_form_prepare_for_akismet( $contact_form_values );
	$is_spam = apply_filters( 'contact_form_is_spam', $akismet_values );
	if ( is_wp_error( $is_spam ) )
		return; // abort
	else if ( $is_spam === TRUE )
		$spam = '***SPAM*** ';

	if ( !$comment_author )
		$comment_author = $comment_author_email;

	$to = apply_filters( 'contact_form_to', $to );
	foreach ( (array) $to as $to_key => $to_value ) {
		$to[$to_key] = wp_kses( $to_value, array() );
	}

	$from_email_addr = $to[0];
	if ( !empty( $comment_author_email ) ) {
		$from_email_addr = $comment_author_email;
	}

	$headers = 'From: ' . wp_kses( $comment_author, array() ) .
		' <' . wp_kses( $from_email_addr, array() ) . ">\r\n" .
		'Reply-To: ' . wp_kses( $from_email_addr, array() ) . "\r\n" .
		"Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\""; 
	$subject = apply_filters( 'contact_form_subject', $contact_form_subject );
	$subject = wp_kses( $subject, array() );

	$time = date_i18n( __( 'l F j, Y \a\t g:i a', 'bootstrap' ), current_time( 'timestamp' ) );
	
	$extra_content = '';
	$extra_content_br = '';
	
	foreach ( $extra_values as $label => $value ) {
		$extra_content .= $label . ': ' . trim($value) . "\n";
		$extra_content_br .= wp_kses( $label, array() ) . ': ' . wp_kses( trim($value), array() ) . "<br />";
	}

	$message = "$comment_author_label: $comment_author\n";
	if ( !empty( $comment_author_email ) ) {
		$message .= "$comment_author_email_label: $comment_author_email\n";
	}
	if ( !empty( $comment_author_url ) ) {
		$message .= "$comment_author_url_label: $comment_author_url\n";
	}
	$message .= "$comment_content_label: $comment_content\n";
	$message .= $extra_content . "\n";

	$message .= __( "Time:", 'bootstrap' ) . " " . $time . "\n";
	$message .= __( "IP Address:", 'bootstrap' ) . " " . $comment_author_IP . "\n";
	$message .= __( "Contact Form URL:", 'bootstrap' ) . " " . get_permalink( $post->ID ) . "\n";


	// Construct message that is returned to user
	$contact_form_message = "<p><br>";
	if (isset($comment_author_label))
		$contact_form_message .= wp_kses( $comment_author_label, array() ) . " " . wp_kses( $comment_author, array() ) . "<br />";
    if ( !empty( $comment_author_email ) )
		$contact_form_message .= wp_kses( $comment_author_email_label, array() ) . " " . wp_kses( $comment_author_email, array() ) . "<br />"; 
    if ( !empty( $comment_author_url ) )
		$contact_form_message .= wp_kses( $comment_author_url_label, array() ) . " " . wp_kses( $comment_author_url, array() ) . "<br />";
	if ( !empty( $contact_form_subject_label ) ) {
		$contact_form_message .= wp_kses( $contact_form_subject_label, array() ) . " " . wp_kses( $contact_form_subject, array() ) . "<br />";
	}
	if (isset($comment_content_label))
		$contact_form_message .= wp_kses( $comment_content_label, array() ) . " " . wp_kses( $comment_content, array() ) . "<br />";
	if (isset($extra_content_br))
		$contact_form_message .= $extra_content_br;
	$contact_form_message .= "</p><br /><br />";

	if ( is_user_logged_in() ) {
		$message .= "\n";
		$message .= sprintf(
			__( 'Sent by a verified %s user.', 'bootstrap' ),
			isset( $GLOBALS['current_site']->site_name ) && $GLOBALS['current_site']->site_name ? $GLOBALS['current_site']->site_name : '"' . get_option( 'blogname' ) . '"'
		);
	} else {
		$message .= __( "Sent by an unverified visitor to your site.", 'bootstrap' );
	}

	$message = apply_filters( 'contact_form_message', $message );
	$message = wp_kses( $message, array() );

	// keep a copy of the feedback as a custom post type
	$feedback_mysql_time = current_time( 'mysql' );
	$feedback_title = "{$comment_author} - {$feedback_mysql_time}";
	$feedback_status = 'publish';
	if ( $is_spam === TRUE )
		$feedback_status = 'spam';

	foreach ( (array) $akismet_values as $av_key => $av_value ) {
		$akismet_values[$av_key] = wp_kses( $av_value, array() );
	}

	foreach ( (array) $all_values as $all_key => $all_value ) {
		$all_values[$all_key] = wp_kses( $all_value, array() );
	}

	foreach ( (array) $extra_values as $ev_key => $ev_value ) {
		$ev_values[$ev_key] = wp_kses( $ev_value, array() );
	}

	# We need to make sure that the post author is always zero for contact
	# form submissions.  This prevents export/import from trying to create
	# new users based on form submissions from people who were logged in
	# at the time.
	#
	# Unfortunately wp_insert_post() tries very hard to make sure the post
	# author gets the currently logged in user id.  That is how we ended up
	# with this work around.
	global $do_lrl_form_insert;
	$do_lrl_form_insert = TRUE;
	add_filter( 'wp_insert_post_data', 'lrl_form_insert_filter', 10, 2 );

	$post_id = wp_insert_post( array(
		'post_date'    => $feedback_mysql_time,
		'post_type'    => 'feedback',
		'post_status'  => $feedback_status,
		'post_parent'  => $post->ID,
		'post_title'   => wp_kses( $feedback_title, array() ),
		'post_content' => wp_kses($comment_content . "\n<!--more-->\n" . "AUTHOR: {$comment_author}\nAUTHOR EMAIL: {$comment_author_email}\nAUTHOR URL: {$comment_author_url}\nSUBJECT: {$contact_form_subject}\nIP: {$comment_author_IP}\n" . print_r( $all_values, TRUE ), array()), // so that search will pick up this data
		'post_name'    => md5( $feedback_title )
	) );

	# once insert has finished we don't need this filter any more
	remove_filter( 'wp_insert_post_data', 'lrl_form_insert_filter' );
	$do_lrl_form_insert = FALSE;

	update_post_meta( $post_id, '_feedback_author', wp_kses( $comment_author, array() ) );
	update_post_meta( $post_id, '_feedback_author_email', wp_kses( $comment_author_email, array() ) );
	update_post_meta( $post_id, '_feedback_author_url', wp_kses( $comment_author_url, array() ) );
	update_post_meta( $post_id, '_feedback_subject', wp_kses( $contact_form_subject, array() ) );
	update_post_meta( $post_id, '_feedback_ip', wp_kses( $comment_author_IP, array() ) );
	update_post_meta( $post_id, '_feedback_contact_form_url', wp_kses( get_permalink( $post->ID ), array() ) );
	update_post_meta( $post_id, '_feedback_all_fields', $all_values );
	update_post_meta( $post_id, '_feedback_extra_fields', $extra_values );
	update_post_meta( $post_id, '_feedback_akismet_values', $akismet_values );
	update_post_meta( $post_id, '_feedback_email', array( 'to' => $to, 'subject' => $subject, 'message' => $message, 'headers' => $headers ) );

	do_action( 'lrl_form_pre_message_sent', $post_id, $all_values, $extra_values );

	# schedule deletes of old spam feedback
	if ( !wp_next_scheduled( 'lrl_form_scheduled_delete' ) ) {
		wp_schedule_event( time() + 250, 'daily', 'lrl_form_scheduled_delete' );
	}

	if ( $is_spam !== TRUE )
		return wp_mail( $to, "{$spam}{$subject}", $message, $headers );
	elseif ( apply_filters( 'lrl_form_still_email_spam', FALSE ) == TRUE )
		return wp_mail( $to, "{$spam}{$subject}", $message, $headers );

}

// populate an array with all values necessary to submit a NEW comment to Akismet
// note that this includes the current user_ip etc, so this should only be called when accepting a new item via $_POST
function lrl_contact_form_prepare_for_akismet( $form ) {

	$form['comment_type'] = 'contact_form';
	$form['user_ip']      = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$form['user_agent']   = $_SERVER['HTTP_USER_AGENT'];
	$form['referrer']     = $_SERVER['HTTP_REFERER'];
	$form['blog']         = home_url();

	$ignore = array( 'HTTP_COOKIE' );

	foreach ( $_SERVER as $k => $value )
		if ( !in_array( $k, $ignore ) && is_string( $value ) )
			$form["$k"] = $value;
			
	return $form;
}

// submit an array to Akismet. If you're accepting a new item via $_POST, run it through lrl_contact_form_prepare_for_akismet() first
function lrl_contact_form_is_spam_akismet( $form ) {
	if ( !function_exists( 'akismet_http_post' ) )
		return false;
		
	global $akismet_api_host, $akismet_api_port;

	$query_string = '';
	foreach ( array_keys( $form ) as $k )
		$query_string .= $k . '=' . urlencode( $form[$k] ) . '&';

	$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
	$result = false;
	if ( 'true' == trim( $response[1] ) ) // 'true' is spam
		$result = true;
	return apply_filters( 'lrl_contact_form_is_spam_akismet', $result, $form );
}

// submit a comment as either spam or ham
// $as should be a string (either 'spam' or 'ham'), $form should be the comment array
function lrl_contact_form_akismet_submit( $as, $form ) {
	global $akismet_api_host, $akismet_api_port;
	
	if ( !in_array( $as, array( 'ham', 'spam' ) ) )
		return false;

	$query_string = '';
	foreach ( array_keys( $form ) as $k )
		$query_string .= $k . '=' . urlencode( $form[$k] ) . '&';

	$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/submit-'.$as, $akismet_api_port );
	return trim( $response[1] );
}

function lrl_contact_form_widget_atts( $text ) {
	static $widget = 0;
	
	$widget++;

	return preg_replace( '/\[contact-form([^a-zA-Z_-])/', '[contact-form widget="' . $widget . '"\\1', $text );
}
add_filter( 'widget_text', 'lrl_contact_form_widget_atts', 0 );

function lrl_contact_form_widget_shortcode_hack( $text ) {
	if ( !preg_match( '/\[contact-form([^a-zA-Z_-])/', $text ) ) {
		return $text;
	}

	$old = $GLOBALS['shortcode_tags'];
	remove_all_shortcodes();
	add_shortcode( 'contact-form', 'lrl_contact_form_shortcode' );
	add_shortcode( 'contact-field', 'lrl_contact_form_field' );
	$text = do_shortcode( $text );
	$GLOBALS['shortcode_tags'] = $old;
	return $text;
}

function lrl_contact_form_init() {
	if ( function_exists( 'akismet_http_post' ) ) {
		add_filter( 'contact_form_is_spam', 'lrl_contact_form_is_spam_akismet', 10 );
		add_action( 'contact_form_akismet', 'lrl_contact_form_akismet_submit', 10, 2 );
	}
	if ( !has_filter( 'widget_text', 'do_shortcode' ) )
		add_filter( 'widget_text', 'lrl_contact_form_widget_shortcode_hack', 5 );

	// custom post type we'll use to keep copies of the feedback items
	register_post_type( 'feedback', array(
		'labels'            => array(
			'name'               => __( 'Responses', 'bootstrap' ),
			'singular_name'      => __( 'Responses', 'bootstrap' ),
			'search_items'       => __( 'Search', 'bootstrap' ),
			'not_found'          => __( 'No responses found', 'bootstrap' ),
			'not_found_in_trash' => __( 'No responses found', 'bootstrap' )
		),
		'show_ui'           => TRUE,
		'show_in_admin_bar' => FALSE,
		'public'            => FALSE,
		'rewrite'           => FALSE,
		'query_var'         => FALSE,
		'capability_type'   => 'page'
	) );

	register_post_status( 'spam', array(
		'label'                  => 'Spam',
		'public'                 => FALSE,
		'exclude_from_search'    => TRUE,
		'show_in_admin_all_list' => FALSE,
		'label_count'            => _n_noop( 'Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>', 'bootstrap' ),
		'protected'              => TRUE,
		'_builtin'               => FALSE
	) );
}
add_action( 'init', 'lrl_contact_form_init' );

/**
 * Add a contact form button to the post composition screen
 */
add_action( 'media_buttons', 'lrl_form_media_button', 999 );
function lrl_form_media_button( ) {
	global $post_ID, $temp_ID;
	$iframe_post_id = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$title = esc_attr( __( 'bootstrap Form Builder', 'bootstrap' ) );
	$plugin_url = esc_url( BOOTSTRAP_FORMS_DIR );
	$site_url = admin_url( "/admin-ajax.php?post_id=$iframe_post_id&amp;lrl_form=form-builder&amp;action=lrl_form_form_builder&amp;TB_iframe=true&amp;width=768" );

	echo '<a href="' . $site_url . '&id=add_form" class="thickbox" title="' . $title . '"><img src="' . $plugin_url . '/images/form.png" alt="' . $title . '" width="15" height="15" /></a>';
}


if ( !empty( $_GET['lrl_form'] ) && $_GET['lrl_form'] == 'form-builder' ) {
	add_action( 'parse_request', 'lrl_parse_wp_request' );
	add_action( 'wp_ajax_lrl_form_form_builder', 'lrl_parse_wp_request' );
}

function lrl_parse_wp_request( $wp ) {
	lrl_display_form_view( );
	exit;
}

function lrl_display_form_view( ) {
	require_once BOOTSTRAP_FORMS_DIR . '/form-view.php';
}



function lrl_form_insert_filter( $data, $postarr ) {
	global $do_lrl_form_insert;

	if ( $do_lrl_form_insert === TRUE ) {
		if ( $data['post_type'] == 'feedback' ) {
			if ( $postarr['post_type'] == 'feedback' ) {
				$data['post_author'] = 0;
			}
		}
	}

	return $data;
}

add_action( 'lrl_form_scheduled_delete', 'lrl_form_delete_old_spam' );
function lrl_form_delete_old_spam() {
	global $wpdb;

	$lrl_form_delete_limit = 100;

	$now_gmt = current_time( 'mysql', 1 );
	$sql = $wpdb->prepare( "
		SELECT `ID`
		FROM $wpdb->posts
		WHERE DATE_SUB( %s, INTERVAL 15 DAY ) > `post_date_gmt`
			AND `post_type` = 'feedback'
			AND `post_status` = 'spam'
		LIMIT %d
	", $now_gmt, $lrl_form_delete_limit );
	$post_ids = $wpdb->get_col( $sql );

	foreach ( (array) $post_ids as $post_id ) {
		# force a full delete, skip the trash
		wp_delete_post( $post_id, TRUE );
	}

	# Arbitrary check points for running OPTIMIZE
	# nothing special about 5000 or 11
	# just trying to periodically recover deleted rows
	$random_num = mt_rand( 1, 5000 );
	if ( apply_filters( 'lrl_form_optimize_table', ( $random_number == 11 ) ) ) {
		$wpdb->query( "OPTIMIZE TABLE $wpdb->posts" );
	}

	# if we hit the max then schedule another run
	if ( count( $post_ids ) >= $lrl_form_delete_limit ) {
		wp_schedule_single_event( time() + 700, 'lrl_form_scheduled_delete' );
	}
}


/* Add Custom Icon for Dashboard -------------------------------------------
add_action('admin_head', 'forms_header');

function forms_header() {
    global $post_type;
    ?>

<style>
 
    #adminmenu #menu-posts-feedback div.wp-menu-image{
    	background:transparent url("<?php echo BOOTSTRAP_FORMS_DIR .'../../../../../../images/menu.png';?>") no-repeat 6px -17px  !important;    
		}
    #adminmenu #menu-posts-feedback:hover div.wp-menu-image,
    #adminmenu #menu-posts-feedback.wp-has-current-submenu div.wp-menu-image {
    	background:transparent url("<?php echo BOOTSTRAP_FORMS_DIR .'../../../../../../images/menu.png';?>") no-repeat 6px 7px  !important;
    	} 
    
   @media all and (-webkit-min-device-pixel-ratio: 1.5) {
		#adminmenu #menu-posts-feedback div.wp-menu-image{
			background:transparent url("<?php echo BOOTSTRAP_FORMS_DIR .'../../../../../../images/menu@2x.png';?>") no-repeat 6px -17px  !important; 
			background-size: 16px 40px!important;   
			}
		#adminmenu #menu-posts-feedback:hover div.wp-menu-image,
		#adminmenu #menu-posts-feedback.wp-has-current-submenu div.wp-menu-image {
			background:transparent url("<?php echo BOOTSTRAP_FORMS_DIR .'../../../../../../images/menu@2x.png';?>") no-repeat 6px 7px  !important;
			background-size: 16px 40px!important;  
			}     
    }
</style>
<?php
}
*/

/**
 * Tell WordPress to expect a custom menu order
 * @since 1.0
 *
 function pixopoint_toggle_menu_order(){
	return true;
}
add_filter( 'custom_menu_order', 'pixopoint_toggle_menu_order' );

/**
 * Erase menu items
 * @since 1.0
 *function pixopoint_grunion_remove( $menu_order ){
	global $menu;

	foreach ( $menu as $mkey => $m ) {
		$key = array_search( 'edit.php?post_type=feedback', $m );

		if ( $key )
			unset( $menu[$mkey] );
	}

	return $menu_order;
}
add_filter( 'menu_order', 'pixopoint_grunion_remove' ); */
