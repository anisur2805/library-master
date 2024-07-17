<?php
namespace CE\Library_Master;

class Ajax {
	public function __construct() {
		add_action( 'wp_ajax_library-book-delete', array( $this, 'delete_book' ) );
	}

	public function delete_book() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'library-master-nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verify failed!', 'library-master' ),
				)
			);
		}

		$post_id    = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$is_deleted = master_delete_book( $post_id );

		if ( $is_deleted ) {
			wp_send_json_success(
				array(
					'message' => __( 'Book deleted successfully!', 'library-master' ),
				)
			);
		}
	}
}
