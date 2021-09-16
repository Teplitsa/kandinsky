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
			'default' => esc_html__( 'We help people to fight alcohol addiction', 'knd' ),
		),
		'text'        => array(
			'type'    => 'string',
			'default' => esc_html__( 'There are 877 people in our region who suffer from alcohol addiction. Your support will help organize a rehabilitation program for them.', 'knd' ),
		),
		'button'        => array(
			'type'    => 'string',
			'default' => esc_html__( 'Help Now', 'knd' ),
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
		'textColor' => array(
			'type'    => 'string',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '#e6e6e6',
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
			'default' => array(
				'url' => esc_url( get_theme_file_uri( 'assets/images/hero.jpg' ) ),
			),
		),
		'featuredImage' => array(
			'type'    => 'object',
			'default' => array(
				'url' => esc_url( get_theme_file_uri( 'assets/images/hero-featured.png' ) ),
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
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'blockId'       => array(
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
		$heading = '<h1 class="knd-block-hero__title">' . $attr['heading'] . '</h1>';
	}

	$text = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$text = '<div class="knd-block-hero__text">' . $attr['text'] . '</div>';
	}

	$actions = '';
	if ( isset( $attr['button'] ) && $attr['button'] ) {
		$actions .= '<div class="knd-block-hero__actions">';
		$actions .= '<a href="' . $attr['buttonUrl'] . '" class="knd-button">' . $attr['button'] . '</a>';
		if ( isset( $attr['buttonAdditional'] ) && $attr['buttonAdditional'] ) {
			$actions .= '<a href="' . $attr['buttonAdditionalUrl'] . '" class="knd-button knd-button-outline">' . $attr['buttonAdditional'] . '</a>';
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

	$style = '';

	$background_image = '';

	if ( isset( $attr['backgroundImage'] ) ) {
		$background_image = $attr['backgroundImage'];
	};

	if ( isset( $background_image['id'] ) && $background_image['id'] ) {
		$background_image_url = wp_get_attachment_image_url( $background_image['id'], 'full' );

		$style .= 'background-image: url(' . esc_url( $background_image_url ) . ');';

	} elseif ( isset( $background_image['title'] ) && $background_image['title'] ){
		$background_attach = get_page_by_title(  $background_image['title'],  OBJECT, 'attachment' );
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
		$featured_image = wp_get_attachment_image( $featured_image['id'], 'large' );
		if ( $featured_image ) {
			$featured_image = '<div class="knd-block-hero__figure">' . $featured_image . '</div>';
		}
	} elseif ( isset( $featured_image['title'] ) && $featured_image['title'] ){
		$attachment = get_page_by_title(  $featured_image['title'],  OBJECT, 'attachment' );
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

	if ( isset( $attr['blockId'] ) && $attr['blockId'] ) {
		$classes[] = 'knd-block-' . $attr['blockId'];
	}

	$html = '<div class="' . knd_block_class( $classes ) . '" style="' . $style . '">
		<div class="knd-block-hero__inner">
			<div class="knd-block-hero__content">
				' . $heading . '
				' . $text . '
				' . $actions . '
			</div>
			' . $featured_image . '
		</div>
	</div>';

	return $html;
}
