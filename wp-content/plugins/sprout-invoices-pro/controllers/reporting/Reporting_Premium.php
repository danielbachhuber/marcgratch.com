<?php


/**
 * Send notifications, apply shortcodes and create management screen.
 *
 * @package Sprout_Invoice
 * @subpackage Reporting
 */
class SI_Reporting_Premium extends SI_Reporting {

	public static function init() {

		add_action( 'wp_head', array( __CLASS__, 'init_data_tables' ) );
		
		// premium views
		add_filter( 'sprout_invoice_template_admin/reports/invoices.php', array( __CLASS__, 'premium_view_invoices' ) );
		add_filter( 'sprout_invoice_template_admin/reports/estimates.php', array( __CLASS__, 'premium_view_estimates' ) );
		add_filter( 'sprout_invoice_template_admin/reports/payments.php', array( __CLASS__, 'premium_view_payments' ) );
		add_filter( 'sprout_invoice_template_admin/reports/clients.php', array( __CLASS__, 'premium_view_clients' ) );

		// premium views
		add_filter( 'sprout_invoice_template_admin/dashboards/invoices.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/payments-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/balances-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/payments-status-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/invoices-status-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/estimates.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/estimates-invoices-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/requests-converted-chart.php', array( __CLASS__, 'premium_dash_view' ) );
		add_filter( 'sprout_invoice_template_admin/dashboards/estimates-status-chart.php', array( __CLASS__, 'premium_dash_view' ) );

		// Enqueue
		add_action( 'init', array( __CLASS__, 'register_resources' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue' ) );
		add_action( 'admin_head', array( __CLASS__, 'init_data_tables' ) );
	}

	public static function premium_view_invoices() {
		return self::locate_premium_template('admin/reports/premium/invoices.php');
	}

	public static function premium_view_estimates() {
		return self::locate_premium_template('admin/reports/premium/estimates.php');
	}

	public static function premium_view_payments() {
		return self::locate_premium_template('admin/reports/premium/payments.php');
	}

	public static function premium_view_clients() {
		return self::locate_premium_template('admin/reports/premium/clients.php');
	}

	public static function premium_view_dashboard() {
		return self::locate_premium_template('admin/reports/premium/dashboard.php');
	}

	public static function premium_dash_view( $file = '' ) {
		$file = str_replace( '/dashboards', '/dashboards/premium', $file );
		return $file;
	}

	public static function locate_premium_template( $view ) {
		$file = apply_filters( 'si_locate_premium_template', SI_PATH.'/views/'.$view, $view );
		if ( defined( 'TEMPLATEPATH' ) ) {
			$file = self::locate_template( array( $view ), $file );
		}
		return $file;
	}

	////////////
	// admin //
	////////////

	public static function register_resources() {
		// Table filtering
		wp_register_style( 'datatables', SI_URL . '/resources/admin/plugins/datatables/media/css/jquery.dataTables.css', array(), self::SI_VERSION  );
		wp_register_script( 'datatables', SI_URL . '/resources/admin/plugins/datatables/media/js/jquery.dataTables.js', array( 'jquery' ), false, false );
		wp_register_style( 'tabletools', SI_URL . '/resources/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css', array(), self::SI_VERSION  );
		wp_register_script( 'tabletools', SI_URL . '/resources/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js', array( 'jquery', 'datatables' ), false, false );
		wp_register_style( 'responsive_dt', SI_URL . '/resources/admin/plugins/datatables/extensions/Responsive/css/responsive.dataTables.css', array(), self::SI_VERSION  );
		wp_register_script( 'responsive_dt', SI_URL . '/resources/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js', array( 'jquery', 'datatables' ), false, false );
		wp_register_style( 'colvis_dt', SI_URL . '/resources/admin/plugins/datatables/extensions/ColVis/css/dataTables.colVis.min.css', array(), self::SI_VERSION  );
		wp_register_script( 'colvis_dt', SI_URL . '/resources/admin/plugins/datatables/extensions/ColVis/js/dataTables.colVis.min.js', array( 'jquery', 'datatables' ), false, false );

	}

	public static function admin_enqueue() {
		// Only on the report pages.
		if ( isset( $_GET[self::REPORT_QV] ) ) {
			wp_enqueue_style( 'datatables' );
			wp_enqueue_script( 'datatables' );
			wp_enqueue_style( 'tabletools' );
			wp_enqueue_script( 'tabletools' );
			wp_enqueue_style( 'responsive_dt' );
			wp_enqueue_script( 'responsive_dt' );
			wp_enqueue_style( 'colvis_dt' );
			wp_enqueue_script( 'colvis_dt' );
		}
	}

	/**
	 * Init the data tables, this function can be removed and modified so don't add
	 * anything but data table options.
	 * @return  
	 */
	public static function init_data_tables() {
		// If not on a report page don't add the below js.
		if ( !isset( $_GET[self::REPORT_QV] ) )
			return;
		?>
			<script type="text/javascript" charset="utf-8">
				jQuery(function($) {
					$(document).ready(function() {
						var table = $('#si_reports_table').dataTable( {
							stateSave: true,
							responsive: true,
							dom: 'CT<"clear">lfrtip',
							tableTools: {
								sSwfPath: '<?php echo SI_URL ?>/resources/admin/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'
							}
						} );

						$("#start_date").change(function() {	
							minDateFilter = new Date( this.value ).getTime();
							table.fnDraw();
						});

						$("#end_date").change(function() {
							maxDateFilter = new Date( this.value ).getTime();
							table.fnDraw();
						});

						// Date range filter
						minDateFilter = '';
						maxDateFilter = '';

						$.fn.dataTableExt.afnFiltering.push(
							function(oSettings, aData, iDataIndex) {
								if (typeof aData._date == 'undefined') {
									aData._date = new Date( aData[2] ).getTime()-(new Date( aData[2] ).getTimezoneOffset()*60000);
								}

								if (minDateFilter && !isNaN(minDateFilter)) {
									if (aData._date < minDateFilter) {
										return false;
									}
								}

								if (maxDateFilter && !isNaN(maxDateFilter)) {
									if (aData._date > maxDateFilter) {
										return false;
									}
								}

								return true;
							}
						);
					} );
				});
			</script>
		<?php
	}

}