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

	/** Window width **/
	var windowWidth = $( '#top' ).width(),
		$adminbar = $( '#wpadminbar' ),
		$siteHeader = $( '#site_header, .knd-header' ),
		breakPointSmall = 480, // Small screens break point
		breakPointMedium = 767; // Medium screen break point

	/**
	 * Get Scrollbar width
	 */
	knd.getScrollBarWidth = function() {
		const scrollbarWidth = window.innerWidth - document.body.clientWidth;
		document.documentElement.style.setProperty('--scroll-bar-width', scrollbarWidth + 'px');
	}

	/**
	 * Open Off-Canvas
	 */
	knd.openOffcanvas = function( e ){

		$('.knd-header').addClass('menu-open');

		setTimeout( function(){
			kndUpdateScreenReaderAlert(knd.i18n.a11y.offCanvasIsOpen);
			setTimeout( function(){
				$('.knd-offcanvas-close').focus();
			},500);
		},400);

	};

	/**
	 * Close Off-Canvas
	 */
	knd.closeOffcanvas = function( e ){

		if ( $('.knd-header').hasClass( 'menu-open' ) ) {
			$('.knd-header').removeClass( 'menu-open' );

			$('.main-menu')
				.find('.menu-item-has-children.open').removeClass('open')
				.find('.submenu-trigger').attr('aria-expanded', 'false').attr('aria-label', knd.i18n.a11y.expand)
				.next('.sub-menu').slideUp( 300 );

			var focusButton = true;

			if ( $(e.target).parents('.main-menu').length ) {
				focusButton = false;
			}

			setTimeout( function(){
				kndUpdateScreenReaderAlert(knd.i18n.a11y.offCanvasIsClosed);
				if ( focusButton ) {
					setTimeout( function(){
						$('.knd-header__inner-desktop .knd-offcanvas-toggle').focus();
					},500);
				}
			},400);
		}

	};

	knd.openSearch= function( e ){
		$('.knd-search').fadeIn().find('.knd-search__input').focus();
		$('body').addClass('knd-search-open');
	};

	knd.closeSearch= function( e ){
		if ( $('body').hasClass('knd-search-open') ) {
			$('.knd-search')
				.fadeOut()
				.removeAttr('aria-modal')
				.attr('aria-hidden', 'true' );
			$('.knd-search-toggle').focus();
			$('body').removeClass('knd-search-open');
		}
	};

	/**
	 * Accesibility Alert
	 */
	function kndUpdateScreenReaderAlert( message ){
		$('.knd-screen-reader-alert').html( ' ' ).html( message );
	}

	/**
	 * Search Open
	 */
	$('.knd-search-toggle').on('click', function(e){
		e.preventDefault();
		knd.openSearch(e);
	});

	/**
	 * Search Close
	 */
	$('.knd-search-close').on('click', function(e){
		e.preventDefault();
		knd.closeSearch(e);
	});

	/** Off-Canvas **/
	$('.knd-offcanvas-toggle').on( 'click', function(e) {
		e.preventDefault();
		knd.openOffcanvas(e);
	});

	$('.knd-offcanvas-close, .nav-overlay').on( 'click', function(e) {
		e.preventDefault();
		knd.closeOffcanvas(e);
	});

	/** Close offcanvas on keydown ESC */
	$( document ).on( 'keydown', function(e) {
		if ( 27 === e.keyCode ) {
			knd.closeOffcanvas(e);
			knd.closeSearch(e);
		}
	});

	/** Submenu toggle  **/
	$( '.submenu-trigger' ).on( 'click', function( e ) {
		e.preventDefault();

		var thisParent = $(this).parent('.menu-item-has-children');

		if ( thisParent.hasClass('open') ) {
			$(this).attr('aria-expanded', 'false').attr('aria-label', knd.i18n.a11y.expand);
			$(this).next('.sub-menu' ).slideUp( 300, function(){
				thisParent.removeClass('open');
			});
		} else {
			$(this).attr('aria-expanded', 'true').attr('aria-label', knd.i18n.a11y.collapse);
			thisParent.addClass('open');
			$(this).next('.sub-menu' ).slideDown( 300 );
		}

	});

	// Dropdown menu Accesibility.
	$('.dropdown-nav-toggle').on('click', function(e){
		e.preventDefault();
		$(this).parent('.menu-item-has-children').toggleClass('focus');
		if ( $(this).parent('.menu-item-has-children').hasClass('focus') ) {
			$(this).attr('aria-expanded', 'true').attr('aria-label', knd.i18n.a11y.collapse);
		} else {
			$(this).attr('aria-expanded', 'false').attr('aria-label', knd.i18n.a11y.expand);
		}
	});

	// Close dropdown menu on focusout.
	$('.menu-item-has-children').on('focusout', function (e) {
		var $elem = $(this);
		setTimeout( function() {
			var hasFocus = !! ($elem.find(':focus').length > 0);
			if (! hasFocus) {
				$elem.removeClass('focus')
				$elem.find('.dropdown-nav-toggle').attr('aria-expanded', 'false').attr('aria-label', knd.i18n.a11y.expand);
			}
		}, 10);
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

		//kndSetupHeaderForSmallScreens();

		position = scroll; //upd scroll position
		return true;
	});

	/** Resize event **/
	$( window ).resize(function() {
		var winW = $( '#top' ).width();

		if ( winW < breakPointMedium && $siteHeader.hasClass( 'newsletter-open' ) ) {
			$siteHeader.removeClass( 'newsletter-open' );
		}

		//kndSetupHeaderForSmallScreens();
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

	/**
	 * Scroll To Element on click link with hash
	 */
	// a[href^="#"]
	$( '.knd-nav-menu a, .main-menu a, .knd-toc a' ).on( 'click', function( e ) {

		var lacationUrl = window.location.href.replace(/#.*$/, '');
		var thisUrl     = this.href.replace(/#.*$/, '');

		var offset = 0;
		if ( $('#wpadminbar').length ) {
			var adminbarHeight = $('#wpadminbar').height();
			//console.log.style.position;
			offset = adminbarHeight;
		}

		if ( this.hash && lacationUrl === thisUrl ) {

			var target = $( this.hash ).offset();
			if ( target) {

				$( 'body, html' ).animate( {
					scrollTop: target.top - offset
				}, 400 );

				if ( $(this).parents('.main-menu') ) {
					window.knd.closeOffcanvas(e);
				}

			}

			$( this ).blur();
			e.preventDefault();
		}

	});

	/**
	 * Scroll To Element on window load
	 */
	$(window).on('load', function(){
		if ( window.location.hash ) {
			var target = $( window.location.hash ).offset();
			if ( target) {

				var offset = 0;
				if ( $('#wpadminbar').length ) {
					var adminbarHeight = $('#wpadminbar').height();
					offset = adminbarHeight;
				}

				$( 'body, html' ).animate( {
					scrollTop: target.top - offset
				}, 400 );
			}
		}
	});

	/**
	 * Scroll To Top Button
	 */
	$( document ).ready( function() {

		var btnToTop = $( '.knd-to-top' );

		$( window ).scroll( function() {
			var offset = $( 'body' ).innerHeight() * 0.1;

			if ( $( this ).scrollTop() > offset ) {
				btnToTop.addClass( 'active' );
			} else {
				btnToTop.removeClass( 'active' );
			}
		} );

		btnToTop.on( 'click', function() {

			$( this ).blur();

			$( 'body, html' ).animate( {
				scrollTop: 0
			}, 400 );

			return false;
		} );
	} );

	/**
	 * FancyBox
	 */
	if ( $('#knd-fancybox-js').length) {
		$('.entry-content .wp-block-image').find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').attr('data-fancybox','');

		$( '.entry-content' ).find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').each( function( index ) {
			if ( $(this).parents('.wp-block-image').length == 0 && $(this).parents('.wp-block-gallery').length == 0 ) {
				$(this).attr('data-fancybox', '');
			}
		});

		$('.entry-content .wp-block-gallery, .entry-content .gallery').each( function( index ) {
			$( this ).find('a[href$=".jpg"], a[href$=".png"], a[href$=".jpeg"], a[href$=".gif"]').attr('data-fancybox', 'gallery-' + ( index + 1 ) );
		});
	}

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
		$( '.knd-block-carousel' ).flickity( 'resize' );
	});

	$('.knd-block-carousel').on( 'ready.flickity select.flickity', function( event ) {
		var allSlides    = $(this).find('.flickity-slider .knd-block-item').length;
		var activeSlides = $(this).find('.flickity-slider .knd-block-item.is-selected').length;
		var sliderArrows = $(this).find('.flickity-button');
		if ( Number( activeSlides ) < Number( allSlides ) ) {
			sliderArrows.removeClass('flickity-button-hidden');
		} else {
			sliderArrows.addClass('flickity-button-hidden');
		}
	});

	// Focus repeat in container.
	function kndFocusInModal( selector ){

		// add all the elements inside modal which you want to make focusable
		const focusableElements = 'button, [href]:not([aria-hidden="true"]), input, [tabindex]:not([tabindex="-1"])';

		const modal = document.querySelector( selector ); // select the modal by it's id

		if ( ! modal ) {
			return;
		}

		const firstFocusableElement = modal.querySelectorAll(focusableElements)[0]; // get first element to be focused inside modal
		const focusableContent = modal.querySelectorAll(focusableElements);
		const lastFocusableElement = focusableContent[focusableContent.length - 1]; // get last element to be focused inside modal

		document.addEventListener('keydown', function(e) {
			let isTabPressed = e.key === 'Tab' || e.keyCode === 9;

			if (!isTabPressed) {
				return;
			}

			if (e.shiftKey) { // if shift key pressed for shift + tab combination
				if (document.activeElement === firstFocusableElement) {
					lastFocusableElement.focus(); // add focus for the last focusable element
					e.preventDefault();
				}
			} else { // if tab key is pressed
				if (document.activeElement === lastFocusableElement) { // if focused has reached to last focusable element then focus first focusable element after pressing tab
					firstFocusableElement.focus(); // add focus for the first focusable element
					e.preventDefault();
				}
			}
		});

		firstFocusableElement.focus();
	}

	kndFocusInModal( '.site-nav' );
	kndFocusInModal( '.knd-search' );

	knd.getScrollBarWidth();

	$('[href="#knd-remove-all-hints"]').on('click', function(e){
		e.preventDefault();

		var data = {
			'nonce':   knd.nonce,
			'action': 'knd_remove_all_hints',
		};

		$.post( knd.ajaxurl, data, function(response) {
			console.log(response);
			if ( response.success === true ) {
				window.location.reload();
			}
		});
	});

})( jQuery );
