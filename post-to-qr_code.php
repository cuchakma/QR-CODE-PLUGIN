<?php
/*
Plugin Name: QR-CODE
Plugin URI: https://beekreta.com/
Description: Display QR CODE Under Every Posts
Version: 1.0
Author: Cupid Chakma
Author URI: https://wpmagnetar.com/ 
License: GPLV2 or later
Text Domain: post-to-qrcode
Domain Path: /languages/
*/

function wordcount_load_textdomain() {
    load_plugin_textdomain( 'post-to-qrcode', false, dirname(__FILE__)."/Languages" );
}

function pqrc_display_qr_code( $content ) {
    $current_post_id    = get_the_ID(); // to get the unique id of the post
    $current_post_title = get_the_title($current_post_id);
    $current_post_url   = urlencode( get_the_permalink( $current_post_id ) ); // to get the permalink of the post and encode the url
    $current_post_type  = get_post_type( $current_post_id );
    
    //Post Type Check

    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );  
    if(in_array( $current_post_type, $excluded_post_types ) ) {
        return $content;
    }

    //Size Hook

    $dimension = apply_filters( 'pqrc_qrcode_dimension', '150x150');

    // Image Attributes
    $Image_attributes = apply_filters('pqrc_image_attributes', '');

    $img_src            = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&data=%s', $dimension, $current_post_url );
    $content           .= sprintf( "<div class='qr-code'> <img %s src='%s' alt='%s'/> </div>", $Image_attributes ,$img_src, $current_post_title );
    return $content;
}

add_filter( 'the_content','pqrc_display_qr_code' );