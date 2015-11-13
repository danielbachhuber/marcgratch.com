<?php

/*-----------------------------------------------------------------------------------*/
/* DEFAULT STYLE, AFTER THEME ACTIVATION */
/*-----------------------------------------------------------------------------------*/         

if ( ! function_exists( 'jaxlite_after_switch_theme' ) ) {

	function jaxlite_after_switch_theme () {
				
		wp_redirect(admin_url("customize.php"));

	}
	
	add_action('after_switch_theme', 'jaxlite_after_switch_theme');
	
}

/*-----------------------------------------------------------------------------------*/
/* GET ARCHIVE TITLE */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_get_the_archive_title')) {

	function jaxlite_get_archive_title() {
		
		if ( get_the_archive_title()  && ( get_the_archive_title() <> 'Archives' ) ) :
		
			return get_the_archive_title();
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* IS SINGLE */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_is_single')) {

	function jaxlite_is_single() {
		
		if ( is_single() || is_page() ) :
		
			return true;
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* POST CLASS */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_post_class')) {

	function jaxlite_post_class($classes) {	

		$masonry  = 'post-container masonry-element col-md-4';
		$standard = 'post-container col-md-12';
		
		if ( ( !jaxlite_is_single()) && ( is_home() ) ) {
			
			if ( ( !jaxlite_setting('jaxlite_home')) || ( jaxlite_setting('jaxlite_home') == "masonry" ) ) {

				$classes[] = $masonry;

			} else {

				$classes[] = $standard;

			}
			
		} else if ( ( !jaxlite_is_single()) && ( jaxlite_get_archive_title() ) ) {
			
			if ( ( !jaxlite_setting('jaxlite_category_layout')) || ( jaxlite_setting('jaxlite_category_layout') == "masonry" ) ) {

				$classes[] = $masonry;

			} else {

				$classes[] = $standard;

			}
			
		} else if ( ( !jaxlite_is_single()) && ( is_search() ) ) {
			
			if ( ( !jaxlite_setting('jaxlite_search_layout')) || ( jaxlite_setting('jaxlite_search_layout') == "masonry" ) ) {

				$classes[] = $masonry;

			} else {

				$classes[] = $standard;

			}
			
		} else if ( jaxlite_is_single() ) {

			$classes[] = 'post-container col-md-12';

		}
	
		return $classes;
		
	}
	
	add_filter('post_class', 'jaxlite_post_class');

}


/*-----------------------------------------------------------------------------------*/
/* VERSION */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_remove_version')) {

	function jaxlite_remove_version( $src ) {
	
		if ( strpos( $src, 'ver=' ) )
	
			$src = remove_query_arg( 'ver', $src );
	
		return $src;
	
	}

	add_filter( 'style_loader_src', 'jaxlite_remove_version', 9999 );
	add_filter( 'script_loader_src', 'jaxlite_remove_version', 9999 );

}

/*-----------------------------------------------------------------------------------*/
/* TAG TITLE */
/*-----------------------------------------------------------------------------------*/  

if ( ! function_exists( '_wp_render_title_tag' ) ) {

	function jaxlite_title( $title, $sep ) {
		
		global $paged, $page;
	
		if ( is_feed() )
			return $title;
	
		$title .= get_bloginfo( 'name' );
	
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";
	
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'jaxlite' ), max( $paged, $page ) );
	
		return $title;
		
	}

	add_filter( 'wp_title', 'jaxlite_title', 10, 2 );

	function jaxlite_addtitle() {
		
?>

	<title><?php wp_title( '|', true, 'right' ); ?></title>

<?php

	}

	add_action( 'wp_head', 'jaxlite_addtitle' );

}

/*-----------------------------------------------------------------------------------*/
/* THEME DATA */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_theme_data')) {

	function jaxlite_theme_data($id, $check = true ) {
		
		if ( ( wp_get_theme()->parent() == true ) && ( $check == true ) ) {

			$themedata = wp_get_theme()->parent();

		} else if ( ( wp_get_theme()->parent() == false ) && ( $check == true ) ) {

			$themedata = wp_get_theme();

		} else if ( $check == false ) {

			$themedata = wp_get_theme();

		}

		return $themedata->get($id);
		
	}
	
}

