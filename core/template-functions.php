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
		$value = $default;

		$field_value = get_theme_mod( $field );

		if ( is_array( $field_value ) && $field_value ) {
			if ( isset( $field_value[ $type ] ) ) {
				$value = $field_value[ $type ];
			}
		}

		return $value;
	}
}
