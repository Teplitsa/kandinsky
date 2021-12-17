<?php
if ( ! defined( 'WPINC' ) )
	die();

get_template_part('/modules/starter/menus');
get_template_part('/vendor/parsedown/Parsedown');
get_template_part('/modules/starter/plot_data_builder');
get_template_part('/modules/starter/plot_shortcode_builder');
get_template_part('/modules/starter/plot_config');
get_template_part('/modules/starter/import_remote_content');

/**
 * Check for remove comments functions
 */
function knd_disable_comments() {
	$options = get_option( 'disable_comments_options', array() );

	$options['disabled_post_types'] = array( 'post', 'page', 'attachment' );
	$options['remove_everywhere'] = true;
	$options['permanent'] = false;
	$options['extra_post_types'] = false;
	$options['db_version'] = 6;

	update_option( 'disable_comments_options', $options );
}
add_action( 'knd_plotdata_build_theme_options', 'knd_disable_comments' );