/*-----------------------------------------------------------------------------------*/
/* BODY CLASSES */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_body_classes_function')) {

	function jaxlite_body_classes_function( $classes ) {

		global $wp_customize;

		if ( jaxlite_setting('jaxlite_infinitescroll_system') == "on" ) :
		
			$classes[] = 'infinitescroll';
				
		endif;

		if ( ( jaxlite_is_single() ) && ( ( jaxlite_get_header_layout() == "header_five") || ( jaxlite_get_header_layout() == "header_six") ) ) :
		
			$classes[] = 'hide_title';
				
		endif;

		if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)) :

			$classes[] = 'ie_browser';
		
		endif;

		if ( isset( $wp_customize ) ) :

			$classes[] = 'customizer_active';
				
		endif;
	
		return $classes;

	}
	
	add_filter( 'body_class', 'jaxlite_body_classes_function' );

}

/*-----------------------------------------------------------------------------------*/
/* SIDEBAR NAME */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_sidebar_name')) {

	function jaxlite_sidebar_name( $type ) {

		$sidebars = array (
		
			"header" => array ( 
				"postmeta" => "jaxlite_header_sidebar",
				"default" => "header-sidebar-area"
			),
			
			"side" => array ( 
				"postmeta" => "jaxlite_sidebar",
				"default" => "side-sidebar-area"
			),
			
			"scroll" => array ( 
				"postmeta" => "jaxlite_scroll_sidebar",
				"default" => "scroll-sidebar-area"
			),
			
			"bottom" => array ( 
				"postmeta" => "jaxlite_bottom_sidebar",
				"default" => "bottom-sidebar-area"
			),
			
			"footer" => array ( 
				"postmeta" => "jaxlite_footer_sidebar",
				"default" => "footer-sidebar-area"
			),
			
		);
	
		if ( jaxlite_is_single() ) :
				
			$sidebar_name = jaxlite_postmeta($sidebars[$type]['postmeta']);
				
		else :

			$sidebar_name = $sidebars[$type]['default'];

		endif;
		
		return $sidebar_name;

	}

}

/*-----------------------------------------------------------------------------------*/
/* SIDEBAR LIST */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_sidebar_list')) {

	function jaxlite_sidebar_list($sidebar_type) {
		
		$default = array("none" => "None", $sidebar_type."-sidebar-area" => "Default");
			
		return $default;
			
	}

}

/*-----------------------------------------------------------------------------------*/
/* GET PAGED */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_paged')) {

	function jaxlite_paged() {
		
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		
		return $paged;
		
	}

}


/*-----------------------------------------------------------------------------------*/
/* EXCERPT MORE  */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_hide_excerpt_more')) {

	function jaxlite_hide_excerpt_more() {
		return '';
	}
	
	add_filter('the_content_more_link', 'jaxlite_hide_excerpt_more');
	add_filter('excerpt_more', 'jaxlite_hide_excerpt_more');

}

/*-----------------------------------------------------------------------------------*/
/* STYLES AND SCRIPTS */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_scripts_styles')) {

	function jaxlite_scripts_styles() {
	
		jaxlite_enqueue_style('/inc/css');

		if ( ( get_theme_mod('jaxlite_skin') ) && ( get_theme_mod('jaxlite_skin') <> "turquoise" ) ):
	
			wp_enqueue_style( 'jaxlite ' . get_theme_mod('jaxlite_skin') , get_template_directory_uri() . '/inc/skins/' . get_theme_mod('jaxlite_skin') . '.css' ); 
	
		endif;

		wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=PT+Sans|Montserrat:400,300,100,700' );

		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	
		wp_enqueue_script( "jquery-ui-core", array('jquery'));
		wp_enqueue_script( "jquery-ui-tabs", array('jquery'));
		wp_enqueue_script( "masonry", array('jquery') );

		jaxlite_enqueue_script('/inc/js');
		
	}
	
	add_action( 'wp_enqueue_scripts', 'jaxlite_scripts_styles', 11 );

}

?>