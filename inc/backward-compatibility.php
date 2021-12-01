<?php
/**
 * Backward Compatibility
 *
 * @package Kandinsky
 */

/**
 * Update logo options
 * 
 * Deprecated, remove in version 3.0
 */
function knd_update_custom_logo(){
	if ( get_theme_support( 'custom-logo' ) ){
		if ( ! get_theme_mod( 'custom_logo' ) ) {
			set_theme_mod( 'custom_logo', knd_get_logo_id() );
			remove_theme_mod( 'header_logo_image' );
			remove_theme_mod( 'knd_custom_logo' );
		}
	}
}
add_action( 'init', 'knd_update_custom_logo' );
