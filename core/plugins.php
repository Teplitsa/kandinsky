<?php
/**
 * The function sets the default options to plugins.
 *
 * @param string $plugin Plugin name.
 */
function knd_plugin_set_options( $plugin ) {
	if ( 'wp-seo' === $plugin ) {
		// Get display options.
		$display_options = get_option( 'wpseo_titles' );
		// Set position value.
		$display_options['breadcrumbs-enable']      = true;
		$display_options['breadcrumbs-sep']         = '<span class="knd-separator"></span>';
		$display_options['post_types-post-maintax'] = 'category';
		$display_options['breadcrumbs-home']        = esc_html_x( 'Home', 'Yoast SEO', 'knd' );
		// Update options.
		update_option( 'wpseo_titles', $display_options );
	}
}

/**
 * Hook into activated_plugin action.
 *
 * @param string $plugin Plugin path to main plugin file with plugin data.
 */
function knd_activated_plugin( $plugin ) {
	// Check if WPSEO constant is defined, use it to get WPSEO path anc compare to activated plugin.
	if ( 'wordpress-seo/wp-seo.php' === $plugin ) {
		knd_plugin_set_options( 'wp-seo' );
	}
}
add_action( 'activated_plugin', 'knd_activated_plugin' );

/**
 * Hook into after_switch_theme action.
 */
function knd_activated_theme() {
	knd_plugin_set_options( 'wp-seo' );
}
add_action( 'after_switch_theme', 'knd_activated_theme' );
