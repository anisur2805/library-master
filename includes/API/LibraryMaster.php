<?php

namespace CE\Library_Master\API;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

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
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permission_check' ),
					'args'                => $this->get_collection_params(),
				),
			)
		);
	}

	/**
	 * Retrieves a list of address items
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Request|WP_Error
	 */
	public function get_items( $request ) {
	}

	/**
	 * Checks if a given request has access to read contacts
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return boolean
	 */
	public function get_items_permission_check( $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return false;
	}
}
