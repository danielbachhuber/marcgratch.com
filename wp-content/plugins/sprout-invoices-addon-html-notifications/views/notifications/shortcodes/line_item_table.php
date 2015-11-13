<table class="invoice-items" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0; padding: 0;">
<?php foreach ( $line_items as $position => $data ) :
		// This is were the loop is for all the items.
		// Variables for each line item info
		$desc = ( isset( $data['desc'] ) ) ? apply_filters( 'the_content', $data['desc'] ) : '' ;
		$li_total = ( isset( $data['total'] ) ) ? $data['total'] : 0 ; ?>

	<tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;"><td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" valign="top"><?php echo $desc ?></td>
		<td class="alignright" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;" align="right" valign="top"><p><?php sa_formatted_money( $li_total, $doc_id ) ?></p></td>
	</tr>
<?php endforeach ?>
<?php if ( $subtotal ) : ?>
	<tr class="total" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;"><td class="alignright" width="80%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #BBB; border-bottom-width: 1px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top"><?php si_e( 'Subtotal' ) ?></td>
		<td class="alignright" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid;  border-bottom-color: #BBB; border-bottom-width: 1px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top"><?php sa_formatted_money( $subtotal, $doc_id ) ?></td>
	</tr>
<?php endif ?>
<tr class="total" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;"><td class="alignleft" width="80%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="left" valign="top"><?php si_e( 'Total' ) ?></td>
	<td class="alignright" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top"><?php sa_formatted_money( $total, $doc_id ) ?></td>
<?php if ( $due ) : ?>
	<tr class="total" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; padding: 0;"><td class="alignright" width="80%" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top"><?php si_e( 'Total Due' ) ?></td>
	<td class="alignright" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; font-weight: 700; margin: 0; padding: 5px 0;" align="right" valign="top"><?php sa_formatted_money( $due, $doc_id ) ?></td>
</tr>
<?php endif ?>
</table>