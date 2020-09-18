<?php
/**
 * Typography and Colors
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Add inline styles
 */
function knd_inline_style() {

	$main_color = knd_get_main_color();
	$dark_color = knd_color_luminance( $main_color, - 0.1 ); // @to_do calculate it

	$second_color      = knd_get_theme_color( 'knd_color_second' );
	$dark_second_color = knd_color_luminance( $second_color, - 0.1 );

	$third_color      = knd_get_theme_color( 'knd_color_third' );
	$dark_third_color = knd_color_luminance( $third_color, - 0.1 );

	$knd_text1_color = knd_get_theme_color( 'knd_text1_color' );

	$knd_text2_color      = knd_get_theme_color( 'knd_text2_color' );
	$knd_text2_color_dark = knd_color_luminance( $knd_text2_color, - 0.3 );

	$knd_text3_color      = knd_get_theme_color( 'knd_text3_color' );
	$knd_text3_color_dark = knd_color_luminance( $knd_text3_color, - 0.3 );

	$knd_page_bg_color      = knd_get_theme_mod( 'knd_page_bg_color', '#ffffff' );
	$knd_page_bg_color_dark = knd_color_luminance( $knd_page_bg_color, - 0.2 );

	$knd_page_text_color       = knd_get_theme_mod( 'knd_page_text_color', '#000000' );
	$knd_page_text_color_light = knd_color_luminance( $knd_page_text_color, 2 );
	$knd_color_base            = $knd_page_text_color;

	$knd_font_family_base     = '"SourceSansPro", Arial, sans-serif';
	$knd_font_family_headings = '"Exo2", Arial, sans-serif';
	$knd_font_family_logo     = $knd_font_family_headings;

	// Base typography.
	if ( get_theme_mod( 'font_base', true ) && class_exists( 'Kirki' ) ) {
		$knd_page_text_color  = knd_typography( 'font_base', 'color', '#000000' );
		$knd_font_family_base = '"' . knd_typography( 'font_base', 'font-family', 'Jost' ) . '"';
		$knd_font_weight_base = knd_typography( 'font_base', 'variant', '400' );
		$knd_font_size_base   = knd_typography( 'font_base', 'font-size', '16px' );
	} else {
		$knd_page_text_color  = '#000000';
		$knd_font_weight_base = '400';
		$knd_font_size_base   = '16px';
	}
	$knd_font_style_base = knd_typography( 'font_base', 'font-style', 'normal' );

	// Heading typography.
	if ( get_theme_mod( 'font_headings' ) && class_exists( 'Kirki' ) ) {
		$knd_headings_color       = knd_typography( 'font_headings', 'color', '#000000' );
		$knd_font_family_headings = '"' . knd_typography( 'font_headings', 'font-family', 'Exo 2' ) . '"';
		$knd_font_weight_headings = knd_typography( 'font_headings', 'variant', '800' );
		$knd_font_family_logo     = $knd_font_family_headings;
	} else {
		$knd_headings_color       = '#000000';
		$knd_font_weight_headings = 800;
	}
	$knd_font_style_headings = knd_typography( 'font_headings', 'font-style', 'normal' );

	// Logo Typography.
	$knd_font_weight_logo = 800;
	$knd_font_style_logo  = 'normal';
	if ( get_theme_mod( 'font_logo' ) && class_exists( 'Kirki' ) ) {
		if ( true !== get_theme_mod( 'font_logo_default' ) ) {
			$knd_font_family_logo = '"' . knd_typography( 'font_logo', 'font-family', 'Exo 2' ) . '"';
			$knd_font_weight_logo = knd_typography( 'font_logo', 'variant', '800' );
			$knd_font_style_logo  = knd_typography( 'font_logo', 'font-style', 'normal' );
			$knd_font_size_logo   = knd_typography( 'font_logo', 'font-size', '22px' );
		}
	}
	$knd_logo_color = get_theme_mod( 'font_logo_color', 'var(--knd-page-text-color)' );

	$custom_css = ':root {
	--knd-color-main:         ' . $main_color . ';
	--knd-color-main-dark:    ' . $dark_color . ';

	--knd-color-second:       ' . $second_color . ';
	--knd-color-second-dark:  ' . $dark_second_color . ';

	--knd-color-third:        ' . $third_color . ';
	--knd-color-third-dark:   ' . $dark_third_color . ';

	--knd-text1-color:        ' . $knd_text1_color . ';
	--knd-text2-color:        ' . $knd_text2_color . ';
	--knd-text2-color-dark:   ' . $knd_text2_color_dark . ';

	--knd-text3-color:        ' . $knd_text3_color . ';
	--knd-text3-color-dark:   ' . $knd_text3_color_dark . ';

	--knd-page-bg-color:        ' . $knd_page_bg_color . ';
	--knd-page-bg-color-dark:   ' . $knd_page_bg_color_dark . ';

	--knd-page-text-color:       ' . $knd_page_text_color . ';
	--knd-page-text-color-light:  ' . $knd_page_text_color_light . ';

	--knd-color-base:       ' . $knd_color_base . ';
	--knd-font-family-base: ' . $knd_font_family_base . ';
	--knd-font-weight-base: ' . $knd_font_weight_base . ';
	--knd-font-size-base:   ' . $knd_font_size_base . ';
	--knd-font-style-base:  ' . $knd_font_style_base . ';

	--knd-color-headings:       ' . $knd_headings_color . ';
	--knd-font-family-headings: ' . $knd_font_family_headings . ';
	--knd-font-weight-headings: ' . $knd_font_weight_headings . ';
	--knd-font-style-headings:  ' . $knd_font_style_headings . ';

	--knd-color-logo:       ' . $knd_logo_color . ';
	--knd-font-family-logo: ' . $knd_font_family_logo . ';
	--knd-font-weight-logo: ' . $knd_font_weight_logo . ';
	--knd-font-style-logo:  ' . $knd_font_style_logo . ';

}';

	$gradient_start = get_theme_mod( 'knd_hero_overlay_start', 'rgba(0,0,0,0)' );
	$gradient_end   = get_theme_mod( 'knd_hero_overlay_end', 'rgba(0,0,0,.8)' );

	$custom_css .= '
.hero-title,
.hero-text {
	color: ' . get_theme_mod( 'knd_hero_text_color', '#fff' ) . ';
}
.hero-section:after {
	background: -webkit-linear-gradient(top, ' . $gradient_start . ' 10%, ' . $gradient_end . ' 100%);
	background: linear-gradient(to bottom, ' . $gradient_start . ' 10%, ' . $gradient_end . ' 100%);
}
.knd-joinus-widget {
	--knd-color-second: ' . esc_attr( get_theme_mod( 'knd_cta_background', get_theme_mod( 'knd_color_second', '#ffc914' ) ) ) . ';
}

	';

	wp_add_inline_style( 'frl-design', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'knd_inline_style', 40 );
