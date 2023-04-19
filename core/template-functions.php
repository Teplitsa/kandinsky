<?php
/**
 * Template Functions
 *
 * @package Kandinsky
 */

if ( ! function_exists( 'knd_typography' ) ) {
	/**
	 * Output typography style.
	 *
	 * @param string $field   The field name of kirki.
	 * @param string $type    The type of typography.
	 * @param string $default The default value.
	 */
	function knd_typography( $field, $type, $default = '' ) {
		$value       = $default;
		$field_value = get_theme_mod( $field );
		if ( is_array( $field_value ) && $field_value ) {
			if ( isset( $field_value[ $type ] ) && $field_value[ $type ] ) {
				$value = $field_value[ $type ];
				if ( 'variant' === $type ) {
					// Get font-weight from variant.
					$value = filter_var( $field_value[ $type ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					$value = ( 'regular' === $field_value[ $type ] || 'italic' === $field_value[ $type ] ) ? 400 : absint( $field_value[ $type ] );
				}
			}
		}
		return $value;
	}
}

/**
 * Cyrillic fonts list
 */
function knd_cyrillic_fonts() {
	$fonts = array(
		'Alegreya',
		'Alegreya SC',
		'Alegreya Sans',
		'Alegreya Sans SC',
		'Alice',
		'Amatic SC',
		'Andika',
		'Anonymous Pro',
		'Arimo',
		'Arsenal',
		'Bad Script',
		'Balsamiq Sans',
		'Bellota',
		'Bellota Text',
		'Caveat',
		'Comfortaa',
		'Cormorant',
		'Cormorant Garamond',
		'Cormorant Infant',
		'Cormorant SC',
		'Cormorant Unicase',
		'Cousine',
		'Cuprum',
		'Didact Gothic',
		'EB Garamond',
		'El Messiri',
		'Exo 2',
		'Fira Code',
		'Fira Mono',
		'Fira Sans',
		'Fira Sans Condensed',
		'Fira Sans Extra Condensed',
		'Forum',
		'Gabriela',
		'IBM Plex Mono',
		'IBM Plex Sans',
		'IBM Plex Serif',
		'Inter',
		'Istok Web',
		'Jost',
		'Jura',
		'Kelly Slab',
		'Kosugi',
		'Kosugi Maru',
		'Kurale',
		'Ledger',
		'Literata',
		'Lobster',
		'Lora',
		'Manrope',
		'Marck Script',
		'Marmelad',
		'Merriweather',
		'Montserrat',
		'Montserrat Alternates',
		'Mulish',
		'Neucha',
		'Noto Sans',
		'Noto Serif',
		'Nunito',
		'Old Standard TT',
		'Open Sans',
		'Open Sans Condensed',
		'Oranienbaum',
		'Oswald',
		'PT Mono',
		'PT Sans',
		'PT Sans Caption',
		'PT Sans Narrow',
		'PT Serif',
		'PT Serif Caption',
		'Pacifico',
		'Pangolin',
		'Pattaya',
		'Philosopher',
		'Play',
		'Playfair Display',
		'Playfair Display SC',
		'Podkova',
		'Poiret One',
		'Prata',
		'Press Start 2P',
		'Prosto One',
		'Raleway',
		'Roboto',
		'Roboto Condensed',
		'Roboto Mono',
		'Roboto Slab',
		'Rubik',
		'Rubik Mono One',
		'Ruda',
		'Ruslan Display',
		'Russo One',
		'Sawarabi Gothic',
		'Scada',
		'Seymour One',
		'Source Code Pro',
		'Source Sans Pro',
		'Spectral',
		'Spectral SC',
		'Stalinist One',
		'Tenor Sans',
		'Tinos',
		'Ubuntu',
		'Ubuntu Condensed',
		'Ubuntu Mono',
		'Underdog',
		'Viaoda Libre',
		'Vollkorn',
		'Vollkorn SC',
		'Yanone Kaffeesatz',
		'Yeseva One',
	);
	return $fonts;
}

/**
 * Get header type
 */
function knd_get_header_type() {
	$header_type = get_theme_mod( 'header_type', '2' );
	return apply_filters( 'knd_get_header_type', $header_type );
}

/**
 * Custom Body class
 */
function knd_body_class( $classes ) {
	if( is_customize_preview() ) {
		$classes[] = 'is-customize-preview';
	}
	if ( is_singular() ) {
		$is_enabled = 'enabled';
		if ( ! knd_is_page_title() ) {
			$is_enabled = 'disabled';
		}
		$classes[] = 'knd-page-title-' . $is_enabled;
	}
	return $classes;
}
add_filter( 'body_class', 'knd_body_class' );

/**
 * Detect color scheme.
 *
 * @param mixed $color Color.
 * @param int   $level Detect level.
 */
function knd_detect_color_scheme( $color, $level = 190 ) {
	// Set alpha channel.
	$alpha = 1;

	$rgba = array( 255, 255, 255 );

	// Trim color.
	$color = trim( $color );

	// If HEX format.
	if ( isset( $color[0] ) && '#' === $color[0] ) {
		// Remove '#' from start.
		$color = str_replace( '#', '', trim( $color ) );

		if ( 3 === strlen( $color ) ) {
			$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
		}

		$rgba[0] = hexdec( substr( $color, 0, 2 ) );
		$rgba[1] = hexdec( substr( $color, 2, 2 ) );
		$rgba[2] = hexdec( substr( $color, 4, 2 ) );

	} elseif ( preg_match_all( '#\((([^()]+|(?R))*)\)#', $color, $color_reg ) ) {
		// Convert RGB or RGBA.
		$rgba = explode( ',', implode( ' ', $color_reg[1] ) );

		if ( array_key_exists( '3', $rgba ) ) {
			$alpha = (float) $rgba['3'];
		}
	}

	// Apply alpha channel.
	foreach ( $rgba as $key => $channel ) {
		$rgba[ $key ] = str_pad( $channel + ceil( ( 255 - $channel ) * ( 1 - $alpha ) ), 2, '0', STR_PAD_LEFT );
	}

	// Set default scheme.
	$scheme = 'default';

	// Get brightness.
	$brightness = ( ( $rgba[0] * 299 ) + ( $rgba[1] * 587 ) + ( $rgba[2] * 114 ) ) / 1000;

	// If color gray.
	if ( $brightness < $level ) {
		$scheme = 'inverse';
	}

	return $scheme;
}

/**
 * Create scheme css class.
 *
 * @param mixed $color Color.
 * @param int   $echo display or return.
 */
function knd_scheme_class( $color = '', $echo = true ) {
	$scheme = knd_detect_color_scheme( $color );
	if ( 'inverse' === $scheme ) {
		$scheme_class = 'knd-scheme-' . $scheme;
		if ( true === $echo ) {
			echo esc_attr( $scheme_class );
		} else {
			return $scheme_class;
		}
	}
}

/**
 * Get theme data.
 *
 * @param object $data Data.
 */
function knd_get_theme_data( $data ) {
	$theme = wp_get_theme( get_template() );

	return $theme->get( $data );
}

/**
 * Get theme version.
 */
function knd_get_theme_version() {
	$theme = wp_get_theme( get_template() );

	return knd_get_theme_data( 'Version' );
}

/**
 * All SVG images in one.
 */
function knd_include_svg(){
	global $hook_suffix;
	include_once get_template_directory() . '/assets/svg/svg.svg';
}
add_action( 'admin_footer-post.php', 'knd_include_svg' );
add_action( 'admin_footer-post-new.php', 'knd_include_svg' );
add_action( 'wp_body_open', 'knd_include_svg' );

/**
 * Get post by title
 */
function knd_get_post_by_title( $title = null, $post_type = 'page' ) {
	if ( ! $title ) {
		return;
	}
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'title'                  => $title,
			'posts_per_page'         => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts' => true,
			'post_status'         => 'inherit',
		)
	);

	if ( ! empty( $query->post ) ) {
		$post_by_title = $query->post;
	} else {
		$post_by_title = null;
	}

	return $post_by_title;
}