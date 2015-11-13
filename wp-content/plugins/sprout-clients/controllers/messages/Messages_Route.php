<?php


/**
* Messaging Routing
*/
class SC_Messages_Route extends SC_Messages {

	/**
	 * Send the message
	 * @param  string $message_name
	 * @param  array  $data
	 * @param  string $to
	 * @param  string $from_email
	 * @param  string $from_name
	 * @param  bool $html
	 * @return
	 */
	public static function send_message( $message_name, $data = array(), $to, $from_email = null, $from_name = null, $html = null ) {
		// don't send disabled messages
		if ( apply_filters( 'suppress_messages', false ) || self::is_disabled( $message_name ) ) {
			return;
		}
		// So shortcode handlers know whether the email is being sent as html or plaintext
		if ( null == $html ) {
			$html = ( self::$message_format == 'HTML' ) ? true : false ;
		}
		$data['html'] = $html;

		$message_title = self::get_message_instance_subject( $message_name, $data );
		$message_content = self::get_message_instance_content( $message_name, $data );
		// Don't send messages with empty titles or content
		if ( empty( $message_title ) || empty( $message_content ) ) {
			do_action( 'sc_error', __CLASS__ . '::' . __FUNCTION__ . ' - Notifications: Message Has no Content', $data );
			return;
		}

		// don't send a message that has already been sent
		if ( apply_filters( 'si_was_message_sent_check', true ) && self::was_message_sent( $message_name, $data, $to, $message_content ) ) {
			do_action( 'sc_error', __CLASS__ . '::' . __FUNCTION__ . ' - Notifications: Message Already Sent', $data );
			return;
		}

		// Plugin addons can suppress specific messages by filtering 'si_suppress_message'
		$suppress_message = apply_filters( 'si_suppress_message', false, $message_name, $data, $from_email, $from_name, $html );
		if ( $suppress_message ) {
			do_action( 'sc_error', __CLASS__ . '::' . __FUNCTION__ . ' - Notifications: Message Suppressed', $data );
			return;
		}

		$from_email = ( null === $from_email ) ? self::$message_from_email : $from_email ;
		$from_name = ( null === $from_name ) ? self::$message_from_name : $from_name ;

		if ( $html ) {
			$headers = array(
				'From: '.$from_name.' <'.$from_email.'>',
				'Content-Type: text/html'
			);
		} else {
			$headers = array(
				'From: '.$from_name.' <'.$from_email.'>',
			);
		}
		$headers = implode( "\r\n", $headers ) . "\r\n";
		$filtered_headers = apply_filters( 'si_message_headers', $headers, $message_name, $data, $from_email, $from_name, $html );

		// Use the wp_email function
		$sent = wp_mail( $to, $message_title, $message_content, $filtered_headers );

		if ( $sent != false ) {
			// Create message record
			self::message_record( $message_name, $data, $to, $message_title, $message_content );
		}
		else {
			do_action( 'sc_error', 'FAILED NOTIFICATION - Attempted e-mail: ' . $to, $data );
			return false;
		}

		// Mark the message as sent.
		self::mark_message_sent( $message_name, $data, $to );
	}

	/**
	 * Create a record that a message was sent.
	 * @param  string $message_name
	 * @param  array $data
	 * @param  string $to
	 * @param  string $message_title
	 * @param  string $message_content
	 * @return null
	 */
	public static function message_record( $message_name, $data, $to, $message_title, $message_content ) {
		$associated_record = 0;
		if ( isset( $data['estimate'] ) && is_a( $data['estimate'], 'SI_Estimate' ) ) {
			$associated_record = $data['estimate']->get_id();
		}
		if ( isset( $data['invoice'] ) && is_a( $data['invoice'], 'SI_Invoice' ) ) {
			$associated_record = $data['invoice']->get_id();
		}
		$content = '';
		$content .= '<b>' . $message_title . "</b>\r\n\r\n";
		$content .= $message_content;
		do_action( 'sc_new_record',
			$content, // content
			self::RECORD, // type slug
			$associated_record, // post id
			sprintf( sc__( 'Notification sent to %s.' ), esc_html( $to ) ), // title
			0, // user id
			false // don't encode
		);
	}

	/**
	 * Log that a message as sent
	 *
	 * @static
	 * @param string  $message_name
	 * @param array   $data
	 * @param string  $to
	 * @return
	 */
	public static function mark_message_sent( $message_name, $data, $to ) {
		global $blog_id;
		$user_id = self::get_message_instance_user_id( $to, $data );
		if ( ! $user_id ) {
			return; // don't know who it is, so we can't log it
		}
		add_user_meta( $user_id, $blog_id.'_si_message-'.$message_name, self::get_hash( $data ) );
	}

	/**
	 *
	 *
	 * @static
	 * @param string  $message_name
	 * @param array   $data
	 * @param string  $to
	 * @return bool Whether this message was previously sent
	 */
	public static function was_message_sent( $message_name, $data, $to, $message_content = '' ) {
		global $blog_id;
		$user_id = self::get_message_instance_user_id( $to, $data );
		if ( ! $user_id ) {
			return false;
		}
		if ( $message_content != '' ) {
			$data['content'] = $message_content;
		}
		$meta = get_user_meta( $user_id, $blog_id.'_si_message-'.$message_name, false );
		if ( in_array( self::get_hash( $data ), $meta ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Convert the data array into a hash
	 *
	 * @static
	 * @param array   $data
	 * @return string
	 */
	private static function get_hash( $data ) {
		foreach ( $data as $key => $value ) {
			// many objects can't be serialized, so convert them to something else
			if ( is_object( $value ) && method_exists( $value, 'get_id' ) ) {
				$data[$key] = array( 'class' => get_class( $value ), 'id' => $value->get_id() );
			}
		}
		return md5( serialize( $data ) );
	}

}