<?php

namespace CE\Library_Master\Frontend;

class Shortcode {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_shortcode( 'library-master', array( $this, 'render_shortcode' ) );
	}


	/**
	 * Shortcode handler
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function render_shortcode( $atts, $content = null ) {
		wp_enqueue_script( 'library-script' );
		wp_enqueue_style( 'library-style' );

		return '<div class="oop-shortcode">BG Green Shortcode</div>';
	}
}
