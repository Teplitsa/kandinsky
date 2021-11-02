/* Scripts */
(function( $ ){
	"use strict";

	/** Has js **/
	$( 'html' ).removeClass( 'no-js' ).addClass( 'js' );

	/**
	 * Add class body on load
	 */
	$(window).on('load', function(){
		$('body').addClass('dom-loaded');
	});

	/**
	 * Accesibility Alert
	 */
	function kndUpdateScreenReaderAlert( e ){
		if ( $(e.currentTarget).data('label').length ) {
			let content = $(e.currentTarget).data('label');
			$('.knd-screen-reader-alert').html( ' ' );
			$('.knd-screen-reader-alert').html( content );
			console.log('este');
		}
	}

	/**
	 * Search Open
	 */
	$('.knd-search-toggle').on('click', function(e){
		e.preventDefault();
		$('.knd-search')
			.fadeIn()
			.find('.knd-search__input').focus();
		$('body').addClass('knd-search-open');
	});

	/**
	 * Search Close
	 */
	$('.knd-search-close').on('click', function(e){
		e.preventDefault();
		$('.knd-search')
			.fadeOut()
			.removeAttr('aria-modal')
			.attr('aria-hidden', 'true' );
		$('.knd-search-toggle').focus();
		$('body').removeClass('knd-search-open');
	});

	$( document ).on( 'keydown', function( e ) {
		// Events on keydown ESC
		if ( 27 === e.keyCode ) {
			// Close search
			if ( $('body').hasClass('knd-search-open') ) {
				$( '.knd-search-close' ).trigger( 'click' );
			}

			if ( $('.knd-header').hasClass('menu-open') ) {
				$( '.knd-offcanvas-toggle' ).focus();
			}
		}
	});

	/** Header states **/

	/** Off-Canvas **/
	$( '#trigger_menu, .knd-offcanvas-toggle' ).on( 'click', function( e ) {

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();

		// Close newsletter if any
		if ( $siteHeader.hasClass( 'newsletter-open' ) ) {
			$siteHeader.removeClass( 'newsletter-open' );
		}

		$siteHeader.addClass( 'menu-open' );
		
		setTimeout(function(){
			kndUpdateScreenReaderAlert(e);
		},400);

	});

	$(document).on('keydown', '.site-nav :tabbable:not([readonly])', function(e) {

		// Tab key only (code 9)
		if (e.keyCode != 9)
			return;

		if ( ! $('.knd-header').hasClass('menu-open') )
			return;

		// Get the loop element
		var loop = $(this).closest('.site-nav');

		// Get the first and last tabbable element
		var firstTabbable = loop.find(':tabbable:not([readonly])').first();
		var lastTabbable = loop.find(':tabbable:not([readonly])').last();

		// Leaving the first element with Tab : focus the last one
		if (firstTabbable.is(e.target) && e.shiftKey == true) {
		  e.preventDefault();
		  lastTabbable.focus();
		}

		// Leaving the last element with Tab : focus the first one
		if (lastTabbable.is(e.target) && e.shiftKey == false) {
			e.preventDefault();
			firstTabbable.focus();
		}
	});


	$(document).on('keydown', '.knd-search :tabbable:not([readonly])', function(e) {

		// Tab key only (code 9)
		if (e.keyCode != 9)
			return;

		if ( ! $('body').hasClass('knd-search-open') )
			return;

		// Get the loop element
		var loop = $(this).closest('.knd-search');

		// Get the first and last tabbable element
		var firstTabbable = loop.find(':tabbable:not([readonly])').first();
		var lastTabbable = loop.find(':tabbable:not([readonly])').last();

		// Leaving the first element with Tab : focus the last one
		if (firstTabbable.is(e.target) && e.shiftKey == true) {
		  e.preventDefault();
		  lastTabbable.focus();
		}

		// Leaving the last element with Tab : focus the first one
		if (lastTabbable.is(e.target) && e.shiftKey == false) {
			e.preventDefault();
			firstTabbable.focus();
		}
	});


	$( '#trigger_menu_close' ).on( 'click', function( e ) {

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();

		$siteHeader.removeClass( 'menu-open' ).find( '.site-nav');
		$('.knd-header__inner-desktop .knd-offcanvas-toggle').focus();

	});

	/** Submenu toggle  **/
	$( '.submenu-trigger' ).on( 'click', function( e ) {

		var li = $( this ).parent( '.menu-item-has-children' );
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
				$('.knd-offcanvas-toggle').focus();
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
	function kndResponsiveEmbeds() {
		var proportion, parentWidth;

		// Loop iframe elements.
		document.querySelectorAll( 'iframe' ).forEach( function( iframe ) {
			// Only continue if the iframe has a width & height defined.
			if ( iframe.width && iframe.height ) {
				// Calculate the proportion/ratio based on the width & height.
				proportion = parseFloat( iframe.width ) / parseFloat( iframe.height );
				// Get the parent element's width.
				parentWidth = parseFloat( window.getComputedStyle( iframe.parentElement, null ).width.replace( 'px', '' ) );
				// Set the max-width & height.
				iframe.style.maxWidth = '100%';
				iframe.style.maxHeight = Math.round( parentWidth / proportion ).toString() + 'px';
			}
		} );
	}

	// Run on initial load.
	kndResponsiveEmbeds();

	// Run on resize.
	window.onresize = kndResponsiveEmbeds;

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

	/**
	 * FancyBox
	 */
	$('.entry-content .wp-block-gallery, .entry-content .gallery').each( function( index ) {
		$( this ).find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').attr('data-fancybox', 'gallery-' + ( index + 1 ) );
	});

	$('.entry-content .wp-block-image').find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').attr('data-fancybox','');

	$( '.entry-content' ).find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').each( function( index ) {
		if ( $(this).parents('.wp-block-image').length == 0 && $(this).parents('.wp-block-gallery').length == 0 ) {
			$(this).attr('data-fancybox', '');
		}
	});

	/**
	 * Archive Events View Type
	 */
	$('.knd-events__layouts > a').on( 'click', function( e ) {
		e.preventDefault();
		$(this).siblings('a').removeClass('active');
		$(this).addClass('active');
		var thisType = $(this).data('type');
		$('.knd-events__main').addClass('knd-events__animate');
		setTimeout( function(){
			if ( 'grid' == thisType ) {
				$('.knd-events__main').addClass('knd-events__grid');
			} else {
				$('.knd-events__main').removeClass('knd-events__grid');
			}
			$('.knd-events__main').removeClass('knd-events__animate');
		}, 500 );
		document.cookie = 'kndArchiveType=' + thisType + ';path=/';
	});

	/**
	 * Sinble Events View Type
	 */
	$('.knd-event__cta-button').on( 'click', function( e ) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $('.knd-event__booking').offset().top - 50
		}, 1000);
	});

	/**
	 * Sinble Events View Type
	 */
	$('.knd-event__login-toggle a').on( 'click', function( e ) {
		e.preventDefault();
		$('.knd-event__login-form').slideToggle();
	});

	$(window).on('load resize', function () {
		jQuery( '.knd-block-carousel' ).flickity( 'resize' );
	});




		function focusMenuWithChildren() {
			// Get all the link elements within the primary menu.
			var links, i, len,
				menu = document.querySelector( '.knd-header-nav' );

			if ( ! menu ) {
				return false;
			}

			links = menu.getElementsByTagName( 'a' );

			// Each time a menu link is focused or blurred, toggle focus.
			for ( i = 0, len = links.length; i < len; i++ ) {
				links[i].addEventListener( 'focus', toggleFocus, true );
				links[i].addEventListener( 'blur', toggleFocus, true );
			}

			//Sets or removes the .focus class on an element.
			function toggleFocus() {
				var self = this;

				// Move up through the ancestors of the current link until we hit .primary-menu.
				while ( -1 === self.className.indexOf( 'knd-nav-menu' ) ) {
					// On li elements toggle the class .focus.
					if ( 'li' === self.tagName.toLowerCase() ) {
						if ( -1 !== self.className.indexOf( 'focus' ) ) {
							self.className = self.className.replace( ' focus', '' );
						} else {
							self.className += ' focus';
						}
					}
					self = self.parentElement;
				}
			}
		}


	focusMenuWithChildren();          // Primary Menu.


	function focusMenuWithChildren2() {
			// Get all the link elements within the primary menu.
			var links, i, len,
				menu = document.querySelector( '.nav-main-menu' );

			if ( ! menu ) {
				return false;
			}

			links = menu.getElementsByTagName( 'a' );

			// Each time a menu link is focused or blurred, toggle focus.
			for ( i = 0, len = links.length; i < len; i++ ) {
				links[i].addEventListener( 'focus', toggleFocus, true );
				links[i].addEventListener( 'blur', toggleFocus, true );
			}

			//Sets or removes the .focus class on an element.
			function toggleFocus() {
				var self = this;

				// Move up through the ancestors of the current link until we hit .primary-menu.
				while ( -1 === self.className.indexOf( 'main-menu' ) ) {
					// On li elements toggle the class .focus.
					if ( 'li' === self.tagName.toLowerCase() ) {
						if ( -1 !== self.className.indexOf( 'focus' ) ) {
							self.className = self.className.replace( ' focus', '' );
						} else {
							self.className += ' focus';
						}
					}
					self = self.parentElement;
				}
			}
		}


		focusMenuWithChildren2(); 

})( jQuery );
