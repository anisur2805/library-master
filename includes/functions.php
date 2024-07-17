<?php

/**
 * Insert a new book
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function master_insert_books( $args = array() ) {
	global $wpdb;

	if ( empty( $args['name'] ) ) {
		return new \WP_Error( 'no-name', __( 'You must provide a name', 'library-master' ) );
	}

	$defaults = array(
		'name'       => '',
		'book'       => '',
		'phone'      => '',
		'created_by' => get_current_user_id(),
		'created_at' => current_time( 'mysql' ),
	);

	$data = wp_parse_args( $args, $defaults );

	if ( isset( $data['id'] ) ) {

		$id = $data['id'];
		unset( $data['id'] );

		$updated = $wpdb->update(
			"{$wpdb->prefix}ce_books",
			$data,
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			),
			array( '%d' )
		);

		return $updated;
	} else {

		$inserted = $wpdb->insert(
			"{$wpdb->prefix}ce_books",
			$data,
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			)
		);

		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', __( 'Failed to insert', 'library-master' ) );
		}

		return $wpdb->insert_id;
	}
}

/**
 * Fetch book
 *
 * @param array
 *
 * @return array
 */
function fetch_master_books( $args = array() ) {
	global $wpdb;

	$defaults = array(
		'offset'  => 0,
		'number'  => 20,
		'orderby' => 'id',
		'order'   => 'ASC',
	);

	$args = wp_parse_args( $args, $defaults );

	$items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}ce_books
            ORDER BY {$args["orderby"]} {$args["order"]}
            LIMIT %d OFFSET %d",
			$args['number'],
			$args['offset'],
		)
	);

	return $items;
}

/**
 * Get the count
 *
 * @return int
 */
function master_books_count() {
	global $wpdb;

	return (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}ce_books " );
}

/**
 * Fetch a single contact form DB
 *
 * @param int $id
 *
 * @return object
 */
function master_get_books( $id ) {
	global $wpdb; // Global WPDB class object

	// $book = wp_cache_get( 'ce-' . $id, 'book' );
	// if ( false === $book ) {
		$book = $wpdb->get_row(
			$wpdb->prepare(  // use prepare for avoid sql injection
				"SELECT  * FROM {$wpdb->prefix}ce_books WHERE id = %d",
				$id // select by id
			)
		);

		// wp_cache_set( 'ce-' . $id, $book, 'books' );

	// }
	return $book;
}

/**
 * Delete an book
 *
 * @param int $id
 *
 * @return int|boolean
 */
function master_delete_book( $id ) {
	global $wpdb;

	return $wpdb->delete(
		$wpdb->prefix . 'ce_books',
		array( 'id' => $id ),
		array( '%d' ),
	);
}
