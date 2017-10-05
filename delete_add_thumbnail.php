<?php
// Delete selected product thumbnail, resize image and add new thumb
$dir = dirname( __FILE__ );

include( $dir . '/wp-load.php' );
include( $dir . '/wp-admin/includes/image.php' );
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


function set_post_attachments( $post_name, $post_id ) {

// Add Featured Image to Post
	$image_url        = "../httpdocs/teknorot/img/" .  utf8_decode($post_name)  . ".JPG";
	$image_url2        = "https://www.teknorot.com/teknorot/img/" .  utf8_decode($post_name)  . ".JPG";
	$image_name       = sanitize_title( $post_name ) . '.jpg';
	$upload_dir       = wp_upload_dir(); // Set upload folder



if(file_exists($image_url)) 
{ 

	$image_data       = file_get_contents( $image_url2 ); // Get image data
	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$filename         = basename( $unique_file_name ); // Create image file name

// Check folder permission and define file location
	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $upload_dir['path'] . '/' . $filename;
	} else {
		$file = $upload_dir['basedir'] . '/' . $filename;
	}

	// Create the image  file on the server
	file_put_contents( $file, $image_data );

	if ( file_exists( $file ) ) {
		list( $width, $height ) = getimagesize( $file );
		exec( 'mogrify -resize 800x800 -gravity center -background white -extent 800x800 ' . $file );
	}


// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

// Set attachment data
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);

// Create the attachment
	$attach_id   = wp_insert_attachment( $attachment, $file, $post_id );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	set_post_thumbnail( $post_id, $attach_id );
	} 
	
}


$products = $wpdb->get_results( "SELECT ID,post_title FROM `wp_posts` WHERE post_type='product' and post_status='publish' and post_title IN ('D-816','B-817','DO-136','DO-257') order by ID ASC", ARRAY_A );

foreach ( $products as $pro ) {
	delete_post_attachments( $pro['ID'] );
	set_post_attachments( $pro['post_title'], $pro['ID'] );
	print $pro['post_title']." ".$pro['ID'] ."\n";
}


print "FINITO!";
