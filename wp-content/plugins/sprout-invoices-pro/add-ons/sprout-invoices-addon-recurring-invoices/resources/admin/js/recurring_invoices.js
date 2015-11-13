;(function( $, si, undefined ) {

	si.recurringInvoices = {
		config: {
			'freq_select': '#sa_recurring_invoice_frequency',
		},
	};

	si.recurringInvoices.maybeToggleDayInput = function( select ) {
		var $select = $(select),
			frequency = $select.val();

		if ( frequency === 'custom' ) {
			$('#sa_recurring_invoice_custom_freq').closest('.form-group').slideDown('fast');
		}
		else {
			$('#sa_recurring_invoice_custom_freq').closest('.form-group').hide();
		};
	};


	/**
	 * methods
	 */
	si.recurringInvoices.init = function() {

		si.recurringInvoices.maybeToggleDayInput( $( si.recurringInvoices.config.freq_select ) );

		$( si.recurringInvoices.config.freq_select ).live( 'change', function( e ) {
			si.recurringInvoices.maybeToggleDayInput( this );
		} );
	};
	
})( jQuery, window.si = window.si || {} );

// Init
jQuery(function() {
	si.recurringInvoices.init();
});
