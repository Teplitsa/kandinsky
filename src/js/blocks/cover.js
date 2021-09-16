/* Cover Image */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, PanelBody, ToggleControl, BaseControl, Button,  Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
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
				d: "M4.5 4.5V19.5H19.5V4.5H4.5ZM4.125 3C3.50368 3 3 3.50368 3 4.125V19.875C3 20.4963 3.50368 21 4.125 21H19.875C20.4963 21 21 20.4963 21 19.875V4.125C21 3.50368 20.4963 3 19.875 3H4.125Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M6 13.6163L18 13.6163V15.1163L6 15.1163V13.6163Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M6 16.6163L18 16.6163V18.1163L6 18.1163V16.6163Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M10.5341 5.5075L14.5881 12.3663H6.61473L10.5341 5.5075ZM10.556 8.4925L9.1995 10.8663H11.9591L10.556 8.4925Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M13.8017 6.95758L17.5182 12.3663H12.5667L11.8615 10.3334L13.8017 6.95758ZM13.9149 9.77093L13.5028 10.4879L13.634 10.8663H14.6675L13.9149 9.77093Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		)
	);

	registerBlockType( 'knd/cover', {
		title: __( 'Cover' ),
		description: __( 'Text over image.', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'image', 'knd' ), __( 'cover', 'knd' ) ],
		supports: {
			align: [ 'wide', 'full' ],
		},

		attributes: {
			align: {
				type: 'string',
				default: 'wide'
			},
			className: {
				type: 'string',
			},
			heading: {
				type: 'string',
				default: __( 'We believe', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'People are created to love and be loved; People need support and help; Your life and your story are important; Better days and a bright life are close; Hope and help are real.', 'knd' ),
			},
			recommend: {
				type: 'string',
				default: __( 'Recommendations', 'knd' ),
			},
			backgroundImage: {
				type: 'object',
				default: {
					url: kndBlock.getImageUrl.heroBackground,
				}
			},
			textColor: {
				type: 'string',
			},
			contentColor: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
				default: '#dadada',
			},
			recommendColor: {
				type: 'string',
			},
			recommendBgColor: {
				type: 'string',
			},
			minHeight: {
				type: 'string',
			},
		},

		example: {
			attributes: {
				minHeight: '400px',
			},
			viewportWidth: 720
		},
		
		// Register block styles.
		styles: [
			{
				name: 'default',
				label: __( 'Default', 'knd' ),
				isDefault: true
			},
			{
				name: 'bottom',
				label: __( 'Bottom', 'knd' )
			},
		],

		edit: function( props ) {
			
			// Pull out the props we'll use
			const { attributes, className, setAttributes, clientId } = props;

			// Pull out specific attributes for clarity below
			const { backgroundImage, featuredImage } = attributes;

			return (
				el( Fragment, {},

					el( InspectorControls, {},

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

							el( TextareaControl, {
								label: __( 'Content', 'knd' ),
								value: props.attributes.text,
								onChange: ( val ) => {
									props.setAttributes( { text: val } );
								},
							}),

							el( TextareaControl, {
								label: __( 'Recommendation', 'knd' ),
								value: props.attributes.recommend,
								onChange: ( val ) => {
									props.setAttributes( { recommend: val } );
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
										__( 'Background Image', 'knd' )
									),
								),

								el( MediaUploadCheck, null,

									el( MediaUpload, {
										multiple: false,
										value: backgroundImage ? backgroundImage.id : "",
										allowedTypes: ["image"],

										onSelect: function onSelect(image) {
											return setAttributes({
												backgroundImage: {
													id: image.id,
													url: image.url,
												}
											});
										},


										render: function render(_ref) {
											var open = _ref.open;

											if ( typeof backgroundImage.url !== 'undefined' && backgroundImage.url ) {
												return el( 'div', null,
													el( 'p', null,
														el( 'img', {
															src: backgroundImage.url,
														})
													),
													el( 'p', null,
														el( Button,
														{
															onClick: function onClick() {
																return setAttributes({
																	backgroundImage: {
																		id: '',
																		title: '',
																		url: '',
																	}
																});
															},
															className: 'button is-small'
														},
														__( 'Remove Background Image', 'knd' )
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
													__( 'Add Background Image', 'knd' )
													)
												)
											}

										}

									}),
								),
							), // Background Image

							el( TextControl, {
								label: __( 'Min Height', 'knd' ),
								value: props.attributes.minHeight,
								onChange: ( val ) => {
									props.setAttributes( { minHeight: val } );
								},
							}),

						),

						// Background Panel
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
									onChange: function( val ) {
										props.setAttributes({ backgroundColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Text Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.textColor,
									onChange: function( val ) {
										props.setAttributes({ textColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Content Background Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.contentColor,
									onChange: function( val ) {
										props.setAttributes({ contentColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Recommendation Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.recommendColor,
									onChange: function( val ) {
										props.setAttributes({ recommendColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Recommendation Background Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.recommendBgColor,
									onChange: function( val ) {
										props.setAttributes({ recommendBgColor: val });
									}
								}
							),

						),
					),

					el(	Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/cover',
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
