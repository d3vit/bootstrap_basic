<?php
	get_header();

	while (have_posts()): 
		the_post();
	?>
        <div id="blog-article" class="section-page">
			<div class="container">
				<div class="row divide">
					<div class="col-md-12">
						<h1><?php the_title(); ?></h1>
                        <a href="/blog/" id="go-back">[Go Back]</a>
                        <div class="storycontent">
                            <?php the_content(); ?>
                        </div>
                        
                        
                        
                        <div id="comments">
                        	<a name="respond"></a>
                        	<?php comments_template(); ?>
                        </div>
                        
                        <div class="meta">
							<?php echo("Published "); ?><?php the_date(); ?> <?php edit_post_link(__('Edit This')); ?>
                            &nbsp; &ndash; &nbsp;
                            Categories: <span class="gent"><?php the_category(', '); ?></span>
                        </div>
                        
                        
					</div>
				
				</div> <!-- end row -->
            </div>    
		</div>
	<?
	endwhile;
	get_footer();

?>