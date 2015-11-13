<?php

/**
 * Send messages, apply shortcodes and create management screen.
 *
 * @package Sprout_Client
 * @subpackage Notification
 */
class SC_Messages_Meta extends SC_Messages {
	const META_BOX_PREFIX = 'si_message_shortcodes_';

	public static function init() {
		// Meta boxes
		add_action( 'admin_init', array( __CLASS__, 'register_meta_boxes' ) );
		add_action( 'do_meta_boxes', array( __CLASS__, 'modify_meta_boxes' ) );

		// Admin js for message management
		add_action( 'load-post.php', array( __CLASS__, 'queue_message_js' ) );
		add_action( 'load-post-new.php', array( __CLASS__, 'queue_message_js' ) );
	}

	/**
	 * enqueue admin js for message management
	 * @return
	 */
	public static function queue_message_js() {
		$screen = get_current_screen();
		$screen_post_type = str_replace( 'edit-', '', $screen->id );
		if ( SC_Message::POST_TYPE === $screen_post_type ) {
			wp_register_script( 'si_admin_messages', SI_URL . '/resources/admin/js/message.js', array( 'jquery' ), self::SI_VERSION );
			wp_enqueue_script( 'si_admin_messages' );
		}
	}

	/**
	 * Regsiter meta boxes for message editing.
	 * @return
	 */
	public static function register_meta_boxes() {
		// message specific
		$args = array(
				'si_message_submit' => array(
					'title' => 'Update',
					'show_callback' => array( __CLASS__, 'show_submit_meta_box' ),
					'save_callback' => array( __CLASS__, 'save_meta_box_message_submit' ),
					'context' => 'side',
					'priority' => 'high',
				)
			);

		foreach ( self::$messages as $message => $data ) {
			$name = ( isset( $data['name'] ) ) ? $data['name'] : self::__( 'N/A' );
			$args[ self::META_BOX_PREFIX . $message ] = array(
					'title' => sprintf( self::__( '%s Shortcodes' ), $name ),
					'show_callback' => array( __CLASS__, 'show_shortcode_meta_box' )
				);
		}
		do_action( 'sprout_meta_box', $args, SC_Message::POST_TYPE );
	}

	/**
	 * Remove publish box and add something custom for messages
	 * @param  string $post_type
	 * @return
	 */
	public static function modify_meta_boxes( $post_type ) {
		remove_meta_box( 'submitdiv', SC_Message::POST_TYPE, 'side' );
		remove_meta_box( 'slugdiv', SC_Message::POST_TYPE, 'normal' );
	}

	/**
	 * View for message shortcodes
	 * @param  SC_Message $message
	 * @param  WP_Post $post
	 * @param  array $metabox
	 * @return
	 */
	public static function show_shortcode_meta_box( $post, $metabox ) {
		$id = preg_replace( '/^' . preg_quote( self::META_BOX_PREFIX ) . '/', '', $metabox['id'] );
		if ( isset( self::$messages[ $id ] ) ) {
			self::load_view( 'admin/meta-boxes/messages/shortcodes', array(
					'id' => $id,
					'type' => self::$messages[ $id ],
					'shortcodes' => self::$shortcodes,
				) );
		}
	}

	/**
	 * Show custom submit box.
	 * @param  WP_Post $post
	 * @param  array $metabox
	 * @return
	 */
	public static function show_submit_meta_box( $post, $metabox ) {
		$message = SC_Message::get_instance( $post->ID );
		self::load_view( 'admin/meta-boxes/messages/submit', array(
				'id' => $post->ID,
				'message_types' => self::$messages,
				'messages_option' => get_option( self::NOTIFICATIONS_OPTION_NAME, array() ),
				'post' => $post,
				'disabled' => $message->get_disabled()
			), false );
	}

	/**
	 * main cllback for saving the message
	 * @param  object  $message
	 * @param  string $message_id
	 * @return
	 */
	public static function save_meta_box_message_submit( $post_id, $post, $callback_args, $message_type = null ) {
		if ( null === $message_type && isset( $_POST['message_type'] ) ) {
			$message_type = $_POST['message_type'];
		}

		if ( is_null( $post_id ) ) {
			if ( isset( $_POST['ID'] ) ) {
				$post_id = $_POST['ID'];
			}
		}
		if ( get_post_type( $post_id ) != SC_Message::POST_TYPE ) {
			return;
		}

		// Remove any existing message types that point to the post currently being saved
		$message_set = get_option( self::NOTIFICATIONS_OPTION_NAME, array() );
		foreach ( $message_set as $op_type => $note_id ) {
			if ( $note_id == $post_id ) {
				unset( $message_set[$post_id] );
			}
		}

		if ( isset( self::$messages[$message_type] ) ) {

			// Associate this post with the given message type
			$message_set[$message_type] = $post_id;
			update_option( self::NOTIFICATIONS_OPTION_NAME, $message_set );
		}

		$message = SC_Message::get_instance( $post_id );

		// Mark as disabled or not.
		if ( isset( $_POST['message_type_disabled'] ) && $_POST['message_type_disabled'] == 'TRUE' ) {
			$message->set_disabled( 'TRUE' );
		} else {
			$message->set_disabled( 0 );
		}
	}

}