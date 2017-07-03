<?php
require get_template_directory().'/modules/starter/class-demo.php';

function knd_import_starter_data_from_csv($file, $post_type = 'post') {
    //Read file
    $input_file = get_template_directory() . '/modules/starter/csv/' . $file;

    if (($handle = fopen( $input_file, "r" )) !== FALSE) {

        $i = 0;

        while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
    
            $i += 1;
            
            if($i == 1) {
                continue;
            }
        
            $post_title = trim( $line[0] );
            $post_name = knd_clean_csv_slug( trim( $line[2] ) );
            $exist_page = knd_get_post( $post_name, 'post' );
        
            $page_data = array();

            $page_data['ID'] = $exist_page ? $exist_page->ID : 0;
            $page_data['post_type'] = $post_type;
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
                $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
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
            
            unset( $line );
        }
    
    }
}


function knd_update_posts() {
    global $wpdb;
    
    // set thumbnail for sample page
    $thumb_id = false;
    $thumbnail_url = 'https://ngo2.ru/kandinsky-files/knd-img2.jpg';
    if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
        $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
    }
    if($thumb_id) {
        $hello_world_posts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_name IN (%s, %s) LIMIT 1", 'hello-world', '%d0%bf%d1%80%d0%b8%d0%b2%d0%b5%d1%82-%d0%bc%d0%b8%d1%80'));
        foreach($hello_world_posts as $hello_world_post) {
            update_post_meta( $hello_world_post->ID, '_thumbnail_id', $thumb_id );
        }
    }
    
}

function knd_setup_menus() {
    
    $main_menu_name = __( 'Kandinsky main menu', 'knd' );
    
    if(is_nav_menu($main_menu_name)){
        wp_delete_nav_menu($main_menu_name);
    }
    $main_menu_id = wp_create_nav_menu( $main_menu_name );
    
    $item_data = array(
        'menu-item-parent-id' => 0,
        'menu-item-position' => 0,
        'menu-item-title' => __( 'Home page', 'knd' ),
        'menu-item-status' => 'publish',
        'menu-item-url' => home_url('/'),
    );
    wp_update_nav_menu_item($main_menu_id, 0, $item_data);
    
    $page = knd_get_post( 'about-us', 'page' );
    if($page) {
        $item_data = array(
            'menu-item-object-id' => $page->ID,
            'menu-item-object' => 'page',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 1,
            'menu-item-type' => 'post_type',
            'menu-item-title' => $page->post_title,
            'menu-item-url' => get_permalink( $page ),
            'menu-item-classes' => 'menu-item menu-item-type-post_type menu-item-object-page menu-item-' . $page->ID,
            'menu-item-status' => 'publish',
        );
        wp_update_nav_menu_item($main_menu_id, 0, $item_data);
    }
    
    $page = knd_get_post( 'activity', 'page' );
    if($page) {
        $item_data = array(
            'menu-item-object-id' => $page->ID,
            'menu-item-object' => 'page',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 1,
            'menu-item-type' => 'post_type',
            'menu-item-title' => $page->post_title,
            'menu-item-url' => get_permalink( $page ),
            'menu-item-classes' => 'menu-item menu-item-type-post_type menu-item-object-page menu-item-' . $page->ID,
            'menu-item-status' => 'publish',
        );
        wp_update_nav_menu_item($main_menu_id, 0, $item_data);
    }
    
    $locations = get_theme_mod('nav_menu_locations');
    $locations['primary'] = $main_menu_id;
    set_theme_mod('nav_menu_locations', $locations );
    
}

function knd_set_theme_options() {
    $thumb_id = TST_Import::get_instance()->maybe_import( 'https://ngo2.ru/kandinsky-files/knd-img1.jpg' );
    
    if($thumb_id) {
        set_theme_mod( 'knd_hero_image', $thumb_id );
    }
    
    set_theme_mod( 'knd_hero_image_support_title', "Поддержать «Линию Цвета»" );
    set_theme_mod( 'knd_hero_image_support_text', "<b>Помоги людям бороться с алкогольной зависимостью.</b> В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваши пожертвования помогут организовать для них реабилитационную программу." );
    set_theme_mod( 'knd_hero_image_support_button_caption', "Сделать пожертвование" );
}

function knd_setup_starter_data() {

    knd_import_starter_data_from_csv('posts.csv', 'post');
    //knd_import_starter_data_from_csv('pages.csv', 'page');

    knd_update_posts();

    knd_set_theme_options();
    
    //knd_setup_menus();
    do_action('knd_save_demo_content');

}

function knd_ajax_setup_starter_data() {

    global $wpdb;

    $res = array('status' => 'ok');

    try {
        knd_setup_starter_data();
    } catch(Exception $ex) {
        error_log($ex);
        $res = array('status' => 'error');
    }
    
    wp_send_json( $res );
    
}
add_action("wp_ajax_setup_starter_data", "knd_ajax_setup_starter_data");
