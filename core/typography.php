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

	$second_color = knd_get_theme_color( 'knd_color_second' );
	$dark_second_color = knd_color_luminance( $second_color, - 0.1 );

	$third_color = knd_get_theme_color( 'knd_color_third' );
	$dark_third_color = knd_color_luminance( $third_color, - 0.1 );

	$knd_text1_color = knd_get_theme_color( 'knd_text1_color' );

	$knd_text2_color = knd_get_theme_color( 'knd_text2_color' );
	$knd_text2_color_dark = knd_color_luminance( $knd_text2_color, - 0.3 );

	$knd_text3_color = knd_get_theme_color( 'knd_text3_color' );
	$knd_text3_color_dark = knd_color_luminance( $knd_text3_color, - 0.3 );

	$knd_page_bg_color         = knd_get_theme_mod( 'knd_page_bg_color', '#ffffff' );
	// knd_get_theme_color( 'knd_page_bg_color' );
	$knd_page_bg_color_dark    = knd_color_luminance( $knd_page_bg_color, - 0.2 );

	$knd_page_text_color       = knd_get_theme_mod( 'knd_page_text_color', '#000000' );
	//knd_get_theme_color( 'knd_page_text_color' );
	$knd_page_text_color_light = knd_color_luminance( $knd_page_text_color, 2 );



	$knd_font_family_base     = '"SourceSansPro", Arial, sans-serif';
	$knd_font_family_headings = '"Exo2", Arial, sans-serif';

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

	--knd-color-base: #585858;
	--knd-font-family-base: ' . $knd_font_family_base . ';
	--knd-font-weight-base: regular;
	--knd-font-size-base: 20px;
	
	--knd-color-headings: #000000;
	--knd-font-family-headings: ' . $knd_font_family_headings . ';
	--knd-font-weight-headings: regular;
	
	--knd-color-logo: #81d742;
	--knd-font-family-logo: var(--knd-font-family-headings);
	--knd-font-weight-logo: 900;
}';

	wp_add_inline_style( 'frl-design', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'knd_inline_style', 40 );



