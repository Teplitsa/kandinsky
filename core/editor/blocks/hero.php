<?php
/**
 * Gutenberg Block Hero
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Hero
 */
register_block_type( 'knd/hero', array(

	'render_callback' => 'knd_block_hero_render_callback',

	'attributes'      => array(
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Заголовок проекта. Суть и основная идея.', 'knd' ),
		),
		'text'        => array(
			'type'    => 'string',
			'default' => esc_html__( 'Описание. Два-три коротких предложения, раскрывающие суть организации. Заголовок привлекает внимание, описание помогает понять как можно поучаствовать, кнопка реализует действие.', 'knd' ),
		),
		'button'        => array(
			'type'    => 'string',
			'default' => esc_html__( 'Текст кнопки', 'knd' ),
		),
		'buttonUrl'        => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonAdditional' => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonAdditionalUrl' => array(
			'type'    => 'string',
			'default' => '',
		),
		'align'           => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'headingLevel' => array(
			'type'    => 'string',
			'default' => 'h1',
		),
		'textColor' => array(
			'type'    => 'string',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '#f7f8f8',
		),
		'overlayColorStart' => array(
			'type'    => 'string',
			'default' => 'rgba(255,255,255,0.8)',
		),
		'overlayColorEnd' => array(
			'type'    => 'string',
			'default' => 'rgba(255,255,255,1)',
		),
		'backgroundImage' => array(
			'type'    => 'object',
		),
		'featuredImage' => array(
			'type'    => 'object',
			'default' => array(
				'url' => esc_url( get_theme_file_uri( 'assets/images/cta-image.png' ) ),
			),
		),
		'buttonBackground' => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonBackgroundHover' => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonColorHover' => array(
			'type'    => 'string',
			'default' => '',
		),
		'blockId'       => array(
			'type'    => 'string',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'anchor'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'minHeight' => array(
			'type'    => 'string',
		),
	),
) );

/**
 * Render Block Hero
 *
 * @param array $attr Block Attributes.
 */
