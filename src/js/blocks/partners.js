/**
 * Partners Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data, hooks ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl, PanelColorSettings } = blockEditor;

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

					el( InspectorControls, {
							group: 'styles',
						},

						el( PanelColorSettings, {
							title: __( 'Colors', 'knd' ),
							initialOpen: true,
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
								}
							]
						}),
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
