<?php
/*-----------------------------------------------------------------------------------*
* bootstrap_basic Image Resize Based on Aqua Resizer https://github.com/sy4mil/Aqua-Resizer

* Title		: Aqua Resizer
* Description	: Resizes WordPress images on the fly
* Version	: 1.1.6
* Author	: Syamil MJ
* Author URI	: http://aquagraphite.com
* License	: WTFPL - http://sam.zoy.org/wtfpl/
* Documentation	: https://github.com/sy4mil/Aqua-Resizer/
* 
* @param string $url - (required) must be uploaded using wp media uploader
* @param int $width - (required)
* @param int $height - (optional)
* @param bool $crop - (optional) default to soft crop
* @param bool $single - (optional) returns an array if false
* @uses wp_upload_dir()
* @uses image_resize_dimensions()
* @uses image_resize()
*
* @return str|array
/*-----------------------------------------------------------------------------------*/

function bootstrap_basic_resize( $url, $width, $height = null, $crop = null, $single = true ) {
	
	//Validate inputs
	if(!$url OR !$width ) return false;
	
	//Define upload path & dir
	$upload_info = wp_upload_dir();
	$upload_dir = $upload_info['basedir'];
	$upload_url = $upload_info['baseurl'];
	
	//Check if $img_url is local
	if(strpos( $url, $upload_url ) === false) return false;
	
	//Define path of image
	$rel_path = str_replace( $upload_url, '', $url);
	$img_path = $upload_dir . $rel_path;
	
	//Check if img path exists, and is an image indeed
	if( !file_exists($img_path) OR !getimagesize($img_path) ) return false;
	
	//Get image info
	$info = pathinfo($img_path);
	$ext = $info['extension'];
	list($orig_w,$orig_h) = getimagesize($img_path);
	
	//Get image size after cropping
	$dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
	$dst_w = $dims[4];
	$dst_h = $dims[5];
	
	//Use this to check if cropped image already exists, so we can return that instead
	$suffix = "{$dst_w}x{$dst_h}";
	$dst_rel_path = str_replace( '.'.$ext, '', $rel_path);
	$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
	
	if(!$dst_h) {
		//Can't resize, so return original url
		$img_url = $url;
		$dst_w = $orig_w;
		$dst_h = $orig_h;
	}
	//Else check if cache exists
	elseif(file_exists($destfilename) && getimagesize($destfilename)) {
		$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
	} 
	//Else, we resize the image and return the new resized image url
	else {
		
		// Note: This pre-3.5 fallback check will edited out in subsequent version
		if(function_exists('wp_get_image_editor')) {
		
			$editor = wp_get_image_editor($img_path);
			
			if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
				return false;
			
			$resized_file = $editor->save();
			
			if(!is_wp_error($resized_file)) {
				$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path']);
				$img_url = $upload_url . $resized_rel_path;
			} else {
				return false;
			}
			
		} else {
		
			$resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo
			if(!is_wp_error($resized_img_path)) {
				$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path);
				$img_url = $upload_url . $resized_rel_path;
			} else {
				return false;
			}
		
		}
		
	}
	
	//Return the output
	if($single) {
		//str return
		$image = $img_url;
	} else {
		//Array return
		$image = array (
			0 => $img_url,
			1 => $dst_w,
			2 => $dst_h
		);
	}
	
	return $image;
}


/*-----------------------------------------------------------------------------------*/
/* OUTPUT THE GALLERY WITH A SLIDER
/*-----------------------------------------------------------------------------------*/

/* Customized the output of caption, you can remove the filter to restore back to the WP default output. 
   Courtesy of DevPress. http://devpress.com/blog/captions-in-wordpress/ */
   
function bootstrap_basic_cleaner_caption( $output, $attr, $content ) {

     /* We're not worried about captions in feeds, so just return the output here. */
     if ( is_feed() )
          return $output;

     /* Set up the default arguments. */
     $defaults = array(
          'id' => '',
          'align' => 'alignnone',
          'width' => '',
          'caption' => ''
     );
	
	 $attr = '';
	 	
     /* Merge the defaults with user input. */
     $attr = shortcode_atts( $defaults, $attr );

     /* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
     if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
          return $content;

     /* Set up the attributes for the caption <div>. */
     $attributes .= ' class="figure ' . esc_attr( $attr['align'] ) . '"';

     /* Open the caption <div>. */
     $output = '<figure' . $attributes .'>';

     /* Allow shortcodes for the content the caption was created for. */
     $output .= do_shortcode( $content );

     /* Append the caption text. */
     $output .= '<figcaption>' . $attr['caption'] . '</figcaption>';

     /* Close the caption </div>. */
     $output .= '</figure>';

     /* Return the formatted, clean caption. */
     return $output;
}
add_filter( 'img_caption_shortcode', 'bootstrap_basic_cleaner_caption', 10, 3 );

