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

	wp.customize( 'knd_social', function( value ) {
		value.bind( function( to ) {
			var socials = JSON.parse(decodeURI(to));

			Object.entries(socials).forEach(([key, value]) => {
				if ( value ) {
					var thisRow   = $('#customize-control-knd_social .repeater-fields .repeater-row').eq(key);
					var thisLabel = thisRow.find('.repeater-field-label');
					var thisImage = thisRow.find('.repeater-field-image');
					if( value['network'] ) {
						thisLabel.css('display','none');
						thisImage.css('display','none');
					} else {
						thisLabel.css('display','block');
						thisImage.css('display','block');
					}
				}
			});

		} );
	} );

	wp.customize.bind( 'ready', function() {
		var socials = wp.customize.instance( 'knd_social' ).get();

		socials = JSON.parse(decodeURI(socials));

		Object.entries(socials).forEach(([key, value]) => {
			var thisRow   = $('#customize-control-knd_social .repeater-fields .repeater-row').eq(key);
			var thisLabel = thisRow.find('.repeater-field-label');
			var thisImage = thisRow.find('.repeater-field-image');
			if( value['network'] ) {
				thisLabel.css('display','none');
				thisImage.css('display','none');
			} else {
				thisLabel.css('display','block');
				thisImage.css('display','block');
			}
		});

		$('.repeater-field-network select[data-field="network"]').on('change', function(e){
			var thisText = $(this).find('option:selected').text();
			if ( this.value ) {
				$(this).parents('.repeater-row').find('[data-field="label"]').val(thisText).change();
			}
		});

	} );

} )( jQuery );
