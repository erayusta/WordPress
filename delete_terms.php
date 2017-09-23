<?php 
$dir = dirname(__FILE__);

include( $dir.'/wp-load.php' );
include( $dir.'/wp-admin/includes/image.php' );
global $wpdb;

function eru_delete_terms() {
		  $terms = get_terms( 'product_cat', array( 'fields' => 'ids', 'hide_empty' => false,'post_per_page'=>10 ) );
        
          foreach ( $terms as $value ) {
               wp_delete_term( $value, 'product_cat' );
               print $value."\n";
            
               
          }
}
eru_delete_terms();
