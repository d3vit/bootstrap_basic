<?php

/*
 * Pulls a 3 column blogroll. 
 * To customize: simply change the col-md-4 class below.
 */
function bb_blogroll() {
	
	ob_start();
	
	?>

	<div id="blogroll" class="clearfix">

		<?php
			$meta_query = array();
			
			$args = array( 
						'post_type' 		=> 'post', 
						'posts_per_page' 	=> 3,
						'order'				=> 'DESC', 
						'orderby'			=> 'post_date',
					);


			$loop = new WP_Query( $args );
			$cnt = 1;
			
			
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
			<div class="blogroll-post col-md-4">
				<div class="blogroll-post-inner">
					<div class="post-image">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); ?>
						</a>
					</div>
					<div class="blogroll-post-content">
						<div class="blogroll-post-category">
							<?php 
								$categories = get_the_category();

								$cnt = 1;
								foreach ($categories as $cat):
									echo "<a href='/blog/{$cat->slug}'>{$cat->name}</a>";
									if ($cnt != sizeof($categories))
										echo ", ";
									$cnt++;
								endforeach;
							?>
						</div>
						<h4>
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h4>
						<a href="<?php the_permalink(); ?>" class="blogroll-post-link">
							<strong>View Post</strong>
						</a>
					</div>
				</div>
			</div>
		<?php
				$cnt++;
			endwhile;
			wp_reset_query();

		?>
	</div> 

	<?php
	$content = ob_get_clean();
	return $content;
}
add_shortcode('blogroll','bb_blogroll');


/*
 * Uses the category from the current video to pull related videos.
 * Similar functionality may be provided by Jetpack, however this gives you
 * full control over the display.
 */
function bb_display_related_posts() {
	
	ob_start();
	
	?>

	<div id="related-posts" class="related-content">
	
		<h2>Related Posts</h2>
		<hr />
		
		<?php
			$meta_query = array();
			$categories = wp_get_post_categories(get_the_ID());
			
			$args = array( 
						'post_type' 		=> 'post', 
						'posts_per_page' 	=> 3,
						'order'				=> 'DESC', 
						'orderby'			=> 'post_date',
						'category'         	=> $categories ,
						'post__not_in' 		=> array(get_the_ID())
					);


			$loop = new WP_Query( $args );
			$cnt = 1;
			
			
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
			<div class="blogroll-post col-md-4">
				<div class="blogroll-post-inner">
					<div class="post-image">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); ?>
						</a>
					</div>
					<div class="blogroll-post-content">
						<div class="blogroll-post-category">
							<?php 
								$categories = get_the_category();

								$cnt = 1;
								foreach ($categories as $cat):
									echo "<a href='/blog/{$cat->slug}'>{$cat->name}</a>";
									if ($cnt != sizeof($categories))
										echo ", ";
									$cnt++;
								endforeach;
							?>
						</div>
						<h3>
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h3>
						<hr />
						<a href="<?php the_permalink(); ?>">
							<strong>View Post</strong>
						</a>
					</div>
				</div>
			</div>
		<?php
				$cnt++;
			endwhile;
			wp_reset_query();

		?>
	</div> 

	<?php
	$content = ob_get_clean();
	return $content;
}
add_shortcode('related_posts','bb_display_related_posts');

?>