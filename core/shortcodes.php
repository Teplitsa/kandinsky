<?php
/**
 * Shortcodes
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Support for leyka shortcode for import
 */
function knd_leyka_inline_campaign_shortcode( $atts, $content = null ){
	if ( defined( 'LEYKA_VERSION' ) ) {

		/** Wrapper to import leyka shortcodes correctly **/
		$atts = shortcode_atts( array( 'slug' => '' ), $atts );

		if ( empty( $atts['slug'] ) ) {
			return;
		}

		$camp = get_page_by_path( $atts['slug'], OBJECT, 'leyka_campaign' );

		if ( ! $camp ) {
			return;
		}

		return do_shortcode( '[leyka_inline_campaign id="' . esc_attr( $camp->ID ) . '" template="star"]' );
	}

	return;

}
add_shortcode( 'knd_leyka_inline_campaign', 'knd_leyka_inline_campaign_shortcode' );

function knd_test_for_revo_template( $revo_displayed ) {
	if ( ! is_singular() ) {
		return $revo_displayed;
	}

	if ( is_singular( 'leyka_campaign' ) ) {
		return $revo_displayed;
	}

	if ( get_post() && has_shortcode( get_post()->post_content, 'knd_leyka_inline_campaign' ) ) {
		$revo_displayed = true;
	}

	return $revo_displayed;
}
add_filter( 'leyka_revo_template_displayed', 'knd_test_for_revo_template' );

/**
 * Donations Shortcode
 * 
 * [knd_donations title="Donation Title" qty="2" exclude="" align="full" bg=""] // align: full, wide. bg: hex color
 * Deprecated, remove in version 3.0
 */
function knd_donations_shortcode( $atts ) {

	$atts = shortcode_atts( array(
		'title'   => esc_html__( 'Donations', 'knd' ),
		'qty'     => 4,
		'exclude' => null,
		'align'   => 'full',
		'bg'      => '',
	), $atts );
	
	$html = '';

	$args = array(
		'title'   => $atts['title'],
		'num'     => $atts['qty'],
		'exclude' => $atts['exclude'],
	);

	$html = '';

	// Classes
	$classes = 'knd-block-donations';

	if ( 'full' === $atts['align'] || 'wide' === $atts['align'] ) {
		$classes .= ' align' . $atts['align'];
	}
	
	$style = '';
	
	if ( $atts['bg'] ) {
		$style .= 'style="background-color:' . sanitize_hex_color( $atts['bg'] ) . ';"';
		$classes .= ' has-background';
	}
	
	ob_start();
	echo '<div class="' . esc_attr( $classes ) . '" ' . wp_kses_post( $style ) . '>';
	the_widget( 'KND_Donations_Widget', $args );
	echo '</div>';

	$html = ob_get_clean();

	return $html;
}
add_shortcode( 'knd_donations', 'knd_donations_shortcode' );
