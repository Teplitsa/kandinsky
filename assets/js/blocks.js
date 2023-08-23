
/* Cover Image */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, ColorPalette, PanelBody, BaseControl, Button, Disabled, __experimentalUnitControl } = components;

	const { registerBlockType } = blocks;
	const { InspectorControls, ColorPaletteControl, PanelColorSettings, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

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
			anchor: true,
		},

		attributes: {
			align: {
				type: 'string',
				default: 'wide'
			},
			className: {
				type: 'string',
			},
			anchor: {
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

							el( BaseControl, {
									label: __( 'Background Image', 'knd' ),
								},

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

							el( __experimentalUnitControl, // __experimentalUseCustomUnits
								{
									label: __('Min Height', 'knd'),
									value: props.attributes.minHeight,
									onChange: ( val ) => {
										props.setAttributes( { minHeight: val } );
									},
									labelPosition: 'side',
									units: [
										{
											value: "px",
											label: "px",
										},
									]
								}
							)
						),
					),

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,

							colorSettings: [
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Text Color', 'knd' ),
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes( { textColor: val } );
									}
								},
								{
									label: __( 'Content Background Color', 'knd' ),
									value: props.attributes.contentColor,
									onChange: ( val ) => {
										props.setAttributes( { contentColor: val } );
									}
								},
								{
									label: __( 'Recommendation Color', 'knd' ),
									value: props.attributes.recommendColor,
									onChange: ( val ) => {
										props.setAttributes( { recommendColor: val } );
									}
								},
								{
									label: __( 'Recommendation Background Color', 'knd' ),
									value: props.attributes.recommendBgColor,
									onChange: ( val ) => {
										props.setAttributes( { recommendBgColor: val } );
									}
								}
							]
						}),
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
) );

/**
 * CTA Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { registerBlockType } = blocks;

	const { TextControl, TextareaControl, PanelBody, ToggleControl, BaseControl, Button, Disabled } = components;

	const { InspectorControls, PanelColorSettings, MediaUpload, MediaUploadCheck } = blockEditor;

	const { Fragment } = element;

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
			anchor: true,
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
			anchor: {
				type: 'string',
			},
			heading: {
				type: 'string',
				default: __( 'Заголовок блока. Отразите тут текущую ситуацию.', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'Здесь должен быть призыв к действию', 'knd' ),
			},
			buttonText: {
				type: 'string',
				default: __( 'Действие', 'knd' ),
			},
			buttonUrl: {
				type: 'string',
			},
			buttonTarget: {
				type: 'boolean',
				default: false,
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
				heading: __( 'Заголовок блока. Отразите тут текущую ситуацию.', 'knd' ),
				text: __( 'Здесь должен быть призыв к действию', 'knd' ),
				buttonText: __( 'Действие', 'knd' ),
				featuredImage: {
					url: kndBlock.getImageUrl.ctaFeatured
				}
			},
			viewportWidth: 1200
		},

		edit: function( props ) {

			// Pull out the props we'll use
			const { attributes, setAttributes } = props;

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

							el( BaseControl, {
									label: __( 'Call to action Image', 'knd' ),
								},

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

						// Buttons Panel
						el( PanelBody,
							{
								title: __( 'Button', 'knd' ),
								initialOpen: true
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

							el( ToggleControl,
								{
									label: __('Open in new tab', 'knd'),
									onChange: ( value ) => {
										props.setAttributes( { buttonTarget: value } );
									},
									checked: props.attributes.buttonTarget,
								}
							)
						),
					),

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,

							colorSettings: [
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Title Color', 'knd' ),
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes( { titleColor: val } );
									}
								},
								{
									label: __( 'Text Color', 'knd' ),
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes( { textColor: val } );
									}
								},
								{
									label: __( 'Button Background', 'knd' ),
									value: props.attributes.buttonBackground,
									onChange: ( val ) => {
										props.setAttributes( { buttonBackground: val } );
									}
								},
								{
									label: __( 'Button Color', 'knd' ),
									value: props.attributes.buttonColor,
									onChange: ( val ) => {
										props.setAttributes( { buttonColor: val } );
									}
								},
								{
									label: __( 'Button Hover Background', 'knd' ),
									value: props.attributes.buttonBackgroundHover,
									onChange: ( val ) => {
										props.setAttributes( { buttonBackgroundHover: val } );
									}
								},
								{
									label: __( 'Button Hover Color', 'knd' ),
									value: props.attributes.buttonColorHover,
									onChange: ( val ) => {
										props.setAttributes( { buttonColorHover: val } );
									}
								},
							]
						}),
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
) );

/**
 * Events Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	if ( ! kndBlock.postTypes.event ) {
		return;
	}

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, Button, FormTokenField, Dashicon, PanelBody, Disabled } = components;

	const { registerBlockType } = blocks;

	const { InspectorControls, PanelColorSettings } = blockEditor;

	const { useState, Fragment } = element;

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
			queryInclude: {
				type: 'array',
				default: [],
			}
		},

		example: {
			attributes: {
				postsToShow: 2,
				backgroundColor: '#f0f0f0',
				queryWhat: 'all'
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

			const queryPostsControl = ( props ) => {

				var events = [];
				var getEvents = kndBlock.getEvents;

				if ( getEvents ) {
					getEvents.map((event) => {
						events.push( event.post_title );
					} )
				}

				const [ selectedEvents, setSelectedEvents ] = useState( props.attributes.queryInclude );

				return el ( FormTokenField,
					{
						label: __( 'Include events', 'knd' ),
						value: selectedEvents,
						suggestions: events,
						__experimentalExpandOnFocus: true,
						onChange: ( val ) => {
							setSelectedEvents( val ),
							props.setAttributes( { queryInclude: val } );
						},
					}
				);
			};

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

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' )
							},

							el( 'div',
								{
									className: 'knd-editor-block-panel__description'
								},

								el( 'a',
									{
										href: kndBlock.getAdminUrl.news,
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
								initialOpen: false
							},

							headingFields

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
										{ value: 'meta_value', label: __( 'Event Start Date', 'knd' ) },
										{ value: 'post__in', label: __( 'Included events', 'knd' ) },
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

							queryPostsControl(props),

							el( SelectControl,
								{
									label: __( 'What events to show', 'knd' ),
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

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,

							colorSettings: [
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Heading Color', 'knd' ),
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes( { headingColor: val } );
									}
								},
								{
									label: __( 'Links Color', 'knd' ),
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								}
							]
						}),
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
) );

/**
 * Hero Block
 */

( function( blocks, blockEditor, element, components, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, ColorPalette, ColorPicker, PanelBody, BaseControl, Button, ButtonGroup, Disabled, __experimentalUnitControl } = components;

	const { registerBlockType } = blocks;

	const { InspectorControls, ColorPaletteControl, PanelColorSettings, MediaUpload, MediaUploadCheck } = blockEditor;

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
			anchor: true,
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'Заголовок проекта. Суть и основная идея.', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'Описание. Два-три коротких предложения, раскрывающие суть организации. Заголовок привлекает внимание, описание помогает понять как можно поучаствовать, кнопка реализует действие.', 'knd' ),
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
			headingLevel: {
				type: 'string',
				default: 'h1',
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
			anchor: {
				type: 'string',
			},
			blockId: {
				type: 'string',
			},
			button: {
				type: 'string',
				default: __( 'Button text', 'knd' ),
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
			minHeight: {
				type: 'string',
			},
		},

		example: {
			attributes: {
				heading: __( 'Заголовок проекта. Суть и основная идея.', 'knd' ),
				text: __( 'Описание. Два-три коротких предложения, раскрывающие суть организации. Заголовок привлекает внимание, описание помогает понять как можно поучаствовать, кнопка реализует действие.', 'knd' ),
				button: __( 'Button text', 'knd' ),
				featuredImage: {
					url: kndBlock.getImageUrl.heroFeatured,
				},
				backgroundColor: '#f7f8f8',
				overlayColorStart: 'rgba(255,255,255,0)',
				overlayColorEnd:  'rgba(255,255,255,0)',
				minHeight: '560px',
			},
			viewportWidth: 1200
		},

		edit: ( props ) => {

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

							el( __experimentalUnitControl,
								{
									label: __('Min Height', 'knd'),
									value: props.attributes.minHeight,
									onChange: ( val ) => {
										props.setAttributes( { minHeight: val } );
									},
									labelPosition: 'side',
									units: [
										{
											value: "px",
											label: "px",
										},
										{
											value: "vh",
											label: "vh",
										},
									]
								}
							)
						), // Panel

						// Images Panel
						el( PanelBody,
							{
								title: __( 'Images', 'knd' ),
								initialOpen: true
							},

							el( BaseControl, {
									label: __( 'Call to action Image', 'knd' ),
								},

								el( 'div',
									{
										className: 'knd-components-heading__label'
									},
									__( 'Displayed on the right side of the Call to action text', 'knd' )
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

							el( BaseControl, {
									label: __( 'Background Image', 'knd' ),
								},

								el( 'div',
									{
										className: 'knd-components-heading__label'
									},
									__( 'Recommended size 1600x663px', 'knd' )
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
						),

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
						),

						// Overlay Panel
						el( PanelBody,
							{
								title: __( 'Overlay', 'knd' ),
								initialOpen: false
							},

							el( 'div',
								{
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

							el( 'div',
								{
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

						el( PanelBody,
							{
								title: __( 'Typography', 'knd' ),
								initialOpen: false,
							},

							el( BaseControl,
								{
									label: __( 'Heading level', 'knd'),
									className: 'knd-components-base-control',
								},

								el( BaseControl,{},

									el( ButtonGroup, null,

										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h1'),
											},
											el ( 'strong', {},
												'H1',
											),
										),
										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h2'),
											},
											el ( 'strong', {},
												'H2',
											),
										),
										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h3'),
											},
											el ( 'strong', {},
												'H3',
											),
										),
										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h4'),
											},
											el ( 'strong', {},
												'H4',
											),
										),
										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h5'),
											},
											el ( 'strong', {},
												'H5',
											),
										),
										el( Button,
											{
												onClick: function( e ) {
													props.setAttributes({ headingLevel: e.target.innerText.toLowerCase() });
												},
												isPrimary: (props.attributes.headingLevel === 'h6'),
											},
											el ( 'strong', {},
												'H6',
											),
										),

									),
								),

							),
						),
					),


					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,

							colorSettings: [
								{
									label: __( 'Text Color', 'knd' ),
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes( { textColor: val } );
									}
								},
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Button Background', 'knd' ),
									value: props.attributes.buttonBackground,
									onChange: ( val ) => {
										props.setAttributes( { buttonBackground: val } );
									}
								},
								{
									label: __( 'Button Color', 'knd' ),
									value: props.attributes.buttonColor,
									onChange: ( val ) => {
										props.setAttributes( { buttonColor: val } );
									}
								},
								{
									label: __( 'Button Hover Background', 'knd' ),
									value: props.attributes.buttonBackgroundHover,
									onChange: ( val ) => {
										props.setAttributes( { buttonBackgroundHover: val } );
									}
								},
								{
									label: __( 'Button Hover Color', 'knd' ),
									value: props.attributes.buttonColorHover,
									onChange: ( val ) => {
										props.setAttributes( { buttonColorHover: val } );
									}
								}
							]
						}),
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
			return null;
		}

	} );

}(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.element,
	window.wp.components,
	window.wp.i18n,
	window.wp.serverSideRender,
) );

