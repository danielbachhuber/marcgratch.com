<?php

/**
 * Plugin Name: Connect Gravity Forms to Custom Tasks
 * Description: Connect Gravity Forms to the mg_task Post Type for form id 3 "Task Creator"
 * Author: Marc Gratch
 * Author URI: https://marcgratch.com
 */
define( 'GF_CPTASK_PATH', plugin_dir_path(__FILE__) );
define('GF_CPTASK_URL', plugin_dir_url(__FILE__));

function populate_project_dropdown( $form ) {

	if ( $form['id'] != 4 ) {
		return $form;
	}

	//Getting all the projects;
	$args = array(
		'post_type' => 'sa_project',
		'posts_per_page' => -1,
		'status' => 'published'
	);
	$posts = get_posts( $args );

	//Creating drop down item array.
	$items = array();

	//Adding initial blank value.
	$items[] = array( 'text' => '', 'value' => '' );

	//Adding post titles to the items array
	foreach ( $posts as $post ) {
		$items[] = array( 'value' => $post->post_title, 'text' => $post->post_title );
	}

	//Adding items to field id 8. Replace 8 with your actual field id. You can get the field id by looking at the input name in the markup.
	foreach ( $form['fields'] as &$field ) {
		if ( $field->id == 2 ) {
			$field->choices = $items;
		}
	}

	return $form;
}
add_filter( 'gform_pre_render', 'populate_pods_dropdown' );

//Note: when changing drop down values, we also need to use the gform_pre_validation so that the new values are available when validating the field.
add_filter( 'gform_pre_validation', 'populate_pods_dropdown' );

//Note: when changing drop down values, we also need to use the gform_admin_pre_render so that the right values are displayed when editing the entry.
add_filter( 'gform_admin_pre_render', 'populate_pods_dropdown' );

//Note: this will allow for the labels to be used during the submission process in case values are enabled
add_filter( 'gform_pre_submission_filter', 'populate_pods_dropdown' );
function populate_pods_dropdown( $form ) {

	//only populating drop down for form id 5
	if ( $form['id'] != 5 ) {
		return $form;
	}

	//Getting all the projects;
	$pod = pods('mg_task',$z_id = null, true);


	//Adding items to field id 8. Replace 8 with your actual field id. You can get the field id by looking at the input name in the markup.
	foreach ( $form['fields'] as &$field ) {
		if ( $field->id == 3 ) {
			$field_options = $pod->fields['issue_type']['options']['pick_custom'];

			$field_options = explode("\n",$field_options);

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $field_options as $option ) {

				$slug = utf8_encode($option);
				$slug = str_replace(' ', '-', $slug);
				$slug = preg_replace('/[^\da-z]/i', '', $slug);
				$slug = sprintf('%s', $slug);
				$slug = urlencode($slug);

				$items[] = array( 'value' => $slug, 'text' => $option );
			}

			$field->choices = $items;
		}
		elseif ($field->id == 4){
			$field_options = $pod->fields['priority']['options']['pick_custom'];

			$field_options = explode("\n",$field_options);

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $field_options as $option ) {

				$slug = utf8_encode($option);
				$slug = str_replace(' ', '-', $slug);
				$slug = preg_replace('/[^\da-z]/i', '', $slug);
				$slug = sprintf('%s', $slug);
				$slug = urlencode($slug);

				$items[] = array( 'value' => $slug, 'text' => $option );
			}

			$field->choices = $items;
		}
		//@todo use ajax to remove user from field 6 when selected in field 8
		elseif ($field->id == 8 || $field->id == 9){

			$users = get_users();

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $users as $user ) {

				$items[] = array( 'value' => $user->ID, 'text' => $user->data->display_name );
			}

			$field->choices = $items;
		}
		elseif ($field->id == 11){

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			$args = array(
					'post_type' => 'mg_task',
					'posts_per_page' => -1,
					'post_status' => 'published'
			);

			$posts = get_posts( $args );

			foreach( $posts as $post ) {

				$items[] = array( 'text' => $post->post_title, 'value' => $post->ID );

			}

			$field->choices = $items;
		}
		elseif ($field->id == 13){

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			$args = array(
				'post_type' => 'sa_invoice',
				'posts_per_page' => -1,
				'public' => false,
				'post_status' => array('temp','request','Pending','Scheduled','publish')
			);

			$posts = get_posts( $args );

			foreach( $posts as $post ) {

				$items[] = array( 'text' => $post->post_title, 'value' => $post->ID );

			}

			$field->choices = $items;
		}
		elseif ($field->id == 14){

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '', 'value' => '' );

			$args = array(
					'post_type' => 'sa_estimate',
					'posts_per_page' => -1,
					'public' => false,
					'post_status' => array('temp','request','Pending','Scheduled','publish')
			);

			$posts = get_posts( $args );

			foreach( $posts as $post ) {

				$items[] = array( 'text' => $post->post_title, 'value' => $post->ID );

			}

			$field->choices = $items;
		}
	}

	return $form;
}