// Clean the output of attributes of images in editor. Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
function image_tag_class($class, $id, $align, $size) {
     $align = 'align' . esc_attr($align);
     return $align;
}
add_filter('get_image_tag_class', 'image_tag_class', 0, 4);

function image_tag($html, $id, $alt, $title) {
     return preg_replace(array(
               '/\s+width="\d+"/i',
               '/\s+height="\d+"/i',
               '/alt=""/i'
          ),
          array(
               '',
               '',
               '',
               'alt="' . $title . '"'
          ),
          $html);
}
add_filter('get_image_tag', 'image_tag', 0, 4);


// Remove gallery shortcode styling
add_filter('gallery_style',
    create_function(
        '$css',
        'return preg_replace("#<style type=\'text/css\'>(.*?)</style>#s", "", $css);'
    )
);

// Img unautop, Courtesy of Interconnectit http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
function img_unautop($string) {
    $string = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $string);
    return $string;
}
add_filter( 'the_content', 'img_unautop', 30 );

if ( !function_exists( 'bootstrap_basic_gallery' ) ) {
    function bootstrap_basic_gallery($postid, $image_w, $image_h = null, $crop = null ) { 
     
    //Validate inputs
    if(!$postid OR !$image_w ) return false;
     
    $slide_id = $postid; 	 
    $thumbid = 0;
    
    // Get the featured image for the post
    if( has_post_thumbnail($postid) ) {
        $thumbid = get_post_thumbnail_id($postid);
    }
 
	
	// Get all of the attachments for the post
	$args = array(
	  'orderby' => 'menu_order',
	  'post_type' => 'attachment',
	  'post_parent' => $postid, 
	  'post_mime_type' => 'image',
	  'post_status' => null,
	  'numberposts' => -1
	);
	
	$attachments = get_posts($args);
	
	if( !empty($attachments) ) {
    
     ?>
    
	<script type="text/javascript">
		jQuery(document).ready(function($){
			jQuery('#slider-<?php echo $postid; ?>').flexslider({
				controlsContainer: ".flex-control-nav-container",
				direction: "horizontal",
				animation: "slide",
				slideshowSpeed: 3500, 
				animationLoop: true,
				animationSpeed: 300,
				randomize: true, 
				initDelay: 800, 
				directionNav: false,  
				pauseOnAction: true,
			 });
		
			jQuery('#slider-<?php echo $postid; ?>').css({ display : 'block' });
			jQuery('#slider-<?php echo $postid; ?>').addClass('loaded');
						
		});
	</script>
   
    <?php 
								
      echo "
		<!-- BEGIN #slider-$postid -->\n
		<div class='slider-wrapper'>
			
			<div class='post-slider'>
			
				 <div id='slider-$postid'>";
			          echo '
	          		    <ul class="slides">';
					          $i = 0;
					          foreach( $attachments as $attachment ) {
					              if( $attachment->ID == $thumbid ) continue;
					              
					              $src = wp_get_attachment_image_src( $attachment->ID, 'full' );
						              $image = bootstrap_basic_resize( $src[0], $image_w, $image_h, $crop ); //resize & crop the image
					              
					              $caption = $attachment->post_excerpt;
					              $caption = ($caption) ? "<div class='slider-desc'><span class='gallery-caption'>$caption</span></div>" : '';
					              $alt = ( !empty($attachment->post_content) ) ? $attachment->post_content : $attachment->post_title;
					              echo "<li>$caption<img src='$image' alt='$alt' /></li>";
					              
					              $i++;
					          }
					          echo '			
						</ul>
			          ';
			      		
						echo "
						</div>
						
					</div>
					
				</div><!-- END #slider-$postid -->\n";
		 }
	}
}

  
  
  
/*-----------------------------------------------------------------------------------*/
/*	OUTPUT AUDIO
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'bootstrap_audio' ) ) {

	function bootstrap_audio($postid) {

		$mp3 = get_post_meta($postid, '_bootstrap_basic_audio_mp3', TRUE);
		$ogg = get_post_meta($postid, '_bootstrap_basic_audio_ogg', TRUE);
		$poster = get_post_meta($postid, '_bootstrap_basic_audio_poster', TRUE);
		$height = get_post_meta($postid, '_bootstrap_basic_poster_height', TRUE);
		//$width = get_post_meta($postid, '_bootstrap_basic_poster_width', true);
		
		?>

			<script type="text/javascript">
			
				jQuery(document).ready(function(){
		
					if(jQuery().jPlayer) {
					
						jQuery('#jquery_jplayer_<?php echo $postid; ?>').jPlayer( { ready : function () { 
						
 								jQuery(this).jPlayer("setMedia", {
								    <?php if($poster != '') : ?>
								    poster: "<?php echo $poster; ?>",
								    <?php endif; ?>
								    <?php if($mp3 != '') : ?>
									mp3: "<?php echo $mp3; ?>",
									<?php endif; ?>
									<?php if($ogg != '') : ?>
									oga: "<?php echo $ogg; ?>",
									<?php endif; ?>
									end: ""
								});
							},
							<?php if( !empty($poster) ) { ?>
							size: {
							    width: "100%",
							    height: "375px"
							},
							<?php } ?>
							swfPath: "<?php echo bootstrap_JS_URL; ?>/",
							cssSelectorAncestor: "#jp_container_<?php echo $postid; ?>",
							supplied: "<?php if($ogg != '') : ?>oga,<?php endif; ?><?php if($mp3 != '') : ?>mp3, <?php endif; ?> all"
						});

					}
					
					jQuery("#jp_container_<?php echo $postid; ?> .jp-interface").css("display", "block");
					
				});
			</script>
			
			<style scoped>
			
				#jp_container_<?php echo $postid; ?>.jp-audio.fullwidth {
						padding-bottom: 50px !important;
						margin-bottom: 6px !important;
				}
					
			</style>
			
	<div id="jp_container_<?php echo $postid; ?>" class="jp-audio fullwidth">
		
		<div class="jp-type-single">
		
			<div id="jquery_jplayer_<?php echo $postid; ?>" class="jp-jplayer"></div>
		
			<div class="jp-gui">
			
				<div class="jp-audio-play"><a href="javascript:;" tabindex="1" title="Play"></a></div>
				
				<div class="jp-interface" style="display: none;">
					
					<div class="jp-progress">
					
						<div class="jp-seek-bar">
					
							<div class="jp-play-bar"></div>
					
						</div><!-- END .jp-seek-bar -->
					
					</div><!-- END .jp-progress -->
					
					<div class="jp-time-frame">
					
						<div class="jp-current-time"></div>
					
						<div class="jp-time-sep">/</div>
					
						<div class="jp-duration"></div>
					
					</div><!-- END .jp-time-frame -->
					
					<div class="jp-controls-holder">

						<ul class="jp-controls">
						
							<li><a href="javascript:;" class="jp-play" tabindex="1" title="Play"><span>Play</span></a></li>
							<li><a href="javascript:;" class="jp-pause" tabindex="1" title="Pause"><span>Pause</span></a></li>
							<li class="li-jp-stop"><a href="javascript:;" class="jp-stop" tabindex="1" title="Stop"><span>Stop</span></a></li>
						
						</ul><!-- END .jp-controls -->
						
						<div class="jp-volume-bar">
						
							<div class="jp-volume-bar-value"></div>
						
						</div><!-- END .jp-volume-bar -->
						
						<ul class="jp-toggles">
						
							<li><a href="javascript:;" class="jp-mute" tabindex="1" title="Mute"><span>Mute</span></a></li>
							<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="Unmute"><span>Unmute</span></a></li>
						
						</ul><!-- END .jp-toggles -->
						
					</div><!-- END .jp-controls-holder -->
					
				</div><!-- END .jp-interface -->
			
				<div class="jp-no-solution">
			 	
			 		<?php _e('<span>Update Required</span>To play the audio you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.','bootstrap') ?>
				
				</div><!-- END .jp-no-solution -->
			
			</div><!-- END .jp-gui -->
				
		</div><!-- END .jp-type-single -->
	
	</div><!-- END #jp_container_<?php echo $postid; ?> -->
		<?php 
	}
}





/*-----------------------------------------------------------------------------------*/
/* OUTPUT VIDEO
/*-----------------------------------------------------------------------------------*/
function bootstrap_basic_video($postid) {
	
	$height = get_post_meta($postid, '_bootstrap_basic_video_height', true);
	$m4v = get_post_meta($postid, '_bootstrap_basic_video_m4v', true);
	$ogv = get_post_meta($postid, '_bootstrap_basic_video_ogv', true);
	$poster = get_post_meta($postid, '_bootstrap_basic_video_poster', true);
	
	?>
 
 	<script type="text/javascript"> 
		
		jQuery(document).ready(function () { 
		
			jQuery('#jquery_jplayer_<?php echo $postid; ?>').jPlayer( { ready : function () { 
				jQuery(this).jPlayer(
					'setMedia', { 
							<?php if($m4v != '') : ?>
								m4v: "<?php echo $m4v; ?>",
							<?php endif; ?>
							<?php if($ogv != '') : ?>
								ogv: "<?php echo $ogv; ?>",
							<?php endif; ?>
							<?php if ($poster != '') : ?>
								poster: "<?php echo $poster; ?>"
							<?php endif; ?>
							} 
						); 
					}, 
					cssSelectorAncestor : '#jp_container_<?php echo $postid; ?>', 
					swfPath : '<?php echo bootstrap_JS_URL; ?>/', 
					supplied: "<?php if($m4v != '') : ?>m4v, <?php endif; ?><?php if($ogv != '') : ?>ogv, <?php endif; ?> all",
					size : { 
						width : '100%', 
						height : '100%' 
					},
					wmode : 'window'
					 
				} 
			);
			
			jQuery("#jp_container_<?php echo $postid; ?> .jp-interface").css("display", "block");
			 
		}); 
		
	</script>
			
		<div id="jp_container_<?php echo $postid; ?>" class="jp-video fullwidth">

			<div class="jp-type-single">
		
				<div id="jquery_jplayer_<?php echo $postid; ?>" class="jp-jplayer"></div>
			
				<div class="jp-gui">

					<div class="jp-interface" style="display: none;">
						
						<div class="jp-progress">
						
							<div class="jp-seek-bar">
						
								<div class="jp-play-bar"></div>
						
							</div><!-- END .jp-seek-bar -->
						
						</div><!-- END .jp-progress -->
						
						<div class="jp-current-time"></div>
						
						<div class="jp-time-sep">/</div>
						
						<div class="jp-duration"></div>
					
						<div class="jp-controls-holder">
							
							<ul class="jp-controls">
								
								<li><a href="javascript:;" class="jp-play" tabindex="1" title="Play"><span>Play</span></a></li>
								<li><a href="javascript:;" class="jp-pause" tabindex="1" title="Pause"><span>Pause</span></a></li>
								<li class="li-jp-stop"><a href="javascript:;" class="jp-stop" tabindex="1" title="Stop"><span>Stop</span></a></li>
							
							</ul><!-- END .jp-controls -->
							
							<div class="jp-volume-bar">
							
								<div class="jp-volume-bar-value"></div>
							
							</div><!-- END .jp-volume-bar -->
							
							<ul class="jp-toggles">
							
								<li><a href="javascript:;" class="jp-mute" tabindex="1" title="Mute"><span>Mute</span></a></li>
								<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="Unmute"><span>Unmute</span></a></li>
																
							</ul><!-- END .jp-toggles -->
							
						</div><!-- END .jp-controls-holder -->
						
					</div><!-- END .jp-interface -->
				
					<div class="jp-no-solution">
				 	
				 		<?php _e('<span>Update Required</span>To play the video you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.','bootstrap') ?>
					
					</div><!-- END .jp-no-solution -->
			
			</div><!-- END .jp-gui -->
				
		</div><!-- END .jp-type-single -->
	
	</div><!-- END #jp_container_<?php echo $postid; ?> -->
 	
<?php }


