<?php

/**
 * Wp in Progress
 * 
 * @package Jax Lite
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

/*-----------------------------------------------------------------------------------*/
/* THEME SETTINGS */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_setting')) {

	function jaxlite_setting($id) {
	
		$jaxlite_setting = get_theme_mod($id);
			
		if(isset($jaxlite_setting))
			return $jaxlite_setting;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* POST META */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_postmeta')) {

	function jaxlite_postmeta($id) {
	
		global $post;
		
		$val = get_post_meta( $post->ID , $id, TRUE);
			
		if ( isset($val) ) :
			
			return $val; 
				
		else :
		
			return null;
		
		endif;
			
		
	}

}

/*-----------------------------------------------------------------------------------*/
/* THEME NAME */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_themename')) {

	function jaxlite_themename() {
		
		$themename = "jax_theme_settings";
		return $themename;	
		
	}
	
}

/*-----------------------------------------------------------------------------------*/
/* REQUIRE */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_require')) {

	function jaxlite_require($folder) {
	
		if (isset($folder)) : 
	
			if ( ( !jaxlite_setting('jaxlite_loadsystem') ) || ( jaxlite_setting('jaxlite_loadsystem') == "mode_a" ) ) {
		
				$folder = dirname(dirname(__FILE__)) . $folder ;  
				
				$files = scandir($folder);  
				  
				foreach ($files as $key => $name) {  
				
					if ( (!is_dir($name)) && ( $name <> ".DS_Store" ) ) { 
					
						require_once $folder . $name;
					
					} 
				}  
			
			} else if ( jaxlite_setting('jaxlite_loadsystem') == "mode_b" ) {
	
	
				$dh  = opendir(get_template_directory().$folder);
				
				while (false !== ($filename = readdir($dh))) {
				   
					if ( ( strlen($filename) > 2 ) && ( $filename <> ".DS_Store" ) ) {
					
						require_once get_template_directory()."/".$folder.$filename;
					
					}
				}
			}
		
		endif;
		
	}

}

/*-----------------------------------------------------------------------------------*/
/* SCRIPTS */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_enqueue_script')) {

	function jaxlite_enqueue_script($folder) {
	
		if (isset($folder)) : 
	
			if ( ( !jaxlite_setting('jaxlite_loadsystem') ) || ( jaxlite_setting('jaxlite_loadsystem') == "mode_a" ) ) {
		
				$dir = dirname(dirname(__FILE__)) . $folder ;  
				
				$files = scandir($dir);  
				  
				foreach ($files as $key => $name) {  

					if ( (!is_dir($name)) && ( $name <> ".DS_Store" ) ) { 
						
						wp_enqueue_script( str_replace('.js','',$name), get_template_directory_uri() . $folder . "/" . $name , array('jquery'), FALSE, TRUE ); 
						
					} 
				}  
			
			} else if ( jaxlite_setting('jaxlite_loadsystem') == "mode_b" ) {
	
				$dh  = opendir(get_template_directory().$folder);
				
				while (false !== ($filename = readdir($dh))) {
				   
					if ( ( strlen($filename) > 2 ) && ( $filename <> ".DS_Store" ) ) {
						
						wp_enqueue_script( str_replace('.js','',$filename), get_template_directory_uri() . $folder . "/" . $filename , array('jquery'), FALSE, TRUE ); 
					
					}
					
				}
		
			}
			
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* STYLES */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('jaxlite_enqueue_style')) {

	function jaxlite_enqueue_style($folder) {
	
		if (isset($folder)) : 
	
			if ( ( !jaxlite_setting('jaxlite_loadsystem') ) || ( jaxlite_setting('jaxlite_loadsystem') == "mode_a" ) ) {
			
				$dir = dirname(dirname(__FILE__)) . $folder ;  
				
				$files = scandir($dir);  
				  
				foreach ($files as $key => $name) {  
					
					if ( (!is_dir($name)) && ( $name <> ".DS_Store" ) ) { 
						
						wp_enqueue_style( str_replace('.css','',$name), get_template_directory_uri() . $folder . "/" . $name ); 
						
					} 
				}  
			
			
			} else if ( jaxlite_setting('jaxlite_loadsystem') == "mode_b" ) {
	
			
				$dh  = opendir(get_template_directory().$folder);
				
				while (false !== ($filename = readdir($dh))) {
				   
					if ( ( strlen($filename) > 2 ) && ( $filename <> ".DS_Store" ) ) {
						
						wp_enqueue_style( str_replace('.css','',$filename), get_template_directory_uri() . $folder . "/" . $filename ); 
				
					}
				
				}
			
			}
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* THUMBNAILS */
/*-----------------------------------------------------------------------------------*/         

if (!function_exists('jaxlite_get_width')) {

	function jaxlite_get_width() {
		
		if ( jaxlite_setting('jaxlite_screen3') ):
			return jaxlite_setting('jaxlite_screen3');
		else:
			return "940";
		endif;
	
	}

}

if (!function_exists('jaxlite_get_height')) {

	function jaxlite_get_height() {
		
		if ( jaxlite_setting('jaxlite_thumbnails') ):
			return jaxlite_setting('jaxlite_thumbnails');
		else:
			return "600";
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* THEME SETUP */
/*-----------------------------------------------------------------------------------*/   

if (!function_exists('jaxlite_setup')) {

	function jaxlite_setup() {

		global $nivoitems, $bxitems;

		if ( ! isset( $content_width ) )
			$content_width = 940;
	
		add_theme_support( 'post-formats', array( 'aside','gallery','quote','video','audio','link','status','chat','image' ) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
	
		add_image_size( 'thumbnail', jaxlite_get_width(), jaxlite_get_height(), TRUE ); 
		
		add_theme_support( 'title-tag' );

		add_image_size( 'large', 449,304, TRUE ); 
		add_image_size( 'medium', 290,220, TRUE ); 
		add_image_size( 'small', 211,150, TRUE ); 
	
		register_nav_menu( 'main-menu', 'Main menu' );

		load_theme_textdomain("jaxlite", get_template_directory() . '/languages');
		
		add_theme_support( 'custom-background', array(
			'default-color' => 'f3f3f3',
		) );
		
		add_theme_support( 'custom-header', array( 
			'default-image' => get_template_directory_uri() . '/inc/images/background/header.jpg',
			'default-text-color' => 'fafafa',
		) );

		register_default_headers( array(
			'wheel' => array(
				'url' => get_template_directory_uri() . '/inc/images/background/header.jpg',
				'thumbnail_url' => get_template_directory_uri() . '/inc/images/background/header.jpg',
				'description' => __( 'Default', "jaxlite" )
			)
		) );

		$require_array = array (
			"/core/classes/",
			"/core/admin/customize/",
			"/core/functions/",
			"/core/templates/",
			"/core/scripts/",
			"/core/metaboxes/",
		);
		
		foreach ( $require_array as $require_file ) {	
		
			jaxlite_require($require_file);
		
		}
		
	}

	add_action( 'after_setup_theme', 'jaxlite_setup' );

}

?>