function pods_modify_data_b4_submit($pieces, $is_new_item = null){
	$current_field = $pieces['fields_active'];
	if ( $pieces['pod']['name'] == 'mg_task' && in_array('estimated_time',$current_field) ) {
		$post_id = $pieces['params']->id;
		$current_val = $pieces['fields']['estimated_time']['value'];
		$new_value = convert_estimated_time_to_minutes($current_val);
		$pre_save_estimated_time = get_post_meta($post_id, 'estimated_time', true);
		$pieces['fields']['estimated_time']['value'] = $new_value;
		if ((float)$new_value !== (float)$pre_save_estimated_time) {
			$add_line_item_to_estimate = get_post_meta($post_id, 'add_line_item_to_estimate', false);
			$add_line_item_to_invoice = get_post_meta($post_id, 'add_line_item_to_invoice', false);
			foreach($current_field as $field) {
				if ($field == 'estimated_time') {

					if ($add_line_item_to_estimate && $add_line_item_to_estimate[0] !== false){
						if (is_object($add_line_item_to_estimate[0]) || is_array($add_line_item_to_estimate[0])){
							foreach ($add_line_item_to_estimate as $li){
								$estimates[] = $li['ID'];
							}
						} else {
							foreach ($add_line_item_to_estimate as $li){
								$estimates[] = $li;
							}
						}

						update_rates_on_task_li_estimates($pieces['fields']['estimated_time']['value'], $estimates, $post_id);
					}
					if ($add_line_item_to_invoice && $add_line_item_to_invoice[0] !== false){
						if (is_object($add_line_item_to_invoice[0]) || is_array($add_line_item_to_invoice[0])){
							foreach ($add_line_item_to_invoice as $inv_li){
								$invoices[] = $inv_li['ID'];
							}
						} else {
							foreach ($add_line_item_to_invoice as $inv_li){
								$invoices[] = $inv_li;
							}
						}
						foreach ( $add_line_item_to_invoice as $inv_li ){
							$invoices[] = $inv_li['ID'];
						}
						update_invoice_line_item($pieces, $invoices, $post_id);
					}

				}
			}
		}
	}
	$_SESSION["prev_est_val"] = get_post_meta(get_the_ID(),'add_line_item_to_estimate',false);
	$_SESSION["prev_inv_val"] = get_post_meta(get_the_ID(),'add_line_item_to_invoice',false);
	return $pieces;
}
add_filter( 'pods_api_pre_save_pod_item', 'pods_modify_data_b4_submit', 10, 2);

