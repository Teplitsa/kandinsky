<?php
/**
 * Gutenberg Blocks
 *
 * @package Kandinsky
 */

function knd_register_blocks() {

	require get_template_directory() . '/core/editor/blocks/hero.php';
	require get_template_directory() . '/core/editor/blocks/news.php';
	require get_template_directory() . '/core/editor/blocks/projects.php';
	require get_template_directory() . '/core/editor/blocks/partners.php';
	require get_template_directory() . '/core/editor/blocks/people.php';
	require get_template_directory() . '/core/editor/blocks/cta.php';
	require get_template_directory() . '/core/editor/blocks/info.php';
	require get_template_directory() . '/core/editor/blocks/cover.php';
	require get_template_directory() . '/core/editor/blocks/recommend.php';

	if ( defined( 'LEYKA_VERSION' ) ) {
		require get_template_directory() . '/core/editor/blocks/leyka-campaign.php';
	}

	if ( defined( 'EM_VERSION' ) ) {
		require get_template_directory() . '/core/editor/blocks/events.php';
	}

}
add_action( 'init', 'knd_register_blocks' );
