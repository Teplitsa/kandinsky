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
add_action( 'init', 'knd_register_patterns' );




// https://stackoverflow.com/questions/48724545/add-static-page-to-reading-settings-for-custom-post-type/48804048

/**
 * Adds a custom field: "Projects page"; on the "Settings > Reading" page.
 */
/*add_action( 'admin_init', function () {
	$id = 'page_for_projects';
	// add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() )
	add_settings_field( $id, 'Projects page:', 'settings_field_page_for_projects', 'reading', 'default', array(
		'label_for' => 'field-' . $id, // A unique ID for the field. Optional.
		'class'     => 'row-' . $id,   // A unique class for the TR. Optional.
	) );
} );*/

/**
 * Renders the custom "Projects page" field.
 *
 * @param array $args
 */
/*function settings_field_page_for_projects( $args ) {
	$id = 'page_for_projects';
	wp_dropdown_pages( array(
		'name'              => $id,
		'show_option_none'  => '&mdash; Select &mdash;',
		'option_none_value' => '0',
		'selected'          => get_option( $id ),
	) );
}*/

/**
 * Adds page_for_projects to the white-listed options, which are automatically
 * updated by WordPress.
 *
 * @param array $options
 */
/*add_filter( 'allowed_options', function ( $options ) {
	$options['reading'][] = 'page_for_projects';

	return $options;
} );*/




/**
 * Filters the post states on the "Pages" edit page. Displays "Projects Page"
 * after the post/page title, if the current page is the Projects static page.
 *
 * @param array $states
 * @param WP_Post $post
 */
/*add_filter( 'display_post_states', function ( $states, $post ) {
	if ( intval( get_option( 'page_for_projects' ) ) === $post->ID ) {
		$states['page_for_projects'] = __( 'Projects Page' );
	}

	return $states;
}, 10, 2 );*/
