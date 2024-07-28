<?php

namespace CE\Library_Master\Admin;

/**
 * Menu class
 */
class Menu {
	public $master_library;

	public function __construct( $master_library ) {
		$this->master_library = $master_library;

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$capability  = 'manage_options';
		$parent_slug = 'library-master';

		$page_title = __( 'Library Master', 'library-master' );
		$hook       = add_menu_page( $page_title, $page_title, $capability, $parent_slug, array( $this->master_library, 'plugin_page' ), 'dashicons-welcome-learn-more' );
		add_submenu_page( $parent_slug, __( 'Library Master', 'library-master' ), __( 'Library Master', 'library-master' ), $capability, 'library-master', array( $this->master_library, 'plugin_page' ) );

		add_action( 'admin_head-' . $hook, array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'admin-style' );
		wp_enqueue_script( 'admin-script' );
	}
}
