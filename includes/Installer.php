<?php
namespace CE\Library_Master;

/**
 * Installer class
 */
class Installer {
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	public function add_version() {
		$installed = get_option( 'library_master_installed' );
		if ( ! $installed ) {
			update_option( 'library_master_installed', time() );
		}

		update_option( 'library_master_version', LIBRARY_MASTER_VERSION );
	}

	public function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ce_books`(
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title text NOT NULL,
            author text NOT NULL,
            publisher text NOT NULL,
            isbn varchar(13) NOT NULL,
            publication_date date NOT NULL,
            PRIMARY KEY  (`id`)
        ) $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}