function update_rates_on_task_li_estimates($time_in_minutes, $estimates, $id){
	$rate = get_post_meta($id, 'rate', true);
	$description = get_post_meta($id, 'description', true);
	$quantity = get_post_meta($id, 'quantity', true);
	$percent_adjustment = get_post_meta($id, 'percent_adjustment', true);
	$new_rate = ((float)$time_in_minutes / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);

	if (!isset($estimates) || empty($estimates)){
		return;
	}

	foreach ($estimates as $estimate) {
		$SI_Estimate = SI_Estimate::get_instance($estimate);
		$line_items = $SI_Estimate->get_line_items();

		$new_line_items_array = array();
		foreach ($line_items as $li){
			$fired = [null,null];
			if( strpos($li['desc'], 'task: '.$id) !== false  && $fired[1] === null){
				$new_line_items_array[] = array(
						'rate' => $new_rate,
						'qty' => $quantity,
						'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
						'type' => 'task',
						'total' => $total,
						'tax' => $percent_adjustment,
				);
				$fired = [true,true];
			} else {
				$new_line_items_array[] = $li;
				$fired[0] = false;
			}
		}
		if (isset($fired) && $fired[0] !== true){
			$new_line_items_array[] = array(
					'rate' => $new_rate,
					'qty' => $quantity,
					'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
					'type' => 'task',
					'total' => $total,
					'tax' => $percent_adjustment,
			);
			$fired = [true,true];
		}
		$SI_Estimate->set_line_items( $new_line_items_array );
		$SI_Estimate->set_calculated_total();
	}
}

function update_rates_on_task_li_invoices($time_in_minutes, $invoices, $id){
	$rate = get_post_meta($id, 'rate', true);
	$description = get_post_meta($id, 'description', true);
	$quantity = get_post_meta($id, 'quantity', true);
	$percent_adjustment = get_post_meta($id, 'percent_adjustment', true);
	$new_rate = ((float)$time_in_minutes / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);

	if (!isset($invoices) || empty($invoices)){
		return;
	}

	foreach ($invoices as $invoice) {
		$SI_Invoice = SI_Invoice::get_instance($invoice);
		$line_items = $SI_Invoice->get_line_items();

		$new_line_items_array = array();
		foreach ($line_items as $li){
			$fired = [null,null];
			if( strpos($li['desc'], 'task: '.$id) !== false  && $fired[1] === null){
				$new_line_items_array[] = array(
						'rate' => $new_rate,
						'qty' => $quantity,
						'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
						'type' => 'task',
						'total' => $total,
						'tax' => $percent_adjustment,
				);
				$fired = [true,true];
			} else {
				$new_line_items_array[] = $li;
				$fired[0] = false;
			}
		}
		if (isset($fired) && $fired[0] !== true){
			$new_line_items_array[] = array(
					'rate' => $new_rate,
					'qty' => $quantity,
					'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
					'type' => 'task',
					'total' => $total,
					'tax' => $percent_adjustment,
			);
			$fired = [true,true];
		}
		$SI_Invoice->set_line_items( $new_line_items_array );
		$SI_Invoice->set_calculated_total();
	}
}

function convert_estimated_time_to_minutes( $current_val )
{
	$divisor = 1;
	$new_value = 0;
	$all_units = explode(' ', $current_val);
	foreach ($all_units as $unit) {
		$unit_of_measurement = substr($unit, -1);
		$current_val = str_replace($unit_of_measurement, '', $unit);
		if ($unit_of_measurement === 'h') {
			$divisor = 60;
		} elseif ($unit_of_measurement === 'd') {
			$divisor = 8 * 60;
		} elseif ($unit_of_measurement === 'w') {
			$divisor = 8 * 60 * 5;
		} elseif ($unit_of_measurement === 'M') {
			$divisor = 8 * 60 * 5 * 4;
		} elseif ($unit_of_measurement === 'm') {
			$divisor = 1;
		}
		$new_value += (float)$current_val * $divisor;
	}
	return $new_value;
}
function add_pods_to_gfcpt( $args, $form_id ){
	$args = array(
			'public'   => false
	);
	return $args;
}
add_filter('gfcpt_post_type_args', 'add_pods_to_gfcpt',9999, 2);

function add_pods_to_gfcpt_tax( $args, $form_id ){
	$args = array(
			'public'   => false
	);
	return $args;
}
add_filter('gfcpt_tax_args', 'add_pods_to_gfcpt_tax',9999, 2);

