/*
 *
 * bootstrap_basic_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function bootstrap_basic_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.bootstrap_basic-radio-img-'+labelclass).removeClass('bootstrap_basic-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('bootstrap_basic-radio-img-selected');
}//function