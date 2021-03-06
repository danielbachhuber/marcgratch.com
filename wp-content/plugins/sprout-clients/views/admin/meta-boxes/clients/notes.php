<div id="private_note_wrap">
	<p>
		<div class="clearfix">
			<a href="<?php echo esc_url( remove_query_arg( 'history_page', add_query_arg( array( 'history_type' => SC_Controller::PRIVATE_NOTES_TYPE ) ) ) ) ?>" title="<?php self::_e('Save Private Note'); ?>" class="button filter_history" id="view_all_notes"><?php self::_e( 'View All Private Notes' ); ?></a>
		</div>
		<textarea id="private_note" name="private_note" class="si_redactorize clearfix"></textarea>
		<div class="private_note_save_wrap clearfix">
			<a href="javascript:void(0)" id="save_private_client_note" class="button" data-post-id="<?php the_ID() ?>"><?php self::_e( 'Save' ) ?></a>&nbsp;<span class="helptip client_note_help" data-sa-dropdown="#client_note_help"></span>
			<div id="client_note_help" class="sa-dropdown sa-dropdown-tip sa-dropdown-relative">
				<div class="sa-dropdown-panel">
					<?php self::_e( 'These private notes will be added to the history.' ) ?>
				</div>
			</div>
		</div>
	</p>
</div>