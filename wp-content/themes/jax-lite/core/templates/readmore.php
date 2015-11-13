<?php 

/**
 * Wp in Progress
 * 
 * @author WPinProgress
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!function_exists('jaxlite_excerpt_function')) {

	function jaxlite_excerpt_function() {
		
		global $post,$more;
		
		$more = 0;
	
		$class = 'button';
		$button = __('Read more',"jaxlite");

		if ( jaxlite_setting('jaxlite_readmore_button') == "off" ): 
	
			$class = 'more';
			$button = ' [...] ';
		
		endif;
	
		if ($pos=strpos($post->post_content, '<!--more-->')): 
		
			$content = substr(apply_filters( 'the_content', get_the_content()), 0, -5);
		
		else:
		
			$content = substr(apply_filters( 'the_excerpt', get_the_excerpt()), 0, -5);
		
		endif;
		
		echo $content. '<a class="'.$class.'" href="'.get_permalink($post->ID).'" title="More">'.$button.'</a></p>';
		
	}

	add_action( 'jaxlite_excerpt', 'jaxlite_excerpt_function', 10, 2 );

}

?>