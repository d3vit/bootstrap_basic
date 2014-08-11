<?php
/**
 * @package WordPress
 * @subpackage Classic_Theme
 */

if ( post_password_required() ) : ?>

<p>
  <?php _e('Enter your password to view comments.'); ?>
</p>
<?php return; endif; ?>
<br />
<h4 id="comments"><?php echo("Comments"); ?>
  <?php if ( comments_open() ) : ?>
  <a href="#postcomment" title="<?php _e("Leave a comment"); ?>">&raquo;</a>
  <?php endif; ?>
</h4>
<?php if ( have_comments() ) : ?>
<div id="commentlist">
  <?php foreach ($comments as $comment) : ?>
  <p> <span class="title">
    <?php comment_type(_x('Written', 'noun'), __('Trackback'), __('Pingback')); ?>
    <?php _e('by'); ?>
    <?php comment_author_link() ?>
    on
    <?php comment_date() ?>
    <?php edit_comment_link(__("Edit This"), ' |'); ?>
    </span>
    <?php comment_text() ?>
  </p>
  <?php endforeach; ?>
</div>
<?php else : // If there are no comments yet ?>
<p>
  <?php _e('No comments yet.'); ?>
</p>
<?php endif; ?>
<?php if ( comments_open() ) : ?>
<h2 id="postcomment">
  <?php _e('Leave your mark.');?>
</h2>
<br />
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url( get_permalink() ) );?></p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( is_user_logged_in() ) : ?>
  <?php printf(__('Logged in as %s.'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>'); ?> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account') ?>">
  <?php _e('Log out &raquo;'); ?>
  </a>
  </p>
  <br />
  <?php else : ?>
  <label for="author"><small>
    <?php _e('Name'); ?>
    <?php if ($req) _e('(required)'); ?>
    </small></label>
  <br />
  <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" class="blueBorder txt" style="margin:0;" />
  <br />
  <label for="email"><small>
    <?php _e('E-mail');?>
    <?php if ($req) _e('(required)'); ?>
    </small></label>
  <br />
  <input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" class="blueBorder txt" style="margin:0;"/>
  <br />
  <label for="url"><small>
    <?php _e('Website'); ?>
    </small></label>
  <br />
  <input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" class="blueBorder txt" style="margin:0;"/>
  <br />
  <?php endif; ?>
  <br />
  <textarea name="comment" id="comment" cols="35" rows="10" tabindex="4" class="blueBorder" style="margin:0;"></textarea>
  <br />
  <input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Submit Comment'); ?>" class="blueBorder submit" style="width: 200px;" />
  <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
  <?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If registration required and not logged in ?>
<?php else : // Comments are closed ?>
<p>
  <?php _e('Sorry, the comment form is closed at this time.'); ?>
</p>
<?php endif; ?>
