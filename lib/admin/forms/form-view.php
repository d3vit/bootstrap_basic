<?php
/**
 * Template for Form Builder.
 */

wp_register_script( 'bootstrap_basic_form', BOOTSTRAP_FORMS_DIR . '/js/bootstrap_basic_form.js', array( 'jquery-ui-sortable', 'jquery-ui-draggable' ), '1.0' );
wp_localize_script( 'bootstrap_basic_form', 'GrunionFB_i18n', array(
	'nameLabel' => esc_attr( _x( 'Name', 'Label for HTML form "Name" field in contact form builder', 'bootstrap' ) ),
	'emailLabel' => esc_attr( _x( 'Email', 'Label for HTML form "Email" field in contact form builder', 'bootstrap' ) ),
	'urlLabel' => esc_attr( _x( 'Website', 'Label for HTML form "URL/Website" field in contact form builder', 'bootstrap' ) ),
	'commentLabel' => esc_attr( _x( 'Comment', 'Label for HTML form "Comment/Response" field in contact form builder', 'bootstrap' ) ),
	'newLabel' => esc_attr( _x( 'New Field', 'Default label for new HTML form field in contact form builder', 'bootstrap' ) ),
	'optionsLabel' => esc_attr( _x( 'Options', 'Label for the set of options to be included in a user-created dropdown in contact form builder', 'bootstrap' ) ),
	'optionsLabel' => esc_attr( _x( 'Option', 'Label for an option to be included in a user-created dropdown in contact form builder', 'bootstrap' ) ),
	'firstOptionLabel' => esc_attr( _x( 'First option', 'Default label for the first option to be included in a user-created dropdown in contact form builder', 'bootstrap' ) ),
	'problemGeneratingForm' => esc_attr( _x( "Oops, there was a problem generating your form.  You'll likely need to try again.", 'error message in contact form builder', 'bootstrap' ) ),
	'moveInstructions' => esc_attr__( "Drag up or down", 'bootstrap' ),
	'moveLabel' => esc_attr( _x( 'Move', 'Label to drag HTML form fields around to change their order in contact form builder', 'bootstrap' ) ),
	'editLabel' => esc_attr( _x( 'Edit', 'Link to edit an HTML form field in contact form builder', 'bootstrap' ) ),
	'savedMessage' => esc_attr__( 'Saved successfully', 'bootstrap' ),
	'requiredLabel' => esc_attr( _x( '(required)', 'This HTML form field is marked as required by the user in contact form builder', 'bootstrap' ) ),
	'exitConfirmMessage' => esc_attr__( 'Are you sure you want to exit the form editor without saving?  Any changes you have made will be lost.', 'bootstrap' ),
) );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php esc_html_e( 'Contact Form', 'bootstrap' ); ?></title>
<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	var postId = <?php echo absint( $_GET['post_id'] ); ?>;
	var ajax_nonce_shortcode = '<?php echo wp_create_nonce( 'bootstrap_basic_form_shortcode' ); ?>';
	var ajax_nonce_json = '<?php echo wp_create_nonce( 'bootstrap_basic_form_shortcode_to_json' ); ?>';
</script>
<?php wp_print_scripts( 'bootstrap_basic_form' ); ?>
<script type="text/javascript">
	jQuery(document).ready(function () {
		FB.ContactForm.init();
		FB.ContactForm.resizePop();
	});
	jQuery(window).resize(function() {
	  	setTimeout(function () { FB.ContactForm.resizePop(); }, 50);
	});
</script>

