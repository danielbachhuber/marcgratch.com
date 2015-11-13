<?php

/**
 * SI_Subscription_Payments Controller
 *
 * @package Sprout_Invoice
 * @subpackage Subscription_Payments
 */
class SI_Subscription_Payments extends SI_Controller {

	private static $meta_keys = array(
		'has_subscription' => '_si_has_subscription_payments', // int
		'token' => '_si_subscription_payments_token', // int
		'duration' => '_si_subscription_duration', // int
		'term' => '_si_subscription_term', // string
		'renew_price' => '_si_subscription_renew_price', // bool
	);

	public static function init() {

		if ( is_admin() ) {
			// Meta boxes
			add_action( 'admin_init', array( __CLASS__, 'register_meta_boxes' ) );

			// Enqueue
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_resources' ) );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue' ), 20 );

			add_action( 'si_estimate_acceptance_fields', array( __CLASS__, 'add_estimate_acceptance_meta_fields' ) );
			add_action( 'save_estimate_acceptance_meta', array( __CLASS__, 'save_estimate_acceptance_meta' ) );
		}

		add_action( self::CRON_HOOK, array( __CLASS__, 'do_something' ) );

		add_filter( 'is_invoice_recurring', array( __CLASS__, 'is_invoice_recurring' ), 10, 2 );

	}


	//////////////
	// Enqueue //
	//////////////

	public static function register_resources() {
		// admin js
		wp_register_script( 'si_subscription_payments', SA_ADDON_SUBSCRIPTION_PAYMENTS_URL . '/resources/admin/js/subscription_payments.js', array( 'jquery' ), self::SI_VERSION );
	}

	public static function admin_enqueue() {
		wp_enqueue_script( 'si_subscription_payments' );
	}

	/////////////////
	// Meta boxes //
	/////////////////

	/**
	 * Regsiter meta boxes for estimate editing.
	 *
	 * @return
	 */
	public static function register_meta_boxes() {
		// estimate specific
		$args = array(
			'si_subscription_payments' => array(
				'title' => __( 'Recurring Invoice Payment', 'sprout-invoices' ),
				'show_callback' => array( __CLASS__, 'show_subscription_payments_meta_box' ),
				'save_callback' => array( __CLASS__, 'save_meta_box_subscription_payments' ),
				'context' => 'normal',
				'priority' => 'low',
				'weight' => 0,
				'save_priority' => 0,
			)
		);
		do_action( 'sprout_meta_box', $args, SI_Invoice::POST_TYPE );
	}

	/**
	 * Show time tracking metabox.
	 * @param  WP_Post $post
	 * @param  array $metabox
	 * @return
	 */
	public static function show_subscription_payments_meta_box( $post, $metabox ) {
		$invoice = SI_Invoice::get_instance( $post->ID );
		$recurring_payment = SI_Payment_Processors::get_recurring_payment( $invoice );

		self::load_addon_view( 'admin/meta-boxes/invoices/subscription-payments', array(
				'invoice_id' => $post->ID,
				'recurring_payment' => $recurring_payment,
				'fields' => self::subscription_options( $invoice )
			), false );

	}

	public static function save_meta_box_subscription_payments( $post_id, $post, $callback_args, $invoice_id = null ) {

		self::set_as_not_subscription( $post_id );

		$term = ( isset( $_POST['sa_recurring_payments_term'] ) ) ? $_POST['sa_recurring_payments_term'] : '' ;
		self::set_term( $post_id, $term );

		$duration = ( isset( $_POST['sa_recurring_payments_duration'] ) ) ? $_POST['sa_recurring_payments_duration'] : '' ;
		self::set_duration( $post_id, $duration );

		if ( isset( $_POST['sa_recurring_payments_is_recurring_payment'] ) && $_POST['sa_recurring_payments_is_recurring_payment'] ) {
			self::set_subscription( $post_id );
		}
	}

	public static function subscription_options( $doc ) {
		$fields = array();

		$doc_id = $doc->get_id();
		$is_recurring = self::has_subscription_payment( $doc_id );

		$term_meta = self::get_term( $doc_id );
		$term = ( $term_meta ) ? $term_meta : 'month' ;

		$duration_meta = self::get_duration( $doc_id );
		$duration = ( $duration_meta ) ? $duration_meta : 1 ;

		$fields['is_recurring_payment'] = array(
			'weight' => 200,
			'label' => __( 'Recurring Payment', 'sprout-invoices' ),
			'type' => 'checkbox',
			'default' => $is_recurring,
			'value' => '1',
			'description' => __( 'This will enable recurring payments.', 'sprout-invoices' ),
		);

		$fields['term'] = array(
			'weight' => 210,
			'label' => __( 'Term', 'sprout-invoices' ),
			'type' => 'select',
			'options' => array(
					'day' => 'Day',
					'week' => 'Week',
					//'bymonth' => 'SemiMonth',
					'month' => 'Month',
					'year' => 'Year',
				),
			'default' => $term,
			'description' => __( 'Unit for billing during this subscription period.', 'sprout-invoices' ),
		);

		$fields['duration'] = array(
			'weight' => 220,
			'label' => __( 'Duration', 'sprout-invoices' ),
			'type' => 'input',
			'default' => $duration,
			'attributes' => array( 'class' => 'small-input' ),
			'description' => __( 'Number of billing periods that make up one billing cycle. Note: If the billing period is SemiMonth, the billing frequency must be 1.', 'sprout-invoices' ),
		);
		

		$fields = apply_filters( 'si_subscription_payments_submission_fields', $fields );
		uasort( $fields, array( __CLASS__, 'sort_by_weight' ) );
		return $fields;
	}

	////////////
	// Hooks //
	////////////

	public static function is_invoice_recurring( $bool, SI_Invoice $invoice ) {
		return self::has_subscription_payment( $invoice->get_id() );
	}

	public static function invoice_has_subscription_payments( $bool, SI_Invoice $invoice, SI_Payment $payment ) {
		// Set the payment token on the invoice.
		$data = $payment->get_data();
		if ( isset( $data['payment_token'] ) ) {
			self::set_token( $invoice->get_id(), $data['payment_token'] );
			// has subscription options
			return self::has_subscription_payment( $invoice->get_id() );
		}
		return $bool;
	}


	/////////////////////
	// Scheduled Task //
	/////////////////////

	public static function do_something() {
		// query all recurring payments and check the status.
		
	}


	///////////
	// Meta //
	///////////

	/**
	 * Is Recurring
	 */
	public static function has_subscription_payment( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$bool = $doc->get_post_meta( self::$meta_keys['has_subscription'] );
		if ( $bool != 1 ) {
			$bool = false;
		}
		return $bool;
	}

	public static function set_subscription( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['has_subscription'] => 1,
			) );
		return 1;
	}

	public static function set_as_not_subscription( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['has_subscription'] => 0,
			) );
		return 1;
	}

	/**
	 * token
	 */
	public static function get_token( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$token = $doc->get_post_meta( self::$meta_keys['token'] );
		return $token;
	}

	public static function set_token( $doc_id = 0, $token = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['token'] => $token,
			) );
		return $token;
	}

	/**
	 * duration
	 */
	public static function get_duration( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$duration = $doc->get_post_meta( self::$meta_keys['duration'] );
		return $duration;
	}

	public static function set_duration( $doc_id = 0, $duration = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['duration'] => $duration,
			) );
		return $duration;
	}

	/**
	 * term
	 */
	public static function get_term( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$term = $doc->get_post_meta( self::$meta_keys['term'] );
		return $term;
	}

	public static function set_term( $doc_id = 0, $term = '' ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['term'] => $term,
			) );
		return $term;
	}

	/**
	 * renew_price
	 */
	public static function get_renew_price( $doc_id = 0 ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$renew_price = $doc->get_post_meta( self::$meta_keys['renew_price'] );
		if ( $renew_price == '' ) {
			$renew_price = $doc->get_calculated_total();
		}
		return $renew_price;
	}

	public static function set_renew_price( $doc_id = 0, $renew_price = '' ) {
		$doc = si_get_doc_object( $doc_id );
		if ( ! is_object( $doc ) ) {
			return 0;
		}
		$doc->save_post_meta( array(
				self::$meta_keys['renew_price'] => $renew_price,
			) );
		return $renew_price;
	}



	/////////////////////////
	// Estimate Acceptance //
	/////////////////////////

	public static function add_estimate_acceptance_meta_fields( $fields = array() ) {
		$estimate = SI_Estimate::get_instance( get_the_id() );
		if ( ! is_a( $estimate, 'SI_Estimate' ) ) {
			return $fields;
		}
		$sub_fields = self::subscription_options( $estimate );
		$sub_fields['subscription_heading'] = array(
			'weight' => 199,
			'label' => __( 'Subscription Payments Settings', 'sprout-invoices' ),
			'type' => 'heading',
		);
		$fields = array_merge( $sub_fields, $fields );
		uasort( $fields, array( __CLASS__, 'sort_by_weight' ) );
		return $fields;
	}

	public static function save_estimate_acceptance_meta( $post_id = 0 ) {
		self::set_as_not_subscription( $post_id );

		$term = ( isset( $_POST['sa_estimate_acceptance_term'] ) ) ? $_POST['sa_estimate_acceptance_term'] : '' ;
		self::set_term( $post_id, $term );

		$duration = ( isset( $_POST['sa_estimate_acceptance_duration'] ) ) ? $_POST['sa_estimate_acceptance_duration'] : '' ;
		self::set_duration( $post_id, $duration );

		if ( isset( $_POST['sa_estimate_acceptance_is_recurring_payment'] ) && $_POST['sa_estimate_acceptance_is_recurring_payment'] ) {
			self::set_subscription( $post_id );
		}
	}



	//////////////
	// Utility //
	//////////////

	public static function load_addon_view( $view, $args, $allow_theme_override = true ) {
		add_filter( 'si_views_path', array( __CLASS__, 'addons_view_path' ) );
		$view = self::load_view( $view, $args, $allow_theme_override );
		remove_filter( 'si_views_path', array( __CLASS__, 'addons_view_path' ) );
		return $view;
	}

	public static function addons_view_path() {
		return SA_ADDON_SUBSCRIPTION_PAYMENTS_PATH . '/views/';
	}

}