<?php if( !defined('WPINC') ) die;

require get_template_directory().'/modules/starter/menus.php';
require get_template_directory().'/modules/starter/sidebars.php';
require get_template_directory().'/vendor/parsedown/Parsedown.php';
require get_template_directory().'/modules/starter/plot_data_builder.php';
require get_template_directory().'/modules/starter/plot_shortcode_builder.php';
require get_template_directory().'/modules/starter/plot_config.php';
require get_template_directory().'/modules/starter/import_remote_content.php';

function knd_setup_starter_data($plot_name) {
    
    $imp = new KND_Import_Remote_Content($plot_name);
    $data = $imp->import_content();
    
//     var_dump($data);


    $pdb = KND_Plot_Data_Builder::produce_builder($imp);
    $pdb->build_all();
    
    do_action('knd_save_demo_content');
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


/** Check for remove comments functions */
add_action('knd_build_test_content_options', 'knd_disable_comments');
function knd_disable_comments() {
    

    $options = get_option( 'disable_comments_options', array() );

    $options['disabled_post_types'] = array('post', 'page', 'attachment');
    $options['remove_everywhere'] = true;
    $options['permanent'] = false;
    $options['extra_post_types'] = false;
    
    update_option('disable_comments_options', $options);
    update_option('knd_disable_comments', 1);
    
}

