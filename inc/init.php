<?php
/**
 * Kandinsky Init
 *
 * @package Kandinsky
 */

/**
 * Events.
 */
if ( defined( 'EM_VERSION' ) ) {
	require get_template_directory() . '/inc/events/events.php';
}

/**
 * Events.
 */
if ( defined( 'LEYKA_VERSION' ) ) {
	require get_template_directory() . '/inc/leyka.php';
}

