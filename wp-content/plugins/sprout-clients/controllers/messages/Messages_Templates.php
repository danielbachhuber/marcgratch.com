<?php

/**
 * Messaging Service
 *
 * @package Sprout_Client
 * @subpackage Messaging
 */
class SC_Templates extends SC_Messages {

	public static function init() {
		// register messages
		add_filter( 'sprout_messages', array( __CLASS__, 'register_messages' ) );
	}

	public static function register_messages( $messages = array() ) {
		$default_messages = array(

			);
		return array_merge( $messages, $default_messages );
	}

}