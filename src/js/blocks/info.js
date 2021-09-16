/**
 * Info Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;

	const { InspectorControls, ColorPaletteControl } = blockEditor;

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
				d: "M8.625 21L8.625 3L10.125 3L10.125 21H8.625Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		 el( 'path',
			{ 
				d: "M14.25 21L14.25 3L15.75 3L15.75 21H14.25Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		)
	);

	registerBlockType( 'knd/info', {
		title: __( 'About Project', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'info', 'knd' ), __( 'columns', 'knd' ), __( 'about', 'knd' ) ],
		description: __( 'Subheading, heading and three columns.', 'knd' ),
		supports: {
			align: [ 'wide', 'full' ],
		},

		attributes: {
			
			align: {
				type: 'string',
				default: 'full'
			},
			heading: {
				type: 'string',
				default: __( '112 volunteers are helping Line of Color at the moment', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'Join a team of volunteers and consultants in our projects', 'knd' ),
			},
			heading1: {
				type: 'string',
				default: __( 'Who we are?', 'knd' ),
			},
			heading2: {
				type: 'string',
				default: __( 'What we do?', 'knd' ),
			},
			heading3: {
				type: 'string',
				default: __( 'Stop drinking?', 'knd' ),
			},
			text1: {
				type: 'string',
				default: __( 'The charitable organization &#34;Line of Color&#34; helps to overcome alcohol addiction and return to a fulfilling life.', 'knd' ),
			},
			text2: {
				type: 'string',
				default: __( 'We organize rehabilitation programs, inform and help those who are ready to give up their addiction and return their lives.', 'knd' ),
			},
			text3: {
				type: 'string',
				default: __( 'Fill out the anonymous form on the website, choose a convenient time for an individual consultation, or sign up for a support group.', 'knd' ),
			},
			linkText1: {
				type: 'string',
				default: __( 'Learn about our work', 'knd' ),
			},
			linkText2: {
				type: 'string',
				default: __( 'View projects', 'knd' ),
			},
			linkText3: {
				type: 'string',
				default: __( 'Get help', 'knd' ),
			},
			linkUrl1: {
				type: 'string',
			},
			linkUrl2: {
				type: 'string',
			},
			linkUrl3: {
				type: 'string',
			},
			className: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
			},
			titleColor: {
				type: 'string',
			},
			headingColor: {
				type: 'string',
			},
			headingsColor: {
				type: 'string',
			},
			textColor: {
				type: 'string',
			},
			linkColor: {
				type: 'string',
			},
			linkHoverColor: {
				type: 'string',
			},
			underlineColor: {
				type: 'string',
			}
		},

		example: {
			viewportWidth: 720
		},

		edit: function( props ) {
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
							}),

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text,
								onChange: ( val ) => {
									props.setAttributes( { text: val } );
								},
							}),
						),

						el( PanelBody,
							{
								title: __( 'Column 1', 'knd' ),
								initialOpen: false,
							},

							el( TextControl, {
								label: __( 'Heading', 'knd' ),
								value: props.attributes.heading1,
								onChange: ( val ) => {
									props.setAttributes( { heading1: val } );
								},
							}),

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text1,
								onChange: ( val ) => {
									props.setAttributes( { text1: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Text', 'knd' ),
								value: props.attributes.linkText1,
								onChange: ( val ) => {
									props.setAttributes( { linkText1: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Url', 'knd' ),
								value: props.attributes.linkUrl1,
								onChange: ( val ) => {
									props.setAttributes( { linkUrl1: val } );
								},
							}),
						),

						el( PanelBody,
							{
								title: __( 'Column 2', 'knd' ),
								initialOpen: false,
							},

							el( TextControl, {
								label: __( 'Heading', 'knd' ),
								value: props.attributes.heading2,
								onChange: ( val ) => {
									props.setAttributes( { heading2: val } );
								},
							}),

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text2,
								onChange: ( val ) => {
									props.setAttributes( { text2: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Text', 'knd' ),
								value: props.attributes.linkText2,
								onChange: ( val ) => {
									props.setAttributes( { linkText2: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Url', 'knd' ),
								value: props.attributes.linkUrl2,
								onChange: ( val ) => {
									props.setAttributes( { linkUrl2: val } );
								},
							}),
						),

						el( PanelBody,
							{
								title: __( 'Column 3', 'knd' ),
								initialOpen: false,
							},

							el( TextControl, {
								label: __( 'Heading', 'knd' ),
								value: props.attributes.heading3,
								onChange: ( val ) => {
									props.setAttributes( { heading3: val } );
								},
							}),

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text3,
								onChange: ( val ) => {
									props.setAttributes( { text3: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Text', 'knd' ),
								value: props.attributes.linkText3,
								onChange: ( val ) => {
									props.setAttributes( { linkText3: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Link Url', 'knd' ),
								value: props.attributes.linkUrl3,
								onChange: ( val ) => {
									props.setAttributes( { linkUrl3: val } );
								},
							}),
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
									label: __( 'Heading Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes({ headingColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Second Heading Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes({ titleColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Heading Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.headingsColor,
									onChange: ( val ) => {
										props.setAttributes({ headingsColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Text Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes({ textColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes({ linkColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Color Hover', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.linkHoverColor,
									onChange: ( val ) => {
										props.setAttributes({ linkHoverColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Underline Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.underlineColor,
									onChange: ( val ) => {
										props.setAttributes({ underlineColor: val });
									}
								}
							),

						),
					),

					el(	Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/info',
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
