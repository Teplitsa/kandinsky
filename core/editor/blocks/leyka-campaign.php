<?php
/**
 * Gutenberg Block Leyka Campaign
 *
 * Remove in version 2.2 (transformed to block leyka/form)
 * 
 * @package Kandinsky
 */

/**
 * Register Block Type Leyka Campaign
 */
register_block_type( 'knd/campaign', array(

	'render_callback' => 'knd_block_campaign_render_callback',

	'attributes'      => array(
		'align'           => array(
			'type'    => 'string',
			'default' => '',
		),
		'campaign'         => array(
			'type'    => 'string',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'preview'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'colorMain' => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorMainInactive' => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorMainThird' => array(
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
 * Render Block Campaign
 *
 * @param array $attr Block Attributes.
 */
function knd_block_campaign_render_callback( $attr ) {

	// Docs https://leyka.org/docs/shortcodes-v-3-6/
	// Classes
	$classes = array(
		'block_class' => 'knd-block-campaign',
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

	// Background Color
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$style .= 'background-color:' . $attr['backgroundColor'] . ';';
		$classes[] = 'has-background';
	}

	// Main Color
	if ( isset( $attr['colorMain'] ) && $attr['colorMain'] ) {
		$style .= '--leyka-color-main:' . $attr['colorMain'] . ';';
		$classes[] = 'has-leyka-color-main';
	}

	// Main Color Active
	if ( isset( $attr['colorMainInactive'] ) && $attr['colorMainInactive'] ) {
		$style .= '--leyka-color-main-inactive:' . $attr['colorMainInactive'] . ';';
		$classes[] = 'has-leyka-color-main-inactive';
	}
	// Main Color Active
	if ( isset( $attr['colorMainThird'] ) && $attr['colorMainThird'] ) {
		$style .= '--leyka-color-main-third:' . $attr['colorMainThird'] . ';';
		$classes[] = 'has-leyka-color-main-third';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	/**
	 * Using shortcode [leyka_inline_campaign id="311"]
	 */
	$html = '';
	if ( isset( $attr['campaign'] ) && $attr['campaign'] ) {
		$html = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">';
			$campaign = $attr['campaign'];
			if ( ! is_numeric( $campaign ) ) {

				$campaign_page = get_page_by_path( $campaign, OBJECT, 'leyka_campaign' );

				if ( $campaign_page ) {
					$campaign = $campaign_page->ID;
				}
			}
			$html .= do_shortcode( '[leyka_inline_campaign id="' . $campaign . '" template="star"]' );
		$html .= '</div>';
	}
	
	if ( true === $attr['preview'] ) {
		$html = '<img src="' . esc_url( get_theme_file_uri( 'assets/images/campaign.jpg' ) ) . '" alt="" style="display:block;height:450px;margin: 0 auto;">';
	}

	return $html;
}