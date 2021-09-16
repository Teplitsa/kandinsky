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

	$main_color        = get_theme_mod( 'knd_main_color', '#f43724' );
	$main_color_active = get_theme_mod( 'knd_main_color_active', '#db3120' );

	$knd_page_bg_color      = get_theme_mod( 'knd_page_bg_color', '#ffffff' );
	$knd_page_bg_color_dark = knd_color_luminance( $knd_page_bg_color, - 0.2 );

	$knd_font_family_base     = '"SourceSansPro", Arial, sans-serif';
	$knd_font_family_headings = '"Exo2", Arial, sans-serif';

	// Base typography.
	if ( get_theme_mod( 'font_base', true ) && class_exists( 'Kirki' ) ) {
		$knd_color_base       = knd_typography( 'font_base', 'color', '#000000' );
		$knd_font_family_base = knd_typography( 'font_base', 'font-family', 'Jost' );
		$knd_font_weight_base = knd_typography( 'font_base', 'variant', '400' );
		$knd_font_size_base   = knd_typography( 'font_base', 'font-size', '16px' );
	} else {
		$knd_color_base = '#000000';
		$knd_font_weight_base = '400';
		$knd_font_size_base   = '16px';
	}
	$knd_font_style_base = knd_typography( 'font_base', 'font-style', 'normal' );

	$knd_page_text_color_light = knd_color_luminance( $knd_color_base, 2 );

	// Heading typography.
	if ( get_theme_mod( 'font_headings' ) && class_exists( 'Kirki' ) ) {
		$knd_headings_color       = knd_typography( 'font_headings', 'color', '#000000' );
		$knd_font_family_headings = '"' . knd_typography( 'font_headings', 'font-family', 'Exo 2' ) . '"';
		$knd_font_weight_headings = knd_typography( 'font_headings', 'variant', '800' );
	} else {
		$knd_headings_color       = '#000000';
		$knd_font_weight_headings = 800;
	}
	$knd_font_style_headings = knd_typography( 'font_headings', 'font-style', 'normal' );

	$knd_font_size_menu = get_theme_mod( 'header_menu_size', '16px' );

	$custom_css = ':root {
	--knd-color-main:         ' . $main_color . ';
	--knd-color-main-active:    ' . $main_color_active . ';

	--knd-page-bg-color:        ' . $knd_page_bg_color . ';
	--knd-page-bg-color-dark:   ' . $knd_page_bg_color_dark . ';

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

	--knd-font-size-menu: ' . $knd_font_size_menu . ';
}';

	$custom_css = apply_filters( 'knd_inline_style', $custom_css );

	wp_add_inline_style( 'knd', $custom_css );
	if ( is_admin() ) {
		wp_add_inline_style( 'knd-blocks', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'knd_inline_style', 40 );


function knd_header_inline_style( $css ){

	// Logo Typography.
	$header_logo_font = '';
	if ( get_theme_mod( 'font_logo' ) && class_exists( 'Kirki' ) ) {
		if ( true !== get_theme_mod( 'font_logo_default' ) ) {
			$knd_font_family_logo = '"' . knd_typography( 'font_logo', 'font-family', 'Exo 2' ) . '"';
			$knd_font_weight_logo = knd_typography( 'font_logo', 'variant', '800' );
			$knd_font_style_logo  = knd_typography( 'font_logo', 'font-style', 'normal' );
			$knd_font_size_logo   = knd_typography( 'font_logo', 'font-size', '22px' );

	$header_logo_font = '
	--knd-font-family-logo: ' . $knd_font_family_logo . ';
	--knd-font-weight-logo: ' . $knd_font_weight_logo . ';
	--knd-font-style-logo:  ' . $knd_font_style_logo . ';
	--knd-font-size-logo:  ' . $knd_font_size_logo . ';
	';
		}
	}

$css .= '
:root {
	--knd-header-height: ' . get_theme_mod( 'header_height', '124px' ) . ';
	--knd-header-background: ' . get_theme_mod( 'header_background', '#ffffff' ) . ';
}
.knd-header-logo {
	--knd-color-logo: ' . get_theme_mod( 'header_logo_color', '#1e2c49' ) . ';
	--knd-color-logo-desc: ' . get_theme_mod( 'header_logo_desc_color', '#1e2c49' ) . ';
	' . $header_logo_font . '
}
.knd-header-nav {
	--knd-color-menu: ' . get_theme_mod( 'header_menu_color', '#585858' ) . ';
	--knd-color-menu-hover: ' . get_theme_mod( 'header_menu_color_hover', '#f43724' ) . ';
}

';
	return $css;
}
add_filter( 'knd_inline_style', 'knd_header_inline_style' );


function knd_footer_inline_style( $css ){

	$footer_logo_font = '';
	if ( get_theme_mod( 'font_footer_logo' ) && class_exists( 'Kirki' ) ) {
		if ( true !== get_theme_mod( 'font_footer_logo_default' ) ) {
			$knd_font_family_logo = '"' . knd_typography( 'font_footer_logo', 'font-family', 'Exo 2' ) . '"';
			$knd_font_weight_logo = knd_typography( 'font_footer_logo', 'variant', '800' );
			$knd_font_style_logo  = knd_typography( 'font_footer_logo', 'font-style', 'normal' );
			$knd_font_size_logo   = knd_typography( 'font_footer_logo', 'font-size', '22px' );

	$footer_logo_font = '
	--knd-font-family-logo: ' . $knd_font_family_logo . ';
	--knd-font-weight-logo: ' . $knd_font_weight_logo . ';
	--knd-font-style-logo:  ' . $knd_font_style_logo . ';
	--knd-font-size-logo:  ' . $knd_font_size_logo . ';
	';
		}
	}

$css .= '
:root {
	--knd-footer-background: ' . get_theme_mod( 'footer_background', '#eeeeee' ) . ';
	--knd-footer-color: ' . get_theme_mod( 'footer_color', '#000000' ) . ';
	--knd-footer-link-color: ' . get_theme_mod( 'footer_color_link', '#f43724' ) . ';
	--knd-footer-link-color-hover: ' . get_theme_mod( 'footer_color_link_hover', '#db3120' ) . ';
}
.knd-footer {
	--knd-social-color: ' . get_theme_mod( 'footer_color_social', '#000000' ) . ';
	--knd-social-color-hover: ' . get_theme_mod( 'footer_color_social_hover', '#333333' ) . ';
}
.knd-footer-logo {
	--knd-color-logo: ' . get_theme_mod( 'footer_logo_color', '#1e2c49' ) . ';
	--knd-color-logo-desc: ' . get_theme_mod( 'footer_logo_desc_color', '#1e2c49' ) . ';
	' . $footer_logo_font . '
}
';
	return $css;
}
add_filter( 'knd_inline_style', 'knd_footer_inline_style' );
