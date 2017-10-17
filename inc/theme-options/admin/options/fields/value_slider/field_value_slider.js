jQuery(document).ready(function($){
 	
	/*
	 *
	 * lrl_Options_value_slider function
	 * Adds a value slider functionality to the page
	 *
	 */
	 
  $(".lrl-opts-value-slider").each(function() {
  		var $this = $(this),
  			min = ($this.attr('data-min')) ? parseFloat($this.attr('data-min')) : 0,
  			max = ($this.attr('data-max')) ? parseFloat($this.attr('data-max')) : 100,
  			unit = $this.attr('data-unit'),
  			name = $this.attr('data-name'),
  			value = parseFloat($this.attr('data-value')),
  			$input = $('<input type="hidden" name="'+name+'" value="'+value+'" />'),
  			$span = $('<span class="lrl-opts-value-slider-description">'+value+unit+'</span>');
  
  		$this.slider({
  			min: min,
  			max: max,
  			value: value,
  			range: 'min',
  			slide: function(ev, ui) {
  				$input.val(ui.value);
  				$span.html(ui.value+unit);
  			}
  		});
   
  		$input.prependTo($this.parent());
  		$span.prependTo($this.parent());
  	});
  	
});  	