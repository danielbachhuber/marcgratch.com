<?php

/**
 * Messaging Service
 *
 * @package Sprout_Client
 * @subpackage Messaging
 */
class SC_Message_Triggers extends SC_Messages {

	public static function init() {
		// Hook actions that would send a message
		self::message_hooks();
	}

	/**
	 * Hooks for all messages
	 * @return
	 */
	private static function message_hooks() {
		// Notifications can be suppressed
		if ( apply_filters( 'suppress_messages', false ) ) {
			return;
		}

	}

}