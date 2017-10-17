<?php
/*-----------------------------------------------------------------------------------
	Pagination - Thanks to Kriesi for this code - http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
-----------------------------------------------------------------------------------*/
if(!function_exists('bootstrap_index_pagination')) {
	/**
	* Displays a page pagination if more posts are available than can be displayed on one page
	* @param string $pages pass the number of pages instead of letting the script check the global paged var
	* @return string $output returns the pagination html code
	*/
	function bootstrap_index_pagination($pages = '') {
		global $paged;
		
		if(get_query_var('paged')) {
		     $paged = get_query_var('paged');
		} elseif(get_query_var('page')) {
		     $paged = get_query_var('page');
		} else {
		     $paged = 1;
		}
		
		$output = "";
		$prev = $paged - 1;							
		$next = $paged + 1;	
		$range = 7; // only edit this if you want to show more page-links
		$showitems = ($range * 2)+1;
		
		if($pages == '')
		{	
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if(!$pages)
			{
				$pages = 1;
			}
		}
		
		$method = "get_pagenum_link";
		if(is_single())
		{
			$method = "lrl_post_pagination_link";
		}
		
		$archive_nav= "lrl_post_pagination_link";
		
		
		if(1 != $pages)
		{
			$output .= "<div class='index-pagination'>";
			
			$output .= ($paged > 2 && $paged > $range+1 && $showitems < $pages)? "<a href='".$method(1)."'></a>":"";
			
			$output .= "<span class='index-pager'>";
			
			$output .= ($paged > 1 )? "<span class='page-previous'><a href='".$method($prev)."'></a></span>":"<span class='page-previous inactive'><a href='#'></a></span>";
			
			$output .= "</span>";
 				
			$output .= "<span class='index-pager'>";
			
			$output .= ($paged < $pages ) ? "<span class='page-next'><a href='".$method($next)."'></a></span>" :"<span class='page-next inactive'><a href='#'></a></span>";
			
			$output .= "</span>\n";
			
			$output .= ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<a href='".$method($pages)."'></a>":"";
			
			$output .= "</div>\n";
			
			
		}
			
		return $output;
	}
	
	function lrl_post_pagination_link($link){
		$url =  preg_replace('!">$!','',_wp_link_page($link));
		$url =  preg_replace('!^<a href="!','',$url);
		return $url;
	}
}

?>