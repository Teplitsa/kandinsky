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
		'anchor'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Название организации/проекта или заголовок блока: «О нас», «О Проекте»', 'knd' ),
		),
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Выразите тут цель, миссию или девиз организации. «Своей целью мы видим…», «Мы помогаем…»', 'knd' ),
		),
		'heading1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Who are we?', 'knd' ),
		),
		'heading2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'What we do?', 'knd' ),
		),
		'heading3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'For whom / How to help?', 'knd' ),
		),
		'text1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Кратко опишите основное направление деятельности организации/проекта/инициативы. На что нацелена деятельность.', 'knd' ),
		),
		'text2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Обозначьте здесь практические решения. Чем вы полезны и какие действия предпринимаются для решения проблемы.', 'knd' ),
		),
		'text3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Добавьте сюда информацию о том, как получить помощь тем, кто в ней нуждается или расскажите как помочь проекту.', 'knd' ),
		),
		'linkText1'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Узнайте о нашей работе', 'knd' ),
		),
		'linkText2'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Посмотреть проекты', 'knd' ),
		),
		'linkText3'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Вам нужна помощь/Как помочь', 'knd' ),
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

	//var_dump($attr['heading2']);

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
		$text = '<h2 class="knd-block-info__text">' . apply_filters( 'knd_block_info_text', $attr['text'] ) . '</h2>';
	}

	$title = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$title = '<div class="knd-block-info__title">' . $attr['heading'] . '</div>';
	}

	$heading = '';
	if ( $text || $title ) {
		$heading = '<div class="knd-block-info__heading">
				' . $title . '
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

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">
		<div class="knd-container">
			' . $heading . '
			' . $columns . '
		</div>
	</div>';

	return $html;
}
