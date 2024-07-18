<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Add Book', 'library-master' ); ?></h1>
	<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus numquam accusantium ducimus!</p>

	<form action="" method="post">
		<table class="form-table">
			<tr class="<?php echo $this->has_error( 'title' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="title"><?php _e( 'Title*', 'library-master' ); ?></label>
				</th>
				<td>
					<input id="title" name="title" value="" type="text" class="regular-text" />
					<?php if ( $this->has_error( 'title' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'title' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'author' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="author"><?php _e( 'Author*', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="author" id="author" class="regular-text" value="" />
					<?php if ( $this->has_error( 'author' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'author' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'publisher' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="publisher"><?php _e( 'Publisher*', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="publisher" id="publisher" class="regular-text" value="" />
					<?php if ( $this->has_error( 'publisher' ) ) : ?>
						<p class="description error"><?php echo $this->get_error( 'publisher' ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
			<tr class="<?php echo $this->has_error( 'isbn' ) ? 'form-invalid' : ''; ?>">
				<th scope="row">
					<label for="isbn"><?php _e( 'ISBN*', 'library-master' ); ?></label>
				</th>
				<td>
					<input type="text" name="isbn" id="isbn" class="regular-text" value="" />
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
					<input type="date" name="publication_date" id="publication_date" class="regular-text" value="" />
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
					<?php
					wp_nonce_field( 'new-book' );
					submit_button( __( 'Add Book', 'library-master' ), 'primary', 'submit_book' );
					?>
				</td>
			</tr>
		</table>
	</form>
</div>
