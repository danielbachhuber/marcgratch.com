<?php

/**
 * Wp in Progress
 * 
 * @package Jax Lite
 * @author WPinProgress
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

$jaxlite_new_metaboxes = new jaxlite_metaboxes ('page', array (

array( "name" => "Navigation",  
       "type" => "navigation",  
	   
       "item" => array( 
	   		
	   		
			"setting" => __( "Setting","jaxlite") , 
			"header_settings" => __( "Header","jaxlite") , 
			"sidebars" => __( "Sidebars","jaxlite") , 

		),
		   
       "start" => "<ul>", 
       "end" => "</ul>"),  
	   
array( "type" => "begintab",
	   "tab" => "setting",
	   "element" =>

		array( "name" => __( "Setting","jaxlite"),
			   "type" => "title",
			  ),
		
		array( "name" => __( "Template","jaxlite"),
			   "desc" => __( "Choose a template for this post","jaxlite"),
			   "id" => "jaxlite_template",
			   "type" => "select",
			   "options" => array(
				   "full" => __( "Full Width","jaxlite"),
				   "left-sidebar" =>  __( "Left Sidebar","jaxlite"),
				   "right-sidebar" => __( "Right Sidebar","jaxlite"),
			  ),
			  
			   "std" => "right-sidebar",
			   
		),
),

array( "type" => "endtab"),

array( "type" => "begintab",
	   "tab" => "header_settings",
	   "element" =>

		array( "name" => __( "Header","jaxlite"),
			   "type" => "title",
			  ),

		array( "name" => __( "Slogan","jaxlite"),
			   "desc" => __( "Insert the slogan, for this content.","jaxlite"),
			   "id" => "jaxlite_slogan",
			   "type" => "text",
		),

		array( "name" => __( "Sub slogan","jaxlite"),
			   "desc" => __( "Insert the subslogan, for this content.","jaxlite"),
			   "id" => "jaxlite_subslogan",
			   "type" => "text",
		),
		
),

array( "type" => "endtab"),

array( "type" => "begintab",
	   "tab" => "sidebars",
	   "element" =>

		array( "name" => __( "Setting","jaxlite"),
			   "type" => "title",
			  ),

		array( "name" => __( "Header Sidebar","jaxlite"),
			   "desc" => __( "Choose a header sidebar","jaxlite"),
			   "id" => "jaxlite_header_sidebar",
			   "type" => "select",
			   "options" => jaxlite_sidebar_list('header'),
			   "std" => "none",
			),

		array( "name" => __( "Scroll Sidebar","jaxlite"),
			   "desc" => __( "Choose a scroll sidebar","jaxlite"),
			   "id" => "jaxlite_scroll_sidebar",
			   "type" => "select",
			   "options" => jaxlite_sidebar_list('scroll'),
			   "std" => "none",
			),

		array( "name" => __( "Bottom Sidebar","jaxlite"),
			   "desc" => __( "Choose a bottom sidebar","jaxlite"),
			   "id" => "jaxlite_bottom_sidebar",
			   "type" => "select",
			   "options" => jaxlite_sidebar_list('bottom'),
			   "std" => "none",
			),

		array( "name" => __( "Footer Sidebar","jaxlite"),
			   "desc" => __( "Choose a footer sidebar","jaxlite"),
			   "id" => "jaxlite_footer_sidebar",
			   "type" => "select",
			   "options" => jaxlite_sidebar_list('footer'),
			   "std" => "none",
			),

),

array( "type" => "endtab"),

array( "type" => "endtab")
)

);


?>