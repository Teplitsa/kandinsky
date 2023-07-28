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
		// register_block_pattern(
		// 	'kandinsky/call-to-action',
		// 	array(
		// 		'title'         => esc_html__( 'Call To Action', 'knd' ),
		// 		'categories'    => array( 'kandinsky' ),
		// 		'viewportWidth' => 1200,
		// 		'description'   => esc_html__( 'Title, Subtitle, Description, Button, and Image.', 'knd' ),
		// 		'content'       => knd_get_block_content( 'cta' ),
		// 	)
		// );

		// // About project (info).
		// register_block_pattern(
		// 	'kandinsky/info',
		// 	array(
		// 		'title'         => esc_html__( 'About Project', 'knd' ),
		// 		'categories'    => array( 'kandinsky' ),
		// 		'viewportWidth' => 1200,
		// 		'description'   => esc_html__( 'Subheading, heading and three columns.', 'knd' ),
		// 		'content'       => knd_get_block_content( 'info' ),
		// 	)
		// );

		// Media
		register_block_pattern(
			'kandinsky/media',
			array(
				'title'         => esc_html__( 'Главный материал (Вёрстка)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Главный материал (Вёрстка)', 'knd' ),
				'content'       => knd_get_block_content( 'media' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-2',
			array(
				'title'         => esc_html__( 'Два важных материала', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Два важных материала', 'knd' ),
				'content'       => knd_get_block_content( 'media-2' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-3',
			array(
				'title'         => esc_html__( 'Главный материал (Холод)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Главный материал (Холод)', 'knd' ),
				'content'       => knd_get_block_content( 'media-3' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-4',
			array(
				'title'         => esc_html__( 'Четыре истории (Черта)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Четыре истории (Черта)', 'knd' ),
				'content'       => knd_get_block_content( 'media-4' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-5',
			array(
				'title'         => esc_html__( 'Новостной блок (Холод)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Новостной блок (Холод)', 'knd' ),
				'content'       => knd_get_block_content( 'media-5' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-6',
			array(
				'title'         => esc_html__( 'Новостной блок (Вёрстка)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Новостной блок (Вёрстка)', 'knd' ),
				'content'       => knd_get_block_content( 'media-6' ),
			)
		);

		register_block_pattern(
			'kandinsky/media-7',
			array(
				'title'         => esc_html__( 'Четыре материала с подложками (Черта)', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'description'   => esc_html__( 'Четыре материала с подложками (Черта)', 'knd' ),
				'content'       => knd_get_block_content( 'media-7' ),
			)
		);

		register_block_pattern(
			'kandinsky/card-default',
			array(
				'title'         => esc_html__( 'Card Default', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'content'       => knd_get_block_content( 'card-default' ),
			)
		);

		register_block_pattern(
			'kandinsky/card-horizontal',
			array(
				'title'         => esc_html__( 'Card Horizontal', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'content'       => knd_get_block_content( 'card-horizontal' ),
			)
		);

		register_block_pattern(
			'kandinsky/card-vertical',
			array(
				'title'         => esc_html__( 'Card Horizontal', 'knd' ),
				'categories'    => array( 'kandinsky' ),
				'viewportWidth' => 1200,
				'content'       => knd_get_block_content( 'card-vertical' ),
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
