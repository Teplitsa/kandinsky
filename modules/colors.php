<?php

/** Colors managemnt for theme **/
function knd_get_deault_main_color() {
	return '#f43724'; // may depends on test content set somehow
}

function knd_get_main_color() {
	return knd_get_theme_color( 'knd_main_color' );
}

function knd_get_theme_color( $color_name ) {
	$main_color = knd_get_theme_mod( $color_name );

	if ( empty( $main_color ) ) {
		$main_color = knd_get_deault_main_color();
	}

	return $main_color;
}

/**
 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
 * @param str $hex Colour as hexadecimal (with or without hash);
 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
 * @return str Lightened/Darkend colour as hexadecimal (with hash);
 * 
 * https://gist.github.com/stephenharris/5532899
 */
function knd_color_luminance( $hex, $percent ) {

	// validate hex string
	$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
	$new_hex = '#';

	if ( strlen( $hex ) < 6 ) {
		$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
	}

	if ( $percent > 0 ) {
		for ( $i = 0; $i <= 5; $i++ ) {
			if ( ! $hex[$i] ) {
				$hex[$i] = 1;
			}
		}
	}

	// convert to decimal and change luminosity
	for ( $i = 0; $i < 3; $i++ ) {
		$dec = hexdec( substr( $hex, $i * 2, 2 ) );
		$dec = round( min( max( 0, $dec + $dec * $percent ), 255 ) );
		$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
	}

	return $new_hex;
}
