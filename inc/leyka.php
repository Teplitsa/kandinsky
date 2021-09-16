<?php
/**
 * Leyka
 *
 * @package Kandinsky
 */

/**
 * Add scripts to page if post content has block knd/campaign
 */
function knd_leyka_modern_template_displayed( $modern_template_displayed ) {

	if ( ! is_singular() ) {
		return false;
	}

	$post = get_post();

	if ( has_blocks( $post->post_content ) ) {
		$blocks = parse_blocks( $post->post_content );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return false;
		}

		foreach ( $blocks as $block ) {
			if ( $block['blockName'] === 'knd/campaign' ) {
				$modern_template_displayed = true;
			}
		}

	}

	return $modern_template_displayed;
}
add_filter( 'leyka_modern_template_displayed', 'knd_leyka_modern_template_displayed' );
