/**
 * Projects Block
 */

( function( blocks, editor, blockEditor, element, components, compose, i18n, serverSideRender, data ) {

	const ServerSideRender = serverSideRender;

	const el = element.createElement;

	const { TextControl, SelectControl, RangeControl, ColorPalette, Button, Dashicon, PanelBody, ToggleControl, Disabled } = components;

	const { registerBlockType, withColors, PanelColorSettings, getColorClassName, useBlockProps } = blocks;
	const { InspectorControls, ColorPaletteControl } = blockEditor;

	const { Fragment } = element;

	const { withState } = compose;

	const { useSelect } = data;

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
		category: 'kandinsky', //
		description: __( 'Projects tile in three column.', 'knd' ),
		keywords: [
			__( 'news', 'knd' ),
			__( 'posts', 'knd' ),
			__( 'articles', 'knd' ),
			__( 'projects', 'knd' ),
		],
		supports: {
			align: [ 'wide', 'full' ],
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
			}
		},

		example: {
			attributes: {
				postsToShow: 3,
				backgroundColor: '#f0f0f0'
			},
			viewportWidth: 720
		},

		edit: function( props ) {

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

			return (
				el( Fragment, {},

					el( InspectorControls, {},

						el( 'div',
							{
								className: 'knd-editor-block-card__description'
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
									label: __( 'Projects to show', 'knd' ),
									value: props.attributes.postsToShow,
									initialPosition: 3,
									min: 2,
									max: 12,
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
							},

							headingFields,

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
									label: __( 'Post Title Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.titleColor,
									onChange: ( val ) => {
										props.setAttributes( { titleColor: val } );
									}
								}
							),

							el( ColorPaletteControl,
								{
									label: __( 'Links Color', 'knd' ),
									colors: kndBlockColors,
									value: props.attributes.linkColor,
									onChange: ( val ) => {
										props.setAttributes( { linkColor: val } );
									}
								}
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
