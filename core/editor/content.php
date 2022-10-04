<?php
/**
 * Content
 *
 * @package Kandinsky
 */

/**
 * Return block content.
 */
function knd_get_block_content( $block_name ){
	$content = null;
	if ( 'info' === $block_name ) {
		$content = '';
	} elseif ( 'cta' === $block_name ) {
		$content = '';
	}

	return $content;
}
