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

	$post_types = array(
		'page',
		//'post
	);

	foreach ( $post_types as $post_type ) {

		register_post_meta(
			$post_type,
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

	}
}
add_action( 'init', 'knd_gutenberg_panels_register_meta' );