function set_post_content( $post_id, $entry, $form ) {

	$attachments = $entry[7];
	$attachment_array = array();
	$attachments = json_decode($attachments);
	$fix_version = $entry[6];
	if (count($attachments) > 0){
		foreach ($attachments as $attachment){
			$attachment_id = pods_attachment_import($attachment, $post_id, false);
			$attachment_array[] = $attachment_id;
		}
	}

	$fix_version = explode(',',$fix_version);

	$status = wp_set_object_terms( $post_id, $fix_version, 'fixversion', true );

	$data = array(
			'project' => $entry[2],
			'issue_type' => $entry[3],
			'priority' => $entry[4],
			'description' => $entry[5],
			'fix_version' => $status,
			'attachments' => $attachment_array,
			'assignee' => $entry[8],
			'cc_users' => $entry[9],
			'estimated_time' => $entry[10],
			'associated_tasks' => $entry[11],
			'description_of_associated_tasks' => $entry[12],
			'add_line_item_to_invoice' => $entry[13],
			'add_line_item_to_estimate' => $entry[14],
			'rate' => $entry[15],
			'quantity' => $entry[16],
			'percent_adjustment' => $entry[17]
	);
	if (isset($entry[13]) && !empty($entry[13])){
		$rate = $entry[15];
		$description = 'task: '.$post_id.' <strong>'.$entry[1].'</strong><br/>'.$entry[5];
		$quantity = $entry[16];
		$percent_adjustment = $entry[17];
		$new_rate = ((float)convert_estimated_time_to_minutes( $entry[10] ) / 60) * $rate;
		$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);
		$invoices = explode(',',$entry[13]);

		foreach ($invoices as $invoice){
			$SI_Invoice = SI_Invoice::get_instance( $invoice );
			$line_items = $SI_Invoice->get_line_items();
			$line_items[] = array(
					'rate' => $new_rate,
					'qty' => $quantity,
					'desc' => $description,
					'type' => 'task',
					'total' => $total,
					'tax' => $percent_adjustment,
			);
			$SI_Invoice->set_line_items( $line_items );
			$SI_Invoice->set_calculated_total();
		}
	}
	if (isset($entry[14]) && !empty($entry[14])){

		$rate = $entry[15];
		$description = 'task: '.$post_id.' <strong>'.$entry[1].'</strong><br/>'.$entry[5];
		$quantity = $entry[16];
		$percent_adjustment = $entry[17];
		$new_rate = ((float)convert_estimated_time_to_minutes( $entry[10] ) / 60) * $rate;
		$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);
		$estimates = explode(',',$entry[14]);

		foreach ($estimates as $estimate){
			$SI_Estimate = SI_Estimate::get_instance( $estimate );
			$line_items = $SI_Estimate->get_line_items();
			$line_items[] = array(
					'rate' => $new_rate,
					'qty' => $quantity,
					'desc' => $description,
					'type' => 'task',
					'total' => $total,
					'tax' => $percent_adjustment,
			);
			$SI_Estimate->set_line_items( $line_items );
			$SI_Estimate->set_calculated_total();

		}
	}

	$pod = pods('mg_task',$post_id);
	apply_filters('pods_api_pre_save_pod_item', $pod->save($data));


}
add_action( 'gform_after_create_post', 'set_post_content', 99999, 3 );

