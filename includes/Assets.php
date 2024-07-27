<?php
namespace CE\Library_Master;

class Assets {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	public function get_scripts() {
		return array(
			'library-script' => array(
				'src'     => LIBRARY_MASTER_ASSETS . '/js/frontend.js',
				'version' => filemtime( LIBRARY_MASTER_PATH . '/assets/js/frontend.js' ),
				'deps'    => array( 'jquery' ),
			),
			'admin-script'   => array(
				'src'     => LIBRARY_MASTER_ASSETS . '/js/admin.js',
				'version' => filemtime( LIBRARY_MASTER_PATH . '/assets/js/admin.js' ),
				'deps'    => array( 'jquery', 'wp-util' ),
			),
		);
	}

	public function get_styles() {
		return array(
			'library-style' => array(
				'src'     => LIBRARY_MASTER_ASSETS . '/css/frontend.css',
				'version' => filemtime( LIBRARY_MASTER_PATH . '/assets/css/frontend.css' ),
			),
			'admin-style'   => array(
				'src'     => LIBRARY_MASTER_ASSETS . '/css/admin.css',
				'version' => filemtime( LIBRARY_MASTER_PATH . '/assets/css/admin.css' ),
			),
		);
	}

	public function enqueue_assets() {
		$scripts = $this->get_scripts();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;
			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		$styles = $this->get_styles();

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
			wp_register_style( $handle, $style['src'], $deps, $style['version'] );

		}

		wp_localize_script(
			'enquiry-script',
			'library',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'error'   => __( 'Something went wrong', 'library-master' ),
			)
		);

		wp_localize_script(
			'admin-script',
			'library',
			array(
				'nonce'   => wp_create_nonce( 'library-master-nonce' ),
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'confirm' => __( 'Are you sure?', 'library-master' ),
				'error'   => __( 'Something went wrong', 'library-master' ),
			)
		);

		wp_enqueue_script( 'tailwind-js', LIBRARY_MASTER_ASSETS . '/js/tailwind.js' );
		wp_enqueue_script( 'react-frontend', LIBRARY_MASTER_DIST . '/index.bundle.js', array( 'jquery', 'wp-element', 'wp-api' ), time(), true );
		wp_localize_script(
			'react-frontend',
			'app',
			array(
				'root'  => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
