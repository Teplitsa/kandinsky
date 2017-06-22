<?php

function knd_get_post( $post_id, $post_type = 'post' ) {
    global $wpdb;
    $post = null;
    if( preg_match( '/^\d+$/', $post_id ) ) {
        $post = get_post( $post_id, OBJECT );
    }
    else {
        //         printf( "post_id=%s\n", $post_id );
        $post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s LIMIT 1 ", $post_id, $post_type ) );
        //         printf( "post_id=%s\n", $post_id );
        if( $post_id ) {
            $post = get_post( $post_id, OBJECT );
        }
    }
    return $post;
}

function knd_clean_csv_slug( $slug ) {
    return preg_replace( '/\s+/', '', trim( $slug ) );
}

function knd_get_temp_dir() {
    return ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
}