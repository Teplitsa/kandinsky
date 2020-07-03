/**
 * Customizer Controls
 */
( function( $ ) {
	'use strict';

		/**
		 * Go to customize section, panel and control id
		 */
		$(document).on( 'click', '.knd-customize-focus', function( e ) {
			e.preventDefault();
			var thisToggle = $(this).data( 'toggle' );
			var thisFocus  = $(this).attr( 'data-focus' );
			
			if ( thisToggle === 'panel' ) {
				wp.customize.panel( thisFocus ).focus(); //.expand();
			}
			if ( thisToggle === 'section' ) {
				wp.customize.section( thisFocus ).focus();//.expand();
			}
			if ( thisToggle === 'control' ) {
				wp.customize.control( thisFocus ).focus();
			}
			
		});

} )( jQuery );