<style>
	/* Reset */
	html { height: 100%; }
	body, div, ul, ol, li, h1, h2, h3, h4, h5, h6, form, fieldset, legend, input, button, textarea, p, blockquote, th, td { margin: 0; padding: 0; }
	body { background: #F9F9F9; font-family:Helvetica,Verdana,Arial,"Bitstream Vera Sans",sans-serif; 
	font-size:12px; color: #333; line-height:1.5em; height: 100%; width: 100%; padding-bottom: 20px !important; }
	a { color: #21759B; text-decoration: none; }
	a:hover { text-decoration: underline; text-shadow: none !important; }
	h1 { font-size: 21px; color:#5A5A5A; font-weight:normal; margin-bottom: 21px; margin-top: 10px;
	}
	h3 { font-size: 13px; color: #333; margin-bottom: 10px; }
	input { width: 298px; }
		.fb-form-case textarea,
	input[type='text'] { 
		padding: 6px 7px; 
		margin-right: 4px;  
		-webkit-border-radius:2px; 
		   -moz-border-radius:2px;
		        border-radius:2px; 
		border: 1px solid #DFDFDF; 
		}
	
	
	.fb-form-case textarea:focus, 
	input[type='text']:focus { border: 1px solid #bababa; outline: 0 !important; }
	input[type='checkbox'], input[type='radio'] { width: auto !important; float: left; margin-top: 3px; }
	input[type='radio'] { margin-right: 8px; }
	input.fieldError, select.fieldError, textarea.fieldError { border: 1px solid #D56F55; }
	img { border: none; }
	label { color: #222; font-weight: bold; display: block; margin-bottom: 4px; }
	label.radio { width: auto; margin: -2px 0 0 5px; }
	label span.label-required { color: #AAA; margin-left: 4px; font-weight: normal; }
	td { vertical-align: top; }
	select { width: 300px; }
	textarea { height: 100px; width: 298px; }
	/* Core */
	#media-upload-header { border-bottom: 1px solid #DFDFDF; font-weight:bold; margin:0; padding:17px 5px 0 5px; position:relative; background: #FFF; }
	#sidemenu { bottom:-1px; font-size:12px; list-style:none outside none; padding-left:10px; position:relative; left:0; margin:0 5px; overflow:hidden; }
	#sidemenu a { text-decoration:none; border-top: 1px solid #FFF; display:block; float:left; line-height:28px; padding:0 13px; outline: none; font-weight:normal;
	border: 1px solid transparent;
	}
	#sidemenu a.current { 
		background-color:#F9F9F9; 
		border-color:#DFDFDF #DFDFDF #F9F9F9; 
		color:#D54E21; 
		-moz-border-radius:4px 4px 0 0; 
		border-radius:4px 4px 0 0; 
		-webkit-border-radius:4px 4px 0 0; 
		border-style:solid; 
		border-width:1px; 
		font-weight:normal; }
	#sidemenu li { display:inline; margin-bottom:6px; line-height:200%; list-style:none outside none; margin:0; padding:0; text-align:center; white-space:nowrap; }
	.button { background-color:#FFFFFF; background:url("<?php echo home_url(); ?>/wp-admin/images/white-grad.png") repeat-x scroll left top #F2F2F2; border-color:#BBBBBB; min-width:80px; text-align:center; color:#464646; text-shadow:0 1px 0 #FFFFFF; border-style:solid; border-width:1px; cursor:pointer; width: auto; font-size:11px !important; line-height:13px; padding:3px 11px; margin-top: 12px; text-decoration:none; -moz-border-radius:11px; border-radius:11px; -webkit-border-radius:11px }
	
	
	.button-primary { font:  Helvetica, Arial,Geneva, sans-serif; background-color:#FFFFFF; font-weight: bold; background: url('<?php echo home_url(); ?>/wp-admin/images/button-grad.png') repeat-x scroll left top #21759B; border-color:#298CBA; text-align:center; color:#EAF2FA; text-shadow:0 -1px 0 rgba(0, 0, 0, 0.3); border-style:solid; border-width:1px; cursor:pointer; width: auto; font-size:11px!important; line-height:16px; padding:3px 15px; margin-top: 21px; text-decoration:none; -moz-border-radius:20px; border-radius:20px; -webkit-border-radius:20px }
	
	.button-primary:hover { border-color: #13455b; }
	.clear { clear: both; }
	.fb-add-field { padding-left: 10px; }
	.fb-add-option { margin: 0 0 14px 100px; }
	.fb-container { margin: 21px; padding-bottom: 20px; }
	.fb-desc, #fb-add-field { margin-top: 47px; }
	.fb-extra-fields { margin-bottom: 2px; }
	
	.fb-form-case-email,
	.fb-form-case { background: #FFF; padding: 13px; border: 1px solid #E2E2E2; width: 336px; -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px }
	
	.fb-form-case-email { padding: 23px 3px 26px 23px; }
	
	.fb-form-case a { outline: none; }
	
	.fb-form-case input[type='text'], 
	
	.fb-form-case textarea { background: #FFF;  }
	
	.fb-radio-label { display: inline-block; margin-left: 8px; float: left; width: 290px; }
	
	.fb-new-fields { position: relative;  background: #FFF; padding: 10px 10px 15px 10px; cursor: default; }
	
	.fb-new-fields:hover { 
		background: #ecf1f5;
		-webkit-border-radius:2px; 
	       -moz-border-radius:2px;
	            border-radius:2px;
	            }
	
	.fb-options { width: 170px !important; }
	.fb-remove { background: url('<?php echo BOOTSTRAP_FORMS_DIR; ?>/images/remove-field.gif') no-repeat; position: absolute; cursor: pointer !important; right: -26px; top: 33px; width: 20px; height: 23px; }
	.fb-remove:hover { background: url('<?php echo BOOTSTRAP_FORMS_DIR; ?>/images/remove-field-hover.gif') no-repeat; }
	.fb-remove-small { top: 2px !important; }
	.fb-remove-option { position: absolute; top: 3px; right: 10px; width: 20px; height: 23px; background: url('<?php echo BOOTSTRAP_FORMS_DIR; ?>/images/remove-option.gif') no-repeat; }
	.fb-remove-option:hover { background: url('<?php echo BOOTSTRAP_FORMS_DIR; ?>/images/remove-option-hover.gif') no-repeat; }
	.fb-reorder { cursor: move; position: relative; }
	.fb-reorder:hover div { display: block !important; width: 130px !important; position: absolute; top: 0; right: 0; z-index: 200; padding: 5px 10px; color: #555; font-size: 11px; background: #FFF; border: 1px solid #CCC; -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px; }
	.fb-right { position: absolute; right: 0; top: 0; width: 315px; margin: 57px 21px 0 0; }
	.fb-right .fb-new-fields { border: none; background: #F9F9F9; padding: 0; }
	.fb-right input[type='text'] { width: 195px; margin-bottom: 14px; }
	.fb-right label { color: #444; width: 100px; float: left; font-weight: normal; }
	.fb-right select { width: 150px !important; margin-bottom: 14px; }
	.fb-right textarea { margin-bottom: 13px; }
	.fb-right p { color: #999; line-height: 22px; }
	.fb-settings input[type='text'], .fb-settings textarea { background-image: none !important; }
	.fb-success { position: absolute; top: -3px; right: 100px; padding: 6px 23px 4px 23px; background: #FFFFE0; font-weight: normal; border: 1px solid #E6DB55; color: #333; -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px; }
	.right { float: right; }
</style>

</head>

<body>
	<div id="media-upload-header">
		<div id="fb-success" class="fb-success" style="display: none;"><?php esc_html_e( 'Your new field was saved successfully', 'bootstrap' ); ?></div>
		<ul id="sidemenu">
			<li id="tab-preview"><a class="current" href=""><?php esc_html_e( 'Form Builder', 'bootstrap' ); ?></a></li>
			<li id="tab-settings"><a href=""><?php esc_html_e( 'Email Notification Setup', 'bootstrap' ); ?></a></li>
		</ul>
	</div>
	<div class="fb-right">
		<div id="fb-desc" class="fb-desc">
			<h3><?php esc_html_e( 'How does this work?', 'bootstrap' ); ?></h3>
			<p><?php esc_html_e( 'By adding a custom form, your viewers will be able to submit responses and feedback to you. All responses are automatically scanned for spam, and the legitimate responses will be emailed to you.', 'bootstrap' ); ?></p>
			<h3 style="margin-top: 21px;"><?php esc_html_e( 'Can I add more fields?', 'bootstrap' ); ?></h3>
			<p><?php printf(
				esc_html( _x( '%1$s to add a new text box, textarea, radio, checkbox, or dropdown field.', '%1$s = "Click here" in an HTML link', 'bootstrap' ) ),
				'<a href="#" class="fb-add-field" style="padding-left: 0;">' . esc_html__( 'Click here', 'bootstrap' ) . '</a>'
			); ?></p>
			<h3 style="margin-top: 21px;"><?php esc_html_e( 'Can I view my feedback within WordPress?', 'bootstrap' ); ?></h3>
			<p><?php printf(
				esc_html( _x( 'You can check your form responses at any time by clicking the "%1$s" link in the admin menu.', '%1$s = "Responses" in an HTML link', 'bootstrap' ) ),
				'<a id="fb-feedback" href="' . admin_url( 'edit.php?post_type=feedback' ) . '">' . esc_html__( 'Responses', 'bootstrap' ) . '</a>'
			); ?></p>
			<div class="clear"></div>
		</div>
		<div id="fb-email-desc" class="fb-desc" style="display: none;">
			<h3><?php esc_html_e( 'Do I need to fill this out?', 'bootstrap' ); ?></h3>
			<p><?php esc_html_e( 'If you do not make any changes here, feedback will be sent to the author of the page/post and the subject will be the name of this page/post.', 'bootstrap' ); ?></p>
			<div class="clear"></div>
		</div>
		<div id="fb-add-field" style="display: none;">
			<h3><?php esc_html_e( 'Edit This Field:', 'bootstrap' ); ?></h3>
			
			<label for="fb-new-label"><?php esc_html_e( 'Field Label:', 'bootstrap' ); ?></label>
			<input type="text" id="fb-new-label" value="<?php esc_attr_e( 'New field', 'bootstrap' ); ?>" />
			
			<label for="fb-new-label"><?php esc_html_e( 'Field Type:', 'bootstrap' ); ?></label>
			<select id="fb-new-type">
				<option value="checkbox"><?php esc_html_e( 'Checkbox', 'bootstrap' ); ?></option>
				<option value="select"><?php esc_html_e( 'Drop down', 'bootstrap' ); ?></option>
				<option value="email"><?php esc_html_e( 'Email', 'bootstrap' ); ?></option>
				<option value="name"><?php esc_html_e( 'Name', 'bootstrap' ); ?></option>
				<option value="radio"><?php esc_html_e( 'Radio', 'bootstrap' ); ?></option>
				<option value="text" selected="selected"><?php esc_html_e( 'Text', 'bootstrap' ); ?></option>
				<option value="textarea"><?php esc_html_e( 'Textarea', 'bootstrap' ); ?></option>
				<option value="url"><?php esc_html_e( 'Website', 'bootstrap' ); ?></option>
			</select>
			<div class="clear"></div>
			
			<div id="fb-options" style="display: none;">
				<div id="fb-new-options">
					<label for="fb-option0"><?php esc_html_e( 'Options', 'bootstrap' ); ?></label>
					<input type="text" id="fb-option0" optionid="0" value="<?php esc_attr_e( 'First option', 'bootstrap' ); ?>" class="fb-options" />
				</div>
				<div id="fb-add-option" class="fb-add-option">
					<a href="#" id="fb-another-option"><?php esc_html_e( 'Add another option', 'bootstrap' ); ?></a>
				</div>
			</div>
			
			<div class="fb-required">
				<label for="fb-new-label"></label>
				<input type="checkbox" id="fb-new-required" />
				<label for="fb-new-label" class="fb-radio-label"><?php esc_html_e( 'Required?', 'bootstrap' ); ?></label>
				<div class="clear"></div>
			</div>
			
			<input type="hidden" id="fb-field-id" />
			<input type="submit" class="button" value="<?php esc_attr_e( 'Save this Field', 'bootstrap' ); ?>" id="fb-save-field" name="save">
		</div>
	</div>
	<form id="fb-preview">
		<div id="fb-preview-form" class="fb-container">
			<h1><?php esc_html_e( 'Create your Custom Form', 'bootstrap' ); ?></h1>
			<div id="sortable" class="fb-form-case">
				
				<div id="fb-extra-fields" class="fb-extra-fields"></div>
				
				<a href="#" id="fb-new-field" class="fb-add-field"><?php esc_html_e( 'Add a new field', 'bootstrap' ); ?></a>
			</div>
			<input type="submit" class="button-primary" tabindex="4" value="<?php esc_attr_e( 'Add this Form', 'bootstrap' ); ?>" id="fb-save-form" name="save">
		</div>
		<div id="fb-email-settings" class="fb-container" style="display: none;">
			<h1><?php esc_html_e( 'Email Notification Setup', 'bootstrap' ); ?></h1>
			<div class="fb-form-case-email fb-settings">
				<label for="fb-fieldname"><?php esc_html_e( 'Notification Email Address:', 'bootstrap' ); ?></label>
				<input type="text" id="fb-field-my-email" style="background: #FFF !important;" />

				<label for="fb-fieldemail" style="margin-top: 14px;"><?php esc_html_e( 'Subject Line:', 'bootstrap' ); ?></label>
				<input type="text" id="fb-field-subject" style="background: #FFF !important;" />
			</div>
			<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'bootstrap' ); ?>" id="fb-prev-form" name="save">
		</div>
	</form>
</body>
</html>
