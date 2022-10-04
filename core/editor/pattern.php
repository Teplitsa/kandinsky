<?php
/**
 * Content
 *
 * @package Kandinsky
 */

/**
 * Register Block Patterns.
 */
function knd_register_patterns() {

	/**
	 * Register Block Pattern Category.
	 */
	if ( function_exists( 'register_block_pattern_category' ) ) {

		register_block_pattern_category(
			'kandinsky',
			array( 'label' => esc_html__( 'Kandinsky', 'knd' ) )
		);
	}

	/**
	 * Register Block Patterns.
	 */
	if ( function_exists( 'register_block_pattern' ) ) {

		// Call To Action.
		register_block_pattern(
			'kandinsky/call-to-action',
			array(
				'title'         => esc_html__( 'Call To Action', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Title, Subtitle, Description, Button, and Image.', 'knd' ),
				'content'       => knd_get_block_content( 'cta' ),
			)
		);

		// About project (info).
		register_block_pattern(
			'kandinsky/info',
			array(
				'title'         => esc_html__( 'About Project', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Subheading, heading and three columns.', 'knd' ),
				'content'       => knd_get_block_content( 'info' ),
			)
		);

	}

/**
 * Additional funcitons
 * unregister_block_pattern( 'kandinsky/call-to-action' );
 * unregister_block_pattern_category( 'hero' );
 */

}
//add_action( 'init', 'knd_register_patterns' );
