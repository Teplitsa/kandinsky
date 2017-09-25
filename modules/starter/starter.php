<?php
if ( ! defined( 'WPINC' ) )
	die();

require get_template_directory() . '/modules/starter/menus.php';
require get_template_directory() . '/modules/starter/sidebars.php';
require get_template_directory() . '/vendor/parsedown/Parsedown.php';
require get_template_directory() . '/modules/starter/plot_data_builder.php';
require get_template_directory() . '/modules/starter/plot_shortcode_builder.php';
require get_template_directory() . '/modules/starter/plot_config.php';
require get_template_directory() . '/modules/starter/import_remote_content.php';

/**
 * Check for remove comments functions
 */
add_action( 'knd_plotdata_build_theme_options', 'knd_disable_comments' );

function knd_disable_comments() {
	$options = get_option( 'disable_comments_options', array() );
	
	$options['disabled_post_types'] = array( 'post', 'page', 'attachment' );
	$options['remove_everywhere'] = true;
	$options['permanent'] = false;
	$options['extra_post_types'] = false;
	$options['db_version'] = 6;
	
	update_option( 'disable_comments_options', $options );
}

