<?php
use CE\Library_Master\Admin;
?>
<div class="wrap">
	<h1 class="wp-heading-inline"> <?php _e( 'Library Master Books', 'library-master' ); ?> </h1>

	<a href="<?php echo admin_url( 'admin.php?page=library-master&action=new' ); ?>" class="page-title-action"> <?php _e( 'Add New', 'library-master' ); ?> </a>

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
	
	<form action="" method="post">
	
		<?php
			$table = new Admin\Book_List();
			$table->prepare_items();
			$table->search_box( 'Search Items', 'item' );
			$table->display();
		?>
		
	</form>
</div>