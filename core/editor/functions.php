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
	array(
			'name'  => esc_html__( 'White', 'knd' ),
			'slug'  => 'white',
			'color' => '#ffffff',
		),
		array(
			'name'  => esc_html__( 'Black', 'knd' ),
			'slug'  => 'black',
			'color' => '#000000',
		),
		array(
			'name'  => esc_html__( 'Cyan bluish gray', 'knd' ),
			'slug'  => 'cyan-bluish-gray',
			'color' => '#afb8c1',
		),
		array(
			'name'  => esc_html__( 'Pale Pink', 'knd' ),
			'slug'  => 'pale-pink',
			'color' => '#dd96a7',
		),
		array(
			'name'  => esc_html__( 'Vivid Red', 'knd' ),
			'slug'  => 'vivid-red',
			'color' => '#b1463d',
		),
		array(
			'name'  => esc_html__( 'Luminous vivid orange', 'knd' ),
			'slug'  => 'luminous-vivid-orange',
			'color' => '#df7740',
		),
		array(
			'name'  => esc_html__( 'Luminous vivid amber', 'knd' ),
			'slug'  => 'luminous-vivid-amber',
			'color' => '#ebbc58',
		),
		array(
			'name'  => esc_html__( 'Light green cyan', 'knd' ),
			'slug'  => 'light-green-cyan',
			'color' => '#a2d8b9',
		),
		array(
			'name'  => esc_html__( 'Vivid green cyan', 'knd' ),
			'slug'  => 'vivid-green-cyan',
			'color' => '#7bc990',
		),
		array(
			'name'  => esc_html__( 'Pale cyan blue', 'knd' ),
			'slug'  => 'pale-cyan-blue',
			'color' => '#a6cff4',
		),
		array(
			'name'  => esc_html__( 'Vivid cyan blue', 'knd' ),
			'slug'  => 'vivid-cyan-blue',
			'color' => '#5492d7',
		),
		array(
			'name'  => esc_html__( 'Vivid purple', 'knd' ),
			'slug'  => 'vivid-purple',
			'color' => '#885Fd1',
		),
	);
	return $colors;
}
