<?php 
$dir = dirname(__FILE__);

include( $dir.'/wp-load.php' );
include( $dir.'/wp-admin/includes/image.php' );
global $wpdb;

function delete_post_attachments( $post_name ) {
	global $wpdb;

	$sql = "SELECT ID FROM {$wpdb->posts} ";
	$sql .= " WHERE post_title = '$post_name' ";
	$sql .= " AND post_type = 'attachment'";

	$ids = $wpdb->get_results( $sql );

	foreach ( $ids as $id ) {
		wp_delete_attachment( $id->ID );
	}
}

// Select custom attachments file list
$skus = $wpdb->get_results("select title from pimport");
	foreach ( $skus as $sku ) {	
		delete_post_attachments($sku->title);
		print $sku->title."\n";
	
	}
