<?php

namespace CE\Library_Master\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Book_List extends \WP_List_Table {
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'book',
				'plural'   => 'books',
				'ajax'     => false,
			)
		);
	}

	public function get_columns() {
		return array(
			'cb'               => '<input type="checkbox" />',
			'title'            => __( 'Title', 'library-master' ),
			'author'           => __( 'Author', 'library-master' ),
			'publisher'        => __( 'Publisher', 'library-master' ),
			'isbn'             => __( 'ISBN', 'library-master' ),
			'publication_date' => __( 'Publication Date', 'library-master' ),
		);
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'title'      => array( 'title', true ),
			'created_at' => array( 'created_at', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Move to Trash', 'arpc-popup-creator' ),
		);

		return $actions;
	}

	protected function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'value':
				break;

			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	public function column_title( $item ) {
		$actions = array();

		$actions['edit']   = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=library-master&action=edit&id=' . $item->book_id ), $item->book_id, __( 'Edit', 'library-master' ), __( 'Edit', 'library-master' ) );
		$actions['delete'] = sprintf(
			'<a href="#" class="submit_delete" title="%s" data-id="%s">%s</a>',
			__( 'Delete', 'library-master' ),
			$item->book_id,
			__( 'Delete', 'library-master' )
		);

		return sprintf(
			'<a href="%1$s"><strong>%2$s</strong></a> %3$s',
			admin_url( 'admin.php?page=library-master&action=view&id=' . $item->book_id ),
			$item->title,
			$this->row_actions( $actions )
		);
	}

	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="book_id[]" value="%d" />',
			$item->book_id
		);
	}

	public function prepare_items() {
		$column   = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$per_page = 20;

		$this->_column_headers = array( $column, $hidden, $sortable );

		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$args = array(
			'number' => $per_page,
			'offset' => $offset,
		);

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
			$args['order']   = $_REQUEST['order'];
		}

		$this->items = fetch_master_books( $args );

		$this->set_pagination_args(
			array(
				'total_items' => master_books_count(),
				'per_page'    => $per_page,
			)
		);
	}
}
