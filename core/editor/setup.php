<?php
/**
 * Gutenberg Setup
 *
 * @package Kandinsky
 */

/**
 * Gutenberg Setup
 */
function knd_gutenberg_setup() {

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	//add_theme_support( 'wp-block-styles' );

	add_theme_support( 'custom-spacing' );

	//add_theme_support( 'custom-units', 'rem', 'em', 'px' );


	// Disable Custom Colors
	//add_theme_support( 'disable-custom-colors' );

	//add_theme_support('experimental-link-color');
	add_theme_support('link-color');

	add_theme_support( 'custom-line-height' );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', knd_color_palette() );

}
add_action( 'after_setup_theme', 'knd_gutenberg_setup' );


function knd_admin_menu_blocks() {
	$url_edit = 'edit.php?post_type=wp_block';

	if ( $url_edit ) {
		add_menu_page( esc_html__( 'Blocks', 'knd' ), esc_html__( 'Blocks', 'knd' ), 'edit_pages', $url_edit, null, 'dashicons-screenoptions', 20 );
	}

}
add_action( 'admin_menu', 'knd_admin_menu_blocks' );
