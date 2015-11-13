<?php

/**
 * Messaging Service
 *
 * @package Sprout_Client
 * @subpackage Messaging
 */
class SC_Message_Shortcodes extends SC_Messages {

	public static function init() {
		// Shortcodes
		add_filter( 'sprout_message_shortcodes', array( __CLASS__, 'register_shortcodes' ) );
	}

	public static function register_shortcodes( $shortcodes = array() ) {
		// Notification shortcodes include the code, a description, and a callback
		// Most shortcodes should be defined by a different controller using the 'sprout_message_shortcodes' filter
		$default_shortcodes = array(

			);
		return array_merge( $shortcodes, $default_shortcodes );
	}

}