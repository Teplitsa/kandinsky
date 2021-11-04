<?php
/**
 * Gutenberg Block Recommend
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Recommend
 */
register_block_type( 'knd/recommend', array(

	'render_callback' => 'knd_block_recommend_render_callback',

	'attributes'      => array(
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Your mission should be to summarize everything you do. In a short phrase, put the whole point of your organization and your projects.', 'knd' ),
		),
		'textColor' => array(
			'type'    => 'string',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'anchor'       => array(
			'type'    => 'string',
			'default' => '',
		),
	),
) );

/**
 * Render Block Recommend
 *
 * @param array $attr Block Attributes.
 */
function knd_block_recommend_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-recommend',
	);

	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	$style = '';

	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-recommend-color:' . $attr['textColor'] . ';';
	}
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style .= '--knd-block-recommend-background:' . $attr['backgroundColor'] . ';';
	}

	// Content
	$content = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$content = $attr['text'];
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	// Style attribute
	$style_attr = '';
	if ( $style ) {
		$style_attr = ' style="' . $style . '"';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . $style_attr . '>
		' . nl2br( $content, false ) . '
	</div>';

	return $html;
}
