<?php
	get_header();

	while (have_posts()): 
		the_post();
	?>
        <div class="section-page">
			<div class="container">
				<div class="row divide">
					<div class="col-md-12">
						<h1><?php the_title(); ?></h1>
                        <div class="storycontent">
                            <?php the_content(); ?>
                        </div>
					</div>

				</div> <!-- end row -->
            </div>    
		</div>
	<?
	endwhile;
	get_footer();

?>