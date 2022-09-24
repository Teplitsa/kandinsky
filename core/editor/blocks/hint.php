<?php
/**
 * Gutenberg Block Hint
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Hint
 */
register_block_type( 'knd/hint', array(

	'render_callback' => 'knd_block_hint_render_callback',

	'attributes'      => array(
		'text'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Text.', 'knd' ),
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
function knd_block_hint_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-hint',
	);

	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	$style = '';

	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-hint-color:' . $attr['textColor'] . ';';
	}
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style .= '--knd-block-hint-background:' . $attr['backgroundColor'] . ';';
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
		<p class="has-background"><strong>ВАЖНО!</strong><br> На серых подсказках мы подготовили рекомендации по редактированию блоков. Эти блоки и блоки с видеоподсказками перед публикацией необходимо удалить!</p>
		<p class="has-background">Шапка</p>
		<p>Компоненты</p>
		<p>Логотип, название, описание, меню, социальные сети, кнопка/контакты (в зависимости от выбора шаблона меню и целевой задачи: действие/звонок).</p>
		<p>Рекомендации</p>
		<p>Если у вас нет логотипа, достаточно использовать название организации. Вы можете использовать шрифт по умолчанию или выбрать для названия необычный шрифт, редактировать его цвет и размер. Если ваш логотип уже содержит название, то используйте только логотип.</p>
		<p>В описании под названием отразите основную деятельность в трёх-четырёх словах.</p>
		<p>Постарайтесь уместиться в 5–6 пунктов меню. Если пунктов больше, используйте выпадающее меню (Боковая панель) или добавьте в меню подпункты.</p>
		' . wpautop( $content ) . '
	</div>';

	return $html;
}
