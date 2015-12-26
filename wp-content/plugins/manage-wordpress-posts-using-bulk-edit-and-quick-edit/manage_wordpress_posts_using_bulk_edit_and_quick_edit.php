<?php

/**
 * Plugin Name: Manage WordPress Posts Using Bulk Edit and Quick Edit
 * Description: This is the code for a tutorial WP Dreamer wrote about managing WordPress posts using bulk and quick edit.
 * Author: WP Dreamer
 * Author URI: http://wpdreamer.com/2012/03/manage-wordpress-posts-using-bulk-edit-and-quick-edit/
 */
 
/**
 * I decided to convert the tutorial to a plugin format
 * so I could easily monitor it on my development environment
 * and store it on GitHub.
 *
 * With that said, you could easily take this code and paste it
 * into your theme's functions.php file. There is, however,
 * an included javascript file so be sure to check the
 * manage_wp_posts_be_qe_enqueue_admin_scripts()
 * function to confirm you're enqueueing the right javascript file.
 *
 * Also, after a few requests for custom field examples other than
 * text boxes, I updated the tutorial to include a select dropdown
 * and a radio button.
 *
 * Custom Fields:
 * 'Release Date - input text
 * 'Coming Soon' - input radio
 * 'Film Rating' - select dropdown
 *
 * If you find any issues with the tutorial, or code, please let me know. Thanks!
 */

/**
 * Since Bulk Edit and Quick Edit hooks are triggered by custom columns,
 * you must first add custom columns for the fields you wish to add, which are setup by
 * 'filtering' the column information.
 *
 * There are 3 different column filters: 'manage_pages_columns' for pages,
 * 'manage_posts_columns' which covers ALL post types (including custom post types),
 * and 'manage_{$post_type_name}_posts_columns' which only covers, you guessed it,
 * the columns for the defined $post_type_name.
 *
 * The 'manage_pages_columns' and 'manage_{$post_type_name}_posts_columns' filters only
 * pass $columns (an array), which is the column info, as an argument, but 'manage_posts_columns'
 * passes $columns and $post_type (a string).
 *
 * Note: Don't forget that it's a WordPress filter so you HAVE to return the first argument that's
 * passed to the function, in this case $columns. And for filters that pass more than 1 argument,
 * you have to specify the number of accepted arguments in your add_filter() declaration,
 * following the priority argument.
 *
 */
