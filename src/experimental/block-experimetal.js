/**
 * Hero Block
 */

/*
( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { registerBlockType } = blocks;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, ColorPicker, PanelBody, ToggleControl, BaseControl, Button, Dropdown, Tooltip, Disabled } = components;

	const { withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl, InnerBlocks, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

	const { withInstanceId, withState } = compose;

	const { __ } = i18n;

	const icon = el('svg', 
		{ 
			width: 24, 
			height: 24 
		},
		el( 'path',
			{ 
				d: "M11 7h6v2h-6zm0 4h6v2h-6zm0 4h6v2h-6zM7 7h2v2H7zm0 4h2v2H7zm0 4h2v2H7zM20.1 3H3.9c-.5 0-.9.4-.9.9v16.2c0 .4.4.9.9.9h16.2c.4 0 .9-.5.9-.9V3.9c0-.5-.5-.9-.9-.9zM19 19H5V5h14v14z" 
			}
		)
	);

	registerBlockType( 'knd/hero-experimental', {
		title: __( 'Hero Experimental', 'knd' ),
		description: __( 'Experimental Block' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ 'hero', 'main', 'jumbotron', 'full' ],
		
		supports: {
			align: [ 'wide', 'full' ],
			className: true
		},

		labels: {
			title: 'hopana',
				instructions: 'nello nene',
		},
		editorStyle: "wp-block-site-logo-editor",
		style: "wp-block-site-logo",
		

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
			},
			featuredImage: {
				type: 'object',
			},
			textColor: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
			},
			overlayColorStart: {
				type: 'string',
				default: 'rgba(0,0,0,0)',
			},
			overlayColorEnd: {
				type: 'string',
				default: 'rgba(0,0,0,0.8)',
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


		edit: function( props ) {
	
			// Pull out the props we'll use
			const { attributes, className, setAttributes, clientId } = props;

			// Pull out specific attributes for clarity below
			const { backgroundImage, featuredImage } = attributes;
			
			const { blockId } = attributes;
			if ( ! blockId ) {
				setAttributes( { blockId: clientId } );
			}

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Block Settings', 'knd' )
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
												  url: image.url,
											  }
										  });
										},

										render: function render(_ref) {
										  var open = _ref.open;
										  return featuredImage
											? el( "div",
												null,
												el( "p",
												  null,
												  el("img", {
													src: featuredImage.url,
												  })
												),
												el( "p",
												  null,
												  el( Button,
													{
													  onClick: function onClick() {
														return setAttributes({
														  featuredImage: ""
														});
													  },
													  className: "button is-small"
													},
													__( 'Remove Image', 'knd' )
												  )
												)
											  )
											: el( "p",
												  null,
												  el( Button,
												{
												  onClick: open,
												  className: "button"
												},
												__( 'Add Image', 'knd' )
											  )
											);
										}

									}),
								),
							 
							), // Featured image
						   
						   el( ColorPaletteControl,
								{
									label: __( 'Text Color', 'knd' ),
									colors: kndBlockColors,
									disableAlpha: false,
									value: props.attributes.textColor,
									
									onChange: function( val ) {
										console.log(val);
										props.setAttributes({ textColor: val });
										console.log(props);
									}
								}
							),
						), // Panel
					   
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
									'Background Image'
								),
								el( 'div',
									{
										className: 'knd-components-heading__help'
									},
									'Recommended size 1600x663px'
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
										  return backgroundImage
											? el( "div",
												null,
												el( "p",
												  null,
												  el("img", {
													src: backgroundImage.url,
												  })
												),
												el( "p",
												  null,
												  el( Button,
													{
													  onClick: function onClick() {
														return setAttributes({
														  backgroundImage: ""
														});
													  },
													  className: "button is-small"
													},
													__( 'Remove Background Image', 'knd' )
												  )
												)
											  )
											: el( "p",
												  null,
												  el( Button,
												{
												  onClick: open,
												  className: "button"
												},
												__( 'Add Background Image', 'knd' )
											  )
											);
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
										console.log(val);
										props.setAttributes({ backgroundColor: val });
										console.log(props);
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


*/
( function( blocks, blockEditor, editor, element, i18n ) {

	const el = element.createElement;

	const { RichText, BlockControls, AlignmentToolbar, useBlockProps } = blockEditor;
	const { registerBlockType } = blocks;

	const { PlainText } = editor;
	
	const icon = el( 'svg', 
		{ 
			width: 24, 
			height: 24 
		},
		el( 'path',
			{ 
				d: "M11 7h6v2h-6zm0 4h6v2h-6zm0 4h6v2h-6zM7 7h2v2H7zm0 4h2v2H7zm0 4h2v2H7zM20.1 3H3.9c-.5 0-.9.4-.9.9v16.2c0 .4.4.9.9.9h16.2c.4 0 .9-.5.9-.9V3.9c0-.5-.5-.9-.9-.9zM19 19H5V5h14v14z" 
			}
		)
	);

	registerBlockType( 'knd/hero-experimental', {
		title: 'Hero Experimental',
		icon: icon,
		category: 'kandinsky',
		keywords: [ 'hero', 'main', 'full' ],
		
		supports: {
			align: [ 'wide', 'full' ],
			className: true
		},

		attributes: {
			heroTitle: {
				type: 'string', // string, boolean, array
				source: 'text', // attribute, text, html, children
				selector: '.knd-block-hero__title',// h2, p, div, .class-name
				default: 'My name is Masha and I’ve had enough', // Default value
				// multiline: 'p',
				//attribute: 'src',
			},
			heroDesc: {
				type: 'string',
				source: 'text',
				selector: '.knd-block-hero__text', // .my-content, div
				default: 'Dubrovino park is the only park around my house where I can take my children for a walk. They are trying to take it away from us. Together we can prove why the park is vital for the city.'
			},
			
			heroButton: {
				type: 'string',
				source: 'text',
				selector: '.knd-button',
				default: 'Start protecting'
			},
			
			heroSecondButton: {
				type: 'string',
				source: 'text',
				selector: '.knd-button knd-button-outline',
				default: 'Start protecting'
			},
			
			backgroundImage: {
				type: 'string',
				default: 'url(http://knd.bootwp.com/test/wp-content/uploads/2020/10/hero_img.jpg)',
			}
		},

		// The "edit" property must be a valid function.
		edit: function( props ) {

			
			console.log(props);

			// when text in RichText component has been changed
			function onChangeContent( newContent ) {
				props.setAttributes( { heroTitle: newContent } );
			}
			
			function onChangeDesc( newContent ) {
				props.setAttributes( { heroDesc: newContent } );
			}
			
			function onChangeButton( newContent ) {
				props.setAttributes( { heroButton: newContent } );
			}

			
			var divStyle = {
			  backgroundImage: "url(https://knd.bootwp.com/gutenberg/wp-content/uploads/2021/03/hero.jpg)"
			};
			
			return (
				el( 'div',
					{
						className: 'knd-block-hero-experimental knd-block knd-block-hero alignfull has-background',
						style: divStyle
					},
					el( 'div',
						{
							className: 'knd-block-hero__inner',
						},
						el( 'div',
							{
								className: 'knd-block-hero__content',
							},
							el( PlainText,
								{
									tagName: 'h1',
									format: 'string',
									className: 'knd-block-hero__title',
									onChange: onChangeContent,
									value: props.attributes.heroTitle,
									//formattingControls: [ 'bold' ]
									allowedFormats: '',
									withoutInteractiveFormatting: true
					
								}
							),
							el( RichText,
								{
									 tagName: 'div',
									format: 'string',
									className: 'knd-block-hero__text',
									onChange: onChangeDesc,
									value: props.attributes.heroDesc,
								}
							),
							el( 'div',
								{
									className: 'knd-block-hero__actions',
								},
								el( RichText,
									{
										 tagName: 'a',
										className: 'knd-button',
										onChange: onChangeButton,
										value: props.attributes.heroButton,
									}
								  ),
							   el( RichText.Content,
									{
										tagName: 'a',
										className: 'knd-button knd-button-outline',
										href: '',
										value: props.attributes.heroSecondButton
									}
								)
							)
						)
					)
				)
			);
		},

		save: function( props ) {
			
		   
			
			 var divStyle = {
			  backgroundImage: "url(https://knd.bootwp.com/gutenberg/wp-content/uploads/2021/03/hero.jpg)"
			};
	 
			return (
				el( 'div', {
					className: 'knd-block-hero-experimental knd-block knd-block-hero alignfull has-background',
					style: divStyle
				},
				   el( 'div', {
						className: 'knd-block-hero__inner',
					},
						el( 'div',
							{
								className: 'knd-block-hero__content',
							},
							el( RichText.Content,
								{
									tagName: 'h1',
									className: 'knd-block-hero__title',
									value: props.attributes.heroTitle
								}
							),
							el( RichText.Content,
								{
									tagName: 'div',
									className: 'knd-block-hero__text',
									value: props.attributes.heroDesc
								}
							),
						   el( 'div',
								{
									className: 'knd-block-hero__actions',
								},
							   el( RichText.Content,
									{
										tagName: 'a',
										className: 'knd-button',
										href: '',
										value: props.attributes.heroButton
									}
								),
								el( RichText.Content,
									{
										tagName: 'a',
										className: 'knd-button knd-button-outline',
										href: '',
										value: props.attributes.heroSecondButton
									}
								)
							)
						)
					)
				)
			);
		},
	} );
} )(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.editor,
	window.wp.element,
	window.wp.i18n,
);

