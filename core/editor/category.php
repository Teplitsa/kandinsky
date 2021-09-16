<?php
/**
 * Gutenberg Category
 *
 * @package Kandinsky
 */

/**
 * Add block category
 *
 * @param array $block_categories Block categories.
 */
function knd_block_categories_all( $block_categories, $editor_context ) {

	if ( ! empty( $editor_context->post ) ) {
		array_push(
			$block_categories,
			array(
				'slug'  => 'kandinsky',
				'title' => esc_html__( 'Kandinsky', 'knd' ),
				'icon'  => null,
			)
		);
	}
	return $block_categories;
}
add_filter( 'block_categories_all', 'knd_block_categories_all', 10, 2 );
