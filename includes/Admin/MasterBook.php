<?php

namespace CE\Library_Master\Admin;

class MasterBook {

	public function plugin_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		switch ( $action ) {

			case 'new':
				$template = __DIR__ . '/views/book-new.php';
				break;

			case 'view':
				$template = __DIR__ . '/views/book-view.php';
				break;

			case 'edit':
				$address  = master_get_books( $id );
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
		if ( ! isset( $_POST['submit_address'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-address' ) ) {
			wp_die( 'Are you cheating!' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating!' );
		}

		$id      = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$address = isset( $_POST['address'] ) ? sanitize_textarea_field( $_POST['address'] ) : '';
		$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';

		if ( empty( $name ) ) {
			$this->errors['name'] = __( 'Please provide a name.', 'library-master' );
		}

		if ( empty( $phone ) ) {
			$this->errors['phone'] = __( 'Please provide a phone number.', 'library-master' );
		}

		if ( ! empty( $this->errors ) ) {
			return;
		}

		$args = array(
			'name'    => $name,
			'address' => $address,
			'phone'   => $phone,
		);

		if ( $id ) {
			$args['id'] = $id;
		}

		$insert_id = master_insert_books( $args );

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

	public function delete_address() {
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
