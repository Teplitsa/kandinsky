<?php
require get_template_directory().'/modules/starter/class-demo.php';
require get_template_directory().'/modules/starter/menus.php';
require get_template_directory().'/modules/starter/sidebars.php';

function knd_import_starter_data_from_csv($file, $post_type = 'post') {
    //Read file
    $input_file = get_template_directory() . '/modules/starter/csv/' . $file;
    knd_import_posts_from_csv($input_file, $post_type);
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

function knd_set_theme_options() {
    $thumb_id = TST_Import::get_instance()->maybe_import( 'https://ngo2.ru/kandinsky-files/knd-img1.jpg' );
    
    if($thumb_id) {
        set_theme_mod( 'knd_hero_image', $thumb_id );
    }
    
    set_theme_mod( 'knd_hero_image_support_title', "Поддержать «Линию Цвета»" );
    set_theme_mod( 'knd_hero_image_support_text', "<b>Помоги людям бороться с алкогольной зависимостью.</b> В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваши пожертвования помогут организовать для них реабилитационную программу." );
    set_theme_mod( 'knd_hero_image_support_button_caption', "Сделать пожертвование" );
}

function knd_setup_menus() {
    
    KND_StarterMenus::knd_setup_our_work_menu();
    KND_StarterMenus::knd_setup_news_menu();
    
    KND_StarterSidebars::setup_footer_sidebar();
    KND_StarterSidebars::setup_homepage_sidebar();
    
}

function knd_setup_starter_data() {

    knd_import_starter_data_from_csv('posts.csv', 'post');

    knd_update_posts();

    knd_set_theme_options();
    
    knd_setup_menus();  // all menus except main nav menu
    
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