add_filter( 'manage_posts_columns', 'manage_wp_posts_be_qe_manage_posts_columns', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_columns( $columns, $post_type ) {

	/**
	 * The first example adds our new columns at the end.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 *
	 * Uncomment this code if you want to add your column at the end
	 */
	/*if ( $post_type == 'movies' ) {
		$columns[ 'release_date' ] = 'Release Date';
		$columns[ 'coming_soon' ] = 'Coming Soon';
		$columns[ 'film_rating' ] = 'Film Rating';
	}
		
	return $columns;*/
	
	/**
	 * The second example adds our new column after the �Title� column.
	 * Notice that we're specifying a post type because our function covers ALL post types.
	 */
	switch ( $post_type ) {
	
		case 'mg_task':
		
			// building a new array of column data
			$new_columns = array();
			
			foreach( $columns as $key => $value ) {
			
				// default-ly add every original column
				$new_columns[ $key ] = $value;
				
				/**
				 * If currently adding the title column,
				 * follow immediately with our custom columns.
				 */
				if ( $key == 'title' ) {
					$new_columns[ 'issue_type' ] = 'Issue Type';
					$new_columns[ 'priority' ] = 'Priority';
					$new_columns[ 'estimated_time' ] = 'Estimated Time';
					$new_columns[ 'project' ] = 'Project';
				}
					
			}
			
			return $new_columns;
			
	}
	
	return $columns;
	
}

/**
 * The following filter allows you to make your column(s) sortable.
 *
 * The 'edit-movies' section of the filter name is the custom part
 * of the filter name, which tells WordPress you want this to run
 * on the main 'movies' custom post type edit screen. So, e.g., if
 * your custom post type's name was 'books', then the filter name
 * would be 'manage_edit-books_sortable_columns'.
 *
 * Don't forget that filters must ALWAYS return a value.
 */
add_filter( 'manage_edit-mg_task_sortable_columns', 'manage_wp_posts_be_qe_manage_sortable_columns' );
function manage_wp_posts_be_qe_manage_sortable_columns( $sortable_columns ) {

	/**
	 * In order to make a column sortable, add the
	 * column data to the $sortable_columns array.
	 *
	 * I want to make my 'Release Date' column
	 * sortable so the array indexes (the 'release_date_column'
	 * value between the []) need to match from
	 * where we added the column in the
	 * manage_wp_posts_be_qe_manage_posts_columns()
	 * function.
	 *
	 * The array value (after the =) should be set to
	 * identify the data that is going to be sorted,
	 * i.e. what will be placed in the URL when it's sorted.
	 * Since my release date is a custom field, I just
	 * use the custom field name, 'release_date'.
	 *
	 * When the column is clicked, the URL will look like this:
	 * http://mywebsite.com/wp-admin/edit.php?post_type=movies&orderby=release_date&order=asc
	 */
	$sortable_columns[ 'issue_type' ] = 'issue_type';
	$sortable_columns[ 'priority' ] = 'priority';
	$sortable_columns[ 'estimated_time' ] = 'estimated_time';
	$sortable_columns[ 'project' ] = 'project';

	return $sortable_columns;
	
}

/**
 * Now that we have a column, we need to fill our column with data.
 * The filters to populate your custom column are pretty similar to the ones
 * that added your column: 'manage_pages_custom_column', 'manage_posts_custom_column',
 * and 'manage_{$post_type_name}_posts_custom_column'. All three pass the same
 * 2 arguments: $column_name (a string) and the $post_id (an integer).
 *
 * Our custom column data is post meta so it will be a pretty simple case of retrieving
 * the post meta with the meta key 'release_date'.
 *
 * Note that we are wrapping our post meta in a div with an id of �release_date-� plus the post id.
 * This will come in handy when we are populating our �Quick Edit� row.
 */
add_action( 'manage_posts_custom_column', 'manage_wp_posts_be_qe_manage_posts_custom_column', 10, 2 );
function manage_wp_posts_be_qe_manage_posts_custom_column( $column_name, $post_id ) {

	switch( $column_name ) {
	
		case 'issue_type':

			$issue_type = get_post_meta( $post_id, 'issue_type', true );
			$pod = pods('mg_task',$z_id = null, true);
			$field = $pod->fields['issue_type'];
			$pod_field = PodsForm::field("issue_type",$issue_type,'pick',$field,$pod,$pod->id());

			echo '<div id="issue_type-' . $post_id . '" class="value">' .$pod_field. '</div>';
			break;
			
		case 'priority':

			$priority = get_post_meta( $post_id, 'priority', true );
			$pod = pods('mg_task',$z_id = null, true);
			$field = $pod->fields['priority'];
			$pod_field = PodsForm::field("priority",$priority,'pick',$field,$pod,$pod->id());

			echo '<div id="priority-' . $post_id . '" class="value">' . $pod_field . '</div>';
			break;

		case 'estimated_time':

			$estimated_time = get_post_meta( $post_id, 'estimated_time', true );
			$pod = pods('mg_task',$z_id = null, true);
			$pod->fields['estimated_time']['type'] = 'hidden';
			$field = $pod->fields['estimated_time'];

			if (!is_numeric($estimated_time)){
				$estimated_time = convert_estimated_time_to_minutes($estimated_time);
			}

			$pod_field = PodsForm::field("estimated_time",(float)round($estimated_time / 60 , 2) . 'h','hidden',$field,$pod,$pod->id());

			echo '<div id="estimated_time-' . $post_id . '" class="value"><span class="editable">' .(float)round($estimated_time / 60 , 2) . 'h'.'</span>'. $pod_field . '<span class="save-options"><span class="dashicons dashicons-yes"></span> <span class="dashicons dashicons-no"></span></span></div>';
			break;

		case 'project':

			$project = get_post_meta( $post_id, 'project', true );
            $invoices_meta = get_post_meta( $post_id, 'add_line_item_to_invoice', false );
            $estimates_meta = get_post_meta( $post_id, 'add_line_item_to_estimate', false );
            $invoices = array();
            $estimates = array();
            $available_fields = array(
                'estimates' => $estimates_meta,
                'invoices' => $invoices_meta
            );

            foreach ($available_fields as $type => $field){
                if (!empty($field)){
                    if (is_array($field)){
                        foreach ($field as $f){
                            if (!empty($f)){
                                if (is_array($f) && isset($f['ID'])){
                                    if ($type === 'estimates'){
                                        $estimates[] = (int)$f['ID'];
                                    }
                                    else {
                                        $invoices[] = (int)$f['ID'];
                                    }
                                }
                            }
                        }
                    }

                }
            }

			if ($project === false || empty($project)){
				$project['ID'] = $post_id;
				$project['post_title'] = 'No Associated<br>Project';
			}

			echo '<div id="project-' . $post_id . '" data-project-id="'.$project['ID'].'">'. $project['post_title'] .'</div>';
			echo '<span style="display:none;" id="estimates-' . $post_id . '" data-estimates-id="'.json_encode($estimates).'"></span>';
			echo '<span style="display:none;" id="invoices-' . $post_id . '" data-invoices-id="'.json_encode($invoices).'"></span>';
			break;

	}
	
}

/**
 * Just because we've made the column sortable doesn't
 * mean the posts will sort by our column data. That's where
 * this next 2 filters come into play.
 * 
 * If your sort data is simple, i.e. alphabetically or numerically,
 * then 'pre_get_posts' is the filter to use. This filter lets you
 * change up the query before it's run.
 *
 * If your orderby data is more complicated, like our release date
 * which is a date string stored in a custom field, then check out
 * the 'posts_clauses' filter example used below.
 *
 * In the example below, when the main query is trying to order by
 * the 'film_rating', it's a simple alphabetical sorting by a custom
 * field so we're telling the query to set our 'meta_key' which is
 * 'film_rating' and that we want to order by the query by the
 * custom field's meta_value, e.g. PG, PG-13, R, etc.
 *
 * Check out http://codex.wordpress.org/Class_Reference/WP_Query
 * for more info on WP Query parameters.
 */
add_action( 'pre_get_posts', 'manage_wp_posts_be_qe_pre_get_posts', 1 );
function manage_wp_posts_be_qe_pre_get_posts( $query ) {

	/**
	 * We only want our code to run in the main WP query
	 * AND if an orderby query variable is designated.
	 */
	if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
	
		switch( $orderby ) {
		
			// If we're ordering by 'issue_type'
			case 'issue_type':
			
				// set our query's meta_key, which is used for custom fields
				$query->set( 'meta_key', 'issue_type' );
				
				/**
				 * Tell the query to order by our custom field/meta_key's
				 * value, in this case: PG, PG-13, R, etc.
				 *
				 * If your meta value are numbers, change
				 * 'meta_value' to 'meta_value_num'.
				 */
				$query->set( 'orderby', 'meta_value' );
				
				break;

			case 'priority':

				// set our query's meta_key, which is used for custom fields
				$query->set( 'meta_key', 'priority' );

				/**
				 * Tell the query to order by our custom field/meta_key's
				 * value, in this case: PG, PG-13, R, etc.
				 *
				 * If your meta value are numbers, change
				 * 'meta_value' to 'meta_value_num'.
				 */
				$query->set( 'orderby', 'meta_value' );

				break;

			case 'project':

				// set our query's meta_key, which is used for custom fields
				$query->set( 'meta_key', 'project' );

				/**
				 * Tell the query to order by our custom field/meta_key's
				 * value, in this case: PG, PG-13, R, etc.
				 *
				 * If your meta value are numbers, change
				 * 'meta_value' to 'meta_value_num'.
				 */
				$query->set( 'orderby', 'meta_value' );

				break;

			case 'estimated_time':

				// set our query's meta_key, which is used for custom fields
				$query->set( 'meta_key', 'estimated_time' );

				/**
				 * Tell the query to order by our custom field/meta_key's
				 * value, in this case: PG, PG-13, R, etc.
				 *
				 * If your meta value are numbers, change
				 * 'meta_value' to 'meta_value_num'.
				 */
				$query->set( 'orderby', 'meta_value_num' );
				
		}
	
	}
	
}

