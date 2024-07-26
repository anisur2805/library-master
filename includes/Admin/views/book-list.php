<?php
use CE\Library_Master\Admin;
?>
<div class="wrap">
	<h1 class="wp-heading-inline"> <?php _e( 'Library Master Books', 'library-master' ); ?> <a href="<?php echo admin_url( 'admin.php?page=library-master&action=new' ); ?>" class="page-title-action"> <?php _e( 'Add New', 'library-master' ); ?> </a></h1>

	<?php if ( isset( $_GET['inserted'] ) ) { ?>
		<div class="notice notice-success">
			<p><?php _e( 'Book added successfully!', 'library-master' ); ?></p>
		</div>
	<?php } ?>
	
	<?php if ( isset( $_GET['book-deleted'] ) ) { ?>
		<div class="notice notice-success">
			<p><?php _e( 'Book deleted successfully!', 'library-master' ); ?></p>
		</div>
	<?php } ?>
	
	<form action="" method="get">
	
		<?php
		$table = new Admin\Book_List();
		$table->prepare_items();

		if ( isset( $_REQUEST['s'] ) ) {
			$search_item = $_REQUEST['s'];
		}

		$table->search_box( 'Search Items', 'search-id' );
		$table->display();
		?>
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
		
	</form>
</div>