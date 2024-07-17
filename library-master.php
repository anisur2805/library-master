<?php
/**
 * Plugin Name: Library Master
 * Description: A Plugin for managing a library system that handles book records using custom SQL queries and a REST API.
 * Plugin URI:  #
 * Version:     1.0.0
 * Author:      Anisur Rahman
 * Author URI:  https:github.com/anisur2805
 * Text Domain: library-master
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

use CE\Library_Master\Ajax;
use CE\Library_Master\Assets;
use CE\Library_Master\Installer;

defined( 'ABSPATH' ) or die( 'No Cheating!' );

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class LIBRARY_MASTER {
	/**
	 * plugin version
	 */
	const VERSION = '1.0';

	/**
	 * class constructor
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initialize a singleton instance
	 *
	 * @return \LIBRARY_MASTER
	 */
	public static function init() {
		static $instance = false;
		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * define plugin require constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'LIBRARY_MASTER_VERSION', self::VERSION );
		define( 'LIBRARY_MASTER_FILE', __FILE__ );
		define( 'LIBRARY_MASTER_PATH', __DIR__ );
		define( 'LIBRARY_MASTER_URL', plugins_url( '', LIBRARY_MASTER_FILE ) );
		define( 'LIBRARY_MASTER_ASSETS', LIBRARY_MASTER_URL . '/assets' );
		define( 'LIBRARY_MASTER_INCLUDES', LIBRARY_MASTER_URL . '/includes' );
	}

	/**
	 * Do staff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new Installer();
		$installer->run();
	}

	public function init_plugin() {

		new CE\Library_Master\Assets();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Ajax();
		}

		if ( is_admin() ) {
			new \CE\Library_Master\Admin();
		} else {
			new \CE\Library_Master\Frontend();
		}

		new CE\Library_Master\API();
	}
}

/**
 * Initialize the main plugin
 *
 * @return \LIBRARY_MASTER
 */
function library_master() {
	return LIBRARY_MASTER::init();
}

library_master();
