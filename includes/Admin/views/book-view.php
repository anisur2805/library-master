<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'View Single book', 'library-master' ); ?></h1>
	<a href="<?php echo admin_url( 'admin.php?page=library-master' ); ?>" class="page-title-action"> <?php _e( 'Back to book list', 'library-master' ); ?> </a>
	<p><?php _e( 'This is the view screen of the single book.', 'library-master' ); ?></p>

	<?php
	if ( ! is_object( $book ) ) {
		echo '<h2>' . __( 'No book found!', 'library-master' ) . '</h2>';
		return;
	}
	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="title"><?php _e( 'Title', 'library-master' ); ?></label>
			</th>
			<td>
				<input id="title" type="text" name="title" value="<?php echo esc_attr( $book->title ); ?>" readonly class="regular-text" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="author"><?php _e( 'Author', 'library-master' ); ?></label>
			</th>
			<td>
				<input type="text" readonly name="author" id="author" class="regular-text" value="<?php echo esc_attr( $book->author ); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="publisher"><?php _e( 'Publisher', 'library-master' ); ?></label>
			</th>
			<td>
				<input type="text" readonly name="publisher" id="publisher" class="regular-text" value="<?php echo esc_attr( $book->publisher ); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="isbn"><?php _e( 'ISBN', 'library-master' ); ?></label>
			</th>
			<td>
				<input type="text" readonly name="isbn" id="isbn" class="regular-text" value="<?php echo esc_attr( $book->isbn ); ?>" />
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label for="publication_date"><?php _e( 'Publication Date', 'library-master' ); ?></label>
			</th>
			<td>
				<input type="date" readonly name="publication_date" id="publication_date" class="regular-text" value="<?php echo esc_attr( $book->publication_date ); ?>" />
			</td>
		</tr>
	</table>
</div>