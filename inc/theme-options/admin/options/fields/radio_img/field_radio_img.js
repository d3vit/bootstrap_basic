/*
 *
 * lrl_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */
function lrl_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');

	jQuery('.lrl-radio-img-'+labelclass).removeClass('lrl-radio-img-selected');	
	
	jQuery('label[for="'+relid+'"]').addClass('lrl-radio-img-selected');
}//function