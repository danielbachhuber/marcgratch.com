;(function( $, sc, undefined ) {

	sc.clientEdit = {
		config: {
		},
	};

	sc.clientSettings = {
		config: {
		},
	};

	sc.clientEdit.changeType = function( $selection ) {
		var $selection_wrap = $selection.closest('.type_change_selection'),
			$button_wrap = $selection.closest('.sc_type_update'),
			item_id = $selection_wrap.data('item-id'),
			nonce = $selection_wrap.data('nonce'),
			type_id = $selection.data('type-id');
		
		$.post( ajaxurl, { action: 'sc_change_client_type', type_id: type_id, client_id: item_id, security: nonce },
			function( data ) {
				if ( data.error ) {
					$selection_wrap.html( data.response );	
				}
				else {
					// swap out the button with the new one
					$button_wrap.parent().html( data );
				};
				return data;
			}
		);
	};

	sc.clientEdit.updateStatusOption = function( $selection, context ) {
		var $term_id = $selection.val(),
			$selections = $selection.closest('.status_change_selection'),
			$selections_wrap = $selection.closest('.sc_statuses_update'),
			item_id = $selections.data('item-id'),
			nonce = $selections.data('nonce'),
			status_id = $selection.val();
		
		$.post( ajaxurl, { action: 'sc_edit_client_status', type_id: status_id, client_id: item_id, security: nonce, context: context },
			function( data ) {
				if ( data.error ) {
					$selections_wrap.html( data.response );	
				}
				if ( 'add' === context ) {
					$( '#current_statuses_' + item_id + '.sc_current_statuses .sc_status.status_id_' + status_id ).addClass('current');
				}
				else {
					$( '#current_statuses_' + item_id + '.sc_current_statuses .sc_status.status_id_' + status_id ).removeClass('current');	
				};
				return true;
			}
		);
	};

	sc.clientEdit.associateUsers = function( $select ) {
		var $option = $select.find('option:selected'),
			user_id = $option.val(),
			client_id = $option.data('client-id');

		sc.clientEdit.refreshAssociatedUserMetaBox( user_id, client_id );
	};

	sc.clientEdit.unassociateUser = function( $button ) {
		var user_id = $button.data('id'),
			client_id = $button.data('client-id');

		sc.clientEdit.refreshAssociatedUserMetaBox( user_id, client_id );
	};

	sc.clientEdit.refreshAssociatedUserMetaBox = function( user_id, client_id ) {
		var $widget = $('#si_client_users .inside');
		$.post( ajaxurl, { action: 'sc_associate_user', user_id: user_id, client_id: client_id, security: _sprout_clients.security, return: 'meta_box' },
			function( data ) {
				if ( data.error ) {
					$select.html( data.response );
				}
				else {
					$widget.html( data.view );
				}

				$('#si_client_users .si_redactorize').redactor();
				//sc.clientEdit.select2_init();
				return data;
			}
		);
	};

	sc.clientEdit.createUser = function( $button ) {
		var $fields = $( "#user_create_form :input" ).serializeArray(),
			$client_id = $( "#sa_user_client_id" ).val(),
			$save_button_og_text = $button.text();

		$button.after( _sprout_clients.spinner );
		$.post( ajaxurl, { action: 'sa_create_user', serialized_fields: $fields },
			function( response ) {
				$('.sc_spinner').remove();
				$('.inline_error_message').remove();
				if ( ! response.success ) {
					$button.after('<span class="inline_error_message">' + response.data.error_message + '</span>');	
				}
				else {
					sc.clientEdit.refreshAssociatedUserMetaBox( response.data.user_id, response.data.client_id );
					self.parent.tb_remove();
				}
			}
		);
	};

	sc.clientEdit.saveUserNote = function( $button ) {
		var user_id = $button.data('user-id'),
			note = $('#user_note_field_' + user_id).val();

		$button.after( _sprout_clients.spinner );
		$.post( ajaxurl, { action: 'sc_save_user_note', user_id: user_id, note: note, security: _sprout_clients.security },
			function( data ) {
				$('.sc_spinner').remove();
				return data;
			}
		);
	};

	sc.clientEdit.createNote = function( $add_button ) {
		var post_id = $add_button.data( 'post-id' ),
			nonce = _sprout_clients.security,
			$private_note = $( '[name="private_note"]' ),
			add_button_og_text = $add_button.text();
		$add_button.html( '' );
		$add_button.append( _sprout_clients.spinner );
		$.post( ajaxurl, { action: 'sa_create_client_private_note', associated_id: post_id, notes: $private_note.val(), security: nonce },
			function( response ) {
				if ( response.success ) {
					var tr = '<tr><td>' + response.data.type + '</td><td>' + response.data.post_date + '</td><td>' + response.data.content + '</td><td>&nbsp;</td></tr>';
					$('#client_history tbody').prepend( tr );
					$private_note.val('');

				}
				else {
					$add_button.after('<span class="inline_message inline_error_message">' + response.data.message + '</span>');
				};

				$add_button.html( add_button_og_text );
				return true;
			}
		);
	};

	sc.clientEdit.deleteRecord = function( button ) {
		var $button = $(button),
			record_id = $button.data( 'id' ),
			$record_wraps = $( '.record-' + record_id ),
			nonce = _sprout_clients.security;

		$.post( ajaxurl, { action: 'si_delete_record', record_id: record_id, nonce: nonce },
			function( response ) {
				console.log(response);
				if ( response.error ) {
					console.log( response.error );
				}
				else {
					$record_wraps.fadeOut();
				}
			}
		);
	};

	sc.clientEdit.editPrivateNote = function( button ) {
		var $button = $(button),
			record_id = $button.data( 'id' ),
			private_note = $( '#sa_note_note' ).val(),
			nonce = _sprout_clients.security;

		$('span.inline_error_message').hide();
		$button.after( _sprout_clients.spinner );
		$.post( ajaxurl, { action: 'si_edit_private_note', record_id: record_id, private_note: private_note, nonce: nonce },
			function( response ) {
				$('.sc_spinner').remove();
				$('.inline_error_message').remove();
				if ( response.error ) {
					$button.after('<span class="inline_error_message">' + response.response + '</span>');	
				}
				else {
					// close modal
					self.parent.tb_remove();
					$( '.record-' + record_id + ' p:first-of-type' ).html( private_note );
				}
			}
		);
	};

	sc.clientEdit.select2_init = function() {
		$('.sa_select2').select2({
			// Support for optgroup searching
			matcher: function(term, optText, els) {
				var allText = optText + els[0].parentNode.getAttribute('label')  || '';
				return (''+allText).toUpperCase().indexOf((''+term).toUpperCase()) >= 0;
			}
		});
	};

	/**
	 * Edit Management Methods
	 */
	sc.clientEdit.init = function() {

		/**
		 * select2 init
		 */
		sc.clientEdit.select2_init();

		/**
		 * Remove user and hidden option associated list
		 */
		$('.item_add_type').live('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.changeType( $( this ) );
		});

		/**
		 * Remove user and hidden option associated list
		 */
		$('.sc_statuses_update input').live('change', function(e) {
			var $selection = $( this );
			if ( $selection.is(':checked') ) {
				sc.clientEdit.updateStatusOption( $selection, 'add' );
			}
			else {
				sc.clientEdit.updateStatusOption( $selection, 'remove' );
			};
		});

		// Associate Users
		$('#associated_users').live('change', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.associateUsers( $( this ) );
		});

		/**
		 * Remove user and hidden option associated list
		 */
		$('.unassociate_user').live('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.unassociateUser( $( this ) );
		});

		/**
		 * Create user via ajax
		 */
		$('#sc_create_user').live('click', function(e) {
			sc.clientEdit.createUser( $(this) );
		});

		/**
		 * Submit user note
		 */
		$('.submit_user_note').live('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.saveUserNote( $( this ) );
		});

		// Create private note
		$("#save_private_client_note").on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.createNote( $( this ) );
		});

		/**
		 * Show save option
		 */
		$('.sc_user_note').live('focus', function(e) {
			var user_id = $(this).data('user-id'),
				$button = $('#submit_user_note_'+user_id);
			$button.fadeIn();
		});


		/**
		 * delete client history record
		 */
		$('.delete_client_record').live( 'click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.deleteRecord( this );
		});

		/**
		 * edit private note
		 */
		$('#save_edit_private_note').live( 'click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			sc.clientEdit.editPrivateNote( this );
		});

		/**
		 * WYSIWYG
		 */
		if ( _sprout_clients.redactor ) {
			$('.si_redactorize').redactor();
		};
	}

	/**
	 * Setting Methods
	 */
	sc.clientSettings.init = function() {

		/**
		 * License Activation
		 */
		$('#sc_activate_license').on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			var $button = $( this ),
				$license_key = $('#sc_license_key').val(),
				$license_message = $('#license_message');

			$button.hide();
			$button.after(_sprout_clients.spinner);
			$.post( ajaxurl, { action: 'sc_activate_license', license: $license_key, security: _sprout_clients.security },
				function( data ) {
					if ( data.error ) {
						$button.show();
						$license_message.html('<span class="inline_error_message">' + data.response + '</span>');	
					}
					else {
						$license_message.html('<span class="inline_success_message">' + data.response + '</span>');
					}
					$('.sc_spinner').hide();
				}
			);
		});

		/**
		 * License Deactivation
		 */
		$('#sc_deactivate_license').on('click', function(e) {
			e.stopPropagation();
			e.preventDefault();
			var $button = $( this ),
				$activate_button = $('#sc_activate_license');
				$license_key = $('#sc_license_key').val(),
				$license_message = $('#license_message');

			$button.hide();
			$button.after(_sprout_clients.spinner);
			$.post( ajaxurl, { action: 'sc_deactivate_license', license: $license_key, security: _sprout_clients.security },
				function( data ) {
					if ( data.error ) {
						$button.show();
						$license_message.html('<span class="inline_error_message">' + data.response + '</span>');	
					}
					else {
						$activate_button.hide();
						$activate_button.removeAttr('disabled').addClass('button-primary').fadeIn();
						$license_message.html('<span class="inline_success_message">' + data.response + '</span>');
					}
					$('.sc_spinner').hide();
				}
			);
		});
	}


})( jQuery, window.sc = window.sc || {} );

// Init
jQuery(function() {
	sc.clientEdit.init();
	sc.clientSettings.init();
});