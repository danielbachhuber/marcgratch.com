<?php if ( is_a( $recurring_payment, 'SI_Payment' ) ): ?>

	<?php do_action( 'recurring_payments_profile_info', $recurring_payment ) ?>

	<p><?php printf( '<a class="payments_link button" title="%s" href="%s&s=%s">%s</a>', __( 'Review Payment', 'sprout-invoices' ), get_admin_url( '','/edit.php?post_type=sa_invoice&page=sprout-apps/invoice_payments' ), $recurring_payment->get_id(), __( 'Recurring Payment', 'sprout-invoices' ) ); ?></p>

<?php else: ?>
	<div id="recurring_invoice_options_wrap" class="admin_fields clearfix">
		<?php sa_admin_fields( $fields, 'recurring_payments' ); ?>
	</div>
<?php endif ?>