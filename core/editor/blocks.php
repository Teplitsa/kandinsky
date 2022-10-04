<?php
/**
 * Gutenberg Blocks
 *
 * @package Kandinsky
 */

function knd_register_blocks() {

	$blocks = array(
		'hero',
		'news',
		'projects',
		'partners',
		'people',
		'cta',
		'info',
		'cover',
		'recommend',
	);

	if ( defined( 'LEYKA_VERSION' ) ) {
		if ( version_compare( LEYKA_VERSION, '3.23', '<' ) ) {
			$blocks[] = 'leyka-campaign';
			$blocks[] = 'leyka-cards';
		}
	}

	if ( defined( 'EM_VERSION' ) ) {
		$blocks[] = 'events';
	}

	foreach ( $blocks as $block ) {
		$block_template_path = get_theme_file_path( 'core/editor/blocks/' . $block . '.php' );
		require_once apply_filters( 'knd_block_template_path', $block_template_path, $block, $blocks );
	}

}
add_action( 'init', 'knd_register_blocks' );