function process_ajax_on_project_change(){
	$has_admin = array();
	//@TODO ADD NONCE CHECK
	$post_id = $_POST['post_id'];
	if ($post_id <= 0){
		return;
	}
	$SI_Project = SI_Project::get_instance( $post_id );
	$invoice_ids = $SI_Project->get_invoices();
	$estimate_ids = $SI_Project->get_estimates();
	$client_ids = $SI_Project->get_associated_clients();
	$clients = array();
	$estimates = array();
	$invoices = array();
	$asscoiativeTasks = array();

	foreach ($client_ids as $clients_id){
		$SI_Client = SI_Client::get_instance($clients_id);
		$user_ids = $SI_Client->get_associated_users();

		foreach ($user_ids as $user_id){

			if (user_can($user_id, 'update_core')){
				$has_admin[] = $user_id;
			}

			$user = get_user_by('ID',$user_id);
			$display_name = $user->data->display_name;
			$clients[] = array(
					'user_id' 	=> (int)$user_id,
					'display_name'	=> $display_name
			);
		}
	}

	if (empty($has_admin && current_user_can('update_core'))){
		$args = array(
				'role' => 'administrator'
		);
		$users = get_users( $args );
		foreach ($users as $user){
			$clients[] = array(
					'user_id' => (int)$user->ID,
					'display_name' => $user->data->display_name
			);
		}
	}

	$blank_option = array(
			'user_id' 	=> '',
			'display_name'	=> ''
	);

	array_unshift($clients, $blank_option);

	foreach ($estimate_ids as $estimate_id){
		$title = get_the_title($estimate_id);
		$estimates[] = array(
			'est_id' => (int)$estimate_id,
			'est_title' => $title
		);
	}

	foreach ($invoice_ids as $invoice_id){
		$title = get_the_title($invoice_id);
		$invoices[] = array(
				'inv_id' => (int)$invoice_id,
				'inv_title' => $title
		);
	}

	$args = array(
		'post_type'		=> 'mg_task',
		'meta_key'   	=> 'project',
		'meta_value'	=> $post_id,
		'post_status'	=> 'publish'
	);

	$the_query = new WP_Query( $args );
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$asscoiativeTasks[] = array(
			'task_title' => get_the_title(),
			'task_id'	 => get_the_ID()
		);
	}

	$data = array(
		'clients' 	=> $clients,
		'estimates' => $estimates,
		'invoices'	=> $invoices,
		'tasks'		=> $asscoiativeTasks
	);


	$output = json_encode($data);

	exit($output);
}
add_action('wp_ajax_mg_process_ajax_on_project_change', 'process_ajax_on_project_change');

function gf_cpt_frontend_scripts(){
	wp_register_script('gf_cpt_frontend_scripts',GF_CPTASK_URL . '/assets/js/gf-cpt-front-end-ajax.js',array('gform_chosen'),null,true);
	wp_enqueue_script('gf_cpt_frontend_scripts');
	wp_localize_script( 'gf_cpt_frontend_scripts', 'gfCPTask', array(
			'nonce' => wp_create_nonce( 'gf_cpt_frontend_scripts'),
			'ajaxurl' => admin_url( 'admin-ajax.php' )
	) );
}
add_action( 'gform_enqueue_scripts_5', 'gf_cpt_frontend_scripts', 10, 2 );


// define the pods_api_get_table_info_default_post_status callback
function filter_pods_api_get_table_info_default_post_status( $array, $post_type, $info, $object_type, $object, $name, $pod, $field ) {
	global $pagenow;
	global $typenow;

	if ($typenow !== 'mg_task' && $pagenow !== 'edit-mg_task'){
		return $array;
	}
	else {
		if(isset($field['name']) && ($field['name'] == 'add_line_item_to_invoice' || $field['name'] == 'add_line_item_to_estimate')) {
			$array[] = 'temp';
			$array[] = 'request';
			$array[] = 'Pending';
			$array[] = 'Scheduled';
			$array[] = 'publish';
		}
	}

	if (strpos($_SERVER["REQUEST_URI"],'/wp-admin/') !== false){
		if (isset($_SERVER['HTTP_REFERER'])){
			if(strpos($_SERVER["HTTP_REFERER"],'post.php')){
				if(isset($field['name']) && ($field['name'] == 'add_line_item_to_invoice' || $field['name'] == 'add_line_item_to_estimate')) {
					$array[] = 'temp';
					$array[] = 'request';
					$array[] = 'Pending';
					$array[] = 'Scheduled';
					$array[] = 'publish';
				}
			}
		} else {
			if(isset($field['name']) && ($field['name'] == 'add_line_item_to_invoice' || $field['name'] == 'add_line_item_to_estimate')) {
				$array[] = 'temp';
				$array[] = 'request';
				$array[] = 'Pending';
				$array[] = 'Scheduled';
				$array[] = 'publish';
			}
		}
	}
	// make filter magic happen here...
	return $array;
};

