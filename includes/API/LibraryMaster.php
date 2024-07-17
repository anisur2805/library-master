<?php

namespace CE\Library_Master\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;

class LibraryMaster extends WP_REST_Controller {
	public function __construct() {
		$this->namespace = 'library/v1';
		$this->rest_base = 'books';
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'method'              => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_book_items' ),
					'permission_callback' => array( $this, 'get_items_permission_check' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'method'              => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_book_item' ),
					'permission_callback' => array( $this, 'create_item_permission_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			array(
				'args'   => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.' ),
						'type'        => 'integer',
					),
				),
				array(
					'method'              => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_book_item' ),
					'permission_callback' => array( $this, 'get_item_permission_check' ),
					'args'                => array(
						'content' => $this->get_context_param( array( 'default' => 'view' ) ),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);
	}

	/**
	 * Retrieves a list of book items
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Request|WP_Error
	 */
	public function get_book_items( \WP_REST_Request $request ) {
		$data = fetch_master_books();
		return new WP_REST_Response( $data, 200 );
	}

		/**
	 * Retrieves a list of book items.
	 *
	 * @param  \WP_Rest_Request $request
	 *
	 * @return \WP_Rest_Response|WP_Error
	 */
	public function get_items( $request ) {
		$args   = array();
		$params = $this->get_collection_params();

		foreach ( $params as $key => $value ) {
			if ( isset( $request[ $key ] ) ) {
				$args[ $key ] = $request[ $key ];
			}
		}

		// change `per_page` to `number`
		$args['number'] = $args['per_page'];
		$args['offset'] = $args['number'] * ( $args['page'] - 1 );

		// unset others
		unset( $args['per_page'] );
		unset( $args['page'] );

		$data  = array();
		$books = fetch_master_books( $args );

		foreach ( $books as $book ) {
			$response = $this->prepare_item_for_response( $book, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		$total     = master_books_count();
		$max_pages = ceil( $total / (int) $args['number'] );

		$response = rest_ensure_response( $data );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		return $response;
	}

	/**
	 * Checks if a given request has access to get a specific item.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|bool
	 */
	public function get_item_permission_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$book = $this->get_book( $request['id'] );

		if ( is_wp_error( $book ) ) {
			return $book;
		}

		return true;
	}

	/**
	 * Retrieves a list of book items
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Request|WP_Error
	 */
	public function get_book_item( \WP_REST_Request $request ) {
		$book = fetch_a_book( $request['id'] );

		$response = $this->prepare_item_for_response( $book, $request );
		$response = rest_ensure_response( $response );

		return $response;
	}

	/**
	 * Checks if a given request has access to manage_options book
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function get_items_permission_check( \WP_REST_Request $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrieves a list of book items
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Request|WP_Error
	 */
	public function create_book_item( \WP_REST_Request $request ) {

		$book = $this->prepare_item_for_database( $request );

		if ( is_wp_error( $book ) ) {
			return $book;
		}

		$book_id = master_insert_book( $book );

		if ( is_wp_error( $book_id ) ) {
			$book_id->add_data(
				array(
					'status' => 400,
				)
			);
			return $book_id;
		}

		$book     = $this->get_book( $book_id );
		$response = $this->prepare_item_for_response( $book, $request );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s%s%d', $this->namespace, $this->rest_base, $book_id ) ) );

		return rest_ensure_response( $response );
	}

		/**
	 * Get the book, if the ID is valid.
	 *
	 * @param int $id Supplied ID.
	 *
	 * @return Object|\WP_Error
	 */
	protected function get_book( $id ) {
		$book = fetch_a_book( $id );

		if ( ! $book ) {
			return new WP_Error(
				'rest_book_invalid_id',
				__( 'Invalid book ID.' ),
				array( 'status' => 404 )
			);
		}

		return $book;
	}


	/**
	 * Checks if a given request has access to create items.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Prepares the item for the REST response.
	 *
	 * @param mixed           $item    WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return \WP_Error|WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data   = array();
		$fields = $this->get_fields_for_response( $request );

		if ( in_array( 'id', $fields, true ) ) {
			$data['id'] = (int) $item->id;
		}

		if ( in_array( 'title', $fields, true ) ) {
			$data['title'] = $item->title;
		}

		if ( in_array( 'author', $fields, true ) ) {
			$data['author'] = $item->author;
		}

		if ( in_array( 'publisher', $fields, true ) ) {
			$data['publisher'] = $item->publisher;
		}

		if ( in_array( 'isbn', $fields, true ) ) {
			$data['isbn'] = $item->isbn;
		}

		if ( in_array( 'publication_date', $fields, true ) ) {
			$data['publication_date'] = $item->publication_date;
		}

		if ( in_array( 'date', $fields, true ) ) {
			$data['date'] = mysql_to_rfc3339( $item->created_at );
		}

		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );
		$response->add_links( $this->prepare_links( $item ) );

		return $response;
	}

	/**
	 * Checks if a given request has access to update a specific item.
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		return $this->get_item_permissions_check( $request );
	}

	/**
	* Prepare the item for create or update operation
	*
	* @param WP_REST_Request $request Request object
	* @return WP_Error|object $prepared_item
	*/
	protected function prepare_item_for_database( $request ) {
		$prepared = array();

		if ( isset( $request['title'] ) ) {
			$prepared['title'] = $request['title'];
		}

		if ( isset( $request['author'] ) ) {
			$prepared['author'] = $request['author'];
		}

		if ( isset( $request['publisher'] ) ) {
			$prepared['publisher'] = $request['publisher'];
		}

		if ( isset( $request['isbn'] ) ) {
			$prepared['isbn'] = $request['isbn'];
		}

		if ( isset( $request['publication_date'] ) ) {
			$prepared['publication_date'] = $request['publication_date'];
		}

		return $prepared;
	}

	/**
	 * Checks if a given request has access to manage_options book
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function create_item_permission_check( $request ) {
		return $this->get_items_permission_check( $request );
	}

	/**
	 * Updates one item from the collection.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function update_item( $request ) {
		$book     = $this->get_book( $request['id'] );
		$prepared = $this->prepare_item_for_database( $request );

		$prepared = array_merge( (array) $book, $prepared );

		$updated = master_insert_book( $prepared );

		if ( ! $updated ) {
			return new WP_Error(
				'rest_not_updated',
				__( 'Sorry, the book could not be updated.' ),
				array( 'status' => 400 )
			);
		}

		$book     = $this->get_book( $request['id'] );
		$response = $this->prepare_item_for_response( $book, $request );

		return rest_ensure_response( $response );
	}

	/**
	 * Checks if a given request has access to delete a specific item.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->get_item_permissions_check( $request );
	}

	/**
	 * Deletes one item from the collection.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$book     = $this->get_book( $request['id'] );
		$previous = $this->prepare_item_for_response( $book, $request );

		$deleted = master_delete_book( $request['id'] );

		if ( ! $deleted ) {
			return new WP_Error(
				'rest_not_deleted',
				__( 'Sorry, the book could not be deleted.' ),
				array( 'status' => 400 )
			);
		}

		$data = array(
			'deleted'  => true,
			'previous' => $previous->get_data(),
		);

		$response = rest_ensure_response( $data );

		return $data;
	}

		/**
	 * Prepares links for the request.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return array Links for the given post.
	 */
	protected function prepare_links( $item ) {
		$base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

		$links = array(
			'self'       => array(
				'href' => rest_url( trailingslashit( $base ) . $item->id ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);

		return $links;
	}

	/**
	 * Retrieves the book schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'book',
			'type'       => 'object',
			'properties' => array(
				'id'               => array(
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'title'            => array(
					'description' => __( 'Title of the book.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'author'           => array(
					'description' => __( 'Author of the book.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'publisher'        => array(
					'description' => __( 'Publisher of the book.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'required'    => true,
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'isbn'             => array(
					'description' => __( 'ISBN' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
				'publication_date' => array(
					'description' => __( 'Publication Date' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			),
		);

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

		/**
	 * Retrieves the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		unset( $params['search'] );

		return $params;
	}
}
