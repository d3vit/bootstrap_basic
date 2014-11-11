<?php 
	get_header(); 
?>
        <div class="section-page">
			<div class="container">
				<div class="row divide">
					<div class="col-md-12">
						<h1><?php the_title(); ?></h1>
                        <div class="storycontent">
                            <?php woocommerce_content(); ?>
                        </div>
					</div>

				</div> <!-- end row -->
            </div>    
		</div>
<?php 
	get_footer();
?>   