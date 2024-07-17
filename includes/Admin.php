<?php
namespace CE\Library_Master;

class Admin {
	public function __construct() {
		$master_book = new Admin\MasterBook();

		$this->dispatch_actions( $master_book );
		new Admin\Menu( $master_book );
	}

	public function dispatch_actions( $master_book ) {
		add_action( 'admin_init', array( $master_book, 'form_handler' ) );
		add_action( 'admin_post_ce-delete-book', array( $master_book, 'delete_book' ) );
	}
}
