/**
 * CTA Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { registerBlockType } = blocks;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, PanelBody, ToggleControl, BaseControl, Button, Disabled } = components;

	const { withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl, MediaUpload, MediaUploadCheck } = blockEditor;

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
				d: "M8.12856 10.2708L18.5611 15.5274L15.5534 17.6556L18.1031 21.259L14.8702 23.5466L12.3205 19.9431L9.22532 22.1332L8.12856 10.2708ZM9.87144 12.8287L10.4798 19.4081L12.6785 17.8523L15.2282 21.4557L16.0122 20.901L13.4625 17.2976L15.6578 15.7442L9.87144 12.8287Z",
								'fill-rule': "evenodd",
								'clip-rule': "evenodd"
			}
		),
				el( 'path',
			{ 
				d: "M2.25 8.841C2.25 7.87451 3.0335 7.091 4 7.091H20C20.9665 7.091 21.75 7.8745 21.75 8.841V16.841C21.75 17.8075 20.9665 18.591 20 18.591H15.3686V17.091H20C20.1381 17.091 20.25 16.9791 20.25 16.841V8.841C20.25 8.70293 20.1381 8.591 20 8.591H4C3.86193 8.591 3.75 8.70293 3.75 8.841V16.841C3.75 16.9791 3.86193 17.091 4 17.091H9.42868V18.591H4C3.0335 18.591 2.25 17.8075 2.25 16.841V8.841Z",
								'fill-rule': "evenodd",
								'clip-rule': "evenodd"
			}
		)
	);

	registerBlockType( 'knd/cta', {
		title: __( 'Call To Action', 'knd' ),
		description: __( 'Title, Subtitle, Description, Button, and Image.', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'call', 'knd' ), __( 'cta', 'knd' ), __( 'action', 'knd' )],
		supports: {
			align: [ 'wide', 'full' ],
		},

		attributes: {
			featuredImage: {
				type: 'object',
				default: {
					url: kndBlock.getImageUrl.ctaFeatured
				}
			},
			align: {
				type: 'string',
				default: 'full',
			},
			className: {
				type: 'string',
			},
			heading: {
				type: 'string',
				default: __( '112 volunteers are helping Line of Color at the moment', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'Join a team of volunteers and consultants in our projects', 'knd' ),
			},
			buttonText: {
				type: 'string',
				default: __( 'Become a volunteer', 'knd' ),
			},
			buttonUrl: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
			},
			titleColor: {
				type: 'string',
			},
			textColor: {
				type: 'string',
			},
			buttonBackground: {
				type: 'string',
			},
			buttonColor: {
				type: 'string',
			},
			buttonBackgroundHover: {
				type: 'string',
			},
			buttonColorHover: {
				type: 'string',
			},
			className: {
				type: 'string',
			}
		},

		example: {
			attributes: {
				heading: __( '112 volunteers are helping Line of Color at the moment', 'knd' ),
				text: __( 'Join a team of volunteers and consultants in our projects', 'knd' ),
				buttonText: __( 'Become a volunteer', 'knd' ),
				featuredImage: {
					url: kndBlock.getImageUrl.ctaFeatured
				}
			},
			viewportWidth: 1200
		},

		edit: function( props ) {

			// Pull out the props we'll use
			const { attributes, className, setAttributes } = props;

			// Pull out specific attributes for clarity below
			const { featuredImage } = attributes;

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' )
							},
							el( TextControl, {
								label: __( 'Title', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
								},
							} ),

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text,
								onChange: ( val ) => {
									props.setAttributes( { text: val } );
								},
							}),

							el( BaseControl, {},

								el( 'div',
									{
										className: 'knd-components-heading',
									},
									el( 'div',
										{
												className: 'knd-components-heading__label'
										},
										__( 'Call to action Image','knd' ),
								),
							),

							el( MediaUploadCheck, null,

								el( MediaUpload, {
									multiple: false,
									value: featuredImage ? featuredImage.id : "",
									allowedTypes: ["image"],
									onSelect: function onSelect(image) {
										return setAttributes({
												featuredImage: {
													id: image.id,
													url: image.url,
												}
										});
									},

									render: function render(_ref) {
										var open = _ref.open;

										if ( typeof featuredImage.url !== 'undefined' && featuredImage.url ) {
											return el( 'div', null,
													el( 'p', null,
														el( 'img', {
															src: featuredImage.url,
														})
													),
													el( 'p', null,
														el( Button,
														{
															onClick: function onClick() {
																return setAttributes({
																	featuredImage: {
																		id: '',
																		title: '',
																		url: '',
																	}
																});
															},
															className: 'button is-small'
														},
														__( 'Remove Image', 'knd' )
														)
													)
												)
											} else {

												return el( 'p', null,
													el( Button,
													{
														onClick: open,
														className: 'button'
													},
													__( 'Add Image', 'knd' )
													)
												)
											}

										}

									}),
								),
							), // Featured image

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
										props.setAttributes({ backgroundColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Title Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes({ titleColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Text Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes({ textColor: val });
									}
								}
							),

						),

						// Buttons Panel
						el( PanelBody,
							{
								title: __( 'Button', 'knd' ),
								initialOpen: false
							},

							el( TextControl, {
								label: __( 'Button text', 'knd' ),
								value: props.attributes.buttonText,
								onChange: ( val ) => {
									props.setAttributes( { buttonText: val } );
								},
							}),
													 
													 el( TextControl, {
								label: __( 'Button url', 'knd' ),
								value: props.attributes.buttonUrl,
								onChange: ( val ) => {
									props.setAttributes( { buttonUrl: val } );
								},
							}),

							el( ColorPaletteControl,
								{
									label: __( 'Button Background', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.buttonBackground,
									onChange: function( val ) {
										props.setAttributes({ buttonBackground: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Button Color', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.buttonColor,
									onChange: function( val ) {
										props.setAttributes({ buttonColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Button Hover Background', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.buttonBackgroundHover,
									onChange: function( val ) {
										props.setAttributes({ buttonBackgroundHover: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Button Hover Color', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.buttonColorHover,
									onChange: function( val ) {
										props.setAttributes({ buttonColorHover: val });
									}
								}
							),

						),
					),

					el(	Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/cta',
							attributes: props.attributes,
						} ),
					)
				)
			);
		},
 
		save: function() {
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
) );
