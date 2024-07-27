<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
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

global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}ce_books" );
