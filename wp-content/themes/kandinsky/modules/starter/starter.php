<?php

function knd_import_starter_data_from_csv() {
    //Read file
    $csv = array_map('str_getcsv', file(get_template_directory() . '/modules/starter/csv/posts.csv'));
    
    //     echo "Read lines ".count($csv).chr(10);

    foreach($csv as $i => $line) {
    
        if($i == 0) {
            continue;
        }
    
        $post_title = trim( $line[0] );
        $post_name = knd_clean_csv_slug( trim( $line[2] ) );
        $exist_page = knd_get_post( $post_name, 'post' );
    
        $page_data = array();
    
        $page_data['ID'] = $exist_page ? $exist_page->ID : 0;
        $page_data['post_type'] = 'post';
        $page_data['post_status'] = 'publish';
        $page_data['post_excerpt'] = '';
    
        $page_data['post_title']	= $post_title;
        $page_data['post_name'] 	= $post_name;
        $page_data['menu_order']	= (int)$line[5];
        $page_data['post_content'] = trim($line[1]);
        $page_data['post_parent'] = 0;
    
        //thumbnail
        $thumb_id = false;
        //imported old photo
        $thumbnail_url = trim($line[4]);
        if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
            ob_start();
            $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
            ob_end_clean();
        }
        
        if($thumb_id){
            $page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
        }
    
        $uid = wp_insert_post($page_data);
    
        //add tags
        if(!empty($line[6]) && $line[6] != 'none') {
            wp_set_post_terms((int)$uid, $line[6], 'project_cat', false);
            wp_cache_flush();
        }
    
    }
}

function knd_setup_starter_data() {

    global $wpdb;

    $res = array('status' => 'ok');

    try {
        knd_import_starter_data_from_csv();

        // set thumbnail for sample page
        $thumb_id = false;
        $thumbnail_url = 'https://ngo2.ru/kandinsky-files/knd-img2.jpg';
        if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
            ob_start();
            $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
            ob_end_clean();
        }
        if($thumb_id) {
            $hello_world_posts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_name IN (%s, %s) LIMIT 1", 'hello-world', '%d0%bf%d1%80%d0%b8%d0%b2%d0%b5%d1%82-%d0%bc%d0%b8%d1%80'));
            foreach($hello_world_posts as $hello_world_post) {
                update_post_meta( $hello_world_post->ID, '_thumbnail_id', $thumb_id );
            }
        }
    }
    catch(Exception $ex) {
        $res = array('status' => 'error');
    }
    
    wp_send_json( $res );
}
add_action("wp_ajax_setup_starter_data", "knd_setup_starter_data");
//add_action("wp_ajax_nopriv_setup_starter_data", "knd_setup_starter_data"); // It's only for admin
