/**
 * News Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, date ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { dateI18n } = date;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Dashicon, PanelBody, ToggleControl, Button, IconButton, Disabled, BaseControl, FontSizePicker, __experimentalUnitControl, __experimentalDivider } = components;

	const { registerBlockType, withColors, getColorClassName } = blocks;
	const { InspectorControls, InspectorAdvancedControls, ColorPaletteControl, useBlockProps, PanelColorSettings, BlockControls,__experimentalBlockAlignmentMatrixControl, BlockSettingsMenu, BlockTitle } = blockEditor;

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
							//console.log(val);
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
	window.wp.date,
) );
