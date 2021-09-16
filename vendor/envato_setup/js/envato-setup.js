var stopLoadingAnimation = null;

var EnvatoWizard = (function( $ ) {

	var t,
		callbacks = {
			installPlugins: function( btn ) {
				var plugins = new PluginManager();
				plugins.init( btn );
			},
			installContent: function( btn ) {
				var content = new ContentManager();
				content.init( btn );
			},
			kndDownloadPlotStep: function( btn ) {
				var tstDownloadStepManager = new KndDownloadPlotStepManager();
				tstDownloadStepManager.init( btn );
			}
		};

	function windowLoaded() {

		$( '.wizard-error-support-text a' ).on( 'click', function() {
			// Follow the support link manually
			window.location = $( this ).attr( 'href' );
		} );

		// Init button clicks:
		$( '.button-next' ).on( 'click', function( e ) {

			stopLoadingAnimation = dtbakerLoadingButton( this );
			
			if ( !stopLoadingAnimation  ) {
				return false;
			}
			
			if (
				$( this ).data( 'callback' ) &&
				typeof callbacks[ $( this ).data( 'callback' ) ] !== 'undefined'
			) {

				callbacks[ $( this ).data( 'callback' ) ]( this );
				return false;

			} else {

				loadingContent();
				return true;

			}
		});
		$( '.button-upload' ).on( 'click', function( e ) {

			e.preventDefault();
			renderMediaUploader();

		});
		/*$( '.theme-presets a' ).on( 'click', function( e ) {

			e.preventDefault();

			$( this ).parents( 'ul' ).first().find( '.current' ).removeClass( 'current' );
			$( this ).parents( 'li' ).first().addClass( 'current' );

			$( '#new_scenario_id' ).val( $( this ).data( 'scenario-id' ) );

			return false;

		});*/
	}

	function loadingContent() {
		$( '.envato-setup-content' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
	}

	function PluginManager() {

		var complete,
			itemsCompleted = 0,
			currentItem = '',
			$currentNode,
			currentItemHash = '';

		function ajaxCallback( response ) {
			if ( 'object' === typeof response && 'undefined' !== typeof response.message ) {

				$currentNode.find( 'span' ).text( response.message );
				if ( typeof response.url !== 'undefined' ) {
					// We have an ajax url action to perform

					if ( response.hash === currentItemHash ) {
						$currentNode.find( 'span' ).text( 'failed' );
						findNext();
					} else {
						currentItemHash = response.hash;
						jQuery.post( response.url, response, function() {
							processCurrent();
							$currentNode.find( 'span' ).
								text( response.message + envatoSetupParams.verify_text );
						} ).fail( ajaxCallback );
					}

				} else if ( typeof response.done !== 'undefined' ) {
					// Finished processing this plugin, move onto next
					findNext();
				} else {
					// Error processing this plugin
					findNext();
				}

			} else {
				// Error - try again with next plugin
				$currentNode.find( 'span' ).text( 'ajax error' );
				findNext();
			}
		}

		function processCurrent() {
			if ( currentItem ) {
				// Query our ajax handler to get the ajax to send to TGM
				// if we don't get a reply we can assume everything worked and continue onto the
				// next one.
				jQuery.post( envatoSetupParams.ajaxurl, {
					action: 'knd_wizard_setup_plugins',
					wpnonce: envatoSetupParams.wpnonce,
					slug: currentItem
				}, ajaxCallback ).fail( ajaxCallback );
			}
		}

		function findNext() {
			var doNext = false,
				$li = $( '.envato-wizard-plugins li,.envato-wizard-plugins-recommended li:has(.plugin-accepted:checked)');
			if ( $currentNode ) {
				if ( ! $currentNode.data( 'done_item' ) ) {
					itemsCompleted++;
					$currentNode.data( 'done_item', 1 );
				}
				$currentNode.find( '.spinner' ).css( 'visibility', 'hidden' );
			}
			$li.each(function() {
				if ( '' === currentItem || doNext ) {
					currentItem = $( this ).data( 'slug' );
					$currentNode = $( this );
					processCurrent();
					doNext = false;
				} else if ( $( this ).data( 'slug' ) === currentItem ) {
					doNext = true;
				}
			});
			if ( itemsCompleted >= $li.length ) {
				complete();
			}
		}

		return {
			init: function( btn ) {
				$( '.envato-wizard-plugins' ).addClass( 'installing' );
				complete = function() {
					loadingContent();
					window.location.href = btn.href;
				};
				findNext();
			}
		};
	}

	function ContentManager() {

		var complete,
			itemsCompleted = 0,
			currentItem = '',
			$currentNode,
			currentItemHash = '';

		function ajaxCallback( response ) {
			if ( 'object' === typeof response && 'undefined' !== typeof response.message ) {

				$currentNode.find( 'span' ).text( response.message );
				if ( typeof response.url !== 'undefined' ) {
					if ( response.hash === currentItemHash ) {

						$currentNode.find( 'span' ).text( 'failed' );
						findNext();

					} else {

						currentItemHash = response.hash;
						jQuery.post( response.url, response, ajaxCallback ).fail( ajaxCallback );

					}
				} else if ( typeof response.done !== 'undefined' ) {
					findNext();
				} else {
					// Error processing
					findNext();
				}

			} else {

				$currentNode.find( 'span' ).text( 'ajax error' );
				findNext();

			}
		}

		function processCurrent() {
			if ( currentItem ) {

				if ( $currentNode.find( 'input:checkbox' ).is( ':checked' ) ) {
					jQuery.post( envatoSetupParams.ajaxurl, {
						action: 'knd_wizard_setup_content',
						wpnonce: envatoSetupParams.wpnonce,
						content: currentItem
					}, ajaxCallback ).fail( ajaxCallback );
				} else {

					$currentNode.find( 'span' ).text( envatoSetupParams.text_processing );
					setTimeout( findNext, 300 );

				}

			}
		}

		function findNext() {
			var doNext = false,
				$items = $( 'tr.envato_default_content' );

			if ( $currentNode ) {
				if ( ! $currentNode.data( 'done_item' ) ) {
					itemsCompleted ++;
					$currentNode.data( 'done_item', 1 );
				}
				$currentNode.find( '.spinner' ).css( 'visibility', 'hidden' );
			}

			$items.each(function() {
				if ( '' === currentItem || doNext ) {
					currentItem = $( this ).data( 'content' );
					$currentNode = $( this );
					processCurrent();
					doNext = false;
				} else if ( $( this ).data( 'content' ) === currentItem ) {
					doNext = true;
				}
			});
			if ( itemsCompleted >= $items.length ) {
				complete();
			}
		}

		return {
			init: function( btn ) {
				$( '.envato-setup-pages' ).addClass( 'installing' ).find( 'input' ).
					prop( 'disabled', true );
				complete = function() {
					loadingContent();
					window.location.href = btn.href;
				};
				findNext();
			}
		};
	}

	/**
	 * Callback function for the 'click' event of the 'Set Footer Image'
	 * anchor in its meta box.
	 *
	 * Displays the media uploader for selecting an image.
	 *
	 * @since 0.1.0
	 */
	function renderMediaUploader() {
		'use strict';

		var fileFrame, attachment;

		if ( undefined !== fileFrame ) {
			fileFrame.open();
			return;
		}

		fileFrame = wp.media.frames.file_frame = wp.media({
			title: envatoSetupParams.upload_logo_text,
			button: {
				text: envatoSetupParams.select_logo_text,
			},
			multiple: false // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback
		fileFrame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = fileFrame.state().get( 'selection' ).first().toJSON();

			jQuery( '.site-logo-img' ).attr( 'src', attachment.url );
			jQuery( '#new_logo_id' ).val( attachment.id );
			// Do something with attachment.id and/or attachment.url here
		});
		// Now display the actual fileFrame
		fileFrame.open();

	}

	function dtbakerLoadingButton( btn ) {

		var $button = jQuery( btn ),
			existingWidth = $button.outerWidth(),
			loadingText = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄',
			completed = false,
			_modifier = $button.is( 'input' ) || $button.is( 'button' ) ? 'val' : 'text',
			animIndex = [ 0, 1, 2 ];
			
		var existingText = $button.is( 'input' ) || $button.is( 'button' ) ? $button.val() : $button.text();

		if ( 'yes' === $button.data( 'done-loading' ) ) {
			return false;
		}

		$button.css( 'width', existingWidth ).addClass( 'dtbaker_loading_button_current' );

		$button[ _modifier ]( loadingText );
		$button.data( 'done-loading', 'yes' );

		// Animate the text indent
		function moo() {
			var currentText = '';
			if ( completed ) {
				return;
			}
			// increase each index up to the loading length
			for ( var i = 0; i < animIndex.length; i ++ ) {
				animIndex[ i ] = animIndex[ i ] + 1;
				if ( animIndex[ i ] >= loadingText.length ) {
					animIndex[ i ] = 0;
				}
				currentText += loadingText.charAt( animIndex[ i ] );
			}
			$button[ _modifier ]( currentText );
			setTimeout( function() { moo(); }, 60 );
		}

		moo();

		return {
			done: function(unsetDone=false) {
				completed = true;
				$button[ _modifier ]( existingText );
				$button.removeClass( 'dtbaker_loading_button_current' );
				$button.attr( 'disabled', false );
				if(unsetDone) {
					$button.data( 'done-loading', '' );
				}
			}
		};

	}

	return {
		init: function() {
			t = this;
			$( windowLoaded );
		},
		callback: function( func ) {
			console.log( func );
			console.log( this );
		}
	};

})( jQuery );

EnvatoWizard.init();

var KndDownloadPlotStepManager = function() {
	
	this.init = function(btn) {
		var $ = jQuery;
		var self = this;
		
		$(btn).parent().find('.knd-download-plot-skip').hide();
		
		self.tries = 0;
		this.doStep();
	}
	
	this.doStep = function(step=0){
		var $ = jQuery;
		var self = this;
		
		var $error = $('#knd-download-plot-error');
		
		var $stepExplanations = $('#knd-download-status-explain');
		$stepExplanations.show();
		
		$.post(envatoSetupParams.ajaxurl, {
			
			action: 'knd_wizard_download_plot_step',
			new_scenario_id: $('#new_scenario_id').val(),
			save_step: '...',
			knd_download_step: step,
			_wpnonce: $('input#_wpnonce').val(),
			_wp_http_referer: $('input[name=_wp_http_referer]').val(),

		}, null, 'json')
		.done(function(json){
			
			if(json.status == 'ok') {
				
				self.tries = 0;
				
				if(json.status_explain) {
					$stepExplanations.text(json.status_explain);
				}
				
				if(json.knd_download_step >= 5) {
					$('input#_wpnonce').val(json.nonce);
					
					if(stopLoadingAnimation) {
						stopLoadingAnimation.done(true);
					}
					
					var $submitButton = $('#knd-install-scenario');
					$submitButton.data('callback', '');
					$submitButton.click();
				}
				else {
					self.doStep(step + 1);
				}
				
			}
			else {
				console.log('error');
				
				if(json.status == 'error') {
					
					if(json.no_scenario_id) {
						$error.find('.error-text').html(json.error);
						$error.find('.wizard-error-support-text').hide();
						$error.find('.envato-setup-actions').hide();
						
						if(stopLoadingAnimation) {
							stopLoadingAnimation.done(true);
						}
						
						$error.parent().find('.button-large').show();
					}
					else {
						$error.find('.wizard-error-support-text').show();
						$error.find('.envato-setup-actions').show();
						$error.find('.wizard-error-support-text').html(json.error);
					}
					
					$error.show();
					
				}
				else {
					if((step == 2 || step == 1 || step == 0) && self.tries < 2) {
						self.tries += 1;
						setTimeout(function(){
							self.doStep(step);
						}, 10 * 1000);
					}
					else {
						$error.show();
					}
				}
			}
			
		})
		.fail(function(){
			
			console.log('fail');
			
			if((step == 2 || step == 1 || step == 0) && self.tries < 2) {
				self.tries += 1;
				setTimeout(function(){
					self.doStep(step);
				}, 10 * 1000);
			}
			else {
				$error.find('.wizard-error-support-text').show();
				$error.find('.envato-setup-actions').show();
				$error.find('.wizard-error-support-text').html('');
				$error.show();
			}
			
		});
		
	}
	
}
