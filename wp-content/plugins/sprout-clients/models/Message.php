<?php

/**
 * Notification Model
 *
 *
 * @package Sprout_Clients
 * @subpackage Notification
 */
class SC_Message extends SC_Post_Type {

	const POST_TYPE = 'sc_message';
	private static $instances = array();

	private static $meta_keys = array(
		'disabled' => '_disabled', // bool
	); // A list of meta keys this class cares about. Try to keep them in alphabetical order.


	public static function init() {
		// register Notification post type
		$post_type_args = array(
			'public' => false,
			'has_archive' => false,
			'show_ui' => false,
			'show_in_menu' => 'sprout-client',
			'supports' => array( 'title', 'editor', 'revisions' )
		);
		self::register_post_type( self::POST_TYPE, 'Message', 'Messages', $post_type_args );
	}

	protected function __construct( $id ) {
		parent::__construct( $id );
	}

	/**
	 *
	 *
	 * @static
	 * @param int     $id
	 * @return Sprout_Clients_Notification
	 */
	public static function get_instance( $id = 0 ) {
		if ( ! $id ) {
			return null; }

		if ( ! isset( self::$instances[ $id ] ) || ! self::$instances[ $id ] instanceof self ) {
			self::$instances[ $id ] = new self( $id ); }

		if ( ! isset( self::$instances[ $id ]->post->post_type ) ) {
			return null; }

		if ( self::$instances[ $id ]->post->post_type !== self::POST_TYPE ) {
			return null; }

		return self::$instances[ $id ];
	}

	public function is_disabled() {
		$disabled = $this->get_post_meta( self::$meta_keys['disabled'] );
		if ( 'TRUE' === $disabled ) {
			return true;
		}
		return;
	}

	public function get_disabled() {
		$disabled = $this->get_post_meta( self::$meta_keys['disabled'] );
		return $disabled;
	}

	public function set_disabled( $disabled ) {
		$this->save_post_meta( array(
				self::$meta_keys['disabled'] => $disabled,
			) );
		return $disabled;
	}

	// A pretty basic post type. Not much else to do here.
}