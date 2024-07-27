<?php

namespace CE\Library_Master\Traits;

/**
 * Sanitization trait
 */
trait Sanitization {

	/**
	 * Sanitize a string input
	 *
	 * @param string $input
	 * @return string
	 */
	public function sanitize_user_input( $text ) {
		return sanitize_text_field( wp_unslash( $text ) );
	}
}
