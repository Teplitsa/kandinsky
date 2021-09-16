/* Recommendation Block */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, PanelBody, ToggleControl, BaseControl, Button,  Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;

	const { InspectorControls, ColorPaletteControl, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { __ } = i18n;

	const icon = el( 'svg',
		{
			width: 24,
			height: 24
		},
		el( 'path',
			{ 
				d: "M12 4.85409L10.9569 8.06432C10.6222 9.09438 9.66235 9.79178 8.57929 9.79178H5.20384L7.93463 11.7758C8.81085 12.4124 9.1775 13.5408 8.84281 14.5709L7.79974 17.7811L10.5305 15.7971C11.4068 15.1605 12.5932 15.1605 13.4695 15.7971L16.2002 17.7811L15.1572 14.5709C14.8225 13.5408 15.1891 12.4124 16.0654 11.7758L18.7961 9.79178H15.4207C14.3376 9.79178 13.3778 9.09438 13.0431 8.06432L12 4.85409ZM12.9511 2.92704C12.6517 2.00573 11.3483 2.00573 11.0489 2.92704L9.53034 7.6008C9.39647 8.01282 9.01251 8.29178 8.57929 8.29178H3.665C2.69628 8.29178 2.2935 9.5314 3.07722 10.1008L7.05296 12.9893C7.40344 13.244 7.5501 13.6954 7.41623 14.1074L5.89763 18.7811C5.59828 19.7024 6.65276 20.4686 7.43647 19.8992L11.4122 17.0106C11.7627 16.756 12.2373 16.756 12.5878 17.0106L16.5635 19.8992C17.3472 20.4686 18.4017 19.7024 18.1024 18.7811L16.5838 14.1074C16.4499 13.6954 16.5965 13.244 16.947 12.9893L20.9228 10.1008C21.7065 9.5314 21.3037 8.29178 20.335 8.29178H15.4207C14.9875 8.29178 14.6035 8.01282 14.4697 7.6008L12.9511 2.92704Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		)
	);

	var defaultContent = __( 'Your mission should be to summarize everything you do. In a short phrase, put the whole point of your organization and your projects.', 'knd' );

	registerBlockType( 'knd/recommend', {
		title: __( 'Key phrase', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		description: __( 'Text on a colored plate.', 'knd' ),
		keywords: [
			__( 'quote', 'knd' ),
			__( 'recommend', 'knd' ),
			__( 'key', 'knd' ),
			__( 'phrase', 'knd' ),
		],

		attributes: {
			text: {
				type: 'string',
				default: defaultContent,
			},
			textColor: {
				type: 'string',
			},
			backgroundColor: {
				type: 'string',
				default: '',
			},
			className: {
				type: 'string',
			}
		},

		example: {
			attributes: {
				text: defaultContent
			},
			//viewportWidth: 720
		},

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
								title: __( 'Recommendation', 'knd' )
							},

							el( TextareaControl, {
								label: __( 'Text', 'knd' ),
								value: props.attributes.text,
								onChange: ( val ) => {
									props.setAttributes( { text: val } );
								},
							}),

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
									label: __( 'Background Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.backgroundColor,
									onChange: function( val ) {
										props.setAttributes({ backgroundColor: val });
									}
								}
							),
						),
					),

					el( Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/recommend',
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
