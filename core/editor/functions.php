<?php
/**
 * Gutenberg Functions
 *
 * @package Kandinsky
 */

if ( ! function_exists( 'knd_block_class' ) ) {
	/**
	 * Return block classes
	 */
	function knd_block_class( $class = '', $block = null ) {
		
		$block_class = array(
			'class' => 'knd-block',
		);
		
		$classes = (array) $class;
		
		if ( ! empty( $class ) ) {
			$class = (array) $class;
			$block_class = array_merge( $block_class, $class );
		}
		
		$block_class = array_unique( array_map( 'esc_attr', $block_class ) );
		
		$block_class = implode( ' ', $block_class );

		return apply_filters( 'knd_block_class', $block_class, $block );
	}
}

if ( ! function_exists( 'knd_is_page_title' ) ) {
	/**
	 * Check Is Page Title
	 */
	function knd_is_page_title() {

		$is_page_title = true;

		if ( is_singular() && get_post_meta( get_the_ID(), '_knd_is_page_title', true ) ) {
			$is_page_title = false;
		}

		return apply_filters( 'knd_is_page_title', $is_page_title );

	}
}

function knd_color_palette() {

	$colors = array(
		'white' => array(
			'name'  => esc_html__( 'White', 'knd' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
		'black' => array(
			'name'  => esc_html__( 'Black', 'knd' ),
			'slug'  => 'black',
			'color' => '#000000',
		),
		'grey' => array(
			'name'  => esc_html__( 'Grey', 'knd' ),
			'slug'  => 'grey',
			'color' => '#eeeeee',
		),
		'light-blue' => array(
			'name'  => esc_html( 'Light Blue', 'knd' ),
			'slug'  => 'light-blue',
			'color' => '#edf6fe',
		),
		'main' => array(
			'name'  => esc_html( 'Main', 'knd' ),
			'slug'  => 'main',
			'color' => get_theme_mod( 'knd_main_color', '#f43724' ),
		),
		'base' => array(
			'name' => esc_html( 'Base', 'knd' ),
			'slug' => 'base',
			'color' => ( '#000000' != knd_typography( 'font_base', 'color' ) ? knd_typography( 'font_base', 'color' ) : '#081d47' ),
		),

	);

	return apply_filters( 'knd_color_palette', $colors );
}
