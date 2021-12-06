/**
 * Events Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data ) {

	if ( ! kndBlock.postTypes.event ) {
		return;
	}

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Button, FormTokenField, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { useSelect } = data;

	const { __ } = i18n;

	function r(e) {
		if (Array.isArray(e)) {
			for (var t = 0, n = Array(e.length); t < e.length; t++) n[t] = e[t];
			return n
		}
		return Array.from(e)
	};

	registerBlockType( 'knd/events', {
		title: __( 'Events', 'knd' ),
		icon: 'calendar',
		category: 'kandinsky',
		description: __( 'Events Manager', 'knd' ),
		keywords: [
			__( 'events', 'knd' ),
		],
		supports: {
			align: [ 'wide', 'full' ],
			anchor: true,
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'Schedule of events', 'knd' ),
			},
			postsToShow: {
				type: 'number',
				default: 2
			},
			align: {
				type: 'string',
			},
			className: {
				type: 'string',
			},
			anchor: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
			},
			headingColor: {
				type: 'string',
			},
			linkColor: {
				type: 'string',
			},
			headingLinks: {
				type: "array",
				default: []
			},
			hiddenReload: {
				type: 'string',
			},
			queryOffset: {
				type: 'string',
				default: '',
			},
			queryOrderBy: {
				type: 'string',
				default: '_event_start_date',
			},
			queryWhat: {
				type: 'string',
				default: 'future',
			},
		},

		example: {
			attributes: {
				postsToShow: 2,
				backgroundColor: '#f0f0f0'
			},
			viewportWidth: 720
		},

		// Register block styles.
		styles: [
			{
				name: 'list',
				label: __( 'List', 'knd' ),
				isDefault: true
			},
			{
				name: 'grid',
				label: __( 'Grid', 'knd' )
			},
		],

		edit: function( props ) {

			var t = function() {
				var t = [].concat(r( props.attributes.headingLinks));
				t.push({
					linkTitle: '',
					linkUrl: ''
				}),
				props.setAttributes({
					headingLinks: t
				})
			};
			var n = function(t) {
				var n = [].concat(r( props.attributes.headingLinks));
				n.splice(t, 1), props.setAttributes({
					headingLinks: n
				})
			};
			var o = function(t, n) {
				var o = [].concat(r(props.attributes.headingLinks));
				o[n].linkTitle = t;
				props.setAttributes({
					headingLinks: o,
					hiddenReload: t
				})
			};
			var oo = function(t, n) {
				var oo = [].concat(r(props.attributes.headingLinks));
				oo[n].linkUrl = t;
				props.setAttributes({
					headingLinks: oo
				});
			};
			var a = '';
			var c = void 0;

			function headingFields() {
				return props.attributes.headingLinks.length && ( a = props.attributes.headingLinks.map( function( t, r ) {
					return el( 'div',
						{
							key: r,
						},
						el( 'div',
							{
								className: 'component-heading-links',
							},

							el( TextControl,
								{
									label: __( 'Link Title', 'knd' ),
									placeholder: '',
									value: props.attributes.headingLinks[r].linkTitle,
									onChange: function(e) {
										return o(e, r)
									}
								}
							),

							el( TextControl,
								{
									label: __( 'Link Url', 'knd' ),
									placeholder: '',
									value: props.attributes.headingLinks[r].linkUrl,
									onChange: function(e) {
										return oo(e, r)
									}
								}
							),

							el( Button,
								{
									className: 'is-link',
									isSmall: true,
									text: __('Remove Link', 'knd' ),
									isDestructive: true,
									onClick: function() {
										return n(r)
									}
								},
							),

						) )
					} )
				),

				el( 'div', {
					className: 'components-base-control',
				},
					[
						a,
						el( Button,
							{
								isSecondary: true,
								variant: 'primary',
								isSmall: true,
								onClick: t.bind(this)
							},
							__('Add Link', 'knd' )
						),
						el( TextControl, {
							type: 'hidden',
							value: props.attributes.hiddenReload,
							onChange: ( val ) => {
								props.setAttributes( { hiddenReload: val } );
							},
						}),
					]
				)
			};

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( 'div',
							{
								className: 'knd-editor-block-card__description knd-editor-block-card__description-alt'
							},

							el( 'a',
								{
									href: kndBlock.getAdminUrl.event,
									target: '_blank',
								},
								__( 'Edit events', 'knd' ),
								' ',
								el( Dashicon,
									{
										icon: 'external',
									}
								),
							),
						),

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' )
							},
							el( TextControl, {
								label: __( 'Heading', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
								},
							}),

							el( RangeControl,
								{
									label: __( 'Events to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 2,
									min: 0,
									max: 50,
									onChange: ( val ) => {
										props.setAttributes({ postsToShow: val })
									}
								}
							),

						),

						el( PanelBody,
							{
								title: __( 'Heading Links', 'knd' ),
								className: 'knd-components-panel__body',
							},

							headingFields

						),

						el( PanelBody,
							{
								title: __( 'Colors', 'knd' ),
								initialOpen: false
							},

							el( ColorPaletteControl,
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Heading Color', 'knd' ),
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes( { headingColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Links Color', 'knd' ),
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								}
							),

						),

						el( PanelBody,
							{
								title: __( 'Query', 'knd' ) ,
								initialOpen: false
							},

							el( SelectControl,
								{
									label: __( 'Order by' ),
									options : [
										{ value: 'date', label: __( 'Published Date', 'knd' ) },
										{ value: '_event_start_date', label: __( 'Event Start Date', 'knd' ) },
									],
									value: props.attributes.queryOrderBy,
									onChange: ( val ) => {
										props.setAttributes( { queryOrderBy: val } );
									},
								},
							),

							el( TextControl,
								{
									label: __( 'Offset', 'knd' ),
									type: 'number',
									min: 0,
									max: 100,
									value: props.attributes.queryOffset,
									help: __( 'Number of events to skip', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { queryOffset: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'What events to show' ),
									options : [
										{ value: 'all', label: __( 'All events', 'knd' ) },
										{ value: 'future', label: __( 'Future events', 'knd' ) },
									],
									value: props.attributes.queryWhat,
									onChange: ( val ) => {
										props.setAttributes( { queryWhat: val } );
									},
								},
							),

						),

					),

					el( Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/events',
							attributes: props.attributes,
						} ),
					)
				)
			);
		},

		save: function() {
			return null;
		}

	} );

}(
	window.wp.blocks,
	window.wp.editor,
	window.wp.blockEditor,
	window.wp.element,
	window.wp.components,
	window.wp.compose,
	window.wp.i18n,
	window.wp.serverSideRender,
	window.wp.data,
) );
