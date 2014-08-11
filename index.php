<?php
	get_header();
?>
<section id="blog-article" class="section-page blogroll">
	<div class="container">
    	<h1>The Blog
			<?php 
            if (is_category())
                printf( __( ' Archives: %s' ), '<span>' . single_cat_title( '', false ) . '</span>' ); 
            ?>
        </h1>
<?php 
	$cnt = 0;
	if (have_posts()) : while (have_posts()) : the_post(); 
?>
        
    <div class="row divide">
        <div class="col-md-12">
        	<?php
			if ($cnt > 0)
				echo '<div class="divider"></div>';
			else
				$cnt++;
			?>
            <h3 class="storytitle">
                <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
            </h3>
            <div class="storycontent">
                <?php the_content(__('(read more..)')); ?>
                <div class="clear"></div>
            </div>
            
            <div class="meta">
				<?php echo("Published "); ?><?php the_date(); ?> <?php edit_post_link(__('Edit This')); ?>
                <?php echo("&nbsp; [");?><?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments')); ?><?php echo("]");?>
                &nbsp; &ndash; &nbsp;
                Categories: <span class="gent"><?php the_category(', '); ?></span>
            </div>
             
		</div>
	</div> <!-- end row -->
            
<?php endwhile; else: ?>
    <p class="error">
    <?php _e('Sorry, no posts matched your criteria.'); ?>
    </p>
    <?php endif; ?>
    
    <p style="text-indent:20px;">
    <?php posts_nav_link(' &#8212; ', __('&laquo; Newer Posts'), __('Older Posts &raquo;')); ?>
    </p>



	</div>    <!-- End container -->
</section>
<?php	get_footer();

?>
