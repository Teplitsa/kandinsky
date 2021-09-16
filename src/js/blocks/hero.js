/**
 * Hero Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, ColorPicker, PanelBody, ToggleControl, BaseControl, Button, Dropdown, Tooltip, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;

	const { InspectorControls, ColorPaletteControl, InnerBlocks, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

	const { __ } = i18n;

	const icon = el('svg',
		{
			width: 24, 
			height: 24 
		},
		el( 'path',
			{
				d: "M11.6366 3.61325C12.2201 3.61325 12.6932 3.14018 12.6932 2.55662C12.6932 1.97307 12.2201 1.5 11.6366 1.5C11.053 1.5 10.58 1.97307 10.58 2.55662C10.58 3.14018 11.053 3.61325 11.6366 3.61325ZM11.6366 5.11325C13.0486 5.11325 14.1932 3.96861 14.1932 2.55662C14.1932 1.14464 13.0486 0 11.6366 0C10.2246 0 9.07995 1.14464 9.07995 2.55662C9.07995 3.96861 10.2246 5.11325 11.6366 5.11325Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M10.7587 6.171C9.41785 6.171 8.11256 6.6022 7.03559 7.40092L5.55231 8.50099C5.42967 8.59194 5.41649 8.77061 5.52445 8.87857L7.69622 11.0503V10.9214H15.5229V11.0503L17.6946 8.87857C17.8026 8.77061 17.7894 8.59194 17.6668 8.50099L16.1835 7.40092C15.1065 6.6022 13.8012 6.171 12.4604 6.171H10.7587ZM14.0229 12.4214V14.6717L18.7553 9.93923C19.511 9.1835 19.4187 7.93282 18.5603 7.29617L17.077 6.19611C15.7416 5.20569 14.123 4.671 12.4604 4.671H10.7587C9.09605 4.671 7.47749 5.20569 6.14205 6.19611L4.65877 7.29617C3.80033 7.93282 3.70807 9.18351 4.46379 9.93923L9.19622 14.6717V12.4214H14.0229Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M5.84626 10.8814L7.261 11.3799L5.61239 16.0583H8.41755V17.5583H3.49341L5.84626 10.8814ZM17.379 10.8996L19.5352 17.5583H14.7875V16.0583H17.4728L15.9519 11.3617L17.379 10.8996Z" 
			}
		),
		el( 'path',
			{
				d: "M7.75029 21.4587V8.80542H9.25029V21.4587C9.25029 21.9106 9.61659 22.2769 10.0684 22.2769C10.5203 22.2769 10.8866 21.9106 10.8866 21.4587V14.7536H12.3866V21.4587C12.3866 22.739 11.3487 23.7769 10.0684 23.7769C8.78816 23.7769 7.75029 22.739 7.75029 21.4587Z" 
			}
		),
		el( 'path',
			{
				d: "M15.5229 21.4587V8.80542H14.0229V21.4587C14.0229 21.9106 13.6566 22.2769 13.2047 22.2769C12.7529 22.2769 12.3866 21.9106 12.3866 21.4587V14.7536H10.8866V21.4587C10.8866 22.739 11.9244 23.7769 13.2047 23.7769C14.485 23.7769 15.5229 22.739 15.5229 21.4587Z" 
			}
		)
	);

	registerBlockType( 'knd/hero', {
		title: __( 'Main Block', 'knd' ),
		description: __( 'Title, description, button, and background image.', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ 'hero', 'main', 'jumbotron', 'full' ],

		supports: {
			align: [ 'wide', 'full' ],
			className: true
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'We help people to fight alcohol addiction', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'There are 877 people in our region who suffer from alcohol addiction. Your support will help organize a rehabilitation program for them.', 'knd' ),
			},
			backgroundImage: {
				type: 'object',
				default: {
					url: kndBlock.getImageUrl.heroBackground,
				}
			},
			featuredImage: {
				type: 'object',
				default: {
					url: kndBlock.getImageUrl.heroFeatured,
				}
			},
			textColor: {
				type: 'string',
			},
			backgroundColor: {
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
			overlayColorStart: {
				type: 'string',
				default: 'rgba(255,255,255,.8)',
			},
			overlayColorEnd: {
				type: 'string',
				default: 'rgba(255,255,255,1)',
			},
			align: {
				type: 'string',
				default: 'full',
			},
			className: {
				type: 'string',
			},
			blockId: {
				type: 'string',
			},
			button: {
				type: 'string',
				default: __( 'Help Now', 'knd' ),
			},
			buttonUrl: {
				type: 'string',
			},
			buttonAdditional: {
				type: 'string',
			},
			buttonAdditionalUrl: {
				type: 'string',
			},
		},

		example: {
			attributes: {
				heading: __( 'We help people to fight alcohol addiction', 'knd' ),
				text: __( 'There are 877 people in our region who suffer from alcohol addiction. Your support will help organize a rehabilitation program for them.', 'knd' ),
				button: __( 'Help Now', 'knd' ),
				backgroundImage: {
					url: kndBlock.getImageUrl.heroBackground,
				},
				featuredImage: {
					url: kndBlock.getImageUrl.heroFeatured,
				},
				backgroundColor: '#e6e6e6',
				overlayColorStart: 'rgba(255,255,255,0.8)',
				overlayColorEnd:  'rgba(255,255,255,1)',
			},
			viewportWidth: 1200
		},

		edit: ( props ) => {

			// Pull out the props we'll use
			const { attributes, className, setAttributes, clientId } = props;

			console.log(props);

			// Pull out specific attributes for clarity below
			const { backgroundImage, featuredImage } = attributes;

			const { blockId } = attributes;
			if ( ! blockId ) {
				//setAttributes( { blockId: clientId, featuredImage: { url: kndBlock.getImageUrl.heroFeatured } } );
			}

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' )
							},

							el( TextControl, {
								label: __( 'Call to action title', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
								},
							}),

							el( TextareaControl, {
								label: __( 'Call to action text', 'knd' ),
								value: props.attributes.text,
								onChange: ( val ) => {
									props.setAttributes( { text: val } );
								},
							}),

							el( BaseControl, null,

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
									el( 'div',
										{
											className: 'knd-components-heading__help'
										},
										__( 'Displayed on the right side of the Call to action text', 'knd' ),
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
													title: image.title,
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
							),// Featured image

							el( ColorPaletteControl,
								{
									label: __( 'Text Color', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.textColor,
									
									onChange: function( val ) {
										props.setAttributes({ textColor: val });
									}
								}
							),
						), // Panel

						// Buttons Panel
						el( PanelBody,
							{
								title: __( 'Buttons', 'knd' ),
								initialOpen: false
							},

							el( TextControl, {
								label: __( 'Button text', 'knd' ),
								value: props.attributes.button,
								onChange: ( val ) => {
									props.setAttributes( { button: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Button url', 'knd' ),
								value: props.attributes.buttonUrl,
								onChange: ( val ) => {
									props.setAttributes( { buttonUrl: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Additional Button text', 'knd' ),
								value: props.attributes.buttonAdditional,
								onChange: ( val ) => {
									props.setAttributes( { buttonAdditional: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Additional Button url', 'knd' ),
								value: props.attributes.buttonAdditionalUrl,
								onChange: ( val ) => {
									props.setAttributes( { buttonAdditionalUrl: val } );
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

						// Background Panel
						el( PanelBody,
							{
								title: __( 'Background', 'knd' ),
								initialOpen: false
							},

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
									el( 'div',
										{
											className: 'knd-components-heading__help'
										},
										__( 'Recommended size 1600x663px', 'knd' )
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
													title: image.title,
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
							),

							el( ColorPaletteControl,
								{
									label: __( 'Background Color', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.backgroundColor,
									
									onChange: function( val ) {
										props.setAttributes({ backgroundColor: val });
									}
								}
							),
						),

						// Overlay Panel
						el( PanelBody,
							{
								title: __( 'Overlay', 'knd' ),
								initialOpen: false
							},

							el( 'div', {
								className: 'knd-components',
							},

							el( 'div',
								{
									className: 'knd-components-heading',
								},
								el( 'div',
									{
										className: 'knd-components-heading__label'
									},
									__( 'Overlay Gradient Start Color', 'knd' ),
								),
							),

							el( ColorPicker,
								{
									disableAlpha: false,
									color: props.attributes.overlayColorStart,
									onChangeComplete: ( val ) => {
										var color = val.hex;
										if ( val.rgb.a !== 1 ) {
											color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')'
										}
										props.setAttributes({ overlayColorStart: color });
									}
								}
							),
						),

						el( 'div', {
							className: 'knd-components',
						},

						el( 'div',
							{
								className: 'knd-components-heading',
							},
							el( 'div',
								{
									className: 'knd-components-heading__label'
								},
								__( 'Overlay Gradient End Color', 'knd' ),
							),
						),

						el( ColorPicker,
							{
								disableAlpha: false,
								color: props.attributes.overlayColorEnd,
								onChangeComplete: ( val ) => {
									var color = val.hex;
									if ( val.rgb.a !== 1 ) {
										color = 'rgba(' + val.rgb.r + ',' + val.rgb.g + ',' + val.rgb.b + ',' + val.rgb.a + ')'
									}
									props.setAttributes({ overlayColorEnd: color });
								}
							}
						),
					),
				),
			),

			el(	Disabled,
				null,
				el( ServerSideRender, {
					block: 'knd/hero',
					attributes: props.attributes,
				} ),
			)
			)
			);
		},

		save: function( attributes ) {
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
