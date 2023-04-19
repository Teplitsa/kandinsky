<?php
/**
 * Gutenberg Block Call To Action
 *
 * @package Kandinsky
 */

/**
 * Register Block Type CTA
 */
register_block_type( 'knd/cta', array(

	'render_callback' => 'knd_block_cta_render_callback',

	'attributes'      => array(
		'align'           => array(
			'type'    => 'string',
			'default' => 'full',
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
			'default' => esc_html__( 'Заголовок блока. Отразите тут текущую ситуацию.', 'knd' ),
		),
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Здесь должен быть призыв к действию', 'knd' ),
		),
		'buttonText'  => array(
			'type'    => 'string',
			'default' => esc_html__( 'Действие', 'knd' ),
		),
		'buttonUrl'         => array(
			'type'    => 'string',
			'default' => '',
		),
		'buttonTarget'         => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'featuredImage' => array(
			'type'    => 'object',
			'default' => array(
				'url' => esc_url( get_theme_file_uri( 'assets/images/cta-image.png' ) ),
			),
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'titleColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'textColor' => array(
			'type'    => 'string',
			'default' => '',
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
	),
) );

/**
 * Render Block CTA
 *
 * @param array $attr Block Attributes.
 */
function knd_block_cta_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-cta',
	);

	// Block Class Name
	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	// Align
	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style .= '--knd-block-cta-background:' . $attr['backgroundColor'] . ';';
		$classes[] = 'has-background';
	}

	// Title Color
	if ( isset( $attr['titleColor'] ) && $attr['titleColor'] ) {
		$style .= '--knd-block-cta-title-color:' . $attr['titleColor'] . ';';
	}

	// Text Color
	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-cta-text-color:' . $attr['textColor'] . ';';
	}

	// Button Colors
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

	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = '<div class="knd-block-cta__title">' . esc_html( $attr['heading'] ) . '</div>';
	}

	$text = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$text = '<h2 class="knd-block-cta__text">' . nl2br( esc_html( $attr['text'] ) ) . '</h2>';
	}

	$figure = '';
	if ( isset( $attr['featuredImage'] ) && $attr['featuredImage'] ) {
		$featured_image = $attr['featuredImage'];
		if ( isset( $featured_image['id'] ) && $featured_image['id'] ) {
			$featured_image = wp_get_attachment_image( $featured_image['id'], 'large' );
			if ( $featured_image ) {
				$figure = '<div class="knd-block-cta__figure">' . $featured_image . '</div>';
			}
		} elseif ( isset( $featured_image['title'] ) && $featured_image['title'] ){
			$attachment = knd_get_post_by_title( $featured_image['title'], 'attachment' );
			if ( $attachment ) {
				$featured_image = wp_get_attachment_image( $attachment->ID, 'large' );
				$figure = '<div class="knd-block-cta__figure">' . $featured_image . '</div>';
			}
		} elseif ( isset( $featured_image['url'] ) && $featured_image['url'] ) {
			$figure = '<div class="knd-block-cta__figure"><img src="' . esc_url( $featured_image['url'] ) . '" alt=""></div>';
		}
	}

	$action = '';
	if ( isset( $attr['buttonText'] ) && $attr['buttonText'] ) {

		$button_attr = array(
			'class' => 'knd-button knd-button-lg',
			'role'  => 'button',
			'href'  => $attr['buttonUrl'],
		);

		if ( isset( $attr['buttonTarget'] ) && $attr['buttonTarget'] ) {
			$button_attr['target'] = '_blank';
		}

		$action .= '<div class="knd-block-cta__action">
				<a '. knd_tag_attr( $button_attr ) . '>' . $attr['buttonText'] . '</a>
		</div>';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">
		<div class="knd-container">
			<div class="knd-block-cta__inner">
				' . $figure . '
				<div class="knd-block-cta__content">
					' . $heading . '
					' . $text . '
					' . $action . '
				</div>
			</div>
		</div>
	</div>';

	return $html;
}
