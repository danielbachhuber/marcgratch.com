<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
class SC_Messages_Table extends WP_List_Table {
	protected static $post_type = SC_Message::POST_TYPE;

	function __construct() {
		global $status, $page;

		//Set parent defaults
		parent::__construct( array(
				'singular' => 'message',
				'plural' => 'messages',
				'ajax' => false,
			) );

	}

	/**
	 *
	 *
	 * @see WP_List_Table::single_row_columns()
	 *
	 * @param array   $item        A singular item (one full row's worth of data)
	 * @param array   $column_name The name/slug of the column to be processed
	 * @return string Text or HTML to be placed inside the column <td>
	 */
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			default:
			return apply_filters( 'si_mngt_message_column_'.$column_name, $item ); // do action for those columns that are filtered in
		}
	}

	/**
	 *
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @param array   $item A singular item (one full row's worth of data)
	 * @return string Text to be placed inside the column <td> (movie title only)
	 */
	function column_title( $item ) {
		$message_id = array_search( $item->ID, get_option( SI_Messaging::NOTIFICATIONS_OPTION_NAME, array() ) );
		$name = ( $message_id ) ? SI_Messaging::$messages[ $message_id ]['name'] : sc__( 'Unassigned' );
		$message = SC_Message::get_instance( $item->ID );
		$status = ( $message->get_disabled() ) ? '<span style="color:red">' . sc__( 'disabled' ) . '</span>' : '<span>' . sc__( 'active' ) . '</span>';

		//Build row actions
		$actions = array(
			'edit'    => sprintf( '<a href="%s">Edit</a>', get_edit_post_link( $item->ID ) ),
		);

		//Return the title contents
		return sprintf( '%1$s <span style="color:silver">(status: %2$s)</span>%3$s',
			$name,
			$status,
			$this->row_actions( $actions )
		);
	}

	function column_subject( $item ) {
		echo esc_html( $item->post_title );
	}

	function column_message( $item ) {
		echo esc_html( substr( strip_tags( $item->post_content ), 0, 200 ) . '...' );
	}

	/**
	 * REQUIRED! This method dictates the table's columns and titles. This should
	 * return an array where the key is the column slug (and class) and the value
	 * is the column's title text.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
	 * */
	function get_columns() {
		$columns = array(
			'title' => sc__( 'Type' ),
			'subject'  => sc__( 'Subject' ),
			'message'  => sc__( 'Message' )
		);
		return apply_filters( 'si_mngt_message_columns', $columns );
	}

	/**
	 * This method merely defines which columns should be sortable and makes them
	 * clickable - it does not handle the actual sorting. You still need to detect
	 * the ORDERBY and ORDER querystring variables within prepare_items() and sort
	 * your data accordingly (usually by modifying your query).
	 *
	 * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
	 * */
	function get_sortable_columns() {
		$sortable_columns = array();
		return apply_filters( 'si_mngt_message_sortable_columns', $sortable_columns );
	}


	/**
	 * Bulk actions are an associative array in the format
	 * 'slug'=>'Visible Title'
	 *
	 * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
	 * */
	function get_bulk_actions() {
		$actions = array();
		return apply_filters( 'si_mngt_messages_bulk_actions', $actions );
	}


	/**
	 * Prep data.
	 *
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 * */
	function prepare_items() {

		/**
		 * First, lets decide how many records per page to show
		 */
		$per_page = 25;

		/**
		 * Define our column headers.
		 */
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		/**
		 * REQUIRED. Build an array to be used by the class for column
		 * headers.
		 */
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$args = array(
			'post_type' => SC_Message::POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => $per_page,
			'paged' => $this->get_pagenum(),
		);
		// Search
		if ( isset( $_GET['s'] ) && '' !== $_GET['s'] ) {
			$args = array_merge( $args, array( 's' => sanitize_text_field( $_GET['s'] ) ) );
		}
		// Filter by date
		if ( isset( $_GET['m'] ) && '' !== $_GET['m'] ) {
			$args = array_merge( $args, array( 'm' => sanitize_text_field( $_GET['m'] ) ) );
		}
		$messages = new WP_Query( $args );

		/**
		 * REQUIRED. *Sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = apply_filters( 'si_mngt_messages_items', $messages->posts );

		/**
		 * REQUIRED. Register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
				'total_items' => $messages->found_posts,
				'per_page'  => $per_page,
				'total_pages' => $messages->max_num_pages,
			) );
	}

}