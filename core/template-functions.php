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
	function knd_typography( $field, $type, $default ) {
		$value       = $default;
		$field_value = get_theme_mod( $field );
		if ( is_array( $field_value ) && $field_value ) {
			if ( isset( $field_value[ $type ] ) ) {
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
