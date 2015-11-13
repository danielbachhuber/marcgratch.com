<?php

/**
 * Send messages, apply shortcodes and create management screen.
 *
 * @package Sprout_Client
 * @subpackage Notification
 */
class SC_Messages_Admin_Settings extends SC_Messages {
	const SETTINGS_PAGE = 'messages';
	const NOTIFICATIONS_OPTION_NAME = 'si_messages';
	const EMAIL_FROM_NAME = 'si_message_name';
	const EMAIL_FROM_EMAIL = 'si_message_email';
	const EMAIL_FORMAT = 'si_message_format';
	const ADMIN_EMAIL = 'si_messages_admin_email';
	const NOTIFICATION_SUB_OPTION = 'si_subscription_messages';

	private static $message_from_name;
	private static $message_from_email;
	private static $message_format;
	private static $admin_email;

	public static function init() {
		// Store options
		self::$message_from_name = get_option( self::EMAIL_FROM_NAME, get_bloginfo( 'name' ) );
		self::$message_from_email = get_option( self::EMAIL_FROM_EMAIL, get_bloginfo( 'admin_email' ) );
		self::$message_format = get_option( self::EMAIL_FORMAT, 'TEXT' );
		self::$admin_email = get_option( self::ADMIN_EMAIL, get_option( 'admin_email' ) );

		// register settings
		self::register_settings();

		// Help Sections
		add_action( 'admin_menu', array( get_class(), 'help_sections' ) );

		if ( is_admin() ) {
			add_action( 'init', array( get_class(), 'maybe_refresh_messages' ) );
		}
	}

	////////////
	// admin //
	////////////

	/**
	 * Hooked on init add the settings page and options.
	 *
	 */
	public static function register_settings() {
		// Option page
		$args = array(
			'slug' => self::SETTINGS_PAGE,
			'title' => self::__( 'Messages' ),
			'menu_title' => self::__( 'Messaging' ),
			'weight' => 20,
			'reset' => false,
			'section' => SC_Controller::SETTINGS_PAGE,
			'tab_only' => true,
			'callback' => array( __CLASS__, 'display_table' )
			);
		do_action( 'sprout_settings_page', $args );

		// Settings
		$settings = array(
			'messages' => array(
				'title' => self::__( 'Messaging Settings' ),
				'weight' => 30,
				'tab' => SC_Controller::SETTINGS_PAGE,
				'settings' => array(
					self::EMAIL_FROM_NAME => array(
						'label' => self::__( 'From name' ),
						'option' => array(
							'type' => 'text',
							'default' => self::$message_from_name,
							)
						),
					self::EMAIL_FROM_EMAIL => array(
						'label' => self::__( 'From email' ),
						'option' => array(
							'type' => 'text',
							'default' => self::$message_from_email,
							)
						),
					self::ADMIN_EMAIL => array(
						'label' => self::__( 'Admin email' ),
						'option' => array(
							'type' => 'text',
							'default' => self::$admin_email,
							'description' => self::__( 'E-mail address that receives the admin messages (e.g. Payment Received).' )
							)
						),
					self::EMAIL_FORMAT => array(
						'label' => self::__( 'Email format' ),
						'option' => array(
							'type' => 'select',
							'options' => array(
									'HTML' => self::__( 'HTML' ),
									'TEXT' => self::__( 'Plain Text' )
								),
							'default' => self::$message_format,
							'description' => self::__( 'Default messages are in plain text. If set to HTML, custom HTML messages are required.' )
							)
						),
					)
				)
			);
		do_action( 'sprout_settings', $settings, self::SETTINGS_PAGE );
	}

	public static function get_admin_page( $prefixed = true ) {
		return ( $prefixed ) ? self::TEXT_DOMAIN . '/' . self::SETTINGS_PAGE : self::SETTINGS_PAGE ;
	}

	////////////
	// Table //
	////////////

	public static function display_table() {
		//Create an instance of our package class...
		$wp_list_table = new SC_Messages_Table();
		//Fetch, prepare, sort, and filter our data...
		$wp_list_table->prepare_items();
	?>
	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<?php do_action( 'sprout_settings_tabs' ); ?>
		</h2>

		<form id="payments-filter" method="get">
			<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
			<?php $wp_list_table->display() ?>
		</form>
	</div>
	<?php
	}

	public static function maybe_redirect_away_from_message_admin_table( $current_screen ) {
		if ( isset( $_GET['noredirect'] ) ) {
			return;
		}
		if ( SC_Message::POST_TYPE == $current_screen->post_type && 'edit' == $current_screen->base ) {
			wp_redirect( admin_url( 'admin.php?page=' . self::get_admin_page() ) );
			exit();
		}
	}

