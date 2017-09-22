/* Scripts */
jQuery( document ).ready( function( $ ) {

/** Has js **/
$( 'html' )
	.removeClass( 'no-js' )
	.addClass( 'js' );

/** Window width **/
var windowWidth = $( '#top' )
		.width(),
	$adminbar = $( '#wpadminbar' ),
	$site_header = $( '#site_header' ),
	breakPointSmall = 480, //small screens break point
	breakPointMedium = 767; //medium screen break point

/** Resize event **/
$( window )
	.resize(function() {
		var winW = $( '#top' )
			.width();

		if ( winW < breakPointMedium && $site_header.hasClass( 'newsletter-open' ) ) {
			$site_header.removeClass( 'newsletter-open' );
		}

		knd_setup_header_for_small_screens();
	});

/** == Header states == **/

/** Drawer **/
$( '#trigger_menu' )
	.on( 'click', function( e ) {

		if ( $site_header.hasClass( 'newsletter-open' ) ) { //close newsletter if any
			$site_header.removeClass( 'newsletter-open' );
		}

		$site_header.addClass( 'menu-open' );

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();

	} );

$( '#trigger_menu_close' )
	.on( 'click', function( e ) {

		$site_header.removeClass( 'menu-open' );

		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();
	} );

	

/** Submenu toggle  **/
$( '.submenu-trigger' )
	.on( 'click', function( e ) {

		var li = $( this )
			.parents( '.menu-item-has-children' );
		if ( li.hasClass( 'open' ) ) {
			li.find( '.sub-menu' )
				.slideUp( 300, function() {
					li.removeClass( 'open' );
					$( this )
						.removeAttr( 'style' );
				} );
		}
		else {

			li.find( '.sub-menu' )
				.slideDown( 300, function() {
					li.addClass( 'open' );
					$( this )
						.removeAttr( 'style' );
				} );
		}
	} );





/** Close by key and click **/
$( document )
	.on( 'click', function( e ) {

		var $etarget = $( e.target );

		if ( $site_header.hasClass( 'menu-open' ) ) {
			if ( ! $etarget.is( '#site_nav, #trigger_menu' ) &&
			     ! $etarget.closest( '#site_nav, #trigger_menu' ).length ) {
				$site_header.removeClass( 'menu-open' );
			}
		}
		else if ( $site_header.hasClass( 'newsletter-open' ) ) {
			if ( ! $etarget.is( '#newsletter_panel, #trigger_newsletter' ) &&
			     ! $etarget.closest( '#newsletter_panel, #trigger_newsletter' ).length ) {
				$site_header.removeClass( 'newsletter-open' );
			}
		}

	} )
	.on( 'keyup', function( e ) { //close search on by ESC
		if ( 27 === e.keyCode ) {
			//hide menu on newsletter
			if ( $site_header.hasClass( 'menu-open' ) ) {
				$site_header.removeClass( 'menu-open' );
			}
			else if ( $site_header.hasClass( 'newsletter-open' ) ) {
				$site_header.removeClass( 'newsletter-open' );
			}
		}
	} )
	.on( 'keydown', function( e ) { //close search on by ESC
		if ( 27 === e.keyCode ) {
			//hide menu on newsletter
			if ( $site_header.hasClass( 'menu-open' ) ) {
				$site_header.removeClass( 'menu-open' );
			}
			else if ( $site_header.hasClass( 'newsletter-open' ) ) {
				$site_header.removeClass( 'newsletter-open' );
			}
		}
	} );



/** Sticky elements **/
var position = $( window )
		.scrollTop(), //store intitial scroll position
	scrollTopLimit = ($( 'body' )
		.hasClass( 'adminbar' )) ? 99 + 32 + 90 : 99 + 90,
	fixedTopPosition = ($( 'body' )
		.hasClass( 'adminbar' )) ? 99 + 32 + 90 : 99 + 90;

$( window )
	.scroll(function() {
		var scroll = $( window )
				.scrollTop(),
			winW = $( '#top' )
				.width();

		//no scroll when menu is open
		if ( $site_header.hasClass( 'menu-open' ) ) {
			$( window )
				.scrollTop( position );
			return;
		}

		//scroll tolerance 3px and ignore out of boundaries scroll
		if ( (Math.abs( scroll - position ) < 3) || rdc_scroll_outOfBounds( scroll ) ) {
			return true;
		}

		//sticky sharing
		if ( winW >= breakPointMedium && $( '#knd_sharing' ).length > 0 ) {
			stickInParent( '#knd_sharing .social-likes-wrapper', '#knd_sharing', position, fixedTopPosition );
		}

		knd_setup_header_for_small_screens();

		position = scroll; //upd scroll position
		return true;
	});

function knd_setup_header_for_small_screens() {
	var scroll = $( window )
		.scrollTop();
	var adminbar_height = $adminbar.height();
	if ( 'absolute' === $adminbar.css( 'position' ) ) {
		if ( scroll > adminbar_height ) {
			$site_header.css( 'top', '0px' );
		}
		else {
			$site_header.css( 'top', '' + (adminbar_height - scroll) + 'px' );
		}
	}
	else {
		$site_header.css( 'top', '' );
	}
}

// Stick element on scroll
function stickInParent( el, el_parent, el_position, el_fixedTopPosition ) {
	var scroll = $( window )
			.scrollTop(),
		$_el = $( el ),
		$_el_parent = $( el_parent ),
		topPos = $_el_parent.offset().top,
		height = $_el_parent.outerHeight();

	if ( scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition) ) { //stick on bottom
		if ( scroll > el_position ) //scroll down
		{
			$_el.addClass( 'fixed-bottom' )
				.removeClass( 'fixed-top' );
		}
	}
	else if ( scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition) ) { //unstick on bottom
		if ( scroll < el_position ) {
			$_el.removeClass( 'fixed-bottom' )
				.addClass( 'fixed-top' );
		}
	}
	else if ( scroll > topPos - el_fixedTopPosition ) { //stick on top
		$_el.removeClass( 'fixed-bottom' )
			.addClass( 'fixed-top' );
	}
	else {
		$_el.removeClass( 'fixed-bottom' )
			.removeClass( 'fixed-top' ); //normal position
	}
}

