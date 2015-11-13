<?php

/**
 * Send messages, apply shortcodes and create management screen.
 *
 * @package Sprout_Client
 * @subpackage Notification
 */
class SC_Messages extends SC_Controller {
	const RECORD = 'si_message';

	public static $messages;
	protected static $shortcodes;

	public static function init() {
		// Default messages
		add_action( 'init', array( __CLASS__, 'messages_and_shortcodes' ), 5 );

		// Create default messages
		add_action( 'admin_init', array( __CLASS__, 'create_messages' ) );

	}

	public static function messages_and_shortcodes() {
		if ( ! isset( self::$messages ) ) {
			// Notification types include a name and a list of shortcodes
			$default_messages = array(); // defaults are in the hooks class
			self::$messages = apply_filters( 'sprout_messages', $default_messages );
		}
		if ( ! isset( self::$shortcodes ) ) {
			// Notification shortcodes include the code, a description, and a callback
			// Most shortcodes should be defined by a different controller using the 'si_message_shortcodes' filter
			$default_shortcodes = array(); // Default shortcodes are in the hooks class
			self::$shortcodes = apply_filters( 'sprout_message_shortcodes', $default_shortcodes );
		}
	}

	/**
	 * Create the default messages
	 * @return
	 */
	public static function create_messages() {
		if ( isset( $_GET['page'] ) && 'sprout-apps/settings' === $_GET['page'] ) {
			foreach ( self::$messages as $message_id => $data ) {
				$message = self::get_message_instance( $message_id );
				if ( is_null( $message ) ) {
					$post_id = wp_insert_post( array(
							'post_status' => 'publish',
							'post_type' => SC_Message::POST_TYPE,
							'post_title' => $data['default_title'],
							'post_content' => $data['default_content'],
						) );
					$message = SC_Message::get_instance( $post_id );
					self::save_meta_box_message_submit( $post_id, $message->get_post(), array(), $message_id );
					if ( isset( $data['default_disabled'] ) && $data['default_disabled'] ) {
						$message->set_disabled( 'TRUE' );
					}
				}
				// Don't allow for a message to enabled if specifically shouldn't
				if ( isset( $data['always_disabled'] ) && $data['always_disabled'] ) {
					$message->set_disabled( 'TRUE' );
				}
			}
		}
	}

	/////////////////
	// Shortcodes //
	/////////////////

	/**
	 * Add the shortcodes via the appropriate WP actions, apply the shortcodes to the content and
	 * remove the shortcodes after the content has been filtered.
	 *
	 * @param  string $message_name
	 * @param  string $content
	 * @return string
	 */
	public static function do_shortcodes( $message_name, $content ) {
		foreach ( self::$messages[$message_name]['shortcodes'] as $shortcode ) {
			add_shortcode( $shortcode, array( __CLASS__, 'message_shortcode' ) );
		}
		$content = do_shortcode( $content );
		foreach ( self::$messages[$message_name]['shortcodes'] as $shortcode ) {
			remove_shortcode( $shortcode );
		}
		return $content;
	}

	/**
	 * Shortcode callbacks.
	 * @param  array $atts
	 * @param  string $content
	 * @param  string $code
	 * @param  array $data
	 * @return string          filtered content
	 */
	public static function message_shortcode( $atts, $content, $code ) {
		if ( isset( self::$shortcodes[$code] ) ) {
			$shortcode = call_user_func( self::$shortcodes[$code]['callback'], $atts, $content, $code, self::$data );
			return apply_filters( 'si_message_shortcode_'.$code, $shortcode, $atts, $content, $code, self::$data );

		}
		return '';
	}


	///////////
	// Misc //
	///////////

	/**
	 * Splits a string with an email into a name and email.
	 * @param  string $email "name" <email@email.com>
	 * @return array        name and email
	 */
	public static function email_split( $email = '' ) {
		$email .= ' ';
		$pattern = '/([\w\s\'\"]+[\s]+)?(<)?(([\w-\.]+)@((?:[\w]+\.)+)([a-zA-Z]{2,4}))?(>)?/';
		preg_match( $pattern, $email, $match );
		$name = ( isset( $match[1] ) ) ? $match[1] : '';
		$email = ( isset( $match[3] ) ) ? $match[3] : '';
		return array( 'name' => trim( $name ), 'email' => trim( $email ) );
	}