// add the filter
add_filter( 'pods_api_get_table_info_default_post_status', 'filter_pods_api_get_table_info_default_post_status', 10, 8 );

// define the pods_form_ui_field_<type>_value callback
function filter_pods_form_ui_field_type_value( $value, $name, $options, $pod, $id ) {
	if ($name = 'pods_meta_estimated_time'){
		$value = (float)round($value / 60 , 2) .'h';
	}
	// make filter magic happen here...
	return $value;
};

// add the filter
add_filter( "pods_form_ui_field_text_value", 'filter_pods_form_ui_field_type_value', 10, 5 );

function add_line_items_on_task_save($pieces, $is_new_item, $id ) {
	if ( strpos($_SERVER["REQUEST_URI"],'/wp-admin/') !== false && strpos($_SERVER["HTTP_REFERER"],'post.php') && !empty($pieces['changed_fields'])){
		$invoice_check = get_post_meta($id, 'add_line_item_to_invoice', false);
		$post_save_invoice = array_values($invoice_check);
		$estimate_check = get_post_meta($id, 'add_line_item_to_estimate', false);
		$post_save_estimate = array_values($estimate_check);
		$pre_save_invoice = isset($_SESSION['prev_inv_val']) && is_array($_SESSION['prev_inv_val']) && array_values($_SESSION['prev_inv_val']) ? $_SESSION['prev_inv_val'] : array();
		$pre_save_estimate = isset($_SESSION['prev_est_val']) && is_array($_SESSION['prev_est_val']) && array_values($_SESSION['prev_est_val']) ? $_SESSION['prev_est_val'] : array();
		$changed_fields = $pieces['changed_fields'];
		$line_item_chnages = invoice_or_estimate_update( $pre_save_invoice, $post_save_invoice, $pre_save_estimate, $post_save_estimate, $changed_fields );

		if ($line_item_chnages === false){
			return;
		}

		if (isset($line_item_chnages['estimates']['estimates_to_update'])){
			update_estimate_line_item( $pieces, $line_item_chnages['estimates']['estimates_to_update'], $id );
		}

		if (isset($line_item_chnages['invoices']['invoices_to_update'])){
			update_invoice_line_item( $pieces, $line_item_chnages['invoices']['invoices_to_update'], $id );
		}

		if (isset($line_item_chnages['estimates']['estimates_to_add'])){
			add_estimate_line_item( $pieces, $line_item_chnages['estimates']['estimates_to_add'], $id );
		}

		if (isset($line_item_chnages['invoices']['invoices_to_add'])){
			add_invoice_line_item( $pieces, $line_item_chnages['invoices']['invoices_to_add'], $id );
		}

		if (isset($line_item_chnages['estimates']['estimates_to_remove'])){
			remove_estimate_line_items($line_item_chnages['estimates']['estimates_to_remove'], $id);
		}

		if (isset($line_item_chnages['invoices']['invoices_to_remove'])){
			remove_invoice_line_items($line_item_chnages['invoices']['invoices_to_remove'], $id);
		}
	}
}

function update_invoice_line_item( $pieces, $invoices, $id ){
	$rate = $pieces['fields']['rate']['value'];
	$description = $pieces['fields']['description']['value'];
	$quantity = $pieces['fields']['quantity']['value'];
	$percent_adjustment = $pieces['fields']['percent_adjustment']['value'];
	$new_rate = ((float)$pieces['fields']['estimated_time']['value'] / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);

	if (!isset($invoices) || empty($invoices)){
		return;
	}

	foreach ($invoices as $invoice) {
		$SI_Invoice = SI_Invoice::get_instance($invoice);
		$line_items = $SI_Invoice->get_line_items();

			$new_line_items_array = array();
			foreach ($line_items as $li){
				$fired = [null,null];
				if( strpos($li['desc'], 'task: '.$id) !== false  && $fired[1] === null){
					$new_line_items_array[] = array(
							'rate' => $new_rate,
							'qty' => $quantity,
							'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
							'type' => 'task',
							'total' => $total,
							'tax' => $percent_adjustment,
					);
					$fired = [true,true];
				} else {
					$new_line_items_array[] = $li;
					$fired[0] = false;
				}
			}
			if (isset($fired) && $fired[0] !== true){
				$new_line_items_array[] = array(
						'rate' => $new_rate,
						'qty' => $quantity,
						'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
						'type' => 'task',
						'total' => $total,
						'tax' => $percent_adjustment,
				);
				$fired = [true,true];
			}
		$SI_Invoice->set_line_items( $new_line_items_array );
		$SI_Invoice->set_calculated_total();
	}
}

