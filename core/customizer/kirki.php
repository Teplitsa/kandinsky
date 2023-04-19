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

add_action( 'init', function(){

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

	/**
	 * Get wp_blocks as array( 'block-name' => 'Block Name' )
	 */
	function knd_get_project_cats(){
		$choices = array(
			'' => esc_html__( 'Select a category', 'knd' ), // Выберите категорию
		);

		$terms = get_terms( array(
			'taxonomy' => 'project_cat',
			'hide_empty' => false,
		) );

		if( $terms && ! is_wp_error($terms) ){
			foreach( $terms as $term ){
				$choices[ $term->slug ] = $term->name;
			}
		}

		return $choices;
	}

	require_once get_theme_file_path( '/core/customizer/theme-mods/global.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/menu.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/header.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/pages.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/homepage.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/archive-settings.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/post-settings.php' );
	require_once get_theme_file_path( '/core/customizer/theme-mods/project-settings.php' );
	if ( defined( 'LEYKA_VERSION' ) ) {
		require_once get_theme_file_path( '/core/customizer/theme-mods/campaign-settings.php' );
	}
	require_once get_theme_file_path( '/core/customizer/theme-mods/footer.php' );
}, 20 );

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
 * Update sections, panels and controls
 *
 * @param object $wp_customize Customizer.
 */
function knd_customize_register( $wp_customize ) {
	/** Change custom logo control section, priority and description */
	$wp_customize->get_control( 'custom_logo' )->section = 'header';
	$wp_customize->get_control( 'custom_logo' )->priority = 5;
	$wp_customize->get_control( 'custom_logo' )->description = esc_html__( 'If only an image is used as a logo, then we recommend uploading an image with the dimensions of 315 x 66 px, or 66 x 66 px for use along with the text.', 'knd' );
	/** Change panel nav_menus priority */
	$wp_customize->get_panel( 'nav_menus' )->priority = 2;

}
add_action( 'customize_register', 'knd_customize_register', 11 );

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
 * Load translate from default wp files.
 */
function knd_remove_kirki_override_load_textdomain() {
	$tag      = 'override_load_textdomain';
	$priority = 5;
	$class    = 'Kirki\L10n';
	$function = 'override_load_textdomain';
	global $wp_filter;

	if (isset( $wp_filter[$tag] ) ) {
		foreach( $wp_filter[$tag]->callbacks[$priority] as $callback ) {
			if ( $callback['function'] 
				&& is_a( $callback['function'][0], $class ) 
				&& $callback['function'][1] == $function) {
				$callable = [ $callback['function'][0], $function ];
				$wp_filter[$tag]->remove_filter( $tag, $callable, $priority );
			break;
			}
		}
	}
}

knd_remove_kirki_override_load_textdomain();
