<?php
namespace CE\Library_Master;

class API {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_api' ) );
	}

	public function register_api() {
		$library_master = new API\LibraryMaster();
		$library_master->register_routes();
	}
}
