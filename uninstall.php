<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Step 1
 * 
 * Clear database stored data
 *
 * Suppose we have a book post type which need to delete upon delete the plugin
 *
 * Query all books post and delete
 *
 */

$books = get_posts( array( 'post_type' => 'book', 'numberposts' => -1 ) );
foreach ( $books as $key => $book ) {
    wp_delete_post( $book->ID, true );
}

/**
 * Step 2
 */

global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book' " );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN ( SELECT id FROM wp_posts ) " );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN ( SELECT id FROM wp_posts ) " );
