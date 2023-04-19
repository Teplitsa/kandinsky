<?php
/**
 * Gutenberg Block Cover
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Cover
 */
register_block_type( 'knd/cover', array(

	'render_callback' => 'knd_block_cover_render_callback',

	'attributes'      => array(
		'align'           => array(
			'type'    => 'string',
			'default' => 'wide',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'anchor'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'We believe', 'knd' ),
		),
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'People are created to love and be loved; People need support and help; Your life and your story are important; Better days and a bright life are close; Hope and help are real.', 'knd' ),
		),
		'recommend'    => array(
			'type'    => 'string',
			'default' => esc_html__( 'Recommendations', 'knd' ),
		),
		'backgroundImage' => array(
			'type'    => 'object',
			'default' => array(
				'url' => esc_url( get_theme_file_uri( 'assets/images/hero.jpg' ) ),
			),
		),
		'textColor' => array(
			'type'    => 'string',
		),
		'contentColor' => array(
			'type'    => 'string',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '#dadada',
		),
		'recommendColor' => array(
			'type'    => 'string',
		),
		'recommendBgColor' => array(
			'type'    => 'string',
		),
		'minHeight' => array(
			'type'    => 'string',
		),
	),
) );

/**
 * Render Block Cover
 *
 * @param array $attr Block Attributes.
 */
function knd_block_cover_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-cover',
	);

	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	$style = '';
	// Get Image
	if ( isset( $attr['backgroundImage'] ) && $attr['backgroundImage'] ) {
		$background_image = $attr['backgroundImage'];
		if ( isset( $background_image['id'] ) && $background_image['id'] ) {
			$background_image_url = wp_get_attachment_image_url( $background_image['id'], 'full' );
			$style .= 'background-image: url(' . esc_url( $background_image_url ) . ');';
		} elseif ( isset( $background_image['title'] ) && $background_image['title'] ){
			$attachment = knd_get_post_by_title( $background_image['title'], 'attachment' );
			if ( $attachment ) {
				$background_image_url = wp_get_attachment_image_url( $attachment->ID, 'large' );
				$style .= 'background-image: url(' . esc_url( $background_image_url ) . ');';
			}
		} elseif ( isset( $background_image['url'] ) && $background_image['url'] ) {
			$style .= 'background-image: url(' . esc_url( $background_image['url'] ) . ');';
		}
		$classes[] = 'has-background-image';
	}

	// Background Color
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style .= '--knd-block-cover-background:' . $attr['backgroundColor'] . ';';
		$classes[] = 'has-background';
	}

	// Content Background
	if ( isset( $attr['contentColor'] ) && $attr['contentColor'] ) {
		$style .= '--knd-block-cover-content-background:' . $attr['contentColor'] . ';';
		$classes[] = 'has-content-background';
	}

	// Text Color
	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-cover-color:' . $attr['textColor'] . ';';
	}

	// Min Height
	if ( isset( $attr['minHeight'] ) && $attr['minHeight'] ) {
		$style .= '--knd-block-cover-height:' . $attr['minHeight'] . ';';
	}

	// Recommendation Color
	if ( isset( $attr['recommendColor'] ) && $attr['recommendColor'] ) {
		$style .= '--knd-block-recommend-color:' . $attr['recommendColor'] . ';';
	}

	// Recommendation Background Color
	if ( isset( $attr['recommendBgColor'] ) && $attr['recommendBgColor'] ) {
		$style .= '--knd-block-recommend-background:' . $attr['recommendBgColor'] . ';';
	}

	// Get Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = '<h2 class="knd-block-cover-title">' . $attr['heading'] . '</h2>';
	}

	// Content
	$content = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$content = '<div class="knd-block-cover-content">' . wpautop( $attr['text'] ) . '</div>';
	}

	// Recommendation
	$recommend = '';
	if ( isset( $attr['recommend'] ) && $attr['recommend'] ) {
		$recommend = '<div class="knd-block-recommend">' . nl2br( $attr['recommend'], true ) . '</div>';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">
		<div class="knd-block-cover-inner">
			' . $heading . '
			' . $content . '
			' . $recommend . '
		</div>
	</div>';

	return $html;
}
