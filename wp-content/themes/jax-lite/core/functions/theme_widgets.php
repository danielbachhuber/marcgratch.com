<?php

if (!function_exists('jaxlite_loadwidgets')) {

	function jaxlite_loadwidgets() {

		register_sidebar(array(
		
			'name' => 'Sidebar',
			'id'   => 'side-sidebar-area',
			'description'   => 'This sidebar will be shown at the side of content.',
			'before_widget' => '<div id="%1$s" class="post-article widget-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-container"><h3 class="title">',
			'after_title'   => '</h3></div>'
		
		));

		register_sidebar(array(
		
			'name' => 'Scroll Sidebar',
			'id'   => 'scroll-sidebar-area',
			'description'   => 'This sidebar will be shown under the scroll sidebar.',
			'before_widget' => '<div id="%1$s" class="post-article widget-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-container"><h3 class="title">',
			'after_title'   => '</h3></div>'
		
		));
	
		register_sidebar(array(
		
			'name' => 'Header Sidebar',
			'id'   => 'header-sidebar-area',
			'description'   => 'This sidebar will be shown before the content.',
			'before_widget' => '<div id="%1$s" class="post-container"><div class="post-article widget-box %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="title-container"><h3 class="title">',
			'after_title'   => '</h3></div>'
		
		));
	
		register_sidebar(array(
		
			'name' => 'Bottom Sidebar',
			'id'   => 'bottom-sidebar-area',
			'description'   => 'This sidebar will be shown after the content.',
			'before_widget' => '<div id="%1$s" class="post-container"><div class="post-article widget-box %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<div class="title-container"><h3 class="title">',
			'after_title'   => '</h3></div>'
		
		));
	
		register_sidebar(array(

			'name' => 'Footer Sidebar',
			'id'   => 'footer-sidebar-area',
			'before_widget' => '<div id="%1$s" class="col-md-4 widget-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="title-container"><h4 class="title">',
			'after_title'   => '</h4></div>'
		
		));

	}

	add_action( 'widgets_init', 'jaxlite_loadwidgets' );

}

?>