	public static function admin_email( $atts = array() ) {
		$admin_to = apply_filters( 'si_admin_message_to_address', self::$admin_email, $atts );
		return $admin_to;
	}

	public static function from_email( $atts = array() ) {
		$from_email = apply_filters( 'si_admin_message_from_email', self::$message_from_email, $atts );
		return $from_email;
	}

	public static function from_name( $atts = array() ) {
		$from_name = apply_filters( 'si_admin_message_from_name', self::$message_from_name, $atts );
		return $from_name;
	}

	//////////////
	// Utility //
	//////////////

	/**
	 * Get message instance.
	 * @param  string $message the slug for the message
	 * @return SC_Message/false
	 */
	public static function get_message_instance( $message ) {
		if ( isset( self::$messages[$message] ) ) {
			$messages = get_option( self::NOTIFICATIONS_OPTION_NAME );
			if ( isset( $messages[$message] ) ) {
				$message_id = $messages[$message];
				$message = SC_Message::get_instance( $message_id );
				if ( $message != null ) {
					$post = $message->get_post();

					// Don't return the message if isn't published (excludes deleted, draft, and future posts)
					if ( 'publish' == $post->post_status ) {
						return $message;
					}
				}
			}
		}
		return null; // return null and not a boolean for the sake of validity checks elsewhere
	}

	/**
	 * Is the message disabled.
	 * @param  string  $message_name
	 * @return boolean
	 */
	public static function is_disabled( $message_name ) {
		$message = self::get_message_instance( $message_name );
		if ( is_a( $message, 'SC_Message' ) ) {
			return $message->is_disabled();
		}
		return true;
	}

	/**
	 * Utility function to get the user ID that the given information would be sent to.
	 *
	 * @static
	 * @param string  $to   The user's email address
	 * @param array   $data
	 * @return int
	 */
	protected static function get_message_instance_user_id( $to = '', $data = array() ) {
		$user_id = 0;
		// first, see if it's stored in the data
		if ( isset( $data['user_id'] ) ) {
			$user_id = $data['user_id'];
		} elseif ( isset( $data['user'] ) ) {
			if ( is_numeric( $data['user'] ) ) {
				$user_id = $data['user'];
			} elseif ( is_object( $data['user'] ) && isset( $data['user']->ID ) ) {
				$user_id = $data['user']->ID;
			}
		}
		if ( isset( $data['user'] ) && is_a( $data['user'], 'WP_User' ) ) {
			return $data['user']->ID;
		}
		// then try to determine based on email address
		if ( ! $user_id ) {
			$email = ( isset( $data['user_email'] ) && $data['user_email'] != '' ) ? $data['user_email'] : $to ;
			$user = get_user_by( 'email', $email );
			if ( $user && isset( $user->ID ) ) {
				$user_id = $user->ID;
			}
		}

		return $user_id;
	}

	public static function get_user_email( $user = false ) {
		if ( false == $user ) {
			$user = get_current_user_id();
		}
		if ( is_numeric( $user ) ) {
			$user = get_userdata( $user );
		}
		if ( ! is_a( $user, 'WP_User' ) ) {
			do_action( 'sc_error', __CLASS__ . '::' . __FUNCTION__ . ' - Get User Email FAILED', $user );
			wpbt();
			return false;
		}
		$user_email = $user->user_email;
		$name = $user->first_name . ' ' . $user->last_name;

		if ( $name == ' ' ) {
			$to = $user_email;
		} else {
			$to = "$name <$user_email>";
		}

		// compensate for strange bug where the name came through but the email wasn't.
		if ( strpos( $to, $user_email ) !== false ) {
			$to = $user_email;
		}

		return $to;
	}

	/**
	 * Get associated client user ids
	 * @param  object $doc Invoice/Estimate
	 * @return array
	 */
	public static function get_document_recipients( $doc ) {
		$client = $doc->get_client();
		$client_users = array();
		// get the user ids associated with this doc.
		if ( ! is_wp_error( $client ) && is_a( $client, 'Sprout_Client' ) ) {
			$client_users = $client->get_associated_users();
		}
		else { // no client associated
			$user_id = $doc->get_user_id(); // check to see if a user id is associated
			if ( $user_id ) {
				$client_users = array( $user_id );
			}
		}
		if ( is_wp_error( $client_users ) || ! is_array( $client_users ) ) {
			do_action( 'sc_error', 'get_document_recipients ERROR', $client_users );
		}
		return $client_users;
	}

}