function add_invoice_line_item( $pieces, $invoices, $id ){
	$rate = $pieces['fields']['rate']['value'];
	$description = $pieces['fields']['description']['value'];
	$quantity = $pieces['fields']['quantity']['value'];
	$percent_adjustment = $pieces['fields']['percent_adjustment']['value'];
	$new_rate = ((float)$pieces['fields']['estimated_time']['value'] / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);
	foreach ($invoices as $invoice) {
		$SI_Invoice = SI_Invoice::get_instance($invoice);
		$line_items = $SI_Invoice->get_line_items();
		$line_items[] = array(
				'rate' => $new_rate,
				'qty' => $quantity,
				'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
				'type' => 'task',
				'total' => $total,
				'tax' => $percent_adjustment,
		);
		$SI_Invoice->set_line_items($line_items);
		$SI_Invoice->set_calculated_total();
	}
}

function remove_invoice_line_items( $invoices, $id ){
	foreach ($invoices as $invoice) {
		$SI_Invoice = SI_Invoice::get_instance($invoice);
		$line_items = $SI_Invoice->get_line_items();
		$fired = null;
		$new_line_items_array = array();
		foreach ($line_items as $li){
			if( strpos($li['desc'], 'task: '.$id) === false ){
				$new_line_items_array[] = $li;
			} elseif ( $fired !== null ) {
				$fired = true;
			}
		}
		$SI_Invoice->set_line_items( $new_line_items_array );
		$SI_Invoice->set_calculated_total();
	}
}

function update_estimate_line_item( $pieces, $estimates, $id ){
	$rate = $pieces['fields']['rate']['value'];
	$description = $pieces['fields']['description']['value'];
	$quantity = $pieces['fields']['quantity']['value'];
	$percent_adjustment = $pieces['fields']['percent_adjustment']['value'];
	$new_rate = ((float)$pieces['fields']['estimated_time']['value'] / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);

	if (!isset($estimates) || empty($estimates)){
		return;
	}

	foreach ($estimates as $estimate) {
		$SI_Estimate = SI_Estimate::get_instance($estimate);
		$line_items = $SI_Estimate->get_line_items();

			$new_line_items_array = array();
			foreach ($line_items as $li){
				$fired = [null,null];
				if( strpos($li['desc'], 'task: '.$id) !== false  && $fired[1] === null){
					$new_line_items_array[] = array(
							'rate' => $new_rate,
							'qty' => $quantity,
							'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
							'type' => 'task',
							'total' => $total,
							'tax' => $percent_adjustment,
					);
					$fired = [true,true];
				} else {
					$new_line_items_array[] = $li;
					$fired[0] = false;
				}
			}
			if (isset($fired) && $fired[0] !== true){
				$new_line_items_array[] = array(
						'rate' => $new_rate,
						'qty' => $quantity,
						'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
						'type' => 'task',
						'total' => $total,
						'tax' => $percent_adjustment,
				);
				$fired = [true,true];
			}
		$SI_Estimate->set_line_items( $new_line_items_array );
		$SI_Estimate->set_calculated_total();
	}
}

