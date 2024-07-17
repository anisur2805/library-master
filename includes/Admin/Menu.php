<?php

namespace CE\Library_Master\Admin;

/**
 * Menu class
 */
class Menu {
	public $addressbook;

	public function __construct( $addressbook ) {
		$this->addressbook = $addressbook;

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		$capability  = 'manage_options';
		$parent_slug = 'library-master';

		$page_title = __( 'Library Master', 'library-master' );
		$hook       = add_menu_page( $page_title, $page_title, $capability, $parent_slug, array( $this->addressbook, 'plugin_page' ), 'dashicons-welcome-learn-more' );
		add_submenu_page( $parent_slug, __( 'Library Master', 'library-master' ), __( 'Library Master', 'library-master' ), $capability, 'library-master', array( $this->addressbook, 'plugin_page' ) );
		add_submenu_page( $parent_slug, __( 'Settings', 'library-master' ), __( 'Settings', 'library-master' ), $capability, 'library-master-settings', array( $this, 'settings_page' ) );

		add_action( 'admin_head-' . $hook, array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'admin-style' );
		wp_enqueue_script( 'admin-script' );
	}
}
