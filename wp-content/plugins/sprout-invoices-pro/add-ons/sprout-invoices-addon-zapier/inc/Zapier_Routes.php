<?php

/**
 * Time_Tracking_Toggl Controller
 *
 * @package Sprout_Invoice
 * @subpackage Time_Tracking_Toggl
 */
class Zapier_Routes extends Zapier_Controller {
	const API_QUERY_VAR = 'si_zapier';
	const API_SLUG = 'zapier_api';

	public static function init() {
		self::register_query_var( self::API_QUERY_VAR, array( __CLASS__, 'api_callback' ) );
	}

	/**
	 * Technically not an endpoint until we start using permalinks but it will work.
	 *
	 * @param  string $endpoint
	 * @return
	 */
	public static function get_url( $endpoint = 'ping' ) {
		return add_query_arg( array( self::API_QUERY_VAR => $endpoint ), trailingslashit( home_url() ) . trailingslashit( self::API_SLUG ) );
	}

	/**
	 * Set callback to endpoint
	 *
	 * @return
	 */
	public static function api_callback() {
		if ( ! isset( $_REQUEST[self::API_QUERY_VAR] ) || $_REQUEST[self::API_QUERY_VAR] == '' ) {
			return;
		}

		if ( ! self::DEBUG ) { // debug should skip authorization
			// Authentication
			if ( ! isset( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
				status_header( 403 );
				self::fail( 'Your API key is missing.' );
			}

			$api_key = Zapier_Settings::get_api_key();
			$auth = wp_parse_args( $_SERVER['HTTP_AUTHORIZATION'] );
			if ( $api_key != $auth['api_key'] ) {
				status_header( 403 );
				self::fail( 'Your API key is incorrect: ' . $key_sent );
			}
		}

		header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		header( 'Expires: '. gmdate( 'D, d M Y H:i:s', mktime( date( 'H' ) + 2, date( 'i' ), date( 's' ), date( 'm' ), date( 'd' ), date( 'Y' ) ) ) .' GMT' );
		header( 'Last-Modified: '. gmdate( 'D, d M Y H:i:s' ) .' GMT' );
		header( 'Cache-Control: no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );

		$data = json_decode( file_get_contents( 'php://input' ) );

		switch ( $_REQUEST[self::API_QUERY_VAR] ) {
			case 'ping':
				$response = self::ping( $data );
				break;
			case 'invoice':
				$response = self::invoice( $data );
				break;
			case 'estimate':
				$response = self::estimate( $data );
				break;
			case 'payment':
				$response = self::payment( $data );
				break;
			case 'client':
				$response = self::client( $data );
				break;
			case 'create_invoice':
				$response = self::create_invoice( $data );
				break;
			case 'create_estimate':
				$response = self::create_estimate( $data );
				break;
			case 'create_payment':
				$response = self::create_payment( $data );
				break;
			case 'create_client':
				$response = self::create_client( $data );
				break;
			// case 'fields':
				// $response = self::fields( $data );
				// break;
			case 'subscribe':
				$response = self::subscribe_zap( $data );
				break;
			case 'unsubscribe':
			case 'delete':
				$response = self::unsubscribe_zap( $data );
				break;

			default:
				status_header( 409 );
				self::fail( 'Not a valid endpoint.' );
				break;
		}

		echo wp_json_encode( $response );
		exit();

	}

	////////////////
	// Endpoints //
	////////////////

	/**
	 * Ping
	 *
	 */
	private static function ping() {
		return array(
			'status' => 'verified'
		);
	}

	public static function create_invoice( $data = array() ) {
		$invoice_id = SI_Invoice::create_invoice( $data );
		$invoice = SI_Invoice::get_instance( $invoice_id );
		if ( ! is_a( $invoice, 'SI_Invoice' ) ) {
			return;
		}
		return self::invoice_data( $invoice );
	}

	public static function create_estimate( $data = array() ) {
		$estimate_id = SI_Estimate::create_estimate( $data );
		$estimate = SI_Estimate::get_instance( $estimate_id );
		if ( ! is_a( $estimate, 'SI_Estimate' ) ) {
			return;
		}
		return self::estimate_data( $estimate );
	}

	public static function create_payment( $data = array() ) {
		$payment_id = SI_Payment::new_payment( $data );
		$payment = SI_Payment::get_instance( $payment_id );
		if ( ! is_a( $payment, 'SI_Payment' ) ) {
			return;
		}
		return self::payment_data( $payment );
	}

	public static function create_client( $data = array() ) {
		$client_id = SI_Client::new_client( $data );
		$client = SI_Client::get_instance( $client_id );
		if ( ! is_a( $client, 'SI_Client' ) ) {
			return;
		}
		return self::client_data( $client );
	}

	public static function invoice( $data = array() ) {
		if ( ! isset( $data['id'] ) ) {
			$data['id'] = $_GET['id'];
		}
		$invoice = SI_Invoice::get_instance( $data['id'] );
		if ( ! is_a( $invoice, 'SI_Invoice' ) ) {
			return;
		}
		return self::invoice_data( $invoice );
	}

	public static function estimate( $data = array() ) {
		if ( ! isset( $data['id'] ) ) {
			$data['id'] = $_GET['id'];
		}
		$estimate = SI_Estimate::get_instance( $data['id'] );
		if ( ! is_a( $estimate, 'SI_Estimate' ) ) {
			return;
		}
		return self::estimate_data( $estimate );
	}

	public static function payment( $data = array() ) {
		if ( ! isset( $data['id'] ) ) {
			$data['id'] = $_GET['id'];
		}
		$payment = SI_Payment::get_instance( $data['id'] );
		if ( ! is_a( $payment, 'SI_Payment' ) ) {
			return;
		}
		return self::payment_data( $payment );
	}

	public static function client( $data = array() ) {
		if ( ! isset( $data['id'] ) ) {
			$data['id'] = $_GET['id'];
		}
		$client = SI_Client::get_instance( $data['id'] );
		if ( ! is_a( $client, 'SI_Client' ) ) {
			return;
		}
		return self::client_data( $client );
	}

	////////////////////
	// Custom Fields //
	////////////////////

	public static function fields() {
		$zap_fields = array();

		$field_map = array(
		   'text' => 'text',
		   'textarea' => 'text',
		   'number' => 'decimal',
		   'date' => 'datetime',
		);

		foreach ( $fields as $field ) {
			$zap_fields[] = array(
				'type' => 'unicode',
				'key' => 'x'. $field->field_key, // make sure key starts with an alpha
				'required' => ($field->required ? true : false),
				'label' => $field->name,
				'help_text' => $field->description,
				'default' => $field->default_value,
			);
		}
		return $zap_fields;
	}

	//////////
	// Zaps //
	//////////

	public static function subscribe_zap( $data ) {
		if ( ! isset( $data->target_url ) ) {
			status_header( 409 );
			return self::fail( 'No target URL provided' );
		}

		$zap_id = self::new_zap( $data, $data->event );
		status_header( 201 );
		return array( 'id' => $zap_id );
	}

	public static function unsubscribe_zap( $data ) {
		if ( ! isset( $data->target_url ) ) {
			status_header( 409 );
			return self::fail( 'No target URL provided' );
		}
		$zap_id = self::get_zap_by_target_url( $data->target_url );
		if ( ! is_numeric( $zap_id ) ) {
			status_header( 409 );
			return self::fail( 'No zap found for deletion' );
		}
		self::delete_zap( $zap_id );

		status_header( 201 );
		return array( 'id' => $zap_id );
	}


	/////////////
	// Utility //
	/////////////

	/**
	 * Failed
	 * @param  string $message
	 * @return json
	 */
	public static function fail( $message = '' ) {
		if ( $message == '' ) {
			$message = __( 'Something failed.', 'sprout-invoices' );
		}
		header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		if ( self::DEBUG ) { header( 'Access-Control-Allow-Origin: *' ); }
		echo wp_json_encode( array( 'error' => $message ) );
		exit();
	}

}