//determines if the scroll position is outside of document boundaries
function rdc_scroll_outOfBounds( scroll ) {
	var documentH = $( document )
			.height(),
		winH = $( window )
			.height();

	if ( scroll < 0 || scroll > (documentH + winH) ) {
		return true;
	}

	return false;
}

/** == Responsive media == **/
var resize_embed_media = function() {

	$( 'iframe, embed, object' )
		.each(function() {

			var $iframe = $( this ),
				$parent = $iframe.parent(),
				do_resize = false;

			if ( $parent.hasClass( 'embed-content' ) ) {
				do_resize = true;
			}
			else {

				$parent = $iframe.parents( '.entry-content, .player' );
				if ( $parent.length ) {
					do_resize = true;
				}
			}

			if ( do_resize ) {
				var change_ratio = $parent.width() / $iframe.attr( 'width' );
				$iframe.width( change_ratio * $iframe.attr( 'width' ) );
				$iframe.height( change_ratio * $iframe.attr( 'height' ) );
			}
		});
};

resize_embed_media(); // Initial page rendering
$( window )
	.resize(function() {
		resize_embed_media();
	});


/* Scroll */
$( '.local-scroll, .inpage-menu a' )
	.on( 'click', function( e ) {
		e.preventDefault();

		var full_url = $( this )
				.attr( 'href' ),
			trgt = full_url.split( '#' )[ 1 ],
			target = $( '#' + trgt )
				.offset();

		if ( target.top ) {
			$( 'html, body' )
				.animate( { scrollTop: target.top - 50 }, 900 );
		}

	} );



} ); //jQuery
