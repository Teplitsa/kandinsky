/**
 * News Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement; // PanelRow

	const { TextControl, SelectControl, RangeControl, ColorPalette, Dashicon, PanelBody, ToggleControl, Button, IconButton, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName } = blocks;
	const { InspectorControls, ColorPaletteControl, useBlockProps } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { __ } = i18n;

	const icon = el('svg', 
		{ 
			width: 24, 
			height: 24 
		},
		el( 'path',
			{ 
				d: "M4.738,4.652h5.206v4.696H4.738V4.652z M6.238,6.152v1.696h2.206V6.152H6.238z M16.972,6.023v12.843 c0,0.941,0.763,1.705,1.705,1.705s1.705-0.763,1.705-1.705V6.023H16.972z M15.472,5.554c0-0.57,0.462-1.032,1.032-1.032h4.346 	c0.57,0,1.031,0.462,1.031,1.032v13.311c0,1.77-1.435,3.205-3.205,3.205c-1.77,0-3.205-1.435-3.205-3.205V5.554z M14.275,12.375 H4.89v-1.5h9.385V12.375z M14.275,15.484H4.89v-1.5h9.385V15.484z M14.275,18.293H4.89v-1.5h9.385V18.293z M2.278,3.983 c0-0.966,0.783-1.75,1.75-1.75h11.195c0.967,0,1.75,0.784,1.75,1.75v1.578h-1.5V3.983c0-0.138-0.112-0.25-0.25-0.25H4.028 c-0.138,0-0.25,0.112-0.25,0.25V20.32c0,0.138,0.112,0.25,0.25,0.25h14.7v1.5h-14.7c-0.967,0-1.75-0.783-1.75-1.75V3.983z" 
			}
		)
	);

	function r(e) {
		if (Array.isArray(e)) {
			for (var t = 0, n = Array(e.length); t < e.length; t++) n[t] = e[t];
			return n
		}
		return Array.from(e)
	};

	registerBlockType( 'knd/news', {
		title: __( 'News', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'news', 'knd' ), __( 'posts', 'knd' ), __( 'articles', 'knd' ) ],
		description: __( 'News tile in three column.', 'knd' ),
		supports: {
			align: [ 'wide', 'full' ],
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'News', 'knd' ),
			},
			postsToShow: {
				type: 'number',
				default: 3
			},
			align: {
				type: 'string',
				default: 'full',
			},
			className: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
			},
			headingColor: {
				type: 'string',
			},
			titleColor: {
				type: 'string',
			},
			linkColor: {
				type: 'string',
			},
			metaColor: {
				type: 'string',
			},
			headingLinks: {
				type: "array",
				default: []
			},
			hiddenReload: {
				type: 'string',
			}
		},

		example: {
			attributes: {
				postsToShow: 3,
				backgroundColor: '#f0f0f0'
			},
			viewportWidth: 720
		},

		edit: ( props ) => {

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
								isDefault: true,
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
								className: 'knd-editor-block-card__description'
							},

							el( 'a',
								{
									href: kndBlock.getAdminUrl.news,
									target: '_blank',
								},
								__( 'Edit news', 'knd' ),
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
								title: __( 'Settings', 'knd' ),
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
									label: __('Posts to show','knd'),
									value: props.attributes.postsToShow,
									initialPosition: 3,
									min: 2,
									max: 12,
									onChange: ( val ) => {
										props.setAttributes( { postsToShow: val } );
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
									colors: kndBlockColors,
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Heading Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes( { headingColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Post Title Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes( { titleColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Links Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Date Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.metaColor,
									onChange: ( val ) => {
										props.setAttributes( { metaColor: val } );
									}
								}
							),

						),

					),

					el(	Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/news',
							attributes: props.attributes,
						} ),
					)
				)
			);
		},

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
) );
