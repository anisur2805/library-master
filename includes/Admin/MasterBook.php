<?php

namespace CE\Library_Master\Admin;

use CE\Library_Master\Traits\Form_Error;

class MasterBook {

	use Form_Error;

	public function plugin_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$book   = master_get_books( $id );

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

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-book' ) ) {
			wp_die( 'Are you cheating!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating!' );
		}

		$id               = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$title            = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
		$author           = isset( $_POST['author'] ) ? sanitize_textarea_field( $_POST['author'] ) : '';
		$publisher        = isset( $_POST['publisher'] ) ? sanitize_text_field( $_POST['publisher'] ) : '';
		$isbn             = isset( $_POST['isbn'] ) ? sanitize_text_field( $_POST['isbn'] ) : '';
		$publication_date = isset( $_POST['publication_date'] ) ? sanitize_text_field( $_POST['publication_date'] ) : '';

		if ( empty( $title ) ) {
			$this->errors['title'] = __( 'Please provide a title.', 'library-master' );
		}

		if ( empty( $author ) ) {
			$this->errors['author'] = __( 'Please provide a author.', 'library-master' );
		}

		if ( empty( $publisher ) ) {
			$this->errors['publisher'] = __( 'Please provide a publisher number.', 'library-master' );
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

		$insert_id = master_insert_books( $args );

		if ( is_wp_error( $insert_id ) ) {
			wp_die( $insert_id->get_error_message() );
		}

		if ( $id ) {
			error_log( 'Alt log: ' );

			$redirect_to = admin_url( 'admin.php?page=library-master&action=edit&book-updated&id=' . $id );
		} else {
			$redirect_to = admin_url( 'admin.php?page=library-master&inserted=true' );
		}

		wp_redirect( $redirect_to );
		exit();
	}

	public function delete_book() {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'master-delete-book' ) ) {
			wp_die( 'Are you cheating mia!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating!' );
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