function add_estimate_line_item( $pieces, $estimates, $id ){
	$rate = $pieces['fields']['rate']['value'];
	$description = $pieces['fields']['description']['value'];
	$quantity = $pieces['fields']['quantity']['value'];
	$percent_adjustment = $pieces['fields']['percent_adjustment']['value'];
	$new_rate = ((float)$pieces['fields']['estimated_time']['value'] / 60) * $rate;
	$total = (float)round(($new_rate * $quantity) - ($new_rate * $quantity) * ( $percent_adjustment / 100 ),2);
	foreach ($estimates as $estimate) {
		$SI_Estimate = SI_Estimate::get_instance($estimate);
		$line_items = $SI_Estimate->get_line_items();
		$line_items[] = array(
				'rate' => $new_rate,
				'qty' => $quantity,
				'desc' => 'task: ' . $id . ' <strong>' . get_the_title($id) . '</strong><br/>' . $description,
				'type' => 'task',
				'total' => $total,
				'tax' => $percent_adjustment,
		);
		$SI_Estimate->set_line_items($line_items);
		$SI_Estimate->set_calculated_total();
	}
}

function remove_estimate_line_items( $estimates, $id ){

	if (empty($estimates)){
		return;
	}

	foreach ($estimates as $estimate) {
		$SI_Estimate = SI_Estimate::get_instance($estimate);
		$line_items = $SI_Estimate->get_line_items();
		$fired = null;
		$new_line_items_array = array();
		foreach ($line_items as $li){
			if( strpos($li['desc'], 'task: '.$id) === false ){
				$new_line_items_array[] = $li;
			} elseif ( $fired !== null ) {
				$fired = true;
			}
		}
		$SI_Estimate->set_line_items( $new_line_items_array );
		$SI_Estimate->set_calculated_total();
	}
}

function invoice_or_estimate_update( $pre_save_invoice, $post_save_invoice, $pre_save_estimate, $post_save_estimate, $changed_fields )
{

	/**
	 * Has the set invoice/Estimate Changed?
	 */
	$invoice_has_change = $pre_save_invoice === $post_save_invoice ? false : true;
	$estimate_has_change = $pre_save_estimate === $post_save_estimate ? false : true;

	$invoice_changes = array();
	$estimate_changes = array();

	/**
	 * If the invoice has changed, then how?
	 */
	if ($invoice_has_change !== false) {
		$invoices_to_remove = array_diff($pre_save_invoice, $post_save_invoice);
		$invoices_to_add = array_diff($post_save_invoice, $pre_save_invoice);
		$invoice_changes = array(
				'invoices_to_remove' => $invoices_to_remove,
				'invoices_to_add' => $invoices_to_add,
		);
	}
	if (isset($changed_fields['percent_adjustment']) || isset($changed_fields['rate']) || isset($changed_fields['quantity']) || isset($changed_fields['estimated_time'])) {
		if ($invoice_has_change === false && !empty($post_save_invoice)) {
			$invoice_changes = array(
					'invoices_to_update' => $post_save_invoice
			);
		}
	}

	/**
	 * if estimtes have changed, then how?
	 */
	if ($estimate_has_change !== false) {
		$estimtes_to_remove = array_diff($pre_save_estimate, $post_save_estimate);
		$estimtes_to_add = array_diff($post_save_estimate, $pre_save_estimate);
		$estimate_changes = array(
				'estimates_to_remove' => $estimtes_to_remove,
				'estimates_to_add' => $estimtes_to_add,
		);
	}
	if (isset($changed_fields['percent_adjustment']) || isset($changed_fields['rate']) || isset($changed_fields['quantity']) || isset($changed_fields['estimated_time'])) {
		if ($estimate_has_change === false && !empty($post_save_estimate)) {
			$estimate_changes = array(
					'estimates_to_update' => $post_save_estimate
			);
		}
	}
	if (!isset($changed_fields['percent_adjustment']) && !isset($changed_fields['rate']) && !isset($changed_fields['quantity']) && !isset($changed_fields['estimated_time'])) {
		if ($estimate_has_change === false && $invoice_has_change === false) {
			return false;
		}
	}
	$output = ['estimates' => $estimate_changes, 'invoices' => $invoice_changes];
	return $output;
}

add_action('pods_api_post_save_pod_item', 'add_line_items_on_task_save', 10, 3);


// define the pods_api_save_pod_item_track_changed_fields_<pod> callback
add_filter( 'pods_api_save_pod_item_track_changed_fields_mg_task', '__return_true' );