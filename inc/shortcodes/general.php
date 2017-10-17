<?php

function year_shortcode() {
	$year = date('Y');
	return $year;
}
add_shortcode('year', 'year_shortcode');

function posted_date() {
	ob_start();
	?>
	<span class="post-date"> 
		Posted 
		<?php 
			echo date('F j, Y', strtotime(get_custom_field('jobpostdate'))); 
		?>
	</span> 
	<?php
	return ob_get_clean();
}
add_shortcode('posted_date', 'posted_date');

function cc_breadcrumbs() {
	ob_start();
	
	if (function_exists("bcn_display")):
		?>
		<div id="breadcrumbs">
			<?php
				bcn_display($return = false, $linked = true, $reverse = false);
			?>
		</div>
		<?php
	else: 
		echo "Please enable the <strong>Breadcrumb NavXT</strong> plugin.";
	endif;
	
	return ob_get_clean();
}
add_shortcode('breadcrumbs', 'cc_breadcrumbs');


function cc_share_this() {
	ob_start();
	
	if (function_exists("swp_initiate_plugin")):
		?>
		<div id="social-share" class="clearfix">
			<h4>Share This</h4>
			<?php echo do_shortcode('[social_warfare]'); ?>
		</div>
		<?php
	else: 
		echo "Please enable the <strong>Social Warfare</strong> plugin.";
	endif;
	
	return ob_get_clean();
}
add_shortcode('share_this','cc_share_this');

?>