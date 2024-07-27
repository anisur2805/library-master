<?php

namespace CE\Library_Master\Admin;

use CE\Library_Master\Traits\Form_Error;
use CE\Library_Master\Traits\Sanitization;

class MasterBook {

	use Form_Error;
	use Sanitization;

	public function plugin_page() {
		$action = isset( $_GET['action'] ) ? $this->sanitize_user_input( $_GET['action'] ) : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$book   = fetch_a_book( $id );

		switch ( $action ) {

			case 'new':
				$template = __DIR__ . '/views/book-new.php';
				break;

			case 'view':
				$template = __DIR__ . '/views/book-view.php';
				break;

			case 'edit':
				$template = __DIR__ . '/views/book-edit.php';
				break;

			default:
				$template = __DIR__ . '/views/book-list.php';
				break;
		}

		if ( file_exists( $template ) ) {
			include $template;
		}
	}

	public function form_handler() {
		if ( ! isset( $_POST['submit_book'] ) ) {
			return;
		}

		//check and validate nonce
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'new-book' ) ) {
			wp_send_json_error( __( 'Are you cheating!', 'library-master' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Are you cheating!', 'library-master' ) );
		}

		$id               = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$title            = isset( $_POST['title'] ) ? $this->sanitize_user_input( $_POST['title'] ) : '';
		$author           = isset( $_POST['author'] ) ? $this->sanitize_user_input( $_POST['author'] ) : '';
		$publisher        = isset( $_POST['publisher'] ) ? $this->sanitize_user_input( $_POST['publisher'] ) : '';
		$isbn             = isset( $_POST['isbn'] ) ? $this->sanitize_user_input( $_POST['isbn'] ) : '';
		$publication_date = isset( $_POST['publication_date'] ) ? $this->sanitize_user_input( $_POST['publication_date'] ) : '';

		if ( empty( $title ) ) {
			$this->errors['title'] = __( 'Please provide a title.', 'library-master' );
		}

		if ( empty( $author ) ) {
			$this->errors['author'] = __( 'Please provide a author.', 'library-master' );
		}

		if ( empty( $publisher ) ) {
			$this->errors['publisher'] = __( 'Please provide a publisher number.', 'library-master' );
		}

		if ( empty( $isbn ) ) {
			$this->errors['isbn'] = __( 'Please provide a ISBN number.', 'library-master' );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		$args = array(
			'title'            => $title,
			'author'           => $author,
			'publisher'        => $publisher,
			'isbn'             => $isbn,
			'publication_date' => $publication_date,
		);

		if ( $id ) {
			$args['id'] = $id;
		}

		$insert_id = master_insert_book( $args );

		if ( is_wp_error( $insert_id ) ) {
			wp_die( $insert_id->get_error_message() );
		}

		if ( $id ) {
			$redirect_to = admin_url( 'admin.php?page=library-master&action=edit&book-updated&id=' . $id );
		} else {
			$redirect_to = admin_url( 'admin.php?page=library-master&inserted=true' );
		}

		wp_redirect( $redirect_to );
		exit();
	}

	public function delete_book() {
		// if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'master-delete-book' ) ) {
		// 	wp_die( __( 'Are you cheating mia!', 'library-master' ) );
		// }

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'master-delete-book' ) ) {
			wp_send_json_error( __( 'Are you cheating!', 'library-master' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Are you cheating!', 'library-master' ) );
		}

		$id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;

		if ( master_delete_book( $id ) ) {
			$redirected_to = admin_url( 'admin.php?page=library-master&book-deleted=true' );

		} else {
			$redirected_to = admin_url( 'admin.php?page=library-master&book-deleted=false' );
		}

		wp_redirect( $redirected_to );
		exit;
	}
}