/*

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { registerBlockType } = blocks;

	const { TextControl, SelectControl, RangeControl, ColorPalette, ColorPicker, PanelBody, ToggleControl, BaseControl, Button, Dropdown, Tooltip, Disabled } = components;

	const { withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl, InnerBlocks, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

	const { withInstanceId, withState } = compose;

	const { __ } = i18n;

	const icon = el('svg', 
		{ 
			width: 24, 
			height: 24 
		},
		el( 'path',
			{ 
				d: "M11 7h6v2h-6zm0 4h6v2h-6zm0 4h6v2h-6zM7 7h2v2H7zm0 4h2v2H7zm0 4h2v2H7zM20.1 3H3.9c-.5 0-.9.4-.9.9v16.2c0 .4.4.9.9.9h16.2c.4 0 .9-.5.9-.9V3.9c0-.5-.5-.9-.9-.9zM19 19H5V5h14v14z" 
			}
		)
	);

	registerBlockType( 'knd/hero-experimental', {
		title: __( 'Hero', 'knd' ),
		description: __( 'Projects Block' ),
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
				default: __( 'Heading', 'knd' ),
			},
			backgroundImage: {
				type: 'object',
			},
			featuredImage: {
				type: 'object',
			},
			backgroundColor: {
				type: 'string',
			},
			overlayColorStart: {
				type: 'string',
				default: 'rgba(0,0,0,0)',
			},
			overlayColorEnd: {
				type: 'string',
				default: 'rgba(0,0,0,0.8)',
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
			}
		},


		edit: function( props ) {
	
			console.log(props);
			// Pull out the props we'll use
			const { attributes, className, setAttributes, clientId } = props;

			// Pull out specific attributes for clarity below
			const { backgroundImage, featuredImage } = attributes;
			
			const { blockId } = attributes;
			if ( ! blockId ) {
				setAttributes( { blockId: clientId } );
			}

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Block Settings', 'knd' )
							},

							el( TextControl, {
								label: __( 'Block Heading', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
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
												  url: image.url,
											  }
										  });
										},

										render: function render(_ref) {
										  var open = _ref.open;
										  return featuredImage
											? el( "div",
												null,
												el( "p",
												  null,
												  el("img", {
													src: featuredImage.url,
												  })
												),
												el( "p",
												  null,
												  el( Button,
													{
													  onClick: function onClick() {
														return setAttributes({
														  featuredImage: ""
														});
													  },
													  className: "button is-small"
													},
													__( 'Remove Image', 'knd' )
												  )
												)
											  )
											: el( "p",
												  null,
												  el( Button,
												{
												  onClick: open,
												  className: "button"
												},
												__( 'Add Image', 'knd' )
											  )
											);
										}

									}),
								),
							 
							), // Featured image
						), // Panel
					   
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
									'Background Image'
								),
								el( 'div',
									{
										className: 'knd-components-heading__help'
									},
									'Recommended size 1600x663px'
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
										  return backgroundImage
											? el( "div",
												null,
												el( "p",
												  null,
												  el("img", {
													src: backgroundImage.url,
												  })
												),
												el( "p",
												  null,
												  el( Button,
													{
													  onClick: function onClick() {
														return setAttributes({
														  backgroundImage: ""
														});
													  },
													  className: "button is-small"
													},
													__( 'Remove Background Image', 'knd' )
												  )
												)
											  )
											: el( "p",
												  null,
												  el( Button,
												{
												  onClick: open,
												  className: "button"
												},
												__( 'Add Background Image', 'knd' )
											  )
											);
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
										console.log(val);
										props.setAttributes({ backgroundColor: val });
										console.log(props);
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
							block: 'knd/hero-experimental',
							attributes: props.attributes,
						} ),
					)
				)
			);
		},

		save: function() {
			el(	Disabled,
				null,
				el( ServerSideRender, {
					block: 'knd/news',
					//attributes: props.attributes,
					
				} ),
			)
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


wp.blocks.registerBlockVariation( 'core/heading', { 
	name: 'green-text', 
	title: 'Green Text', 
	description: 'This block has green text. It overrides the default description.',  
	attributes: { 
		content: 'Green Text', 
		textColor: 'vivid-green-cyan' 
	}, 
	icon: 'palmtree', 
	scope: [ 'inserter', 'transform' ] 
} );

*/