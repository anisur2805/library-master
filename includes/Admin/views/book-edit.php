<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Edit book', 'library-master' ); ?><a href="<?php echo admin_url( 'admin.php?page=library-master' ); ?>" class="page-title-action"> <?php _e( 'Back to Book List', 'library-master' ); ?> </a></h1>
	<p><?php _e( 'For edit the book information please update the form.', 'library-master' ); ?></p>
	
	<?php
	if ( isset( $_GET['book-updated'] ) ) {
		printf( '<div class="notice notice-success"><p>' . __( 'Book has been update successfully!', 'library-master' ) . '</p></div>' );
	}
	?>
	
	<form action="" method="post">
		<table class="form-table">
			<tr class="<?php echo $this->has_error( 'title' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="title"><?php _e( 'Title', 'library-master' ); ?></label>
				</th>
				<td>
					<input id="title" name="title" value="<?php echo esc_attr( $book->title ); ?>" type="text" class="regular-text" />
					<?php if ( $this->has_error( 'title' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'title' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'author' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="author"><?php _e( 'Author', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="author" id="author" class="regular-text" value="<?php echo esc_attr( $book->author ); ?>" />
					<?php if ( $this->has_error( 'author' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'author' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'publisher' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="publisher"><?php _e( 'Publisher', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="publisher" id="publisher" class="regular-text" value="<?php echo esc_attr( $book->publisher ); ?>" />
					<?php if ( $this->has_error( 'publisher' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'publisher' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'isbn' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="isbn"><?php _e( 'ISBN', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="isbn" id="isbn" class="regular-text" value="<?php echo esc_attr( $book->isbn ); ?>" />
					<?php if ( $this->has_error( 'isbn' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'isbn' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="publication_date"><?php _e( 'Publication Date', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="date" name="publication_date" id="publication_date" class="regular-text" value="<?php echo esc_attr( $book->publication_date ); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
					<input type="hidden" name="id" value="<?php echo esc_attr( $book->id ); ?>" />
					<?php
					wp_nonce_field( 'new-book' );
					submit_button( __( 'Update Book', 'library-master' ), 'primary', 'submit_book' );
					?>
				</td>
			</tr>
		</table>
	</form>
</div>