/**
 * Just because we've made the column sortable doesn't
 * mean the posts will sort by our column data. That's where
 * the filter above, 'pre_get_posts', and the filter below,
 * 'posts_clauses', come into play.
 *
 * If your sort data is simple, i.e. alphabetically or numerically,
 * then check out the 'pre_get_posts' filter used above.
 *
 * If your orderby data is more complicated, like combining
 * several values or a date string stored in a custom field,
 * then the 'posts_clauses' filter used below is for you.
 * The 'posts_clauses' filter allows you to manually tweak
 * the query clauses in order to sort the posts by your
 * custom column data.
 *
 * The reason more complicated sorts will not with the
 * "out of the box" WP Query is because the WP Query orderby
 * parameter will only order alphabetically and numerically.
 *
 * Usually I would recommend simply using the 'pre_get_posts'
 * and altering the WP Query itself but because our custom
 * field is a date, we have to manually set the query to
 * order our posts by a date.
 */
add_filter( 'posts_clauses', 'manage_wp_posts_be_qe_posts_clauses', 1, 2 );
function manage_wp_posts_be_qe_posts_clauses( $pieces, $query ) {
	global $wpdb;
	
	/**
	 * We only want our code to run in the main WP query
	 * AND if an orderby query variable is designated.
	 */
	if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
	
		// Get the order query variable - ASC or DESC
		$order = strtoupper( $query->get( 'order' ) );
		
		// Make sure the order setting qualifies. If not, set default as ASC
		if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) )
			$order = 'ASC';
	
		switch( $orderby ) {
		
			// If we're ordering by release_date
			case 'issue_type':
			
				/**
				 * We have to join the postmeta table to include
				 * our release date in the query.
				 */
				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'issue_type'";
				
				// Then tell the query to order by our date
				$pieces[ 'orderby' ] = " wp_rd.meta_value $order, " . $pieces[ 'orderby' ];
				
				break;

			case 'estimated_time':

				/**
				 * We have to join the postmeta table to include
				 * our release date in the query.
				 */
				$pieces[ 'join' ] .= " LEFT JOIN $wpdb->postmeta wp_rd ON wp_rd.post_id = {$wpdb->posts}.ID AND wp_rd.meta_key = 'estimated_time'";

				// Then tell the query to order by our date
				$pieces[ 'orderby' ] = " wp_rd.meta_value $order, " . $pieces[ 'orderby' ];

				break;

		}
	
	}

	return $pieces;

}

