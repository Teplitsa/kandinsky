<?php
/**
 * Gutenberg Meta
 *
 * @package Kandinsky
 */

/**
 * Register meta fields for gutenberg panels
 */
function knd_gutenberg_panels_register_meta() {

	/** Register meta field for page */
	register_post_meta(
		'page',
		'_knd_is_page_title',
		array(
			'show_in_rest'  => true,
			'type'          => 'boolean',
			'single'        => true,
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);

	/** Register meta field for post type org */
	register_post_meta(
		'org',
		'_knd_org_url',
		array(
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string',
			'sanitize_callback' => 'esc_url',
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);

}
add_action( 'init', 'knd_gutenberg_panels_register_meta' );
