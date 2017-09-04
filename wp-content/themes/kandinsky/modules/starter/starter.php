<?php if( !defined('WPINC') ) die;

require get_template_directory().'/modules/starter/class-demo.php';
require get_template_directory().'/modules/starter/menus.php';
require get_template_directory().'/modules/starter/sidebars.php';
require get_template_directory().'/vendor/parsedown/Parsedown.php';
require get_template_directory().'/modules/starter/plot_data_builder.php';
require get_template_directory().'/modules/starter/plot_shortcode_builder.php';
require get_template_directory().'/modules/starter/plot_config.php';
require get_template_directory().'/modules/starter/import_remote_content.php';

function knd_import_starter_data_from_csv($file, $post_type = 'post') {

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

function knd_setup_site_icon() {
    
    if(has_site_icon()) {
        return;
    }
    
    $site_icon_id = false;
    $thumbnail_url = 'https://ngo2.ru/kandinsky-files/favicon-small.png';
    
    if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
        $site_icon_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
    }
    
    if($site_icon_id) {
        update_option('site_icon', $site_icon_id);
    }

}

function knd_setup_starter_data($plot_name) {
    
    $imp = new KND_Import_Remote_Content($plot_name);
    $data = $imp->import_content();
    
//     print_r($data['color-line']['howtohelp']);
//     exit();
    
//     $piece = $imp->get_piece('footer');
//     var_dump($piece); echo "\n<br />\n";
//     $title = $imp->get_val('article1', 'title', 'articles');
//     var_dump($title); echo "\n<br />\n";
//     exit();
    
    $pdb = KND_Plot_Data_Builder::produce_builder($imp);
    $pdb->build_all();
    
    knd_update_posts();

    do_action('knd_save_demo_content');

    knd_setup_site_icon();
}

function knd_ajax_setup_starter_data() {

    $res = array('status' => 'ok');

    $plot_name = get_theme_mod('knd_site_scenario'); // problem-org, fundraising-org, public-campaign
    
    // debug
//     $plot_name = 'problem-org';
//     $plot_name = 'fundraising-org';
//     $plot_name = 'public-campaign';
    $plot_name = isset($_GET['plot']) ? $_GET['plot'] : 'problem-org';
    
    $imp = new KND_Import_Remote_Content($plot_name);
    if(!in_array($plot_name, $imp->possible_wizard_plots)) {
        $plot_name = "";
    }
    
    set_theme_mod('knd_site_scenario', $plot_name);

    if($plot_name) {
        try {
            knd_setup_starter_data($plot_name);
        } catch(Exception $ex) {
            error_log($ex);
            $res = array('status' => 'error');
        }
    }
    else {
        $res = array('status' => 'error', 'message' => "unknown plot");
    }

    wp_send_json($res);

}
add_action('wp_ajax_setup_starter_data', 'knd_ajax_setup_starter_data');
