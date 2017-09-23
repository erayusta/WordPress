<?php 

$dir = dirname(__FILE__);

include( $dir.'/wp-load.php' );
include( $dir.'/wp-admin/includes/image.php' );
global $wpdb;

function delete_post_attachments( $post_id ) {
	global $wpdb;

	$sql = "SELECT ID FROM {$wpdb->posts} ";
	$sql .= " WHERE post_parent = $post_id ";
	$sql .= " AND post_type = 'attachment'";

	$ids = $wpdb->get_results( $sql );

	foreach ( $ids as $id ) {
		wp_delete_attachment( $id->ID );
	}
}

$args     = array(
	'fields'         => 'ids',
	'post_type'      => 'product',
	'posts_per_page' => '5000',
	'suppress_filters' => true // Wpml get all post lang
);
$my_query = new WP_Query( $args );
if ( $my_query->have_posts() ) {
	while ( $my_query->have_posts() ) : $my_query->the_post();
		$delete_post_id = get_the_id();
		delete_post_attachments( $delete_post_id );
		wp_delete_post( $delete_post_id );
		print $delete_post_id." silindi!\n";
	endwhile;
}
