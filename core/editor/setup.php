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

	add_theme_support( 'custom-spacing' );

	add_theme_support('link-color');

	add_theme_support( 'custom-line-height' );

	// Editor Color Palette
	add_theme_support( 'editor-color-palette', knd_color_palette() );

}
add_action( 'after_setup_theme', 'knd_gutenberg_setup' );
