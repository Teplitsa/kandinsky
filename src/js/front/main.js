/* Scripts */
jQuery( document ).ready(function( $ ) {

	/** Has js **/
	$( 'html' ).removeClass( 'no-js' ).addClass( 'js' );

	/**
	 * Add class body on load
	 */
	$(window).on('load', function(){
		$('body').addClass('dom-loaded');
	});
	
	/**
	 * Search toggle 
	 */
	$('.knd-search-toggle').on('click', function(e){
		e.preventDefault();
		$('.knd-search').fadeIn();
	});
	
	$('.knd-search-close').on('click', function(e){
		e.preventDefault();
		$('.knd-search').fadeOut();
	});

	/**
	 * Keeping sub menu inside screen
	 */
	$(window).on('load resize', function(){
		var subMenus = $('.knd-header-nav .menu-item-has-children > .sub-menu');
		subMenus.each(function( index ) {
			var subMenuLeft = $(this).offset().left;
			if ( subMenuLeft + $(this).outerWidth() > $(window).width()) {
				$(this).addClass('sub-menu-left');
			}
		});
	});

	/** Has js **/
	$( 'html' ).removeClass( 'no-js' ).addClass( 'js' );

	/** Window width **/
	var windowWidth = $( '#top' ).width(),
		$adminbar = $( '#wpadminbar' ),
		$siteHeader = $( '#site_header, .knd-header' ),
		breakPointSmall = 480, // Small screens break point
		breakPointMedium = 767; // Medium screen break point

	/** Resize event **/
	$( window ).resize(function() {
		var winW = $( '#top' ).width();

		if ( winW < breakPointMedium && $siteHeader.hasClass( 'newsletter-open' ) ) {
			$siteHeader.removeClass( 'newsletter-open' );
		}

		kndSetupHeaderForSmallScreens();
	});

	/** Header states **/

	/** Drawer **/
	$( '#trigger_menu, .knd-offcanvas-toggle' ).on( 'click', function( e ) {

		// Close newsletter if any
		if ( $siteHeader.hasClass( 'newsletter-open' ) ) {
			$siteHeader.removeClass( 'newsletter-open' );
		}

		$siteHeader.addClass( 'menu-open' );

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();

	});

	$( '#trigger_menu_close' ).on( 'click', function( e ) {

		$siteHeader.removeClass( 'menu-open' );

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();
	});

	/** Submenu toggle  **/
	$( '.submenu-trigger' ).on( 'click', function( e ) {

		var li = $( this ).parents( '.menu-item-has-children' );
		if ( li.hasClass( 'open' ) ) {
			li.find( '.sub-menu' ).slideUp( 300, function() {
				li.removeClass( 'open' );
				$( this ).removeAttr( 'style' );
			});
		} else {

			li.find( '.sub-menu' ).slideDown( 300, function() {
				li.addClass( 'open' );
				$( this ).removeAttr( 'style' );
			});
		}
	});

	/** Close by key and click **/
	$( document ).on( 'click', function( e ) {

		var $eTarget = $( e.target );

		if ( $siteHeader.hasClass( 'menu-open' ) ) {
			if ( ! $eTarget.is( '#site_nav, #trigger_menu' ) &&
				 ! $eTarget.closest( '#site_nav, #trigger_menu' ).length ) {
				$siteHeader.removeClass( 'menu-open' );
			}
		} else if ( $siteHeader.hasClass( 'newsletter-open' ) ) {
			if ( ! $eTarget.is( '#newsletter_panel, #trigger_newsletter' ) &&
				 ! $eTarget.closest( '#newsletter_panel, #trigger_newsletter' ).length ) {
				$siteHeader.removeClass( 'newsletter-open' );
			}
		}

	}).on( 'keyup', function( e ) { //close search on by ESC
		if ( 27 === e.keyCode ) {
			//hide menu on newsletter
			if ( $siteHeader.hasClass( 'menu-open' ) ) {
				$siteHeader.removeClass( 'menu-open' );
			} else if ( $siteHeader.hasClass( 'newsletter-open' ) ) {
				$siteHeader.removeClass( 'newsletter-open' );
			}
		}
	}).on( 'keydown', function( e ) { //close search on by ESC
		if ( 27 === e.keyCode ) {
			//hide menu on newsletter
			if ( $siteHeader.hasClass( 'menu-open' ) ) {
				$siteHeader.removeClass( 'menu-open' );
			} else if ( $siteHeader.hasClass( 'newsletter-open' ) ) {
				$siteHeader.removeClass( 'newsletter-open' );
			}
		}
	});

	/** Sticky elements **/
	var position = $( window ).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($( 'body' ).hasClass( 'adminbar' )) ? 99 + 32 + 90 : 99 + 90,
		fixedTopPosition = ($( 'body' ).hasClass( 'adminbar' )) ? 99 + 32 + 90 : 99 + 90;

	$( window ).scroll(function() {
		var scroll = $( window ).scrollTop(),
			winW = $( '#top' ).width();

		//no scroll when menu is open
		if ( $siteHeader.hasClass( 'menu-open' ) ) {
			$( window ).scrollTop( position );
			return;
		}

		//scroll tolerance 3px and ignore out of boundaries scroll
		if ( (Math.abs( scroll - position ) < 3) || kndScrollOutOfBounds( scroll ) ) {
			return true;
		}
		
        //stick header
        if (scroll < position) { //upword
            $siteHeader.removeClass('invisible').addClass('fixed-header');
        }
        else if(scroll >= scrollTopLimit) {
            $siteHeader.removeClass('fixed-header').addClass('invisible');
        }
        else {
            $siteHeader.removeClass('fixed-header').removeClass('invisible');
        }
        
		//sticky sharing
		if ( winW >= breakPointMedium && $( '#knd_sharing' ).length > 0 ) {
			stickInParent( '#knd_sharing .social-likes-wrapper', '#knd_sharing', position, fixedTopPosition );
		}

		kndSetupHeaderForSmallScreens();

		position = scroll; //upd scroll position
		return true;
	});

	function kndSetupHeaderForSmallScreens() {
		var scroll = $( window ).scrollTop(),
			adminbarHeight = $adminbar.height();

		if ( 'absolute' === $adminbar.css( 'position' ) ) {
			if ( scroll > adminbarHeight ) {
				$siteHeader.css( 'top', '0px' );
			} else {
				$siteHeader.css( 'top', '' + (adminbarHeight - scroll) + 'px' );
			}
		} else {
			$siteHeader.css( 'top', '' );
		}
	}

	// Stick element on scroll
	function stickInParent( element, elementParent, elementPosition, elementFixedTopPosition ) {
		var scroll = $( window ).scrollTop(),
			$element = $( element ),
			$elementParent = $( elementParent ),
			topPos = $elementParent.offset().top,
			height = $elementParent.outerHeight();

		// Stick on bottom
		if ( scroll > ((height + topPos) - $element.outerHeight() - elementFixedTopPosition) ) {
			// Scroll down
			if ( scroll > elementPosition ) {
				$element.addClass( 'fixed-bottom' ).removeClass( 'fixed-top' );
			}
		} else if ( scroll > height + topPos - $element.outerHeight() - elementFixedTopPosition ) {
			// Unstick on bottom
			if ( scroll < elementPosition ) {
				$element.removeClass( 'fixed-bottom' ).addClass( 'fixed-top' );
			}
		} else if ( scroll > topPos - elementFixedTopPosition ) { //stick on top
			$element.removeClass( 'fixed-bottom' ).addClass( 'fixed-top' );
		} else {
			$element.removeClass( 'fixed-bottom' ).removeClass( 'fixed-top' ); //normal position
		}
	}

	// Determines if the scroll position is outside of document boundaries
	function kndScrollOutOfBounds( scroll ) {
		return scroll < 0 || scroll > $( document ).height() + $( window ).height();
	}

	/** Responsive media **/
	var resizeEmbedMedia = function() {

		$( 'iframe, embed, object' ).each(function() {

			var $iframe = $( this ),
				$parent = $iframe.parent(),
				doResize = false;

			if ( $parent.hasClass( 'embed-content' ) ) {
				doResize = true;
			} else {

				$parent = $iframe.parents( '.entry-content, .player' );
				if ( $parent.length ) {
					doResize = true;
				}
			}

			if ( doResize ) {
				var changeRatio = $parent.width() / $iframe.attr( 'width' );
				$iframe.width( changeRatio * $iframe.attr( 'width' ) );
				$iframe.height( changeRatio * $iframe.attr( 'height' ) );
			}
		});
	};

	// Initial page rendering
	resizeEmbedMedia();

	$( window ).resize(function() {
		resizeEmbedMedia();
	});

	/* Scroll */
	$( '.local-scroll, .inpage-menu a' ).on( 'click', function( e ) {
		e.preventDefault();

		var full_url = $( this ).attr( 'href' ),
			trgt = full_url.split( '#' )[ 1 ],
			target = $( '#' + trgt ).offset();

		if ( target.top ) {
			$( 'html, body' ).animate( { scrollTop: target.top - 50 }, 900 );
		}

	});

}); //jQuery