/**
 * Now that you have your custom column, it's bulk/quick edit showtime!
 * The filters are 'bulk_edit_custom_box' and 'quick_edit_custom_box'. Both filters
 * pass the same 2 arguments: the $column_name (a string) and the $post_type (a string).
 *
 * Your data's form fields will obviously vary so customize at will. For this example,
 * we're using an input. Also take note of the css classes on the <fieldset> and <div>.
 * There are a few other options like 'inline-edit-col-left' and 'inline-edit-col-center'
 * for the fieldset and 'inline-edit-col' for the div. I recommend studying the WordPress
 * bulk and quick edit HTML to see the best way to layout your custom fields.
 */

add_action( 'bulk_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box', 10, 2 );
add_action( 'quick_edit_custom_box', 'manage_wp_posts_be_qe_bulk_quick_edit_custom_box', 10, 2 );
function manage_wp_posts_be_qe_bulk_quick_edit_custom_box( $column_name, $post_type ) {

	$post_id = get_the_ID();

	switch ( $post_type ) {
	
		case 'mg_task':
		
			switch( $column_name ) {

				case 'issue_type':

					$issue_type = get_post_meta( $post_id, 'issue_type', true );
					$pod = pods('mg_task',$z_id = null, true);
					$field = $pod->fields['issue_type'];
					$pod_field = PodsForm::field("issue_type",$issue_type,'pick',$field,$pod,$pod->id()); ?>

					<fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<label>
								<span class="title">Issue Type</span>
								<span class="input-text-wrap">
									<?php echo $pod_field; ?>
								</span>
							</label>
                    <?php
                    break;
					
				case 'priority':

					$priority = get_post_meta( $post_id, 'priority', true );
					$pod = pods('mg_task',$z_id = null, true);
					$field = $pod->fields['priority'];
					$pod_field = PodsForm::field("priority",$priority,'pick',$field,$pod,$pod->id()); ?>

					<label>
                        <span class="title">Priority</span>
                        <span class="input-text-wrap">
                            <?php echo $pod_field; ?>
                        </span>
                    </label>

                    <?php
                    break;

				case 'estimated_time':

					$estimated_time = get_post_meta( $post_id, 'estimated_time', true );
					$pod = pods('mg_task',$z_id = null, true);
					$field = $pod->fields['estimated_time'];
					$pod_field = PodsForm::field("estimated_time", $estimated_time,'text',$field,$pod,$pod->id()); ?>

					<label>
                        <span class="title">Estimated Time</span>
                            <span class="input-text-wrap">
                                <?php echo $pod_field; ?>
                            </span>
                    </label>

					<?php
					break;

				case 'project':

					$project = get_post_meta( $post_id, 'project', true );
					$pod = pods('mg_task');
					$field = $pod->fields['project'];
					$field['options']['pick_format_style'] = 'dropdown';
					$field['options']['pick_format_single'] = 'dropdown';
					$pod_field = PodsForm::field("project",$project,'pick',$field,$pod,$pod->id()); ?>

                            <label>
                                <span class="title">Project</span>
                                    <span class="input-text-wrap">
                                        <?php echo $pod_field; ?>
                                    </span>
                            </label>
                        </div>
					</fieldset>

					<?php
					$estimates = get_post_meta( $post_id, 'add_line_item_to_estimate', false );
					$invoice_field = $pod->fields['add_line_item_to_estimate'];
					$estimate_ids = array();

					if (!empty($estimates)){
                        foreach ($estimates as $estimate){
                            $estimate_ids[] = $estimate['ID'];
                        }
					}
					$pod_field = PodsForm::field("add_line_item_to_estimate",$estimate_ids,'pick',$invoice_field,$pod,$pod->id());
					?>

					<fieldset class="inline-edit-col-left">
					<div class="inline-edit-col">
						<label>
							<span class="title">Estimates</span>
							<span class="input-text-wrap"><?php echo $pod_field; ?></span>
						</label>
					</div>

					<?php  $invoices = get_post_meta( $post_id, 'add_line_item_to_invoice', false );
					$invoice_field = $pod->fields['add_line_item_to_invoice'];
					$invoice_ids = array();

					if (!empty($invoices)){
                        foreach ($invoices as $invoice){
                            $invoice_ids[] = $invoice['ID'];
                        }
					}
					$pod_field = PodsForm::field("add_line_item_to_invoice",$invoice_ids,'pick',$invoice_field,$pod,$pod->id()); ?>

					<fieldset class="inline-edit-col-left">
					<div class="inline-edit-col">
						<label>
							<span class="title">Invoices</span>
							<span class="input-text-wrap"><?php echo $pod_field; ?></span>
						</label>
					</div>
					</fieldset>
				<?php break;
			}
			break;
	}
	
}

