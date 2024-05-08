/* Gutenberg */
(function( $ ){
	"use strict";

	/**
	 * Add Page Panel
	 */

	window.knd = {};

	/* Constants */
	const el = wp.element.createElement;
	const { __ } = wp.i18n;
	const { registerPlugin } = wp.plugins;
	const { PluginSidebar, PluginDocumentSettingPanel } = wp.editPost;
	const { compose, withState } = wp.compose;
	const { select, dispatch, withSelect, withDispatch, useSelect, useDispatch } = wp.data;
	const { TextControl, SelectControl, IconButton, ColorPalette, ToggleControl, PanelRow } = wp.components;
	const { useState, useEffect, Fragment } = wp.element;
	const { registerBlockStyle } = wp.blocks;

	const { addFilter, addAction } = wp.hooks;

	/**
	 * https://developer.wordpress.org/block-editor/how-to-guides/plugin-sidebar-0/plugin-sidebar-4-initialize-input/
	 */

	/* Is Page Title */
	var kndIsPageTitle = compose( [ withSelect( function ( select, props ) {
		if ( select( 'core/editor' ).getEditedPostAttribute( 'meta' ) !== undefined ) {
			return {
				metaFieldValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.fieldName ],
			}
		}
		return;
	} ), withDispatch( function ( dispatch ) {
		return {
			setPostMeta: function setPostMeta(value) {
				dispatch('core/editor').editPost({
					meta: {
						_knd_is_page_title: value
					}
				});
			}
		};
	})])(function (props) {

		const [ isPageTitle, setIsPageTitle ] = useState( props.metaFieldValue );

		return el( ToggleControl, {
			label: __( 'Hide the page title', 'knd' ),
			checked: isPageTitle,
			onChange: val => {
				setIsPageTitle( val );
				props.setPostMeta(val)
			},
		} );

	});

	/* Render page options */
	var kndRegisterPageOptions = function() {
		return el( PluginDocumentSettingPanel,
			{
				name: 'knd-page-options-panel',
				priority: 9999,
				className: 'knd-page-options-panel',
				title: __( 'Page Options', 'knd' ),
			},
			el( 'div',
				{ className: 'knd-page-options-header-type' },
				el( kndIsPageTitle,
					{ fieldName: '_knd_is_page_title' }
				)
			)
		);
	}

	/* Register Page Options Fields */
	if( window.pagenow == 'page' ) {
		registerPlugin( 'knd-page-options-panel', {
			icon: null,
			render: kndRegisterPageOptions
		} );
	}

	/** Partner Url Post Meta */
	var kndOrgUrl = function ( props ) {

		var metaFieldValue = useSelect( function ( select ) {
			var getCurrentPostId = select( 'core/editor' ).getCurrentPostId();
			var urlValue = select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ '_knd_org_url' ];
			var getExcerpt = select('core/editor').getEditedPostAttribute('excerpt')

			if ( ! urlValue ) {
				urlValue = getExcerpt;
			}

			return urlValue;
		}, [] );

		var editPost = useDispatch( 'core/editor' ).editPost;

		return el( TextControl, {
			label: __( 'Partner Url', 'knd' ),
			value: metaFieldValue,
			onChange: function ( val ) {
				console.log('val');
				editPost( {
					meta: { _knd_org_url: val },
					excerpt: '',
				} );
			},
		} );
	};

	/* Render page options */
	var kndRegisterOrgOptions = function() {
		return el( PluginDocumentSettingPanel,
			{
				name: 'knd-org-options-panel',
				className: 'knd-page-options-panel',
				title: __( 'Page Options', 'knd' ),
			},
			el( 'div',
				{ className: 'knd-org-options-url' },
				el( kndOrgUrl )
			)
		);
	}

	/* Register Page Options Fields */
	if( window.pagenow == 'org' ) {
		registerPlugin( 'knd-org-options-panel', {
			icon: null,
			render: kndRegisterOrgOptions
		} );

		var withImageSize = function () {
			return 'medium_large';
		};

		wp.hooks.addFilter(
			'editor.PostFeaturedImage.imageSize',
			'knd/change-image-size',
			withImageSize
		);

		function wrapPostFeaturedImage( OriginalComponent ) { 
			return function( props ) {
				return (
					el( Fragment,
						{},
						el ( PanelRow,
							{},
							el( 'p',
								{},
								__( 'It is recommended to use image of logo with a transparent background and with extensions .PNG or .SVG', 'knd' )
							)
						),
						el(
							OriginalComponent,
							props
						)
					)
				);
			}
		}

		wp.hooks.addFilter( 
			'editor.PostFeaturedImage', 
			'knd/wrap-post-featured-image',
			wrapPostFeaturedImage
		);

		/* Remove editor panel post-excerpt */
		dispatch('core/edit-post').removeEditorPanel( 'post-excerpt' );
	}

	/* Filters for editor page person */
	if( window.pagenow == 'person' ) {

		var withImageSize = function () {
			return 'medium_large';
		};

		wp.hooks.addFilter(
			'editor.PostFeaturedImage.imageSize',
			'knd/change-image-size',
			withImageSize
		);

		function wrapPostFeaturedImage( OriginalComponent ) { 
			return function( props ) {
				return (
					el( Fragment,
						{},
						el ( PanelRow,
							{},
							el( 'p',
								{},
								__( 'It is recommended to add a square image for a person and a width of at least 920px.', 'knd' )
							)
						),
						el(
							OriginalComponent,
							props
						)
					)
				);
			}
		}

		wp.hooks.addFilter( 
			'editor.PostFeaturedImage', 
			'knd/wrap-post-featured-image',
			wrapPostFeaturedImage
		);

	}

	registerBlockStyle( 'core/table', {
		name: 'underline',
		label: __( 'Underline', 'knd' ),
		Default: false,
	});

	addAction( 'knd.block.edit', 'knd/carousel.init', function( props ) {
		if ( 'knd/people' === props.name || 'knd/partners' === props.name || 'knd/testimonials' === props.name ) {

			useEffect( () => {
				//kndStartObserver( 'knd-people-server-side-observer' );

				kndMutationObserver();

				return () => {
					
				}
			}, [ props.attributes ] );

		}
	} );


	function kndMutationObserver(){
		const MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

		jQuery( document ).ready( function() {
			observer.observe( document.body, {
				childList: true,
				subtree: true,
				attributes: false,
				characterData: false,
			} );
		} );

		const observer = new MutationObserver( function( mutations ) {
			mutations.forEach( function( mutation, index ) {

				if ( ! mutation.addedNodes ) {
					return;
				}

				for ( let i = 0; i < mutation.addedNodes.length; i++ ) {
					// do things to your newly added nodes here
					const node = mutation.addedNodes[ i ];

					if ( node.className === 'knd-block-server-side-rendered' ) {

						kndReinitFlickity();

						setTimeout(function(){
							observer.disconnect();
						}, 500 );

					}
				}
			} );
		} );

	}

	function kndReinitFlickity(){
		let carousel     = jQuery( '.knd-block-carousel' ); // .not('.flickity-enabled').length
		if ( jQuery( '.knd-block-carousel' ).length ) {
			let flktyData    = carousel.attr( 'data-flickity' );
			let flktyOptions = JSON.parse( flktyData );
			carousel.flickity( flktyOptions ).flickity( 'resize' );
		}
	}

})( jQuery );