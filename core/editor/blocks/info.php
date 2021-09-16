<?php
/**
 * Gutenberg Block Info
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Info
 */
register_block_type( 'knd/info', array(

	'render_callback' => 'knd_block_info_render_callback',

	'attributes'      => array(
		'align'           => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( '112 volunteers are helping Line of Color at the moment', 'knd' ),
		),
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Join a team of volunteers and consultants in our projects', 'knd' ),
		),
		'heading1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Who we are?', 'knd' ),
		),
		'heading2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'What we do?', 'knd' ),
		),
		'heading3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Stop drinking?', 'knd' ),
		),
		'text1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'The charitable organization &#34;Line of Color&#34; helps to overcome alcohol addiction and return to a fulfilling life.', 'knd' ),
		),
		'text2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'We organize rehabilitation programs, inform and help those who are ready to give up their addiction and return their lives.', 'knd' ),
		),
		'text3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Fill out the anonymous form on the website, choose a convenient time for an individual consultation, or sign up for a support group.', 'knd' ),
		),
		'linkText1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Learn about our work', 'knd' ),
		),
		'linkText2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'View projects', 'knd' ),
		),
		'linkText3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Get help', 'knd' ),
		),
		'linkUrl1'         => array(
			'type'    => 'string',
			'default' => home_url( '/about/' ),
		),
		'linkUrl2'         => array(
			'type'    => 'string',
			'default' => home_url( '/projects/' ),
		),
		'linkUrl3'         => array(
			'type'    => 'string',
			'default' => home_url( '/gethelp/' ),
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'titleColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingsColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'textColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'linkColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'linkHoverColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'underlineColor' => array(
			'type'    => 'string',
			'default' => '',
		),
	),
) );

/**
 * Render Block News
 *
 * @param array $attr Block Attributes.
 */
function knd_block_info_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-info',
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
		$color = $attr['backgroundColor'];
		$style .= '--knd-block-info-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$color = $attr['headingColor'];
		$style .= '--knd-block-info-heading-color:' . $color . ';';
	}

	// Title Color
	if ( isset( $attr['titleColor'] ) && $attr['titleColor'] ) {
		$color = $attr['titleColor'];
		$style .= '--knd-block-info-title-color:' . $color . ';';
	}

	// Columns Headings Color
	if ( isset( $attr['headingsColor'] ) && $attr['headingsColor'] ) {
		$color = $attr['headingsColor'];
		$style .= '--knd-block-info-headings-color:' . $color . ';';
	}

	// Columns Text Color
	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$color = $attr['textColor'];
		$style .= '--knd-block-info-text-color:' . $color . ';';
	}

	// Columns Link Color
	if ( isset( $attr['linkColor'] ) && $attr['linkColor'] ) {
		$color = $attr['linkColor'];
		$style .= '--knd-block-info-link-color:' . $color . ';';
	}

	// Columns Link Color
	if ( isset( $attr['linkHoverColor'] ) && $attr['linkHoverColor'] ) {
		$color = $attr['linkHoverColor'];
		$style .= '--knd-block-info-link-hover-color:' . $color . ';';
	}

	// Columns Underline Color
	if ( isset( $attr['underlineColor'] ) && $attr['underlineColor'] ) {
		$color = $attr['underlineColor'];
		$style .= '--knd-block-info-underline-color:' . $color . ';';
	}

	$text = '';
	if ( isset( $attr['text'] ) && $attr['text'] ) {
		$text = '<h2 class="knd-block-info__text">' . $attr['text'] . '</h2>';
	}

	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = '<div class="knd-block-info__heading">
				<div class="knd-block-info__title">' . $attr['heading'] . '</div>
				' . $text . '
		</div>';
	}
	
	$columns = '';
	for ($i = 1; $i <= 3; $i++) {
		if ( isset( $attr['heading' . $i ] ) && $attr['heading' . $i] ) {
			$columns .= '<div class="knd-block-info__col">
				<div class="knd-block-info__content">
					<h3>' .  $attr['heading' . $i] . '</h3>
					' . wpautop( $attr['text' . $i ] ) . '
				</div>';
				if ( isset( $attr['linkText' . $i ] ) && $attr['linkText' . $i] ) {
					$columns .= '<div class="knd-block-info__link"><a href="' . $attr['linkUrl' . $i ] . '">' . $attr['linkText' . $i ] . '</a></div>';
				}
			$columns .= '</div>';
		}
	}
	
	if ( $columns ) {
		$columns = '<div class="knd-block-info__row">' . $columns . '</div>';
		$classes[] = 'has-columns';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '" style="' . $style . '">
		<div class="knd-container">
			' . $heading . '
			' . $columns . '
		</div>
	</div>';

	return $html;
}
