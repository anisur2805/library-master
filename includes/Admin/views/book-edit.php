<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'Edit book', 'library-master' ); ?></h1>
	
	<?php
	if ( isset( $_GET['book-updated'] ) ) {
		printf( '<div class="notice notice-success"><p>' . __( 'Book has been update successfully!', 'library-master' ) . '</p></div>' );
	}

	function input_label( $for, $name ) {
		echo '<label for="' . $for . '">' . __( $name, 'library-master' ) . '</label>';
	}

	function input_field( $id, $value, $name, $type = 'text', $class = 'regular-text' ) {
		echo '<input id="' . $id . '" name="' . $name . '" value="' . $value . '" type="' . $type . '" class="' . $class . '" />';
	}
	?>
	
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<th>
					<tr class="row<?php echo $this->has_error( 'name' ) ? ' form-invalid' : ''; ?>">
						<th scope="row">
							<?php input_label( 'name', 'Name' ); ?>
						</th>
						<td>
							<?php
							input_field( 'name', esc_attr( $book->name ), 'name' );
							?>
							<?php if ( $this->has_error( 'name' ) ) { ?>
								<p class="description error"> <?php echo $this->get_error( 'name' ); ?> </p>
							<?php } ?>

						</td>
					</tr>
					<tr>
						<th scope="row">
							<?php input_label( 'address', 'Address' ); ?>
						</th>

						<td>
							<textarea name="address" id="address" class="regular-text"><?php echo esc_textarea( $book->address ); ?></textarea>
						</td>
					</tr>

					<tr class="row<?php echo $this->has_error( 'name' ) ? ' form-invalid' : ''; ?>">
						<th scope="row">
							<?php input_label( 'phone', 'Phone' ); ?>
						</th>

						<td>
							<?php input_field( 'phone', esc_attr( $book->phone ), 'phone' ); ?>
							<?php if ( $this->has_error( 'phone' ) ) { ?>
								<p class="description error"> <?php echo $this->get_error( 'phone' ); ?> </p>
							<?php } ?>

						</td>
					</tr>
				</th>
			</tbody>
		</table>

		<input type="hidden" name="id" value="<?php echo $book->id; ?>" />
		<?php
			wp_nonce_field( 'new-book' );
			submit_button( __( 'Update Book', 'library-master' ), 'primary', 'submit_book' );
		?>
	</form>
</div>