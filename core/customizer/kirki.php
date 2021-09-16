<?php
/**
 * Kirki
 *
 * @package Kandinsky
 */

/**
 * WP Kses
 */
function knd_kses( $content ) {
	return $content;
}

/**
 * Telemetry implementation for Kirki
 */
add_filter( 'kirki_telemetry', '__return_false' );

/**
 * Kirki Config
 *
 * @param array $config is an array of Kirki configuration parameters.
 */
function csco_kirki_config( $config ) {

	// Disable Kirki preloader styles.
	$config['disable_loader'] = true;

	return $config;

}
add_filter( 'kirki/config', 'csco_kirki_config' );

/**
 * Add config
 */
Kirki::add_config( 'knd_theme_mod', array(
	'capability'     => 'edit_theme_options',
	'option_type'    => 'theme_mod',
	'disable_output' => true,
) );

/**
 * Theme Mods
 */
require_once get_theme_file_path( '/core/customizer/theme-mods/global.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/menu.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/header.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/pages.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/homepage.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/archive-settings.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/post-settings.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/project-settings.php' );
require_once get_theme_file_path( '/core/customizer/theme-mods/footer.php' );
//require_once get_theme_file_path( '/core/customizer/theme-mods/migrate.php' );

/**
 * Customizer Controls Scripts
 */
function knd_customize_controls_enqueue_scripts() {

	$current_theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'knd-customizer', get_theme_file_uri( '/assets/css/customizer.css' ), array(), $current_theme_version );
	wp_enqueue_script( 'knd-customizer', get_theme_file_uri( '/assets/js/customizer.js' ), array(), $current_theme_version, true );

}
add_action( 'customize_controls_enqueue_scripts', 'knd_customize_controls_enqueue_scripts' );

/**
 * Remove sections and panels
 *
 * @param object $wp_customize Customizer.
 */
function knd_customize_register( $wp_customize ) {
	$wp_customize->remove_section( 'sidebar-widgets-knd-projects-archive-sidebar' );
	$wp_customize->remove_section( 'sidebar-widgets-knd-news-archive-sidebar' );
	/* Trick for hide widgets panel */
	Kirki::add_panel(
		'widgets'
	);
}
add_action( 'customize_register', 'knd_customize_register' );

/**
 * Custom translate
 *
 * @param string $translation Translation string.
 * @param string $text Original string.
 * @param string $domain Textdomain.
 */
function knd_gettext( $translation, $text, $domain ) {
	if ( 'kirki' === $domain ) {
		if ( $text === 'Font Family' ) {
			$translation = esc_html__( 'Font Family', 'knd' );
		}
		if ( $text === 'Variant' ) {
			$translation = esc_html__( 'Variant', 'knd' );
		}
		if ( $text === 'Color' ) {
			$translation = esc_html__( 'Color', 'knd' );
		}
		if ( $text === 'Font Size' ) {
			$translation = esc_html__( 'Font Size', 'knd' );
		}
		if ( $text === 'No image selected' ) {
			$translation = esc_html__( 'No image selected', 'knd' );
		}
		if ( $text === 'Remove' ) {
			$translation = esc_html__( 'Remove', 'knd' );
		}
		if ( $text === 'Select image' ) {
			$translation = esc_html__( 'Select image', 'knd' );
		}
		if ( $text === 'No File Selected' ) {
			$translation = esc_html__( 'No File Selected', 'knd' );
		}
	}
	return $translation;
}
add_filter( 'gettext', 'knd_gettext', 10, 3 );

/**
 * Sanitize metrics code
 *
 * @param string $code Content.
 */
function knd_kses_metrics( $code = null ) {
	return wp_kses_post( esc_html( $code ) );
}

/**
 * Body open action
 */
function knd_body_open() {
	do_action( 'knd_body_open' );
}

/**
 * Add analitics on end head
 */
function knd_add_wp_head() {
	if ( get_theme_mod( 'analitics_head' ) ) {
		echo wp_specialchars_decode( get_theme_mod( 'analitics_head' ), ENT_QUOTES ) . "\n";
	}
}
add_action( 'wp_head', 'knd_add_wp_head', 999 );

/**
 * Add analitics on body open
 */
function knd_add_body_open() {
	if ( get_theme_mod( 'analitics_body_open' ) ) {
		echo wp_specialchars_decode( get_theme_mod( 'analitics_body_open' ), ENT_QUOTES ) . "\n";
	}
}
add_action( 'wp_body_open', 'knd_add_body_open' );

/**
 * Add analitics on end body
 */
function knd_add_wp_footer() {
	if ( get_theme_mod( 'analitics_body' ) ) {
		echo wp_specialchars_decode( get_theme_mod( 'analitics_body' ), ENT_QUOTES ) . "\n";
	}
}
add_action( 'wp_footer', 'knd_add_wp_footer', 999 );

/**
 * Get wp_blocks as array( 'block-name' => 'Block Name' )
 */
function knd_get_blocks_option(){
	$choices = array(
		'0' => esc_html__( 'None', 'knd' ),
	);

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'wp_block',
	) );

	foreach( $posts as $post ){
		$choices[ $post->post_name ] = $post->post_title;
	}
	return $choices;
}

/**
 * Get wp_get_nav_menus as array( 'menu-slug' => 'Menu Name' )
 */
function knd_get_menus_option(){
	$choices = array(
		'0' => esc_html__( 'None', 'knd' ),
	);

	$menus = wp_get_nav_menus();

	foreach( $menus as $menu ){
		$choices[ $menu->slug ] = $menu->name;
	}
	return $choices;
}