/**
 * When you click 'Quick Edit', you may have noticed that your form fields are not populated.
 * WordPress adds one 'Quick Edit' row which moves around for each post so the information cannot
 * be pre-populated. It has to be populated with JavaScript on a per-post 'click Quick Edit' basis.
 *
 * WordPress has an inline edit post function that populates all of their default quick edit fields
 * so we want to hook into this function, in a sense, to make sure our JavaScript code is run when
 * needed. We will 'copy' the WP function, 'overwrite' the WP function so we're hooked in, 'call'
 * the original WP function (via our copy) so WordPress is not left hanging, and then run our code.
 *
 * Remember where we wrapped our column data in a <div> in Step 2? This is where it comes in handy,
 * allowing our Javascript to retrieve the data by the <div>'s element ID to populate our form field.
 * There are other methods to retrieve your data that involve AJAX but this route is the simplest.
 *
 * Don't forget to enqueue your script and make sure it's dependent on WordPress's 'inline-edit-post' file.
 * Since we'll be using the jQuery library, we need to make sure 'jquery' is loaded as well.
 *
 * I have provided several scenarios for where you've placed this code. Simply uncomment the scenario
 * you're using. For all scenarios, make sure your javascript file is in the same folder as your code.
 */
add_action( 'admin_print_scripts-edit.php', 'manage_wp_posts_be_qe_enqueue_admin_scripts' );
function manage_wp_posts_be_qe_enqueue_admin_scripts() {
	wp_enqueue_script( 'manage-wp-posts-using-bulk-quick-edit', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'bulk_quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}

add_action( 'admin_print_styles-edit.php', 'manage_wp_posts_be_qe_enqueue_admin_styles' );
function manage_wp_posts_be_qe_enqueue_admin_styles() {
	wp_register_style( 'manage-wp-posts-using-bulk-quick-edit-styles', plugins_url('bulk_quick_edit.css', __FILE__) );
	wp_enqueue_style( 'manage-wp-posts-using-bulk-quick-edit-styles' );
}

/**
 * Saving your 'Quick Edit' data is exactly like saving custom data
 * when editing a post, using the 'save_post' hook. With that said,
 * you may have already set this up. If you're not sure, and your
 * 'Quick Edit' data is not saving, odds are you need to hook into
 * the 'save_post' action.
 *
 * The 'save_post' action passes 2 arguments: the $post_id (an integer)
 * and the $post information (an object).
 */
add_action( 'save_post', 'manage_wp_posts_be_qe_save_post', 10, 2 );
function manage_wp_posts_be_qe_save_post( $post_id, $post ) {

	// pointless if $_POST is empty (this happens on bulk edit)
	if ( empty( $_POST ) )
		return $post_id;
		
	// verify quick edit nonce
	if ( isset( $_POST[ '_inline_edit' ] ) && ! wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) )
		return $post_id;
			
	// don't save for autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return $post_id;
		
	// dont save for revisions
	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
		return $post_id;

	if ($post->post_type === 'mg_task'){

		/**
		 * Because this action is run in several places, checking for the array key
		 * keeps WordPress from editing data that wasn't in the form, i.e. if you had
		 * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
		 */
		$custom_fields = array( 'issue_type', 'priority', 'estimated_time' );

		foreach( $custom_fields as $field ) {

			if ( array_key_exists( $field, $_POST ) ){
				update_post_meta( $post_id, $field, $_POST[ $field ] );
			}

			if ($field == 'project'){
				$pod = pods('mg_task',$post_id, true);

				$data = array(
					'project' => $_POST[ $field ],
				);

				$pod->save($data);

			}
		}
	}
}

/**
 * Saving the 'Bulk Edit' data is a little trickier because we have
 * to get JavaScript involved. WordPress saves their bulk edit data
 * via AJAX so, guess what, so do we.
 *
 * Your javascript will run an AJAX function to save your data.
 * This is the WordPress AJAX function that will handle and save your data.
 */
add_action( 'wp_ajax_manage_wp_posts_using_bulk_quick_save_bulk_edit', 'manage_wp_posts_using_bulk_quick_save_bulk_edit' );
function manage_wp_posts_using_bulk_quick_save_bulk_edit() {

	// we need the post IDs
	$post_ids = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : NULL;
		
	// if we have post IDs
	if ( ! empty( $post_ids ) && is_array( $post_ids ) ) {
	
		// get the custom fields
		$custom_fields = array( 'issue_type', 'priority', 'estimated_time', 'project' );
		
		foreach( $custom_fields as $field ) {
			
			// if it has a value, doesn't update if empty on bulk
			if ( isset( $_POST[ $field ] ) && !empty( $_POST[ $field ] ) ) {
			
				// update for each post ID
				foreach( $post_ids as $post_id ) {

					update_post_meta( $post_id, $field, $_POST[ $field ] );

					if ($field == 'project'){
						$pod = pods('mg_task',$post_id, true);

						$data = array(
								'project' => $_POST[ $field ],
						);

						$pod->save($data);

					}
				}
			}
		}
		
	}
	
}
add_action( 'wp_ajax_inline_edit_mg_task_meta', 'inline_edit_mg_task_meta',9999 );
function inline_edit_mg_task_meta() {

	$response = false;
	$new_data = '';

	// we need the post IDs
	$post_id = ( isset( $_POST[ 'post_ID' ] ) && !empty( $_POST[ 'post_ID' ] ) ) ? $_POST[ 'post_ID' ] : NULL;

	// if we have post IDs
	if ( ! empty( $post_id ) && is_numeric( $post_id ) ) {
		if ( ! empty( $_POST['referrer'] ) && $_POST['referrer'] !== 'quick_save' ) {

			$field = str_replace('-','_',$_POST['referrer']);

			// if it has a value, doesn't update if empty on bulk
			if ( isset( $_POST[ $field ] ) && !empty( $_POST[ $field ] ) ) {

				// update for each post ID
					$old_value = get_post_meta($post_id, $field, true);
					$response = update_post_meta( $post_id, $field, $_POST[ $field ], $old_value );
					$new_data = get_post_meta($post_id, $field, true);

				if ($field === 'estimated_time' && $response === true){

					if (!is_numeric($new_data)){
						$new_data = convert_estimated_time_to_minutes($new_data);
					}

					$r = array(
							'post_id' => (int)$post_id,
							$field => $new_data
					);
					$response = json_encode($r, JSON_FORCE_OBJECT);
				}

			}
		}
		elseif ($_POST['referrer'] == 'quick_save'){
			$new_data = get_post_meta($post_id, 'estimated_time', true);
			$r = array(
					'post_id' => (int)$post_id,
					'estimated_time' => (int)$new_data
			);
			$response = json_encode($r, JSON_FORCE_OBJECT);
		}
	}
	exit($response);
}

function add_name_to_project_meta($output){
	$output = get_the_title($output);
	return $output;
}
add_filter('wp_dropdown_metas_labels', 'add_name_to_project_meta');


add_action( 'restrict_manage_posts', 'admin_posts_filter_restrict_manage_posts_act_events' );
/**
 * Create a drop-down to filter posts by project
 *
 * @return void
 */
function admin_posts_filter_restrict_manage_posts_act_events(){

	//If post_type isn't set, default to 'post'
	global $typenow;
	if ($typenow=='mg_task') {

		global $wpdb;
		$results = $wpdb->get_results("SELECT meta_value FROM `wp_postmeta` WHERE meta_key = 'project'");

		//assemble an array of all cities, along with the # of occurrences of each
		$values = array();
		foreach($results as $result)
		{

			if(!isset($values[$result->meta_value]))
				$values[$result->meta_value] = 1;
			else
				$values[$result->meta_value] = intval($values[$result->meta_value]) + 1;// an array like 'Victoria' => 1, 'Vancouver' => 5

		}
		?><select name="project"><option value="">All Projects</option>
		<?php

		$current_v = isset($_GET['project'])? $_GET['project']:'';
		foreach ($values as $project => $num_occ) {
			printf
			(
					'<option value="%s" %s="">%s</option>',
					$project,
					$project == $current_v? ' selected="selected"':'',
					get_the_title($project).' ('.$num_occ.')'
			);
		}
 ?>
		</select>
		<?php
 }
}

add_filter( 'parse_query', 'posts_filter_act_events' );
/**
 * if submitted filter by post meta
 *
 *
 * @return Void
 */
function posts_filter_act_events($query) {

	global $pagenow;

	$qv = &$query->query_vars;//grab a reference to manipulate directly
	if( $pagenow=='edit.php' &&
			'mg_task'==$_GET['post_type'])
	{

		/* If this drop-down has been affected, add a meta query to the query
        *
        */
		if(!empty($_GET['project']))
		{
			$qv['meta_query'][] = array(
					'field' => 'project',
					'value' => $_GET['project'],
					'compare' => '=');
		}

		/* more queries go here */

	}
}


?>