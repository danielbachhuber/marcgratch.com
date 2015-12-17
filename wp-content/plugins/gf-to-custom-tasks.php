<?php

/**
 * Plugin Name: Connect Gravity Forms to Custom Tasks
 * Description: Connect Gravity Forms to the mg_task Post Type for form id 3 "Task Creator"
 * Author: Marc Gratch
 * Author URI: https://marcgratch.com
 */

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
	$items[] = array( 'text' => '--Select--', 'value' => '' );

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
	if ( $form['id'] != 4 ) {
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
			$items[] = array( 'text' => '--Select--', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $field_options as $option ) {

				$slug = utf8_encode($option);
				$slug = str_replace(' ', '-', $slug);
				$slug = strtolower($slug);
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
			$items[] = array( 'text' => '--Select--', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $field_options as $option ) {

				$slug = utf8_encode($option);
				$slug = str_replace(' ', '-', $slug);
				$slug = strtolower($slug);
				$slug = preg_replace('/[^\da-z]/i', '', $slug);
				$slug = sprintf('%s', $slug);
				$slug = urlencode($slug);

				$items[] = array( 'value' => $slug, 'text' => $option );
			}

			$field->choices = $items;
		}
		//@todo use ajax to remove user from field 6 when selected in field 8
		elseif ($field->id == 7 || $field->id == 8){

			$users = get_users();

			//Creating drop down item array.
			$items = array();

			//Adding initial blank value.
			$items[] = array( 'text' => '--Select--', 'value' => '' );

			//Adding post titles to the items array
			foreach ( $users as $user ) {

				$items[] = array( 'value' => $user->ID, 'text' => $user->data->display_name );
			}

			$field->choices = $items;
		}
	}

	return $form;
}

add_filter( 'gform_pre_render_4', 'populate_associated_tasks_checkbox' );
add_filter( 'gform_pre_validation_4', 'populate_associated_tasks_checkbox' );
add_filter( 'gform_pre_submission_filter_4', 'populate_associated_tasks_checkbox' );
add_filter( 'gform_admin_pre_render_4', 'populate_associated_tasks_checkbox' );
function populate_associated_tasks_checkbox( $form ) {

	//@todo add ajax to only show tasks associated with the current project

	foreach( $form['fields'] as &$field )  {

		//NOTE: replace 3 with your checkbox field id
		$field_id = 11;
		if ( $field->id != $field_id ) {
			continue;
		}

		$args = array(
				'post_type' => 'mg_task',
				'posts_per_page' => -1,
				'status' => 'published'
		);

		$posts = get_posts( $args );

		$input_id = 1;
		foreach( $posts as $post ) {

			//skipping index that are multiples of 10 (multiples of 10 create problems as the input IDs)
			if ( $input_id % 10 == 0 ) {
				$input_id++;
			}

			$choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
			$inputs[] = array( 'label' => $post->post_title, 'id' => "{$field_id}.{$input_id}" );

			$input_id++;
		}

		$field->choices = $choices;
		$field->inputs = $inputs;

	}

	return $form;
}
function pods_modify_data_b4_submit($pieces, $is_new_item){
	$current_field = $pieces['fields_active'];

	if ( $pieces['params']->pod == 'mg_task' ) {
		if ($current_field[0] == 'estimated_time') {
			$divisor = 1;
			$new_value = 0;
			$current_val = $pieces['fields']['estimated_time']['value'];
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
			$pieces['fields']['estimated_time']['value'] = $new_value;
		}
	}

	return $pieces;
}
add_filter( 'pods_api_pre_save_pod_item', 'pods_modify_data_b4_submit', 10, 2);

function add_pods_to_gfcpt( $args, $form_id ){

	return $args;
}
add_filter('gfcpt_post_type_args', 'add_pods_to_gfcpt',9999, 2);

function set_post_content( $post_id, $entry, $form ) {

	//getting post
	$post = get_post( $post_id );
	$entry_stuff = $entry;

}
add_action( 'gform_after_create_post', 'set_post_content', 10, 3 );