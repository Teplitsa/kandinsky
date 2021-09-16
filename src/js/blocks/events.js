/**
 * Events Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data ) {

	if ( ! kndBlock.postTypes.event ) {
		return;
	}

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Button, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { useSelect } = data;

	const { __ } = i18n;

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
			backgroundColor: {
				type: 'string',
			},
			headingColor: {
				type: 'string',
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
									min: -1,
									max: 12,
									onChange: ( val ) => {
										props.setAttributes({ postsToShow: val })
									}
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
