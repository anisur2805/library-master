<?php

/**
 * Insert a new book
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function master_insert_book( $args = array() ) {

	global $wpdb;

	if ( empty( $args['title'] ) ) {
		return new \WP_Error( 'no-title', __( 'You must provide a title', 'library-master' ) );
	}

		$defaults = array(
			'title'            => '',
			'author'           => '',
			'publisher'        => '',
			'isbn'             => '',
			'publication_date' => '',
			'created_by'       => get_current_user_id(),
			'created_at'       => current_time( 'mysql' ),
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
					'%s',
					'%s',
					'%d',
					'%s',
				),
				array( '%d' )
			);

			master_book_purge_cache( $id );

			return $updated;
		} else {

			$inserted = $wpdb->insert(
				"{$wpdb->prefix}ce_books",
				$data,
				array(
					'%s',
					'%s',
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

			master_book_purge_cache();

			return $wpdb->insert_id;
		}
}

/**
 * Fetch all books
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
		'search'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$search_term = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';

	// Generate a cache key that includes the search term if present
	$key       = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
	$cache_key = "ce_all_books:$key";

	// Only use cache when there's no search term
	if ( empty( $search_term ) ) {
		$items = get_transient( $cache_key );
	} else {
		$items = false;
	}

	if ( false === $items ) {
		$search_sql = '';
		if ( ! empty( $search_term ) ) {
			$search_term = '%' . $wpdb->esc_like( $search_term ) . '%';
			$search_sql  = $wpdb->prepare(
				'WHERE author LIKE %s OR title LIKE %s OR isbn LIKE %s',
				$search_term,
				$search_term,
				$search_term
			);
		}

		$sql = $wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}ce_books
			{$search_sql}
			ORDER BY {$args['orderby']} {$args['order']}
			LIMIT %d OFFSET %d",
			$args['number'],
			$args['offset']
		);

		$items = $wpdb->get_results( $sql );

		// Set cache only when there's no search term
		if ( empty( $search_term ) ) {
			set_transient( $cache_key, $items, 12 * HOUR_IN_SECONDS );
		}
	}

	return $items;
}

/**
 * Get the count
 *
 * @return int
 */
function master_books_count() {
	global $wpdb;

	$cache_key = 'ce_all_books_count';
	$count     = get_transient( $cache_key );

	if ( false === $count ) {
		$count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}ce_books" );

		set_transient( $cache_key, $count, 12 * HOUR_IN_SECONDS );
	}

	return $count;
}

/**
 * Fetch a single book form DB
 *
 * @param int $id
 *
 * @return object
 */
function fetch_a_book( $id ) {
	global $wpdb;

	if ( ! $id ) {
		return;
	}

	$cache_key = 'ce-single-book-' . $id;
	$book      = get_transient( $cache_key );

	if ( false === $book ) {
		$book = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT  * FROM {$wpdb->prefix}ce_books WHERE id = %d",
				$id
			)
		);

		set_transient( $cache_key, $book, 12 * HOUR_IN_SECONDS );

	}
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

	master_book_purge_cache( $id );

	return $wpdb->delete(
		$wpdb->prefix . 'ce_books',
		array( 'id' => $id ),
		array( '%d' ),
	);
}

/**
 * Purge cache for books.
 *
 * Deletes the transients related to books, including a specific book if its ID is provided.
 *
 * @param int|null $book_id Optional. The ID of the book to purge the cache for. Default null.
 */
function master_book_purge_cache( $book_id = null ) {
	// Define constants for transient keys
	$all_books_transient_key       = 'ce_all_books';
	$all_books_count_transient_key = 'ce_all_books_count';
	$single_book_transient_prefix  = 'ce-single-book-';

	// Delete transients for all books and all books count
	delete_transient( $all_books_transient_key );
	delete_transient( $all_books_count_transient_key );

	// If a book ID is provided, delete the transient for that specific book
	if ( ! is_null( $book_id ) && is_int( $book_id ) && $book_id > 0 ) {
		delete_transient( $single_book_transient_prefix . $book_id );
	}
}