	////////////////
	// Admin Help //
	////////////////

	/**
	 * Used within the help section to refresh all messages
	 * @return [type] [description]
	 */
	public static function maybe_refresh_messages() {
		if ( ! is_admin() ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( isset( $_GET['refresh-messages'] ) && $_GET['refresh-messages'] ) { // If dev than don't cache.
			$active_messages = get_option( self::NOTIFICATIONS_OPTION_NAME );

			$args = array(
				'post_type' => SC_Message::POST_TYPE,
				'posts_per_page' => -1,
				'exclude' => array_values( $active_messages ),
				'fields' => 'ids',
			);
			$posts = get_posts( $args );

			foreach ( $posts as $post_id ) {
				wp_delete_post( $post_id, true );
			}
		}
	}

	public static function help_sections() {
		add_action( 'load-edit.php', array( __CLASS__, 'help_tabs' ) );
		add_action( 'load-post.php', array( __CLASS__, 'help_tabs' ) );
		add_action( 'load-sprout-apps_page_sprout-apps/settings', array( __CLASS__, 'help_tabs' ) );
	}

	public static function help_tabs() {
		$post_type = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] == self::SETTINGS_PAGE ) {
			$post_type = SC_Message::POST_TYPE;
		}
		if ( $post_type == '' && isset( $_GET['post'] ) ) {
			$post_type = get_post_type( $_GET['post'] );
		}
		if ( $post_type == SC_Message::POST_TYPE ) {
			// get screen and add sections.
			$screen = get_current_screen();

			$screen->add_help_tab( array(
					'id' => 'message-customizations',
					'title' => self::__( 'About Notifications' ),
					'content' => sprintf( '<p>%s</p><p>%s</p>', self::__( 'Notifications include the emails sent to you and your clients, including responses to prospective clients after submitting an estimate request.' ), self::__( 'Each one of your messages can be customized; hover over the message you want and click the edit link.' ) ),
				) );

			$screen->add_help_tab( array(
					'id' => 'message-disable',
					'title' => self::__( 'Disable Notifications' ),
					'content' => sprintf( '<p>%s</p>', self::__( 'The messages edit screen will have an option next to the "Update" button to disable the message from being sent.' ) ),
				) );

			$screen->add_help_tab( array(
					'id' => 'message-editing',
					'title' => self::__( 'Notification Editing' ),
					'content' => sprintf( '<p>%s</p><p>%s</p><p>%s</p><p>%s</p>', self::__( '<b>Subject</b> - The first input is for the messages subject. If the message is an e-mail than it would be subject line for that e-mail message.' ), self::__( '<b>Message Body</b> - The main editor is the message body. Use the available shortcodes to have dynamic information included when the message is received. Make sure to change the Notification Setting if HTML formatting is added to your messages.' ), self::__( '<b>Shortcodes</b> – A list of shortcodes is provided with descriptions for each.' ), self::__( '<b>Update</b> - The select list can be used if you want to change the current message to a different type; it’s recommended you go to the message you want to edit instead of using this option. The Disabled option available to prevent this message from sending.' ) ),
				) );

			$screen->add_help_tab( array(
					'id' => 'message-advanced',
					'title' => self::__( 'Advanced' ),
					'content' => sprintf( '<p><b>HTML Emails</b> - Enable HTML messages within the <a href="%s">General Settings</a> page. Make sure to change use HTML on all messages.</p>', admin_url( 'admin.php?page=sprout-apps/settings' ) ),
				) );

			$screen->add_help_tab( array(
					'id' => 'message-refresh',
					'title' => self::__( 'Notifications Cleanup' ),
					'content' => sprintf( '<p>%s</p><p><span class="cache_button_wrap casper clearfix"><a href="%s">%s</a></span></p></p>', sc__( 'In an earlier version of Sprout Clients numerous messages were improperly created. Click refresh below to delete all extraneous messages. Backup any modifications that you might have made to your messages before continuing.' ), esc_url( add_query_arg( array( 'refresh-messages' => 1 ) ) ), sc__( 'Clean' ) )
				) );

			$screen->set_help_sidebar(
				sprintf( '<p><strong>%s</strong></p>', self::__( 'For more information:' ) ) .
				sprintf( '<p><a href="%s" class="button">%s</a></p>', 'https://sproutapps.co/support/knowledgebase/sprout-invoices/messages/', self::__( 'Documentation' ) ) .
				sprintf( '<p><a href="%s" class="button">%s</a></p>', 'https://sproutapps.co/support/', self::__( 'Support' ) )
			);
		}
	}

}