function knd_block_hero_render_callback( $attr ) {

	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading_level = $attr['headingLevel'];
		$heading = '<' . $heading_level . ' class="knd-block-hero__title">' . $attr['heading'] . '</' . $heading_level . '>';
	}

	$text = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$text = '<div class="knd-block-hero__text">' . nl2br( $attr['text'] ) . '</div>';
	}

	$actions = '';
	if ( isset( $attr['button'] ) && $attr['button'] ) {
		$actions .= '<div class="knd-block-hero__actions">';
		$actions .= '<a href="' . $attr['buttonUrl'] . '" role="button" class="' . apply_filters( 'knd_block_hero_button_classes', 'knd-button' ) . '">' . $attr['button'] . '</a>';
		if ( isset( $attr['buttonAdditional'] ) && $attr['buttonAdditional'] ) {
			$actions .= '<a href="' . $attr['buttonAdditionalUrl'] . '" role="button" class="' . apply_filters( 'knd_block_hero_additional_button_classes', 'knd-button knd-button-outline' ) . '">' . $attr['buttonAdditional'] . '</a>';
		}
		$actions .= '</div>';
	}

	// Classes
	$classes = array(
		'block_class' => 'knd-block-hero',
	);

	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	$style            = '';
	$background_image = '';
	$cover_image      = '';

	if ( isset( $attr['backgroundImage'] ) ) {
		$background_image = $attr['backgroundImage'];
	};

	if ( isset( $background_image['id'] ) && $background_image['id'] ) {
		$background_image_url = wp_get_attachment_image_url( $background_image['id'], 'full' );

		//$style .= 'background-image: url(' . esc_url( $background_image_url ) . ');';
		$cover_image = wp_get_attachment_image( $background_image['id'], 'full', false, array('class' => 'knd-block-hero__image-background' ) );

	} elseif ( isset( $background_image['title'] ) && $background_image['title'] ){
		$background_attach = knd_get_post_by_title(  $background_image['title'], 'attachment' );
		if ( $background_attach ) {
			$background_image_url = wp_get_attachment_image_url( $background_attach->ID, 'full' );
			if ( $background_image ) {
				$style .= 'background-image: url(' . esc_url( $background_image_url ) . ');';
			}
		}
	} elseif ( isset( $background_image['url'] ) && $background_image['url'] ) {
		$style .= 'background-image: url(' . esc_url( $background_image['url'] ) . ');';
	}

	if ( isset( $attr['featuredImage'] ) ) {
		$featured_image = $attr['featuredImage'];
	};

	if ( isset( $featured_image['id'] ) && $featured_image['id'] ) {
		$featured_image = wp_get_attachment_image( $featured_image['id'], apply_filters( 'knd_hero_block_featured_image_size', 'large' ) );
		if ( $featured_image ) {
			$featured_image = '<div class="knd-block-hero__figure">' . $featured_image . '</div>';
		}
	} elseif ( isset( $featured_image['title'] ) && $featured_image['title'] ){
		$attachment = knd_get_post_by_title( $featured_image['title'], 'attachment' );
		if ( $attachment ) {
			$featured_image = wp_get_attachment_image($attachment->ID, 'large' );
			if ( $featured_image ) {
				$featured_image = '<div class="knd-block-hero__figure">' . $featured_image . '</div>';
			}
		}
	} elseif ( isset( $featured_image['url'] ) && $featured_image['url'] ) {
		$featured_image = '<div class="knd-block-hero__figure"><img src="' . esc_url( $featured_image['url'] ) . '" alt=""></div>';
	} else {
		$featured_image = '';
	}

	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-hero-color:' . $attr['textColor'] . ';';
		$style .= '--knd-color-headings:' . $attr['textColor'] . ';';
	}

	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style    .= '--knd-block-hero-background:' . $attr['backgroundColor'] . ';';
		$classes[] = 'has-background';
	}

	if ( isset( $attr['overlayColorStart'] ) && $attr['overlayColorStart'] ) {
		$style .= '--knd-block-hero-overlay-start:' . $attr['overlayColorStart'] . ';';
	}

	if ( isset( $attr['overlayColorEnd'] ) && $attr['overlayColorEnd'] ) {
		$style .= '--knd-block-hero-overlay-end:' . $attr['overlayColorEnd'] . ';';
	}

	if ( isset( $attr['buttonColor'] ) && $attr['buttonColor'] ) {
		$style .= '--knd-button-color:' . $attr['buttonColor'] . ';';
	}

	if ( isset( $attr['buttonBackground'] ) && $attr['buttonBackground'] ) {
		$style .= '--knd-button-background:' . $attr['buttonBackground'] . ';';
	}

	if ( isset( $attr['buttonColorHover'] ) && $attr['buttonColorHover'] ) {
		$style .= '--knd-button-color-hover:' . $attr['buttonColorHover'] . ';';
	}

	if ( isset( $attr['buttonBackgroundHover'] ) && $attr['buttonBackgroundHover'] ) {
		$style .= '--knd-button-background-hover:' . $attr['buttonBackgroundHover'] . ';';
	}

	// Min Height
	if ( isset( $attr['minHeight'] ) && $attr['minHeight'] ) {
		$style .= '--knd-block-hero-height:' . $attr['minHeight'] . ';';
	}

	if ( isset( $attr['blockId'] ) && $attr['blockId'] ) {
		$classes[] = 'knd-block-' . $attr['blockId'];
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">
		' . $cover_image . '
		<div class="knd-block-hero__inner">
			<div class="knd-block-hero__content">
				' . $heading . '
				' . apply_filters( 'knd_hero_block_text', $text ) . '
				' . $actions . '
			</div>
			' . $featured_image . '
		</div>
	</div>';

	return $html;
}
