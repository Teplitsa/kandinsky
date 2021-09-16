/**
 * People Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, PanelBody, Dashicon, ToggleControl, Disabled } = components;

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
				d: "M8.60166 10.9182C9.99117 10.9182 11.1176 9.79175 11.1176 8.40223C11.1176 7.01272 9.99117 5.88629 8.60166 5.88629C7.21214 5.88629 6.08572 7.01272 6.08572 8.40223C6.08572 9.79175 7.21214 10.9182 8.60166 10.9182ZM8.60166 12.4182C10.8196 12.4182 12.6176 10.6202 12.6176 8.40223C12.6176 6.18429 10.8196 4.38629 8.60166 4.38629C6.38372 4.38629 4.58572 6.18429 4.58572 8.40223C4.58572 10.6202 6.38372 12.4182 8.60166 12.4182Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M16.8913 10.9182C17.6461 10.9182 18.2579 10.3063 18.2579 9.55155C18.2579 8.7968 17.6461 8.18495 16.8913 8.18495C16.1366 8.18495 15.5247 8.7968 15.5247 9.55155C15.5247 10.3063 16.1366 10.9182 16.8913 10.9182ZM16.8913 12.4182C18.4745 12.4182 19.7579 11.1347 19.7579 9.55155C19.7579 7.96837 18.4745 6.68495 16.8913 6.68495C15.3081 6.68495 14.0247 7.96837 14.0247 9.55155C14.0247 11.1347 15.3081 12.4182 16.8913 12.4182Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M5.03815 14.1426C6.00177 13.528 7.26508 13.1483 8.90021 13.1483C10.536 13.1483 11.8131 13.5281 12.7995 14.1366C13.7839 14.7438 14.4364 15.5525 14.8657 16.347C15.2929 17.1374 15.5029 17.9199 15.6072 18.4992C15.6597 18.7905 15.6863 19.035 15.6998 19.21C15.7065 19.2976 15.7101 19.3681 15.7119 19.4188C15.7128 19.4441 15.7133 19.4645 15.7135 19.4796L15.7138 19.4983L15.7138 19.5046L15.7138 19.507C15.7138 19.507 15.7138 19.5088 14.9638 19.5088H15.7138V20.2588H2.42499L2.38709 19.5488L3.13603 19.5088C2.38709 19.5488 2.38709 19.5488 2.38709 19.5488L2.38699 19.5469L2.38688 19.5446L2.38658 19.5383L2.38584 19.5196C2.38529 19.5045 2.3847 19.4841 2.38426 19.4588C2.38338 19.4081 2.38313 19.3376 2.38521 19.2499C2.38939 19.0748 2.40293 18.8298 2.43994 18.5379C2.51347 17.9578 2.68224 17.1713 3.06998 16.3752C3.46011 15.5741 4.07581 14.7565 5.03815 14.1426ZM3.92402 18.7588C3.92532 18.7481 3.92665 18.7373 3.92803 18.7265C3.98784 18.2546 4.12319 17.6384 4.41855 17.032C4.71151 16.4304 5.15788 15.8454 5.8448 15.4073C6.53043 14.97 7.50355 14.6483 8.90023 14.6483C10.2963 14.6483 11.293 14.9697 12.012 15.4132C12.733 15.858 13.2174 16.4519 13.5461 17.0601C13.8754 17.6695 14.0443 18.2869 14.1298 18.7588H3.92402Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{ 
				d: "M13.2024 14.9178C13.9729 14.1396 15.1272 13.6092 16.7847 13.6092C17.9926 13.6092 18.9487 13.8901 19.6944 14.35C20.4381 14.8088 20.9301 15.4194 21.2524 16.016C21.5726 16.6084 21.7291 17.1929 21.8067 17.6237C21.8458 17.8408 21.8657 18.0235 21.8758 18.1551C21.8809 18.2211 21.8836 18.2746 21.885 18.3136C21.8857 18.3332 21.8861 18.3491 21.8863 18.3612L21.8865 18.3765L21.8866 18.3819L21.8866 18.3841C21.8866 18.3841 21.8866 18.3859 21.1366 18.3859H21.8866V19.1359H15.1321V17.6359H20.2766C20.2091 17.3595 20.1019 17.042 19.9328 16.7291C19.7111 16.3189 19.3872 15.9229 18.9069 15.6267C18.4286 15.3317 17.7528 15.1092 16.7847 15.1092C15.4727 15.1092 14.7207 15.5163 14.2683 15.9732L13.2024 14.9178Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		)

	);

	registerBlockType( 'knd/people', {
		title: __( 'Team', 'knd' ), // Команда
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'people', 'knd' ), __( 'user', 'knd' ), __( 'team', 'knd' ), __( 'profile', 'knd' ) ],
		description: __( 'Tile with photos, names and short descriptions in 3-8 columns.', 'knd' ),
		supports: {
			align: [ 'wide', 'full' ],
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'Our Team', 'knd' ),
			},
			postsToShow: {
				type: 'number',
				default: 4
			},
			columns: {
				type: 'number',
				default: 4
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
			nameColor: {
				type: 'string',
			},
			metaColor: {
				type: 'string',
			},
			category: {
				type: 'string',
			}
		},

		example: {
			attributes: {
				postsToShow: 4,
			},
			viewportWidth: 720
		},

		// Register block styles.
		styles: [
			{
				name: 'default',
				label: __( 'Squared', 'knd' ),
				isDefault: true
			},
			{
				name: 'rounded',
				label: __( 'Rounded', 'knd' )
			},
		],

		edit: function( props ) {

			var peopleCats = kndBlock.peopleCats;
			var peopleCatsOptions = [];

			Object.keys(peopleCats).forEach(function (key) {
				peopleCatsOptions.push({value: key, label: peopleCats[key]})
			});

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( 'div',
							{
								className: 'knd-editor-block-card__description knd-editor-block-card__description-alt'
							},

							el( 'a',
								{
									href: kndBlock.getAdminUrl.people,
									target: '_blank',
								},
								__( 'Edit team', 'knd' ),
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
									label: __( 'People to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 4,
									min: 0,
									max: 16,
									onChange: function( val ) {
										props.setAttributes({ postsToShow: val })
									}
								}
							),
							el( RangeControl,
								{
									label: __( 'Columns', 'knd' ),
									value: props.attributes.columns,
									initialPosition: 4,
									min: 3,
									max: 8,
									onChange: function( val ) {
										props.setAttributes({ columns: val })
									}
								}
							),
							el ( SelectControl,
								{
									//multiple: true,
									label: __( 'Category', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { category: val } );
									},
									value: props.attributes.category,
									options: peopleCatsOptions,
								}
							),
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
									label: __( 'Name Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.nameColor,
									onChange: ( val ) => {
										props.setAttributes( { nameColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Description Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.metaColor,
									onChange: ( val ) => {
										props.setAttributes( { metaColor: val } );
									}
								}
							),

						),
					),

					el( Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/people',
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