/*-----------------------------------------------------------------------------------*/
/* CONVERT A HEX DECIMAL COLOR CODE TO ITS RGB EQUIVALENT AND VICE VERSA
/*-----------------------------------------------------------------------------------*/
function bootstrap_basic_rgb2hex( $c ){
   if ( ! $c ) return false;
   $c = trim( $c );
   $out = false;
  if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $c) ){
      $c = str_replace( '#','', $c);
      $l = strlen( $c) == 3 ? 1 : (strlen( $c) == 6 ? 2 : false);

      if( $l){
         unset( $out);
         $out['red'] = hexdec(substr( $c, 0,1*$l) );
         $out['green'] = hexdec(substr( $c, 1*$l,1*$l) );
         $out['blue'] = hexdec(substr( $c, 2*$l,1*$l) );
      }else $out = false;
             
   }elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $c) ){
      $spr = str_replace(array( ',',' ','.' ), ':', $c);
      $e = explode(":", $spr);
      if(count( $e) != 3) return false;
         $out = '#';
         for( $i = 0; $i<3; $i++)
            $e[$i] = dechex( ( $e[$i] <= 0)?0:( ( $e[$i] >= 255)?255:$e[$i]) );
             
         for( $i = 0; $i<3; $i++)
            $out .= ( (strlen( $e[$i]) < 2)?'0':'' ).$e[$i];
                 
         $out = strtoupper( $out);
   }else $out = false;
         
   return $out;
}


/*-----------------------------------------------------------------------------------*/
/* PERFORM ADDING OR SUBTRACTING OPERATION ON A HEXADECIMAL COLOR CODE
/*-----------------------------------------------------------------------------------*/
function bootstrap_basic_hex_addition( $hex, $num ){
	$rgb = bootstrap_basic_rgb2hex( $hex );
	foreach ( $rgb as $key => $val ) {
		$rgb[$key] += $num;
		$rgb[$key] = ( $rgb[$key] < 0) ? 0 : $rgb[$key];
	}
	$hex = bootstrap_basic_rgb2hex( implode( ',', $rgb ) );
	
	return $hex;
}