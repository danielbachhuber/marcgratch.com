<?php

/*-----------------------------------------------------------------------------------*/
/* HEADER IMAGE */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_header_image')) {

	function jaxlite_header_image() {

		return get_header_image();
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* POST ICON */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_posticon')) {

	function jaxlite_posticon() {
	
		$icons = array (
				
			"video" => "genericon-video" , 
			"gallery" => "genericon-image" , 
			"audio" => "genericon-audio" , 
			"chat" => "genericon-chat", 
			"status" => "genericon-status", 
			"image" => "genericon-picture", 
			"quote" => "genericon-quote" , 
			"link" => "genericon-external", 
			"aside" => "genericon-aside"
			
		);
		
		if ( get_post_format() ) : 
			
			$icon = '<span class="genericon '.$icons[get_post_format()].'"></span> '.ucfirst(get_post_format()); 
			
		else:
			
			$icon = '<span class="genericon genericon-standard"></span> Standard'; 
			
		endif;
		
		return $icon;
		
	}

}

/*-----------------------------------------------------------------------------------*/
/* HEADER LAYOUT */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_get_header_layout')) {

	function jaxlite_get_header_layout() {

		if ( is_home() )  {
		
			$layout = "header_one";
			
		} else {

			$layout = "header_two";

		}
		
		return $layout;
	
	}

}


/*-----------------------------------------------------------------------------------*/
/* LOGIN AREA */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_custom_login_logo')) {
	
	function jaxlite_custom_login_logo() { 
	
		if ( jaxlite_setting('jaxlite_login_logo') ) : ?>
	
			<style type="text/css">
				
				body.login div#login h1 a {
					background-image: url('<?php echo jaxlite_setting('jaxlite_login_logo'); ?>');
					-webkit-background-size: inherit;
					background-size: inherit ;
					width:100%;
					height:<?php echo jaxlite_setting('jaxlite_login_logo_height'); ?>;
				}
				
			</style>
		
<?php 
	
		endif;
	
	}
	
	add_action( 'login_enqueue_scripts', 'jaxlite_custom_login_logo' );

}

/*-----------------------------------------------------------------------------------*/
/* Content template */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_template')) {

	function jaxlite_template($id) {
	
		$template = array ("full" => "col-md-12" , "left-sidebar" => "col-md-8" , "right-sidebar" => "col-md-8" );
	
		$span = $template["right-sidebar"];
		$sidebar =  "right-sidebar";
	
		if  ( ( (is_category()) || (is_tag()) || (is_tax()) || (is_month() ) ) && (jaxlite_setting('jaxlite_category_layout')) ) {
			
			$span = $template[jaxlite_setting('jaxlite_category_layout')];
			$sidebar =  jaxlite_setting('jaxlite_category_layout');
				
		} else if ( (is_home()) && (jaxlite_setting('jaxlite_home')) ) {
			
			$span = $template[jaxlite_setting('jaxlite_home')];
			$sidebar =  jaxlite_setting('jaxlite_home');
			
		} else if ( (is_search()) && (jaxlite_setting('jaxlite_search_layout')) ) {
			
			$span = $template[jaxlite_setting('jaxlite_search_layout')];
			$sidebar =  jaxlite_setting('jaxlite_search_layout');
			
		} else if ( ( (is_single()) || (is_page()) ) && (jaxlite_postmeta('jaxlite_template')) ) {
			
			$span = $template[jaxlite_postmeta('jaxlite_template')];
			$sidebar =  jaxlite_postmeta('jaxlite_template');
				
		}
	
		return ${$id};
		
	}

}

/*-----------------------------------------------------------------------------------*/
/* PRETTYPHOTO */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_prettyPhoto')) {

	function jaxlite_prettyPhoto( $html, $id, $size, $permalink, $icon, $text ) {
		
		if ( ! $permalink )
			return str_replace( '<a', '<a rel="prettyPhoto" ', $html );
		else
			return $html;
	
	}
	
	add_filter( 'wp_get_attachment_link', 'jaxlite_prettyPhoto', 10, 6);
	
}

/*-----------------------------------------------------------------------------------*/
/* REMOVE CATEGORY LIST REL */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_remove_category_list_rel')) {

	function jaxlite_remove_category_list_rel($output) {
		$output = str_replace('rel="category"', '', $output);
		return $output;
	}
	
	add_filter('wp_list_categories', 'jaxlite_remove_category_list_rel');
	add_filter('the_category', 'jaxlite_remove_category_list_rel');

}

/*-----------------------------------------------------------------------------------*/
/* REMOVE THUMBNAIL DIMENSION */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_remove_thumbnail_dimensions')) {

	function jaxlite_remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
		return $html;
	}
	
	add_filter( 'post_thumbnail_html', 'jaxlite_remove_thumbnail_dimensions', 10, 3 );

}

/*-----------------------------------------------------------------------------------*/
/* REMOVE CSS GALLERY */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_my_gallery_style')) {

	function jaxlite_my_gallery_style() {
		return "<div class='gallery'>";
	}
	
	add_filter( 'gallery_style', 'jaxlite_my_gallery_style', 99 );
	
}


?>