/* Hint Block */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, TextareaControl, SelectControl, RangeControl, ColorPalette, PanelBody, ToggleControl, BaseControl, Button,  Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName } = blocks;

	const { InspectorControls, ColorPaletteControl, MediaUpload, MediaUploadCheck, InnerBlocks, useBlockProps } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { __ } = i18n;

	registerBlockType( 'knd/hint', {
		title: __( 'Hint', 'knd' ), // Подсказка
		icon: 'lightbulb',
		category: 'kandinsky',
		keywords: [
			__( 'hint', 'knd' ),
		],

		supports: {
			anchor: true,
		},

		attributes: {
			direction: {
				type: 'string',
				default: 'top',
			},
			className: {
				type: 'string',
			},
			anchor: {
				type: 'string',
			}
		},

		// Register block styles.
		styles: [
			{
				name: 'default',
				label: __( 'Up Arrow', 'knd' ),
				isDefault: true
			},
			{
				name: 'bottom',
				label: __( 'Down Arrow', 'knd' )
			},
		],

		example: {
			innerBlocks: [
				{
					name: 'core/paragraph',
					attributes: {
						/* translators: example text. */
						content: __(
							'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent et eros eu felis.'
						),
						color: {
							background: '#fdda73',
						}
					},
				},
			],
			viewportWidth: 720
		},


		edit: function( props ) {
			
			// Pull out the props we'll use
			const { attributes, className, setAttributes, clientId } = props;

			var blockProps = useBlockProps( {
				className: 'knd-block-hint ' + className,
			} );

			return (
				el( Fragment, {},

					el( 'div',
						blockProps,
						el( 'div',
							{
								className: 'knd-block-hint__inner',
							},

							el(
								InnerBlocks,
								{
									allowedBlocks : [ 'core/paragraph' ],
								}
							)
						)
					)
				)
			);
		},

		save: function () {
			var blockProps = useBlockProps.save();

			return el( 'div',
				Object.assign( blockProps,
					{
						className: 'knd-block-hint'
					}
				),
				el( 'div',
					{
						className: 'knd-block-hint__inner',
					},
					el( InnerBlocks.Content )
				)
			);
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
			anchor: true,
		},

		attributes: {
			align: {
				type: 'string',
				default: 'full'
			},
			heading: {
				type: 'string',
				default: __( 'Название организации/проекта или заголовок блока: «О нас», «О Проекте»', 'knd' ),
			},
			text: {
				type: 'string',
				default: __( 'Выразите тут цель, миссию или девиз организации. «Своей целью мы видим…», «Мы помогаем…»', 'knd' ),
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
			anchor: {
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
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes({ backgroundColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Heading Color', 'knd' ),
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes({ headingColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Second Heading Color', 'knd' ),
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes({ titleColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Heading Color', 'knd' ),
									value: props.attributes.headingsColor,
									onChange: ( val ) => {
										props.setAttributes({ headingsColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Text Color', 'knd' ),
									value: props.attributes.textColor,
									onChange: ( val ) => {
										props.setAttributes({ textColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Color', 'knd' ),
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes({ linkColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Color Hover', 'knd' ),
									value: props.attributes.linkHoverColor,
									onChange: ( val ) => {
										props.setAttributes({ linkHoverColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Column Link Underline Color', 'knd' ),
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
) );

/**
 * News Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data, date ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { dateI18n } = date;

	const { TextControl, SelectControl, RangeControl, Dashicon, PanelBody, ToggleControl, Button, IconButton, Disabled, BaseControl, __experimentalUnitControl, __experimentalDivider } = components;

	const { registerBlockType } = blocks;

	const { InspectorControls, useBlockProps, PanelColorSettings, BlockControls,__experimentalBlockAlignmentMatrixControl } = blockEditor;

	const { Fragment } = element;

	const { useSelect } = data;

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
			anchor: true,
			// defaultStylePicker: false,
		},

		attributes: {
			layout: {
				type: 'string',
				default: 'type-1',
			},
			heading: {
				type: 'string',
				//default: __( 'News', 'knd' ),
			},
			headingLink: {
				type: 'string',
			},
			titleAlign: {
				type: 'boolean',
				default: false,
			},
			postsToShow: {
				type: 'number',
				default: 3
			},
			columns: {
				type: 'number',
				default: 3
			},
			radius: {
				type: 'number',
				default: 5
			},
			align: {
				type: 'string',
				default: 'full',
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
			cardBackroundColor: {
				type: 'string',
			},
			overlayColor: {
				type: 'string',
			},
			overlayHoverColor: {
				type: 'string',
			},
			titleColor: {
				type: 'string',
			},
			titleHoverColor: {
				type: 'string',
			},
			linkColor: {
				type: 'string',
			},
			linkHoverColor: {
				type: 'string',
			},
			linkHoverBackground: {
				type: 'string',
			},
			metaColor: {
				type: 'string',
			},
			excerptColor: {
				type: 'string',
			},
			dateColor: {
				type: 'string',
			},
			headingLinks: {
				type: "array",
				default: []
			},
			hiddenReload: {
				type: 'string',
			},
			queryOrder: {
				type: 'string',
				default: 'date/desc'
			},
			queryOffset: {
				type: 'string',
				default: '',
			},
			queryCategory: {
				type: 'string',
				default: 0,
			},

			thumbnail: {
				type: 'boolean',
				default: true,
			},
			imageOrientation: {
				type: 'string',
				default: 'landscape',
			},
			imageSize: {
				type: 'string',
				default: 'post-thumbnail',
			},
			imageWidth: {
				type: 'string',
				default: 'half',
			},
			imagePosition: {
				type: 'string',
				default: 'left',
			},
			date: {
				type: 'boolean',
				default: true,
			},
			author: {
				type: 'boolean',
				default: false,
			},
			avatar: {
				type: 'boolean',
				default: false,
			},
			category: {
				type: 'boolean',
				default: true,
			},
			title: {
				type: 'boolean',
				default: true,
			},
			excerpt: {
				type: 'boolean',
				default: false,
			},
			excerptLength: {
				type: 'number',
				default: 30,
			},
			excerptLength: {
				type: 'number',
				default: 30,
			},
			titleFontSize: {
				type: 'string',
				default: '18px',
			},
			titleFontSizeMobile: {
				type: 'string',
				default: '18px',
			},
			excerptFontSize: {
				type: 'string',
				default: '14px',
			},
			excerptFontSizeMobile: {
				type: 'string',
				default: '14px',
			},
			titleFontWeight: {
				type: 'string',
				default: 'bold',
			},
			dateFormat: {
				type: 'string',
				default: 'd.m.Y',
			},
			dateFormat: {
				type: 'string',
				default: 'd.m.Y',
			},
			alignment: {
				type: 'string',
				default: '',
			},
			paddingTop: {
				type: 'boolean',
				default: true,
			},
			paddingBottom: {
				type: 'boolean',
				default: true,
			},
		},

		example: {
			attributes: {
				postsToShow: 3,
				backgroundColor: '#f0f0f0'
			},
			viewportWidth: 720
		},

		edit: ( props ) => {

			let categoryOptions = function(){
				// Get Terms
				var getTerms = useSelect( ( select, props ) => {
					return select('core').getEntityRecords('taxonomy', 'category', {per_page: -1});
				}, [] );

				var categories = [
					{ value: 0, label: __( 'All Categories', 'knd' ) },
				];

				if ( getTerms ) {
					getTerms.map((term) => {
						categories.push({ value: term.id, label: term.name } );
					} );
				}
				return categories;
			}

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

			const fontSizes = [
				{
					name: __( 'Small' ),
					slug: 'small',
					size: 14,
				},
				{
					name: __( 'Normal' ),
					slug: 'normal',
					size: 18,
				},
				{
					name: __( 'Medium' ),
					slug: 'medium',
					size: 26,
				},
				{
					name: __( 'Large' ),
					slug: 'large',
					size: 36,
				},
			];

			function ToggleControlAvatar( props ) {
				if ( ! props.attributes.author ) {
					return;
				}

				return el( ToggleControl,
					{
						label: __( 'Показать аватар автора', 'knd' ), // Display author avatar
						checked: props.attributes.avatar,
						onChange: ( val ) => {
							props.setAttributes( { avatar: val } );
						},
					}
				)
			}

			function RangeControlExcerptLength(props) {
				if ( ! props.attributes.excerpt ) {
					return;
				}

				return el( RangeControl,
					{
						label: __('Max number of words in excerpt','knd'),
						value: props.attributes.excerptLength,
						initialPosition: 30,
						min: 5,
						max: 60,
						onChange: ( val ) => {
							props.setAttributes( { excerptLength: val } );
						}
					}
				)
			}

			function dateFromatToggleControl(props) {
				if ( ! props.attributes.date ) {
					return;
				}

				return el( SelectControl,
					{
						label: __( 'Формат даты', 'knd' ), // Date format
						value: props.attributes.dateFormat,
						onChange: ( val ) => {
							props.setAttributes( { dateFormat: val } );
						},
						options: [
							{
								label: dateI18n( 'j F Y' ),
								value: 'j F Y'
							},
							{
								label: dateI18n( 'd.m.Y' ),
								value: 'd.m.Y'
							},
							{
								label: __( 'Относительная дата публикации', 'knd' ), // Relative publication date
								value: 'relative'
							},
						],
					}
				)
			}

			function getImageSizes() {
				var imageSizes = [];
				Object.entries( kndBlock.imageSizes ).forEach( ( [ key, value ] ) => {
					imageSizes.push({ label: value, value: key });
				});
				return imageSizes;
			}

			function kndBlockControls(props) {
				if ( props.attributes.layout !== 'type-3' ) {
					return;
				}
				return el( BlockControls,
					{ key: 'controls' },
					el( __experimentalBlockAlignmentMatrixControl, {
						label: __('Change content position','knd'),
						value: props.attributes.alignment,
						onChange: ( val ) => {
							props.setAttributes( { alignment: val } );
						},
					} )
				)
			}

			function imageWidthControl(props) {
				if ( props.attributes.layout !== 'type-2' ) {
					return;
				}

				return el( SelectControl,
					{
						label: __( 'Image Width', 'knd' ),
						value: props.attributes.imageWidth,
						onChange: ( val ) => {
							props.setAttributes( { imageWidth: val } );
						},
						options: [
							{
								label: __( '1 / 3', 'knd' ),
								value: 'one-third'
							},
							{
								label: __( '1 / 2', 'knd' ),
								value: 'half'
							},
							{
								label: __( '2 / 3', 'knd' ),
								value: 'two-thirds'
							},
						],
					}
				)
			}

			function imagePositionControl(props) {
				if ( props.attributes.layout !== 'type-2' ) {
					return;
				}

				return el( SelectControl,
					{
						label: __( 'Image Position', 'knd' ),
						value: props.attributes.imagePosition,
						onChange: ( val ) => {
							props.setAttributes( { imagePosition: val } );
						},
						options: [
							{
								label: __( 'Align Left', 'knd' ),
								value: 'left'
							},
							{
								label: __( 'Align Right', 'knd' ),
								value: 'right'
							},
						],
					}
				)
			}

			function rangeControlPostsToShow(props) {
				return el( RangeControl,
					{
						label: __('Posts to show','knd'),
						value: props.attributes.postsToShow,
						initialPosition: 3,
						min: 1,
						max: 30,
						onChange: ( val ) => {
							props.setAttributes( { postsToShow: val } );
						}
					}
				)
			}

			function rangeControlColumns(props) {
				return el( RangeControl,
					{
						label: __( 'Columns', 'knd' ),
						value: props.attributes.columns,
						initialPosition: 3,
						min: 1,
						max: 4,
						onChange: function( val ) {
							props.setAttributes({ columns: val });
						}
					}
				)
			}

			return (
				el( Fragment, {},

					kndBlockControls(props),

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelBody,
							{
								title: __( 'Typography', 'knd' ),
								className: 'knd-components-panel__body',
								initialOpen: false
							},

							el( __experimentalUnitControl,
								{
									label: __('Размер шрифта заголовка (Desktop)', 'knd'), // Title Font Size (Desktop)
									value: props.attributes.titleFontSize,
									onChange: ( val ) => {
										props.setAttributes( { titleFontSize: val } );
									},
									//labelPosition: 'side',
									units: [
										{
											value: 'px',
											label: 'px',
										},
										{
											value: 'rem',
											label: 'rem',
										},
									]
								}
							),

							el( __experimentalUnitControl,
								{
									label: __('Размер шрифта заголовка (Mobile)', 'knd'), // Title Font Size (Mobile)
									value: props.attributes.titleFontSizeMobile,
									onChange: ( val ) => {
										props.setAttributes( { titleFontSizeMobile: val } );
									},
									//labelPosition: 'side',
									units: [
										{
											value: 'px',
											label: 'px',
										},
										{
											value: 'rem',
											label: 'rem',
										},
									]
								}
							),

							el( SelectControl,
								{
									label: __( 'Жирность шрифта заголовка', 'knd' ), // Title Font Weight
									value: props.attributes.titleFontWeight,
									onChange: ( val ) => {
										props.setAttributes( { titleFontWeight: val } );
									},
									options: [
										{
											label: __( 'Regular', 'knd' ),
											value: 'regular'
										},
										{
											label: __( 'Medium', 'knd' ),
											value: 'medium'
										},
										{
											label: __( 'Semi Bold', 'knd' ),
											value: 'semibold'
										},
										{
											label: __( 'Bold', 'knd' ),
											value: 'bold'
										},
									],
								}
							),

							el( __experimentalDivider ),

							el( __experimentalUnitControl,
								{
									label: __('Размер шрифта цитаты (Desktop)', 'knd'), // Excerpt Font Size (Desktop)
									value: props.attributes.excerptFontSize,
									onChange: ( val ) => {
										props.setAttributes( { excerptFontSize: val } );
									},
									units: [
										{
											value: 'px',
											label: 'px',
										},
										{
											value: 'rem',
											label: 'rem',
										},
									]
								}
							),

							el( __experimentalUnitControl,
								{
									label: __('Размер шрифта цитаты (Mobile)', 'knd'), // Excerpt Font Size (Mobile)
									value: props.attributes.excerptFontSizeMobile,
									onChange: ( val ) => {
										props.setAttributes( { excerptFontSizeMobile: val } );
									},
									units: [
										{
											value: 'px',
											label: 'px',
										},
										{
											value: 'rem',
											label: 'rem',
										},
									]
								}
							),
						),

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,
							colorSettings: [
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Heading Color', 'knd' ),
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes( { headingColor: val } );
									}
								},
								{
									label: __( 'Цвет фона карточки', 'knd' ), // Card Background Color
									value: props.attributes.cardBackroundColor,
									onChange: ( val ) => {
										props.setAttributes( { cardBackroundColor: val } );
									}
								},
								{
									label: __( 'Цвет наложения', 'knd' ), // Overlay Color
									value: props.attributes.overlayColor,
									onChange: ( val ) => {
										props.setAttributes( { overlayColor: val } );
									}
								},
								{
									label: __( 'Цвет наложения при наведении', 'knd' ), // Overlay Hover Color
									value: props.attributes.overlayHoverColor,
									onChange: ( val ) => {
										props.setAttributes( { overlayHoverColor: val } );
									}
								},


								{
									label: __( 'Цвет названия', 'knd' ), // Post Title Color
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes( { titleColor: val } );
									}
								},
								{
									label: __( 'Цвет названия при наведении', 'knd' ), // Post Title Hover Color
									value: props.attributes.titleHoverColor,
									onChange: ( val ) => {
										props.setAttributes( { titleHoverColor: val } );
									}
								},

								{
									label: __( 'Цвет цитаты', 'knd' ), // Excerpt Color
									value: props.attributes.excerptColor,
									onChange: ( val ) => {
										props.setAttributes( { excerptColor: val } );
									}
								},
								{
									label: __( 'Цвет ссылок', 'knd' ), // Links Color
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								},
								{
									label: __( 'Цвет ссылок при наведении', 'knd' ), // Links Hover Color
									value: props.attributes.linkHoverColor,
									onChange: ( val ) => {
										props.setAttributes( { linkHoverColor: val } );
									}
								},
								{
									label: __( 'Цвет фона ссылок при наведении', 'knd' ), // Links Hover Background Color
									value: props.attributes.linkHoverBackground,
									onChange: ( val ) => {
										props.setAttributes( { linkHoverBackground: val } );
									}
								},
								{
									label: __( 'Date Color', 'knd' ),
									value: props.attributes.dateColor,
									onChange: ( val ) => {
										props.setAttributes( { dateColor: val } );
									}
								}
							]
						}),
					),

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' ),
							},

							el( 'div',
								{
									className: 'knd-editor-block-panel__description'
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

							el( SelectControl,
								{
									label: __( 'Layout', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { layout: val } );
									},
									value: props.attributes.layout,
									options: [
										// {
										// 	label: __( 'Type 0', 'knd' ),
										// 	value: 'type-0'
										// },
										{
											label: __( 'Изображение над текстом', 'knd' ), // Type 1
											value: 'type-1'
										},
										{
											label: __( 'Изображение рядом с текстом', 'knd' ), // Type 2
											value: 'type-2'
										},
										{
											label: __( 'Tекст поверх изображения', 'knd' ), // Type 3
											value: 'type-3'
										},
									],

								}
							),

							rangeControlPostsToShow(props),

							rangeControlColumns(props),

							el( ToggleControl,
								{
									label: __( 'Отступ сверху', 'knd' ), // Padding Top
									checked: props.attributes.paddingTop,
									onChange: ( val ) => {
										props.setAttributes( { paddingTop: val } );
									},
								}
							),

							el( ToggleControl,
								{
									label: __( 'Отступ снизу', 'knd' ), // Padding Bottom
									checked: props.attributes.paddingBottom,
									onChange: ( val ) => {
										props.setAttributes( { paddingBottom: val } );
									},
								}
							),

						),

						el( PanelBody,
							{
								title: __( 'Heading', 'knd' ),
								className: 'knd-components-panel__body',
								initialOpen: false
							},

							el( TextControl, {
								label: __( 'Текст заголовка', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
								},
							}),

							el( TextControl, {
								label: __( 'Ссылка заголовка', 'knd' ),
								value: props.attributes.headingLink,
								onChange: ( val ) => {
									props.setAttributes( { headingLink: val } );
								},
							}),

							el( ToggleControl,
								{
									label: __( 'Выравнивать по центру', 'knd' ), // Сenter align title
									checked: props.attributes.titleAlign,
									onChange: ( val ) => {
										props.setAttributes( { titleAlign: val } );
									},
								}
							),

							el( __experimentalDivider ),

							el ( BaseControl, {
								label: __( 'Heading Links', 'knd' ),
								},
								headingFields()
							),

						),

						el( PanelBody,
							{
								title: __( 'Настройки изображения', 'knd' ), // Featured image settings
								//className: 'knd-components-panel__body',
								initialOpen: false
							},

							el( ToggleControl,
								{
									label: __( 'Показывать изображение', 'knd' ), // Display featured image
									checked: props.attributes.thumbnail,
									onChange: ( val ) => {
										props.setAttributes( { thumbnail: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'Ориентация изображения', 'knd' ), // Image Orientation
									value: props.attributes.imageOrientation,
									onChange: ( val ) => {
										props.setAttributes( { imageOrientation: val } );
									},
									options: [
										{
											label: __( 'Оригинал', 'knd' ), // Original
											value: 'original'
										},
										{
											label: __( 'Альбомный 16:9', 'knd' ), // Landscape 16:9
											value: 'landscape-16-9'
										},
										{
											label: __( 'Альбомный 4:3', 'knd' ), // Landscape 4:3
											value: 'landscape'
										},
										{
											label: __( 'Квадтарный', 'knd' ), // Square
											value: 'square'
										},
										{
											label: __( 'Портретный', 'knd' ), // Portrait
											value: 'portrait'
										},
									],
								}
							),

							el( SelectControl,
								{
									label: __( 'Размер изображения', 'knd' ), // Image Size
									value: props.attributes.imageSize,
									onChange: ( val ) => {
										props.setAttributes( { imageSize: val } );
									},
									options: getImageSizes(),
									
								}
							),

							imageWidthControl(props),

							imagePositionControl(props),

							el( RangeControl,
								{
									label: __( 'Округление углов', 'knd' ), // Image Border Radius
									value: props.attributes.radius,
									initialPosition: 5,
									min: 0,
									max: 25,
									onChange: ( val ) => {
										props.setAttributes({ radius: val })
									}
								}
							),
						),

						el( PanelBody,
							{
								title: __( 'Метаполя', 'knd' ), //Post Meta
								className: 'knd-components-panel__body',
								initialOpen: false
							},

							el( ToggleControl,
								{
									label: __( 'Показать автора', 'knd' ), // Display author
									checked: props.attributes.author,
									onChange: ( val ) => {
										props.setAttributes( { author: val } );
									},
								}
							),

							ToggleControlAvatar(props),

							el( ToggleControl,
								{
									label: __( 'Показать категорию', 'knd' ), // Display post category
									checked: props.attributes.category,
									onChange: ( val ) => {
										props.setAttributes( { category: val } );
									},
								}
							),

							el( ToggleControl,
								{
									label: __( 'Показать название статьи', 'knd' ), // Display post title
									checked: props.attributes.title,
									onChange: ( val ) => {
										props.setAttributes( { title: val } );
									},
								}
							),

							el( ToggleControl,
								{
									label: __( 'Показать отрывок', 'knd' ), // Display post excerpt
									checked: props.attributes.excerpt,
									onChange: ( val ) => {
										props.setAttributes( { excerpt: val } );
									},
								}
							),

							RangeControlExcerptLength(props),

							el( ToggleControl,
								{
									label: __( 'Показать дату', 'knd' ), // Display date
									checked: props.attributes.date,
									onChange: ( val ) => {
										props.setAttributes( { date: val } );
									},
								}
							),

							dateFromatToggleControl(props),
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
										{ value: 'date/desc', label: __( 'Newest to oldest' ) },
										{ value: 'date/asc', label: __( 'Oldest to newest' ) },
										{ value: 'title/asc', label: __( 'A → Z' ) },
										{ value: 'title/desc', label: __( 'Z → A' ) },
									],
									value: props.attributes.queryOrder,
									onChange: ( val ) => {
										props.setAttributes( { queryOrder: val } );
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
									help: __( 'Number of posts to skip', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { queryOffset: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'Select category', 'knd' ),
									options : categoryOptions(),
									value: props.attributes.queryCategory,
									onChange: ( val ) => {
										props.setAttributes( { queryCategory: val } );
									},
								},
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
	window.wp.date,
) );

/**
 * Partners Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data, hooks ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl } = blockEditor;

	const { Fragment } = element;

	const { useSelect } = data;

	const { withState } = compose;

	const { doAction } = hooks;

	const { __ } = i18n;

	const icon = el('svg',
		{ 
			width: 24, 
			height: 24 
		},
		el( 'path',
			{
				d: "M9.77379 5.28551L10.2305 6.71429L10.0497 6.77176C9.93479 6.80819 9.77017 6.86019 9.57113 6.92256C9.17324 7.04725 8.63683 7.21371 8.0841 7.38035C7.53251 7.54665 6.95967 7.71464 6.49058 7.84162C6.25669 7.90493 6.042 7.95984 5.86561 7.99949C5.71886 8.03247 5.51783 8.07467 5.35803 8.07467C5.04919 8.07467 4.27546 7.9846 3.64887 7.90659C3.51148 7.88949 3.37691 7.8724 3.25 7.85606V14.3715C3.30472 14.3778 3.36109 14.3851 3.41857 14.3933C3.66246 14.4281 3.94486 14.483 4.21373 14.5727C4.4697 14.658 4.78517 14.7978 5.03033 15.0429C5.03033 15.0429 5.03144 15.044 5.03395 15.0464L5.04529 15.0569C5.05529 15.066 5.06859 15.0777 5.08561 15.0924C5.11985 15.1219 5.16443 15.1591 5.21967 15.204C5.33011 15.2939 5.47431 15.4076 5.6465 15.5409C5.99045 15.8073 6.43519 16.1435 6.9224 16.5079C7.89621 17.2362 9.02997 18.0696 9.84527 18.6666C10.1309 18.8756 10.5318 18.8217 10.7533 18.5408C10.9877 18.2434 10.9266 17.8106 10.619 17.5899L10.3075 17.3663L11.182 16.1476L11.4935 16.3712C12.4958 17.0904 12.6951 18.5008 11.9312 19.4695C11.212 20.3816 9.89943 20.5652 8.95918 19.8769C8.14263 19.279 7.00401 18.442 6.02403 17.7091C5.53435 17.3429 5.08185 17.0009 4.72809 16.7269C4.39487 16.4689 4.10056 16.2345 3.96967 16.1036V16.1036C3.96967 16.1036 3.95517 16.0902 3.91493 16.0686C3.87291 16.0459 3.81489 16.0209 3.73939 15.9957C3.58639 15.9447 3.40004 15.9059 3.20643 15.8782C3.01583 15.851 2.83597 15.837 2.70277 15.83C2.63674 15.8265 2.58358 15.8248 2.54799 15.824L2.50873 15.8233L2.50031 15.8233L2.49929 15.8232H1.75V6.13967L2.6023 6.25702L2.7031 6.27076C2.768 6.27957 2.86141 6.29216 2.97512 6.30728C3.20269 6.33754 3.51087 6.37784 3.83416 6.41808C4.50017 6.50099 5.14478 6.57223 5.34797 6.57461L5.34631 6.57436C5.34631 6.57436 5.39924 6.56689 5.53667 6.536C5.68262 6.5032 5.87372 6.45461 6.09866 6.39372C6.54722 6.2723 7.10417 6.1091 7.65112 5.9442C8.19692 5.77965 8.7278 5.61491 9.12257 5.4912C9.31986 5.42938 9.48291 5.37787 9.59654 5.34186L9.77379 5.28551ZM5.35803 6.57467C5.35481 6.57467 5.35146 6.57465 5.34797 6.57461Z" 
			}
		),
		el( 'path',
			{
				d: "M11.745 6.28573C12.122 6.30472 12.5361 6.3654 12.9199 6.43861C13.7033 6.58807 14.4666 6.85379 15.2904 7.14121C15.8466 7.33524 16.5086 7.56106 17.0732 7.73907C17.3547 7.82783 17.6194 7.90703 17.8379 7.96479C18.0189 8.01264 18.2684 8.07467 18.4608 8.07467C18.7697 8.07467 19.5434 7.98459 20.17 7.90659C20.3074 7.88949 20.442 7.8724 20.5689 7.85606V13.691C20.4148 13.6837 20.2476 13.6783 20.0757 13.6769C19.7301 13.6741 19.3414 13.6871 18.9916 13.7407C18.8166 13.7675 18.6322 13.8073 18.4592 13.8693C18.2937 13.9286 18.0869 14.0264 17.915 14.1983L18.9756 15.259C18.9529 15.2817 18.9357 15.2927 18.9309 15.2956C18.9345 15.2937 18.9452 15.2885 18.9655 15.2813C19.0179 15.2625 19.101 15.2414 19.2188 15.2234C19.4548 15.1872 19.7531 15.1743 20.0635 15.1768C20.3691 15.1793 20.6634 15.1964 20.883 15.2132C20.9922 15.2216 21.0815 15.2298 21.1429 15.2358C21.1736 15.2388 21.1972 15.2412 21.2126 15.2429L21.2297 15.2447L21.2333 15.2451L22.0689 15.3406V6.13967L21.2166 6.25702L21.1158 6.27076C21.0509 6.27956 20.9575 6.29216 20.8438 6.30728C20.6162 6.33754 20.308 6.37784 19.9847 6.41808C19.3131 6.50169 18.6632 6.57344 18.4658 6.57465C18.4769 6.57455 18.4736 6.57387 18.4515 6.56933C18.4192 6.5627 18.347 6.54784 18.2212 6.5146C18.0357 6.46556 17.7965 6.39432 17.5242 6.30848C16.9813 6.1373 16.336 5.91732 15.7846 5.72493L15.7463 5.71157C14.9524 5.43456 14.0972 5.13616 13.2009 4.96518C12.7801 4.88489 12.292 4.81137 11.8204 4.78763C11.3667 4.76478 10.849 4.78287 10.4119 4.94023C9.95034 5.10639 9.47311 5.49707 9.07909 5.86588C8.65991 6.25824 8.23702 6.72151 7.87836 7.13803C6.99843 8.15991 7.18504 9.6516 8.15802 10.4961C9.08905 11.3042 10.4727 11.3044 11.404 10.4966L13.9451 8.29222L12.9622 7.15913L10.4211 9.3635C10.0539 9.68202 9.50834 9.68194 9.14126 9.36332C8.74626 9.02047 8.71297 8.46758 9.01502 8.1168C9.35692 7.71976 9.74044 7.30142 10.1041 6.96099C10.493 6.59701 10.7751 6.40371 10.92 6.35156C11.0729 6.29651 11.3501 6.26585 11.745 6.28573ZM18.9295 15.2963L18.9309 15.2956Z" 
			}
		),
		el( 'path',
			{
				d: "M9.70284 16.8948C9.16537 16.4659 8.64898 16.0438 8.46344 15.8453L9.55917 14.8209C9.6503 14.9184 10.0542 15.2562 10.6385 15.7224C11.1948 16.1664 11.8528 16.6788 12.3928 17.0964C12.6258 17.2765 12.9647 17.227 13.1378 16.9801C13.268 16.7944 13.2651 16.5463 13.1305 16.3638L12.9412 16.107L14.1487 15.217L14.338 15.4738C14.8548 16.175 14.8661 17.128 14.366 17.8412C13.7055 18.7832 12.391 18.991 11.4753 18.283C10.9332 17.8639 10.2682 17.346 9.70284 16.8948Z" 
			}
		),
		el( 'path',
			{
				d: "M14.4343 12.2867C14.9506 12.7274 15.5611 13.2341 16.0636 13.6478C16.9294 14.3606 17.02 15.6588 16.2516 16.482C15.5605 17.2226 14.4152 17.3092 13.6217 16.6753C13.0545 16.2222 12.304 15.6216 11.6816 15.1194C11.3706 14.8686 11.0901 14.6412 10.8801 14.4687C10.7754 14.3827 10.6861 14.3087 10.6185 14.2515L10.6145 14.2481C10.5667 14.2077 10.4859 14.1393 10.4354 14.0849L11.5353 13.065C11.5258 13.0547 11.5196 13.0483 11.5196 13.0483C11.5196 13.0483 11.5376 13.0643 11.5873 13.1063C11.6466 13.1565 11.7293 13.2251 11.832 13.3095C12.0369 13.4777 12.3133 13.7019 12.6234 13.9519C13.2429 14.4518 13.9912 15.0506 14.5579 15.5033C14.7371 15.6464 14.9968 15.6281 15.155 15.4586C15.33 15.2711 15.3117 14.9717 15.1102 14.8058C14.6054 14.3902 13.9869 13.8769 13.4606 13.4277C13.1977 13.2034 12.9546 12.9924 12.7589 12.8162C12.5765 12.6519 12.3988 12.4858 12.2955 12.3644L13.4381 11.3926C13.4659 11.4253 13.5679 11.5261 13.7626 11.7015C13.9441 11.8649 14.1758 12.0662 14.4343 12.2867Z" 
			}
		),
		el( 'path',
			{
				d: "M13.2217 7.60857L18.7421 12.2719C19.6529 13.0413 19.7441 14.4126 18.9432 15.2958C18.3522 15.9476 17.3803 16.1921 16.5405 15.7702C16.3189 15.6589 16.0773 15.5297 15.8582 15.3944C15.6519 15.267 15.4149 15.1039 15.2408 14.9177L16.3365 13.8933C16.3692 13.9283 16.4656 14.0065 16.6463 14.1181C16.8143 14.2218 17.0135 14.3293 17.2138 14.4298C17.3988 14.5228 17.6527 14.486 17.832 14.2882C18.0626 14.034 18.0363 13.6392 17.7742 13.4178L12.2537 8.75444L13.2217 7.60857Z" 
			}
		)
	);

	let autoPlayToggleControl = ( props ) => {

		if ( props.attributes.layout !== 'carousel' ) {
			return;
		}

		return el( ToggleControl, {
			label: __( 'Auto Play', 'knd' ),
			checked: props.attributes.autoplay,
			onChange: val => {
				props.setAttributes( { autoplay: ! props.attributes.autoplay } );
			},
		});
	};

	registerBlockType( 'knd/partners', {
		title: __( 'Partners', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'partners', 'knd' ), __( 'logos', 'knd' ) ],
		description: __( 'Image tile in four columns.', 'knd' ),
		supports: {
			align: [ 'wide', 'full' ],
			anchor: true,
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'Our Partners', 'knd' ),
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
				default: 'full'
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
			layout: {
				type: 'string',
				default: 'grid',
			},
			autoplay: {
				type: 'boolean',
				default: false,
			},
			isLink: {
				type: 'boolean',
				default: true,
			},
			preview: {
				type: 'boolean',
				default: false,
			},
			queryOrder: {
				type: 'string',
				default: 'date/desc'
			},
			queryOffset: {
				type: 'string',
				default: '',
			},
			queryCategory: {
				type: 'string',
				default: 0,
			}
		},

		example: {
			attributes: {
				postsToShow: 4,
				preview : true
			},
			viewportWidth: 720
		},

		edit: function( props ) {

			doAction( 'knd.block.edit', props );

			let categoryOptions = function(){
				// Get Terms
				var getTerms = useSelect( ( select, props ) => {
					return select('core').getEntityRecords('taxonomy', 'org_cat', {per_page: -1});
				}, [] );

				var categories = [
					{ value: 0, label: __( 'All Categories', 'knd' ) },
				];

				if ( getTerms ) {
					getTerms.map((term) => {
						categories.push({ value: term.id, label: term.name } );
					} );
				}
				return categories;
			}

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( 'div',
							{
								className: 'knd-editor-block-card__description'
							},

							el( 'a',
								{
									href: kndBlock.getAdminUrl.partners,
									target: '_blank',
								},
								__( 'Edit partners', 'knd' ),
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

							el ( SelectControl,
								{
									//multiple: true,
									label: __( 'Layout', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { layout: val } );
									},
									value: props.attributes.layout,
									options: [
										{
											label: __( 'Grid', 'knd' ),
											value: 'grid'
										},
										{
											label: __( 'Carousel', 'knd' ),
											value: 'carousel'
										},
									],

								}
							),

							el( RangeControl,
								{
									label: __( 'Partners to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 4,
									min: 2,
									max: kndBlock.partnerCount,
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
									min: 4,
									max: 8,
									onChange: function( val ) {
										props.setAttributes({ columns: val })
									}
								}
							),

							el( ToggleControl,
								{
									label: __( 'Enable link', 'knd' ),
									checked: props.attributes.isLink,
									onChange: val => {
										props.setAttributes( { isLink: ! props.attributes.isLink } );
									},
								}
							),

							autoPlayToggleControl( props ),

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
										{ value: 'date/desc', label: __( 'Newest to oldest' ) },
										{ value: 'date/asc', label: __( 'Oldest to newest' ) },
										{ value: 'title/asc', label: __( 'A → Z' ) },
										{ value: 'title/desc', label: __( 'Z → A' ) },
									],
									value: props.attributes.queryOrder,
									onChange: ( val ) => {
										props.setAttributes( { queryOrder: val } );
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
									help: __( 'Number of partners to skip', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { queryOffset: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'Select category', 'knd' ),
									options : categoryOptions(),
									value: props.attributes.queryCategory,
									onChange: ( val ) => {
										props.setAttributes( { queryCategory: val } );
									},
								},
							),

						),
					),

					el( Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/partners',
							attributes: props.attributes,
							className: 'knd-block-server-side-rendered',
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
	window.wp.hooks,
) );

/**
 * People Block
 */

( function( blocks, editor, blockEditor, element, components, i18n, serverSideRender, hooks, data ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, PanelBody, Dashicon, ToggleControl, Disabled, Spinner, Placeholder } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;

	const { InspectorControls, ColorPaletteControl } = blockEditor;

	const { Fragment, Component, useEffect, useState } = element;

	const { subscribe, select, dispatch, withSelect, withDispatch, useSelect, useDispatch } = data;

	const { doAction } = hooks;

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

	let autoPlayToggleControl = ( props ) => {

		if ( props.attributes.layout !== 'carousel' ) {
			return;
		}

		return el( ToggleControl, {
			label: __( 'Auto Play', 'knd' ),
			checked: props.attributes.autoplay,
			onChange: val => {
				props.setAttributes( { autoplay: ! props.attributes.autoplay } );
			},
		});
	};

	// Get person count
	let personCount = function(){
		// Get Terms
		var getPerson = useSelect( ( select, props ) => {
			return select('core').getEntityRecords('postType', 'person', { per_page: -1 } );
		}, [] );

		var personCount = 0;

		if ( getPerson ) {
			personCount = getPerson.length;
		}

		return personCount;
	}

	registerBlockType( 'knd/people', {
		title: __( 'Team', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		keywords: [ __( 'people', 'knd' ), __( 'user', 'knd' ), __( 'team', 'knd' ), __( 'profile', 'knd' ) ],
		description: __( 'Tile with photos, names and short descriptions in 3-8 columns.', 'knd' ),
		supports: {
			align: [ 'wide', 'full' ],
			anchor: true,
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
			anchor: {
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
			layout: {
				type: 'string',
				default: 'grid',
			},
			autoplay: {
				type: 'boolean',
				default: false,
			},
			isLink: {
				type: 'boolean',
				default: false,
			},
			preview: {
				type: 'boolean',
				default: false,
			},
			queryOrder: {
				type: 'string',
				default: 'date/desc'
			},
			queryOffset: {
				type: 'string',
				default: '',
			},
			category: {
				type: 'string',
				default: 0,
			}
		},

		example: {
			attributes: {
				postsToShow: 2,
				heading: '',
				preview : true
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

			let categoryOptions = function(){
				// Get Terms
				var getTerms = useSelect( ( select, props ) => {
					return select('core').getEntityRecords('taxonomy', 'person_cat', { per_page: -1 } );
				}, [] );

				var categories = [
					{ value: 0, label: __( 'All Categories', 'knd' ) },
				];

				if ( getTerms ) {
					getTerms.map((term) => {
						categories.push({ value: term.id, label: term.name } );
					} );
				}
				return categories;
			}

			doAction( 'knd.block.edit', props );

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
							el( TextControl,
								{
									label: __( 'Heading', 'knd' ),
									value: props.attributes.heading,
									onChange: ( val ) => {
										props.setAttributes( { heading: val } );
									},
								}
							),

							el ( SelectControl,
								{
									//multiple: true,
									label: __( 'Layout', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { layout: val } );
									},
									value: props.attributes.layout,
									options: [
										{
											label: __( 'Grid', 'knd' ),
											value: 'grid'
										},
										{
											label: __( 'Carousel', 'knd' ),
											value: 'carousel'
										},
									],

								}
							),

							el( RangeControl,
								{
									label: __( 'People to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 4,
									min: 0,
									max: personCount(),
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
										props.setAttributes({ columns: val });
									}
								}
							),

							el( ToggleControl,
								{
									label: __( 'Enable link', 'knd' ),
									checked: props.attributes.isLink,
									onChange: val => {
										props.setAttributes( { isLink: ! props.attributes.isLink } );
									},
								}
							),

							autoPlayToggleControl( props ),

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
									label: __( 'Name Color', 'knd' ),
									value: props.attributes.nameColor,
									onChange: ( val ) => {
										props.setAttributes( { nameColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Description Color', 'knd' ),
									value: props.attributes.metaColor,
									onChange: ( val ) => {
										props.setAttributes( { metaColor: val } );
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
										{ value: 'date/desc', label: __( 'Newest to oldest' ) },
										{ value: 'date/asc', label: __( 'Oldest to newest' ) },
										{ value: 'title/asc', label: __( 'A → Z' ) },
										{ value: 'title/desc', label: __( 'Z → A' ) },
									],
									value: props.attributes.queryOrder,
									onChange: ( val ) => {
										props.setAttributes( { queryOrder: val } );
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
									help: __( 'Number of peoples to skip', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { queryOffset: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'Select category', 'knd' ),
									options : categoryOptions(),
									value: props.attributes.category,
									onChange: ( val ) => {
										props.setAttributes( { category: val } );
									},
								},
							),

						),

					),

					el( Disabled, null,

						el( ServerSideRender,
							{
								block: 'knd/people',
								attributes: props.attributes,
								className: 'knd-block-server-side-rendered',
							}
						),

					)
				)
			)
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
	window.wp.i18n,
	window.wp.serverSideRender,
	window.wp.hooks,
	window.wp.data
) );

/**
 * Projects Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Button, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType } = blocks;

	const { InspectorControls, PanelColorSettings } = blockEditor;

	const { Fragment } = element;

	const { useSelect } = data;

	const { withState } = compose;

	const { __ } = i18n;

	const icon = el('svg',
		{ 
			width: 24,
			height: 24
		},
		el( 'path',
			{
				d: "M6.99204 11.3995C6.56978 11.551 5.79387 11.8525 5.01186 12.2749C4.47005 12.5675 3.94838 12.9059 3.54336 13.2763C3.4414 13.3696 3.35003 13.4619 3.26914 13.5531C3.59415 13.5005 3.96907 13.4297 4.35873 13.3502C5.30776 13.1566 6.29766 12.9218 6.76186 12.8095L7.06909 12.2685C7.23077 11.9838 7.19058 11.6383 6.99204 11.3995ZM7.47539 10.1236C8.43067 10.7039 8.75148 11.9385 8.19951 12.9104L7.76915 13.6682C7.68874 13.8098 7.54156 13.9566 7.31751 14.0119C7.11667 14.0615 5.83832 14.3752 4.61851 14.624C4.0121 14.7476 3.40155 14.8592 2.94114 14.9122C2.71902 14.9378 2.4916 14.9549 2.30743 14.9412C2.22834 14.9354 2.07587 14.9197 1.92745 14.8446C1.84699 14.8039 1.72214 14.7229 1.63178 14.5718C1.53128 14.4036 1.51533 14.219 1.55044 14.0617C1.7064 13.3628 2.15927 12.7805 2.66604 12.317C3.17904 11.8479 3.80154 11.4511 4.39409 11.131C5.58019 10.4904 6.73875 10.1088 6.90433 10.0555C7.12514 9.98443 7.33145 10.0361 7.47539 10.1236Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M12.8181 17.2111C12.6738 17.6345 12.3855 18.4175 11.9819 19.212C11.7019 19.763 11.3777 20.2968 11.0228 20.7156C10.9657 20.7831 10.9089 20.8461 10.8527 20.9047C10.9017 20.6048 10.9636 20.2687 11.0318 19.9214C11.2196 18.9655 11.4466 17.9693 11.5529 17.5118L12.0857 17.1756C12.3199 17.0279 12.6123 17.0515 12.8181 17.2111ZM14.0963 16.7311C13.5409 15.7892 12.317 15.4928 11.3922 16.0761L10.657 16.5398C10.5262 16.6223 10.3929 16.7659 10.3427 16.978C10.2979 17.1673 9.99648 18.4478 9.7562 19.6707C9.63671 20.2789 9.52879 20.8903 9.47689 21.3499C9.45182 21.5718 9.43503 21.7969 9.44708 21.9775C9.45216 22.0537 9.46603 22.2046 9.53754 22.3517C9.57587 22.4306 9.65681 22.5627 9.8154 22.6568C9.99502 22.7633 10.1905 22.772 10.3501 22.7288C11.0227 22.5468 11.577 22.0725 12.0146 21.5561C12.4595 21.031 12.8365 20.3999 13.1408 19.8008C13.7502 18.6015 14.1137 17.4402 14.1621 17.2824C14.2254 17.0754 14.1824 16.8771 14.0963 16.7311Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M7.97098 8.67134C10.7018 5.19342 14.9742 1.73175 20.8664 2.48554L21.4295 2.55758L21.5131 3.11909C22.355 8.77693 18.9534 13.1068 15.5394 15.9412C13.8191 17.3694 12.0541 18.455 10.7226 19.1827C10.0557 19.5472 9.49472 19.8235 9.09852 20.0095C8.90036 20.1025 8.74322 20.173 8.63446 20.2208C8.58007 20.2446 8.53776 20.2628 8.50844 20.2753L8.47431 20.2897L8.46477 20.2937L8.46192 20.2949L8.46097 20.2953C8.46064 20.2954 8.46035 20.2955 8.17429 19.6022L8.46097 20.2953L8.00193 20.4847L3.74097 16.2938L3.90407 15.8416L4.60958 16.0961C3.90408 15.8416 3.90395 15.842 3.90407 15.8416L3.90467 15.84L3.90574 15.837L3.9093 15.8273L3.92215 15.7926C3.93327 15.7629 3.94948 15.72 3.97082 15.665C4.01349 15.555 4.07667 15.3961 4.16057 15.1958C4.3283 14.7952 4.57917 14.2281 4.91477 13.5535C5.58475 12.2068 6.59849 10.4193 7.97098 8.67134ZM5.48872 15.9088C5.50591 15.8669 5.52438 15.8224 5.54414 15.7752C5.70105 15.4005 5.93852 14.8634 6.25776 14.2217C6.89745 12.9358 7.85936 11.2424 9.15077 9.59767C11.6492 6.41562 15.2688 3.5445 20.0987 3.91433C20.524 8.51473 17.7275 12.1751 14.5812 14.7871C12.9565 16.136 11.2785 17.1694 10.0032 17.8665C9.3667 18.2144 8.8334 18.4769 8.46114 18.6516C8.41729 18.6722 8.37568 18.6916 8.33642 18.7097L5.48872 15.9088Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M13.4731 10.6496C13.8669 11.0369 14.5 11.0317 14.8873 10.6379C15.2745 10.2442 15.2693 9.61103 14.8755 9.22376C14.4818 8.83648 13.8487 8.84173 13.4614 9.23548C13.0741 9.62923 13.0794 10.2624 13.4731 10.6496ZM12.4213 11.7191C13.4056 12.6872 14.9885 12.6741 15.9567 11.6898C16.9249 10.7054 16.9117 9.12252 15.9274 8.15434C14.943 7.18616 13.3601 7.19928 12.392 8.18365C11.4238 9.16802 11.4369 10.7509 12.4213 11.7191Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M8.35398 14.7004C8.02623 15.1562 7.33111 16.1346 6.688 17.1151C6.41624 17.5294 6.15831 17.9373 5.94279 18.3024C6.3044 18.081 6.70808 17.8166 7.11799 17.5382C8.08804 16.8794 9.05506 16.1687 9.50555 15.8336C9.43213 15.2367 8.95198 14.7642 8.35398 14.7004ZM7.993 13.4025C9.58282 13.2674 10.9186 14.5819 10.8091 16.1737C10.7972 16.3462 10.7189 16.5486 10.5295 16.6912C10.3562 16.8217 9.108 17.7582 7.84836 18.6136C7.22109 19.0396 6.57553 19.4562 6.05301 19.743C5.79573 19.8843 5.54181 20.0089 5.32226 20.0839C5.21529 20.1204 5.08136 20.1576 4.94084 20.1659C4.81991 20.1731 4.54541 20.1675 4.3229 19.9486C4.1004 19.7296 4.09043 19.4552 4.09564 19.3342C4.10168 19.1936 4.13673 19.0591 4.17154 18.9515C4.24297 18.7308 4.36352 18.4749 4.5006 18.2154C4.77902 17.6883 5.1851 17.0361 5.60096 16.4021C6.43607 15.1289 7.35235 13.8658 7.48003 13.6904C7.61957 13.4987 7.82075 13.4172 7.993 13.4025Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M15.1422 3.91875C15.1422 3.91837 15.1422 3.918 15.8922 3.91179C16.6422 3.90557 16.6422 3.90521 16.6422 3.90485L16.6422 3.90289L16.6421 3.90069L16.6421 3.89777C16.6422 3.90016 16.6425 3.90731 16.6434 3.9192C16.6452 3.94299 16.6492 3.98494 16.6579 4.04244C16.6753 4.15758 16.7114 4.33389 16.7858 4.55111C16.9338 4.98295 17.2352 5.58332 17.8557 6.19368C18.4763 6.80404 19.0816 7.09546 19.5158 7.23621C19.7342 7.30701 19.9111 7.3402 20.0265 7.35571C20.0842 7.36346 20.1262 7.36676 20.15 7.36815C20.1619 7.36884 20.1692 7.36906 20.1716 7.36911C20.1728 7.36914 20.1729 7.36912 20.1716 7.36911L20.1685 7.36911L20.1663 7.36912L20.1651 7.36912C20.1647 7.36913 20.1637 7.36913 20.1699 8.11911C20.1761 8.86908 20.1757 8.86909 20.1753 8.86909L20.1729 8.8691L20.1692 8.86911L20.1601 8.86908L20.1357 8.86868C20.1167 8.86823 20.0922 8.86732 20.0626 8.8656C20.0036 8.86215 19.9239 8.85541 19.8267 8.84234C19.6324 8.81623 19.3666 8.76469 19.0533 8.66312C18.424 8.45913 17.6092 8.05518 16.8039 7.2631C15.9986 6.47102 15.5812 5.66309 15.3668 5.03723C15.26 4.72561 15.2041 4.46073 15.1748 4.26688C15.1601 4.16989 15.152 4.0904 15.1476 4.03138C15.1454 4.00186 15.1441 3.97741 15.1433 3.9584L15.1425 3.934L15.1423 3.92494L15.1423 3.9212L15.1422 3.91875Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
			}
		),
		el( 'path',
			{
				d: "M7.59281 10.3214C7.59246 10.3213 7.59211 10.3212 7.47506 11.0621C7.35802 11.8029 7.35768 11.8028 7.35735 11.8028L7.35559 11.8025L7.35376 11.8022L7.3519 11.8019C7.35146 11.8018 7.35291 11.802 7.35559 11.8025C7.36095 11.8035 7.37215 11.8058 7.3895 11.8096C7.42418 11.8173 7.48214 11.8313 7.56073 11.8544C7.71789 11.9004 7.95753 11.9825 8.25865 12.1221C8.85969 12.4006 9.71095 12.9105 10.6437 13.8279C11.5765 14.7454 12.1004 15.5881 12.3889 16.1844C12.5334 16.4832 12.6195 16.7214 12.6681 16.8778C12.6925 16.956 12.7075 17.0137 12.7157 17.0483C12.7199 17.0655 12.7223 17.077 12.7234 17.0824L12.7232 17.0809C12.7232 17.0806 12.723 17.0796 13.4618 16.9503C14.2005 16.821 14.2005 16.8207 14.2004 16.8203L14.2 16.818L14.1993 16.8143L14.1976 16.8051L14.1925 16.7793C14.1883 16.7588 14.1824 16.732 14.1745 16.6991C14.1588 16.6333 14.135 16.5433 14.1004 16.432C14.0311 16.2094 13.9183 15.9014 13.7392 15.5312C13.3805 14.7897 12.7584 13.8039 11.6956 12.7585C10.6328 11.7132 9.63673 11.1075 8.88937 10.7611C8.5163 10.5882 8.20647 10.4805 7.9827 10.415C7.87082 10.3822 7.78046 10.3599 7.71443 10.3452C7.68142 10.3379 7.65449 10.3325 7.63399 10.3286L7.6081 10.3239L7.59885 10.3223L7.59516 10.3217L7.59281 10.3214ZM12.7234 17.0824C12.7238 17.0843 12.7241 17.0855 12.7241 17.0857C12.7241 17.0858 12.7241 17.0859 12.7241 17.0857L12.7238 17.0839L12.7234 17.0824Z",
				'fill-rule': "evenodd",
				'clip-rule': "evenodd"
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

	registerBlockType( 'knd/projects', {
		title: __( 'Projects', 'knd' ),
		icon: icon,
		category: 'kandinsky',
		description: __( 'Projects tile in three column.', 'knd' ),
		keywords: [
			__( 'news', 'knd' ),
			__( 'posts', 'knd' ),
			__( 'articles', 'knd' ),
			__( 'projects', 'knd' ),
		],
		supports: {
			align: [ 'wide', 'full' ],
			anchor: true,
		},

		attributes: {
			heading: {
				type: 'string',
				default: __( 'Projects', 'knd' ),
			},
			postsToShow: {
				type: 'number',
				default: 3
			},
			columns: {
				type: 'number',
				default: 3
			},
			radius: {
				type: 'number',
				default: 5
			},
			align: {
				type: 'string',
				default: 'full',
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
			titleColor: {
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
			queryOrder: {
				type: 'string',
				default: 'date/desc'
			},
			queryOffset: {
				type: 'string',
				default: '',
			},
			queryTag: {
				type: 'string',
				default: 0,
			},
			imageOrientation: {
				type: 'string',
				default: 'landscape',
			},
			imageSize: {
				type: 'string',
				default: 'post-thumbnail',
			},
		},

		example: {
			attributes: {
				postsToShow: 3,
				backgroundColor: '#f0f0f0'
			},
			viewportWidth: 720
		},

		edit: function( props ) {

			let tagOptions = function(){
				// Get Terms
				var getTerms = useSelect( ( select, props ) => {
					return select('core').getEntityRecords('taxonomy', 'project_tag', { per_page: -1 } );
				}, [] );

				var tags = [
					{ value: 0, label: __( 'All Tags', 'knd' ) },
				];

				if ( getTerms ) {
					getTerms.map((term) => {
						tags.push( { value: term.id, label: term.name } );
					} );
				}
				return tags;
			}

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

			function getImageSizes() {
				var imageSizes = [];
				Object.entries( kndBlock.imageSizes ).forEach( ( [ key, value ] ) => {
					imageSizes.push({ label: value, value: key });
				});
				return imageSizes;
			}

			return (
				el( Fragment, {},

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
							enableAlpha: true,
							colorSettings: [
								{
									label: __( 'Background Color', 'knd' ),
									value: props.attributes.backgroundColor,
									onChange: ( val ) => {
										props.setAttributes( { backgroundColor: val } );
									}
								},
								{
									label: __( 'Heading Color', 'knd' ),
									value: props.attributes.headingColor,
									onChange: ( val ) => {
										props.setAttributes( { headingColor: val } );
									}
								},
								{
									label: __( 'Цвет названия', 'knd' ), // Post Title Color
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes( { titleColor: val } );
									}
								},
								{
									label: __( 'Цвет ссылок', 'knd' ), // Links Color
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								},
							]
						}),
					),

					el( InspectorControls, {},

						el( PanelBody,
							{
								title: __( 'Settings', 'knd' )
							},
							el( 'div',
								{
									className: 'knd-editor-block-panel__description'
								},

								el( 'a',
									{
										href: kndBlock.getAdminUrl.projects,
										target: '_blank',
									},
									__( 'Edit projects', 'knd' ),
									' ',
									el( Dashicon,
										{
											icon: 'external',
										}
									),
								),
							),
							el( TextControl, {
								label: __( 'Heading', 'knd' ),
								value: props.attributes.heading,
								onChange: ( val ) => {
									props.setAttributes( { heading: val } );
								},
							}),

							el( RangeControl,
								{
									label: __( 'Projects to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 3,
									min: 1,
									max: 30,
									onChange: ( val ) => {
										props.setAttributes({ postsToShow: val })
									}
								}
							),

							el( RangeControl,
								{
									label: __( 'Columns', 'knd' ),
									value: props.attributes.columns,
									initialPosition: 3,
									min: 1,
									max: 4,
									onChange: function( val ) {
										props.setAttributes({ columns: val });
									}
								}
							),

						),

						el( PanelBody,
							{
								title: __( 'Heading Links', 'knd' ),
								className: 'knd-components-panel__body',
								initialOpen: false
							},

							headingFields,

						),

						// el( PanelBody,
						// 	{
						// 		title: __( 'Colors', 'knd' ),
						// 		initialOpen: false
						// 	},

						// 	el( ColorPaletteControl,
						// 		{
						// 			label: __( 'Background Color', 'knd' ),
						// 			value: props.attributes.backgroundColor,
						// 			onChange: ( val ) => {
						// 				props.setAttributes( { backgroundColor: val } );
						// 			}
						// 		}
						// 	),

						// 	el( ColorPaletteControl,
						// 		{
						// 			label: __( 'Heading Color', 'knd' ),
						// 			value: props.attributes.headingColor,
						// 			onChange: ( val ) => {
						// 				props.setAttributes( { headingColor: val } );
						// 			}
						// 		}
						// 	),

						// 	el( ColorPaletteControl,
						// 		{
						// 			label: __( 'Post Title Color', 'knd' ),
						// 			value: props.attributes.titleColor,
						// 			onChange: ( val ) => {
						// 				props.setAttributes( { titleColor: val } );
						// 			}
						// 		}
						// 	),

						// 	el( ColorPaletteControl,
						// 		{
						// 			label: __( 'Links Color', 'knd' ),
						// 			value: props.attributes.linkColor,
						// 			onChange: ( val ) => {
						// 				props.setAttributes( { linkColor: val } );
						// 			}
						// 		}
						// 	),

						// ),

						el( PanelBody,
							{
								title: __( 'Настройки изображения', 'knd' ), // Featured image settings
								initialOpen: false
							},

							el( SelectControl,
								{
									label: __( 'Ориентация изображения', 'knd' ), // Image Orientation
									value: props.attributes.imageOrientation,
									onChange: ( val ) => {
										props.setAttributes( { imageOrientation: val } );
									},
									options: [
										{
											label: __( 'Оригинал', 'knd' ), // Original
											value: 'original'
										},
										{
											label: __( 'Альбомный 16:9', 'knd' ), // Landscape 16:9
											value: 'landscape-16-9'
										},
										{
											label: __( 'Альбомный 4:3', 'knd' ), // Landscape 4:3
											value: 'landscape'
										},
										{
											label: __( 'Квадтарный', 'knd' ), // Square
											value: 'square'
										},
										{
											label: __( 'Портретный', 'knd' ), // Portrait
											value: 'portrait'
										},
									],
								}
							),

							el( SelectControl,
								{
									label: __( 'Размер изображения', 'knd' ), // Image Size
									value: props.attributes.imageSize,
									onChange: ( val ) => {
										props.setAttributes( { imageSize: val } );
									},
									options: getImageSizes(),
									
								}
							),

							el( RangeControl,
								{
									label: __( 'Округление углов', 'knd' ), // Image Border Radius
									value: props.attributes.radius,
									initialPosition: 5,
									min: 0,
									max: 25,
									onChange: ( val ) => {
										props.setAttributes({ radius: val })
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
										{ value: 'date/desc', label: __( 'Newest to oldest' ) },
										{ value: 'date/asc', label: __( 'Oldest to newest' ) },
										{ value: 'title/asc', label: __( 'A → Z' ) },
										{ value: 'title/desc', label: __( 'Z → A' ) },
									],
									value: props.attributes.queryOrder,
									onChange: ( val ) => {
										props.setAttributes( { queryOrder: val } );
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
									help: __( 'Number of projects to skip', 'knd' ),
									onChange: ( val ) => {
										props.setAttributes( { queryOffset: val } );
									},
								}
							),

							el( SelectControl,
								{
									label: __( 'Select tag', 'knd' ),
									options : tagOptions(),
									value: props.attributes.queryTag,
									onChange: ( val ) => {
										props.setAttributes( { queryTag: val } );
									},
								},
							),

						),

					),

					el( Disabled,
						null,
						el( ServerSideRender, {
							block: 'knd/projects',
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

		supports: {
			anchor: true,
		},

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
			},
			anchor: {
				type: 'string',
			}
		},

		example: {
			attributes: {
				text: defaultContent
			},
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
									value: props.attributes.textColor,
									onChange: function( val ) {
										props.setAttributes({ textColor: val });
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Background Color', 'knd' ),
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
) );
