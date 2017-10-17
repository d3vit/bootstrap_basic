jQuery( document ).ready( function($) {

	var $labels = $('.rwmb-label-radio-image');

	// Highlight current selection
	$labels.click(function() {
		$labels.removeClass('selected');
		$(this).addClass('selected');
	});

});