<?php

if (!function_exists('jaxlite_customize_panel_function')) {

	function jaxlite_customize_panel_function() {
		
		$theme_panel = array ( 

			array(
				
				"label" => __( "Full Image Background","jaxlite"),
				"description" => __( "Do you want to set a full background image? (After the upload, check 'Fixed', from the Background Attachment section)","jaxlite"),
				"id" => "jaxlite_full_image_background",
				"type" => "select",
				"section" => "background_image",
				"options" => array (
				   "off" => __( "No","jaxlite"),
				   "on" => __( "Yes","jaxlite"),
				),
				
				"std" => "off",
			
			),

			array(
				
				"label" => __( "Background Repeat","jaxlite"),
				"description" => __( "Choose a background repeat.","jaxlite"),
				"id" => "jaxlite_header_background_repeat",
				"type" => "select",
				"section" => "header_image",
				"options" => array (
				   "no-repeat" => __( "None","jaxlite"),
				   "repeat" => __( "Repeat","jaxlite"),
				   "repeat-x" => __( "Horizontal Repeat","jaxlite"),
				   "repeat-y" => __( "Vertical Repeat","jaxlite"),
				),
				
				"std" => "",
			
			),

			array(
				
				"label" => __( "Background Position","jaxlite"),
				"description" => __( "Choose a background position.","jaxlite"),
				"id" => "jaxlite_header_background_position",
				"type" => "select",
				"section" => "header_image",
				"options" => array (
				   "" => __( "None","jaxlite"),
				   "top-left" => __( "Top Left","jaxlite"),
				   "top-center" => __( "Top Center","jaxlite"),
				   "top-right" => __( "Top Right","jaxlite"),
				   "left" => __( "Left","jaxlite"),
				   "center" => __( "Center","jaxlite"),
				   "right" => __( "Right","jaxlite"),
				   "bottom-left" => __( "Bottom Left","jaxlite"),
				   "bottom-center" => __( "Bottom Center","jaxlite"),
				   "bottom-right" => __( "Bottom Right","jaxlite"),
				),
				
				"std" => "center",
			
			),

			array(
				
				"label" => __( "Background Attachment","jaxlite"),
				"description" => __( "Choose a background attachment.","jaxlite"),
				"id" => "jaxlite_header_background_attachment",
				"type" => "select",
				"section" => "header_image",
				"options" => array (
				   "scroll" => __( "Scroll","jaxlite"),
				   "fixed" => __( "Fixed","jaxlite"),
				),
				
				"std" => "fixed",
			
			),

			array(
				
				"label" => __( "Full Image Background","jaxlite"),
				"description" => __( "Do you want to set a full background image?(Recommended with a fixed attachment)","jaxlite"),
				"id" => "jaxlite_full_image_header_background",
				"type" => "select",
				"section" => "header_image",
				"options" => array (
				   "off" => __( "No","jaxlite"),
				   "on" => __( "Yes","jaxlite"),
				),
				
				"std" => "on",
			
			),

			array(
				
				"label" => __( "Header padding","jaxlite"),
				"description" => __( "Change the top and bottom padding, for increase the header height.","jaxlite"),
				"id" => "jaxlite_header_padding",
				"type" => "text",
				"section" => "header_image",
				"std" => "200px",
			
			),

			array(
				
				"label" => __( "Header Text Color Hover","jaxlite"),
				"description" => __( "","jaxlite"),
				"id" => "jaxlite_header_textcolor_hover",
				"type" => "color",
				"section" => "colors",
				"std" => "#ffffff",
				"priority" => "09",

			),

			/* START GENERAL SECTION */ 

			array( 
				
				"title" => __( "General","jaxlite"),
				"description" => __( "General","jaxlite"),
				"type" => "panel",
				"id" => "general_panel",
				"priority" => "10",
				
			),

			array( 

				"title" => __( "Load system","jaxlite"),
				"type" => "section",
				"id" => "loadsystem_section",
				"panel" => "general_panel",
				"priority" => "10",

			),

			array(
				
				"label" => __( "Choose a load system","jaxlite"),
				"description" => __( "Select a load system, if you've some problems with the theme (for example a blank page).","jaxlite"),
				"id" => "jaxlite_skins",
				"type" => "select",
				"section" => "loadsystem_section",
				"options" => array (
				   "mode_a" => __( "Mode a","jaxlite"),
				   "mode_b" => __( "Mode b","jaxlite"),
				),
				
				"std" => "mode_a",
			
			),

			/* SKINS */ 

			array( 

				"title" => __( "Color Scheme","jaxlite"),
				"type" => "section",
				"panel" => "general_panel",
				"priority" => "11",
				"id" => "colorscheme_section",

			),

			array(
				
				"label" => __( "Predefined Color Schemes","jaxlite"),
				"description" => __( "Choose your Color Scheme","jaxlite"),
				"id" => "jaxlite_skin",
				"type" => "select",
				"section" => "colorscheme_section",
				"options" => array (
				   "turquoise" => __( "Turquoise","jaxlite"),
				   "orange" => __( "Orange","jaxlite"),
				   "blue" => __( "Blue","jaxlite"),
				   "red" => __( "Red","jaxlite"),
				   "pink" => __( "Pink","jaxlite"),
				   "purple" => __( "Purple","jaxlite"),
				   "yellow" => __( "Yellow","jaxlite"),
				   "green" => __( "Green","jaxlite"),
				   "white_turquoise" => __( "White & Turquoise","jaxlite"),
				   "white_orange" => __( "White & Orange","jaxlite"),
				   "white_blue" => __( "White & Blue","jaxlite"),
				   "white_red" => __( "White & Red","jaxlite"),
				   "white_pink" => __( "White & Pink","jaxlite"),
				   "white_purple" => __( "White & Purple","jaxlite"),
				   "white_yellow" => __( "White & Yellow","jaxlite"),
				   "white_green" => __( "White & Green","jaxlite"),
				),
				
				"std" => "turquoise",
			
			),

			/* PAGE WIDTH */ 

			array( 

				"title" => __( "Page width","jaxlite"),
				"type" => "section",
				"id" => "pagewidth_section",
				"panel" => "general_panel",
				"priority" => "12",

			),

			array( 

				"label" => __( "Screen greater than 768px","jaxlite"),
				"description" => __( "Set a width, for a screen greater than 768 pixel (for example 750 and not 750px ) ","jaxlite"),
				"id" => "jaxlite_screen1",
				"type" => "text",
				"section" => "pagewidth_section",
				"std" => "750",

			),

			array( 

				"label" => __( "Screen greater than 992px","jaxlite"),
				"description" => __( "Set a width, for a screen greater than 992 pixel (for example 940 and not 940px ) ","jaxlite"),
				"id" => "jaxlite_screen2",
				"type" => "text",
				"section" => "pagewidth_section",
				"std" => "940",

			),

			array( 

				"label" => __( "Screen greater than 1200px","jaxlite"),
				"description" => __( "Set a width, in px, for a screen greater than 1200 pixel (for example 1170 and not 1170px ) ","jaxlite"),
				"id" => "jaxlite_screen3",
				"type" => "text",
				"section" => "pagewidth_section",
				"std" => "940",

			),

			/* SETTINGS SECTION */ 

			array( 

				"title" => __( "Settings","jaxlite"),
				"type" => "section",
				"id" => "settings_section",
				"panel" => "general_panel",
				"priority" => "13",

			),

			array(
				
				"label" => __( "Infinite scroll","jaxlite"),
				"description" => __( "Do you want to use the infinite scroll, for the articles?","jaxlite"),
				"id" => "jaxlite_infinitescroll_system",
				"type" => "select",
				"section" => "settings_section",
				"options" => array (
				   "off" => __( "No","jaxlite"),
				   "on" => __( "Yes","jaxlite"),
				),
				
				"std" => "off",
			
			),

			array(
				
				"label" => __( "Read more button","jaxlite"),
				"description" => __( "Do you want to use a button, for open the posts? (Instead of [...])","jaxlite"),
				"id" => "jaxlite_readmore_button",
				"type" => "select",
				"section" => "settings_section",
				"options" => array (
				   "off" => __( "No","jaxlite"),
				   "on" => __( "Yes","jaxlite"),
				),
				
				"std" => "off",
			
			),

			array( 

				"title" => __( "Styles","jaxlite"),
				"type" => "section",
				"id" => "styles_section",
				"panel" => "general_panel",
				"priority" => "14",

			),

			array( 

				"label" => __( "Custom css","jaxlite"),
				"description" => __( "Insert your custom css code.","jaxlite"),
				"id" => "jaxlite_custom_css_code",
				"type" => "textarea",
				"section" => "styles_section",
				"std" => "",

			),

			/* LAYOUTS SECTION */ 

			array( 

				"title" => __( "Layouts","jaxlite"),
				"type" => "section",
				"id" => "layouts_section",
				"panel" => "general_panel",
				"priority" => "16",

			),

			array(
				
				"label" => __("Home Blog Layout","jaxlite"),
				"description" => __("If you've set the latest articles, for the homepage, choose a layout.","jaxlite"),
				"id" => "jaxlite_home",
				"type" => "select",
				"section" => "layouts_section",
				"options" => array (
				   "full" => __( "Full Width","jaxlite"),
				   "left-sidebar" => __( "Left Sidebar","jaxlite"),
				   "right-sidebar" => __( "Right Sidebar","jaxlite"),
				   "masonry" => __( "Masonry","jaxlite"),
				),
				
				"std" => "masonry",
			
			),
	

			array(
				
				"label" => __("Category Layout","jaxlite"),
				"description" => __("Select a layout for category pages.","jaxlite"),
				"id" => "jaxlite_category_layout",
				"type" => "select",
				"section" => "layouts_section",
				"options" => array (
				   "full" => __( "Full Width","jaxlite"),
				   "left-sidebar" => __( "Left Sidebar","jaxlite"),
				   "right-sidebar" => __( "Right Sidebar","jaxlite"),
				   "masonry" => __( "Masonry","jaxlite"),
				),
				
				"std" => "masonry",
			
			),
	

			array(
				
				"label" => __("Search Layout","jaxlite"),
				"description" => __("Select a layout for the search page.","jaxlite"),
				"id" => "jaxlite_search_layout",
				"type" => "select",
				"section" => "layouts_section",
				"options" => array (
				   "full" => __( "Full Width","jaxlite"),
				   "left-sidebar" => __( "Left Sidebar","jaxlite"),
				   "right-sidebar" => __( "Right Sidebar","jaxlite"),
				   "masonry" => __( "Masonry","jaxlite"),
				),
				
				"std" => "masonry",
			
			),

			/* THUMBNAILS SECTION */ 

			array( 

				"title" => __( "Thumbnails","jaxlite"),
				"type" => "section",
				"id" => "thumbnails_section",
				"panel" => "general_panel",
				"priority" => "17",

			),

			array( 

				"label" => __( "Blog Thumbnail","jaxlite"),
				"description" => __( "Insert the height for blog thumbnail.","jaxlite"),
				"id" => "jaxlite_blog_thumbinal",
				"type" => "text",
				"section" => "thumbnails_section",
				"std" => "550",

			),

			/* LOGIN AREA SECTION */ 

			array( 

				"title" => __( "Login Area","jaxlite"),
				"type" => "section",
				"id" => "login_area_section",
				"panel" => "general_panel",
				"priority" => "18",

			),

			array( 

				"label" => __( "Custom Logo","jaxlite"),
				"description" => __( "Insert the url of your custom logo.( Max 320px as width )","jaxlite"),
				"id" => "jaxlite_login_logo",
				"type" => "url",
				"section" => "login_area_section",
				"std" => "",

			),


			array( 

				"label" => __( "Height","jaxlite"),
				"description" => __( "Insert the height of your custom logo.","jaxlite"),
				"id" => "jaxlite_login_logo_height",
				"type" => "text",
				"section" => "login_area_section",
				"std" => "550",

			),

			/* HEADER AREA SECTION */ 

			array( 

				"title" => __( "Header","jaxlite"),
				"type" => "section",
				"id" => "header_section",
				"panel" => "general_panel",
				"priority" => "19",

			),

			array( 

				"label" => __( "Custom Logo","jaxlite"),
				"description" => __( "Insert the url of your custom logo","jaxlite"),
				"id" => "jaxlite_custom_logo",
				"type" => "url",
				"section" => "header_section",
				"std" => "",

			),

			array( 

				"label" => __( "Custom Favicon","jaxlite"),
				"description" => __( "Insert the url of your custom favicon.","jaxlite"),
				"id" => "jaxlite_custom_favicon",
				"type" => "url",
				"section" => "header_section",
				"std" => "",

			),

			/* FOOTER AREA SECTION */ 

			array( 

				"title" => __( "Footer","jaxlite"),
				"type" => "section",
				"id" => "footer_section",
				"panel" => "general_panel",
				"priority" => "20",

			),

			array( 

				"label" => __( "Copyright Text","jaxlite"),
				"description" => __( "Insert your copyright text.","jaxlite"),
				"id" => "jaxlite_copyright_text",
				"type" => "textarea",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Facebook Url","jaxlite"),
				"description" => __( "Insert Facebook Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_facebook_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Twitter Url","jaxlite"),
				"description" => __( "Insert Twitter Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_twitter_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Flickr Url","jaxlite"),
				"description" => __( "Insert Flickr Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_flickr_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Google Url","jaxlite"),
				"description" => __( "Insert Google Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_google_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Linkedin Url","jaxlite"),
				"description" => __( "Insert Linkedin Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_linkedin_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Pinterest Url","jaxlite"),
				"description" => __( "Insert Pinterest Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_pinterest_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Tumblr Url","jaxlite"),
				"description" => __( "Insert Tumblr Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_tumblr_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Youtube Url","jaxlite"),
				"description" => __( "Insert Youtube Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_youtube_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Skype Url","jaxlite"),
				"description" => __( "Insert Skype ID (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_skype_button",
				"type" => "button",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Instagram Url","jaxlite"),
				"description" => __( "Insert Instagram Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_instagram_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Github Url","jaxlite"),
				"description" => __( "Insert Github Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_github_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Xing Url","jaxlite"),
				"description" => __( "Insert Xing Url (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_xing_button",
				"type" => "url",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "WhatsApp number","jaxlite"),
				"description" => __( "Insert WhatsApp number (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_whatsapp_button",
				"type" => "button",
				"section" => "footer_section",
				"std" => "",

			),

			array( 

				"label" => __( "Email Address","jaxlite"),
				"description" => __( "Insert Email Address (empty if you want to hide the button)","jaxlite"),
				"id" => "jaxlite_footer_email_button",
				"type" => "button",
				"section" => "footer_section",
				"std" => "",

			),

			array(
				
				"label" => __( "Feed Rss Button","jaxlite"),
				"description" => __( "Do you want to display the Feed Rss button?","jaxlite"),
				"id" => "jaxlite_footer_rss_button",
				"type" => "select",
				"section" => "footer_section",
				"options" => array (
				   "off" => __( "No","jaxlite"),
				   "on" => __( "Yes","jaxlite"),
				),
				
				"std" => "off",
			
			),

			/* TYPOGRAPHY SECTION */ 

			array( 
				
				"title" => __( "Typography","jaxlite"),
				"description" => __( "Typography","jaxlite"),
				"type" => "panel",
				"id" => "typography_panel",
				"priority" => "11",
				
			),

			/* LOGO */ 

			array( 

				"title" => __( "Logo","jaxlite"),
				"type" => "section",
				"id" => "logo_section",
				"panel" => "typography_panel",
				"priority" => "10",

			),

			array( 

				"label" => __( "Font size","jaxlite"),
				"description" => __( "Insert a size, for logo font (For example, 60px) ","jaxlite"),
				"id" => "jaxlite_logo_font_size",
				"type" => "text",
				"section" => "logo_section",
				"std" => "70px",

			),
			
			array( 

				"label" => __( "Description size","jaxlite"),
				"description" => __( "Insert a size, for logo description (For example, 60px) ","jaxlite"),
				"id" => "jaxlite_logo_description_size",
				"type" => "text",
				"section" => "logo_section",
				"std" => "18px",

			),
			
			/* LOGO */ 

			array( 

				"title" => __( "Slogan","jaxlite"),
				"type" => "section",
				"id" => "slogan_section",
				"panel" => "typography_panel",
				"priority" => "11",

			),

			array( 

				"label" => __( "Slogan font size","jaxlite"),
				"description" => __( "Choose a size for slogan.","jaxlite"),
				"id" => "jaxlite_slogan_font_size",
				"type" => "text",
				"section" => "slogan_section",
				"std" => "40px",

			),
			
			array( 

				"label" => __( "Subslogan font size","jaxlite"),
				"description" => __( "Choose a size for subslogan.","jaxlite"),
				"id" => "jaxlite_subslogan_font_size",
				"type" => "text",
				"section" => "slogan_section",
				"std" => "14px",

			),

			/* MENU */ 

			array( 

				"title" => __( "Menu","jaxlite"),
				"type" => "section",
				"id" => "menu_section",
				"panel" => "typography_panel",
				"priority" => "12",

			),

			array( 

				"label" => __( "Font size","jaxlite"),
				"description" => __( "Insert a size, for menu font (For example, 14px) ","jaxlite"),
				"id" => "jaxlite_menu_font_size",
				"type" => "text",
				"section" => "menu_section",
				"std" => "14px",

			),

			/* CONTENT */ 

			array( 

				"title" => __( "Content","jaxlite"),
				"type" => "section",
				"id" => "content_section",
				"panel" => "typography_panel",
				"priority" => "13",

			),

			array( 

				"label" => __( "Font size","jaxlite"),
				"description" => __( "Insert a size, for content font (For example, 14px) ","jaxlite"),
				"id" => "jaxlite_content_font_size",
				"type" => "text",
				"section" => "content_section",
				"std" => "14px",

			),


			/* HEADLINES */ 

			array( 

				"title" => __( "Headlines","jaxlite"),
				"type" => "section",
				"id" => "headlines_section",
				"panel" => "typography_panel",
				"priority" => "14",

			),

			array( 

				"label" => __( "H1 headline","jaxlite"),
				"description" => __( "Insert a size, for for H1 elements (For example, 24px) ","jaxlite"),
				"id" => "jaxlite_h1_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "24px",

			),

			array( 

				"label" => __( "H2 headline","jaxlite"),
				"description" => __( "Insert a size, for for H2 elements (For example, 22px) ","jaxlite"),
				"id" => "jaxlite_h2_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "22px",

			),

			array( 

				"label" => __( "H3 headline","jaxlite"),
				"description" => __( "Insert a size, for for H3 elements (For example, 20px) ","jaxlite"),
				"id" => "jaxlite_h3_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "20px",

			),

			array( 

				"label" => __( "H4 headline","jaxlite"),
				"description" => __( "Insert a size, for for H4 elements (For example, 18px) ","jaxlite"),
				"id" => "jaxlite_h4_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "18px",

			),

			array( 

				"label" => __( "H5 headline","jaxlite"),
				"description" => __( "Insert a size, for for H5 elements (For example, 16px) ","jaxlite"),
				"id" => "jaxlite_h5_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "16px",

			),

			array( 

				"label" => __( "H6 headline","jaxlite"),
				"description" => __( "Insert a size, for for H6 elements (For example, 14px) ","jaxlite"),
				"id" => "jaxlite_h6_font_size",
				"type" => "text",
				"section" => "headlines_section",
				"std" => "14px",

			),
		);
		
		new jaxlite_customize($theme_panel);
		
	} 
	
	add_action( 'jaxlite_customize_panel', 'jaxlite_customize_panel_function', 10, 2 );

}

do_action('jaxlite_customize_panel');

?>