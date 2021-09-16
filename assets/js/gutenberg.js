/**
 * Add Page Panel
 */

/* Constants */
const el = wp.element.createElement;
const { __ } = wp.i18n;
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginDocumentSettingPanel } = wp.editPost;
const { compose, withState } = wp.compose;
const { select, withSelect, withDispatch } = wp.data;
const { TextControl, SelectControl, IconButton, ColorPalette, ToggleControl } = wp.components;
const { useState } = wp.element;

/* Select Control */
/*var kndHeaderSelect = compose( [ withSelect( function ( select, props ) {
	if ( select( 'core/editor' ).getEditedPostAttribute( 'meta' ) !== undefined ) {
		return {
			metaFieldValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.fieldName ],
		}
	}
	return;
} ), withDispatch( function ( dispatch ) {
	return {
		setPostMeta: function setPostMeta(value) {
			dispatch('core/editor').editPost({
				meta: {
					_knd_page_header_type: value
				}
			});
			console.log(value);
		}
	};
})])(function (props) {
	return el( SelectControl, {
		label: __('Page Header Type', 'knd' ),
		value: props.metaFieldValue,
		onChange: props.setPostMeta,
		options: [
			{
				"value": "default",
				"label": __('Default', 'knd' )
			},
			{
				"value":"none",
				"label": __('None', 'knd' )
			}
		]
	} );

});
*/

/* Is Page Title */
var kndIsPageTitle = compose( [ withSelect( function ( select, props ) {
	if ( select( 'core/editor' ).getEditedPostAttribute( 'meta' ) !== undefined ) {
		return {
			metaFieldValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.fieldName ],
		}
	}
	return;
} ), withDispatch( function ( dispatch ) {
	return {
		setPostMeta: function setPostMeta(value) {
			dispatch('core/editor').editPost({
				meta: {
					_knd_is_page_title: value
				}
			});
		}
	};
})])(function (props) {

	const [ isPageTitle, setIsPageTitle ] = useState( props.metaFieldValue );

	return el( ToggleControl, {
		label: __( 'Hide the page title', 'knd' ),
		checked: isPageTitle,
		onChange: val => {
			setIsPageTitle( val );
			props.setPostMeta(val)
		},
	} );

});

/* Render page options */
var kndRegisterPageOptions = function() {
	return el( PluginDocumentSettingPanel,
		{
			name: 'knd-page-options-panel',
			priority: 9999,
			className: 'knd-page-options-panel',
			title: __( 'Page Options', 'knd' ),
		},
		/*el( 'div',
			{ className: 'knd-page-options-header-type' },
			el( kndHeaderSelect,
				{ fieldName: '_knd_page_header_type' }
			)
		),*/
		el( 'div',
			{ className: 'knd-page-options-header-type' },
			el( kndIsPageTitle,
				{ fieldName: '_knd_is_page_title' }
			)
		)
	);
}

/* Register Page Options Fields */
if( window.pagenow == 'page' ) {
	registerPlugin( 'knd-page-options-panel', {
		icon: null,
		render: kndRegisterPageOptions
	} );
}
