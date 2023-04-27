<?php
/**
 * Customizer options
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Kirki Customizer Framework
 *
 * @package Kandinsky
 */

if ( class_exists( 'Kirki' ) ) {
	// Load new functions if active Kirki Customizer Framework.
	require_once get_theme_file_path( '/core/customizer/kirki.php' );
} else {
	// Load old deprecated functions if not installed Kirki Customizer Framework.
	require_once get_theme_file_path( '/core/customizer/customizer-default.php' );
}
