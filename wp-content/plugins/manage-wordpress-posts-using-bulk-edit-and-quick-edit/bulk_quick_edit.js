(function($) {

	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;
	var $wp_inline_save = inlineEditPost.save;
	var $the_list = $('#the-list');
	var spinner = $( '.spinner').clone().css({float: "left"});

	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {
		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );
		
		// now we take care of our business

		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' )
			$post_id = parseInt( this.getId( id ) );


		if ( $post_id > 0 ) {
		
			// define the edit row
			var $edit_row = $( '#edit-' + $post_id );
			
			// get the release date
			var $issue_type = $( '#issue_type-' + $post_id ).find(":selected").text();
			
			// set the release date
			$edit_row.find( 'select[name="issue_type"]' ).find( 'option[value="'+$issue_type+'"]').prop('selected',true);
			
			// get the release date
			var $priority = $( '#priority-' + $post_id).find(":selected").text();

			// set the film rating
			$edit_row.find( 'select[name="priority"]' ).find( 'option[value="'+$priority+'"]').prop('selected',true);

			var $estimated_time = $( '#estimated_time-' + $post_id).find(".editable").text();

			// set the estimated time
			$edit_row.find( 'input[name="estimated_time"]' ).val( $estimated_time);
			$edit_row.find( 'input[name="estimated_time"]' ).text( $estimated_time );


		}
	};

	inlineEditPost.save = function( id, referrer ) {

		$wp_inline_save.apply( this, arguments );

		var params, fields, page = $('.post_status_page').val() || '';

		if ( typeof(id) === 'object' ) {
			id = this.getId(id);
		}

		var $issue_type = $( '#issue_type-' + id ).find(":selected").text();
		var $priority = $( '#priority-' + id).find(":selected").text();
		var $estimated_time = $( '#estimated_time-' + id).find('input[name="estimated_time"]').val();

		$( 'table.widefat .spinner' ).addClass( 'is-active' );

		params = {
			action: 'inline_edit_mg_task_meta',
			post_type: typenow,
			post_ID: id,
			edit_date: 'true',
			post_status: page,
			issue_type: $issue_type,
			priority: $priority,
			estimated_time: $estimated_time,
			referrer: referrer
		};

		fields = $('#edit-'+id).find(':input').serialize();
		params = fields + '&' + $.param(params);

		// make ajax request
		$.post( ajaxurl, params )
			.done(function( response ) {
				var response = JSON.parse(response);
				$('table.widefat .spinner').removeClass('is-active');
				$('.ac_results').hide();
				if (response == 'undefined') {
					$('#edit-' + id + ' .inline-edit-save .error').html(inlineEditL10n.error).show();
				}
				else if ( response !== 1 && 'undefined' !== response.estimated_time ){
					var currentField =  $('#estimated_time-'+ response.post_id).find('.editable'),
						hours = +(response.estimated_time /60).toFixed(2) + 'h',
						row = currentField.parents('td');
					spinner.fadeOut('slow',function(){
						spinner.remove();
						currentField.text(hours).fadeIn('slow');
						currentField.next('input').val(hours);
					});
				}
			});
		return false;
	};


	$( '#bulk_edit' ).on( 'click', function() {
	
		// define the bulk edit row
		var $bulk_row = $( '#bulk-edit' );
		
		// get the selected post ids that are being edited
		var $post_ids = new Array();
		$bulk_row.find( '#bulk-titles' ).children().each( function() {
			$post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});

		// get the custom fields
		var $issue_type = $( '#issue_type-' + id ).find(":selected").text();
		var $priority = $( '#priority-' + id).find(":selected").text();
		var $estimated_time = $( '#estimated_time-' + id).find('input[name="estimated_time"]').val();

		// save the data
		$.ajax({
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'manage_wp_posts_using_bulk_quick_save_bulk_edit', // this is the name of our WP AJAX function that we'll set up next
				post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
				issue_type: $issue_type,
				priority: $priority,
				estimated_time: $estimated_time			}
		});
		
	});

	$($the_list).on( 'change', '.iedit select.pods-form-ui-field-type-pick', function( e, id ) {
		return inlineEditPost.save(this, $(e.target).attr('data-name-clean'));
	});

	$($the_list).on('click', 'td.estimated_time span.editable', function(evt){
		var elem = $(this);
		var oldElem = $("td.active").not(elem);
		oldElem.find('.editable').show();
		oldElem.find('input').prop('type','hidden');
		oldElem.find('span.save-options').hide();
		oldElem.removeClass('active');
		elem.parents('td').addClass('active');
		elem.hide();
		elem.siblings('input[name="estimated_time"]').prop('type','text');
		elem.siblings('span.save-options').show();
		evt.stopPropagation();
		return false;
	});

	$($the_list).on('click', 'td.estimated_time .dashicons-no', function(evt){
		var elem = $(this);
		elem.parents('td').removeClass('active');
		elem.parents('td').find('.editable').show();
		elem.parents('td').find('input').prop('type','hidden');
		elem.parents('span.save-options').hide();
		evt.stopPropagation();
		return false;
	});

	$('input[name="estimated_time"]').on('click',function(evt){
		evt.preventDefault();
		evt.stopPropagation();
	});

	$(document).on('click', function(evt){
		var elem = $("td.active");
		elem.find('.editable').show();
		elem.find('input').prop('type','hidden');
		elem.find('span.save-options').hide();
		elem.removeClass('active');
	});

	$($the_list).on( 'click', 'td.estimated_time .dashicons-yes', function( id ) {
		var row = $(this).parents('td'),
			input = row.find('input[name="estimated_time"]'),
			value = input.val();

		row.find('span.save-options').hide();
		input.prop('type','hidden');
		row.prepend(spinner.fadeIn('slow'));

		return inlineEditPost.save(this, 'estimated_time');
	});

})(jQuery);