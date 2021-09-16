/**
Dependencies wp_enqueue_script

element = 'wp-element'
createElement

components = 'wp-components'
TextControl,
SelectControl,
RangeControl,
ColorPalette,
PanelBody,
ToggleControl,
Disabled

i18n = 'wp-i18n'
__

compose = 'wp-compose'
compose
withState

data = 'wp-data'
select
withSelect
withDispatch

editPost = 'wp-edit-post'
PluginSidebar

plugins = 'plugins'
registerPlugin

*/


// Using ES5 syntax
const el = { createElement } = wp.element;
const { __ } = wp.i18n;
const { registerPlugin } = wp.plugins;
const { PluginSidebar, PluginDocumentSettingPanel } = wp.editPost;
const { compose, withState } = wp.compose;
const { select, withSelect, withDispatch } = wp.data;
const { TextControl, SelectControl, IconButton, ColorPalette } = wp.components;


/* Text Control */
var textControlField = compose(
    withDispatch( function( dispatch, props ) {
        return {
            setMetaFieldValue: function( value ) {
                dispatch( 'core/editor' ).editPost(
                    { meta: { [ props.fieldName ]: value } }
                );
            }
        }
    } ),
    withSelect( function( select, props ) {
        return {
            metaFieldValue: select( 'core/editor' )
                .getEditedPostAttribute( 'meta' )
                [ props.fieldName ],
        }
    } )
)( function( props ) {
    return el( TextControl, {
        label: '',//__('Text Control', 'knd' ),
        value: props.metaFieldValue,
        onChange: function( content ) {
            props.setMetaFieldValue( content );
        },
    } );
} );

/* Select Control */
var selectControlField = compose( [ withSelect( function ( select, props ) {
    return {
        metaFieldValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.fieldName ],
    }
  } ), withDispatch( function ( dispatch ) {
    return {
      setPostMeta: function setPostMeta(value) {
        dispatch('core/editor').editPost({
          meta: {
            _knd_page_header_type: value
          }
        });
      }
    };
  })])(function (props) {
    return el( SelectControl, {
        label: '',//__( 'Select Option Label', 'knd' ),
        value: props.metaFieldValue,
        onChange: props.setPostMeta,
        options: [
            {
                "value": "default",
                "label": __('Default', 'knd' )
            },
            {
                "value": "none",
                "label": __('None', 'knd' )
            }
        ]
    } );
    
});

/* Render page options */
var registerPanelPageOptions = function() {
    return el( PluginDocumentSettingPanel,
        {
            name: 'slug-page-options-panel',
            className: 'slug-page-options-panel',
            title: __( 'Page Options', 'knd' ),
        },
        el( 'div',
            { className: 'slug-page-options-header-text' },
            el( textControlField,
                { fieldName: '_slug_page_header_text' }
            )
        ),
        el( 'div',
            { className: 'slug-page-options-header-type' },
            el( selectControlField,
                { fieldName: '_slug_page_header_type' }
            )
        )
    );
}

/* Register Page Options Fields */
registerPlugin( 'slug-page-options-panel', {
    icon: null,
    render: registerPanelPageOptions
} );


/**
 * Remove panels from post edit sidebar.
 */

// Remove panels
// remove excerpt panel
/*
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'post-excerpt' );

wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'taxonomy-panel-category' ) ; // category
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'taxonomy-panel-TAXONOMY-NAME' ) ; // custom taxonomy
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'taxonomy-panel-post_tag' ); // tags
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'featured-image' ); // featured image
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'post-link' ); // permalink
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'page-attributes' ); // page attributes
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'post-excerpt' ); // Excerpt
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'discussion-panel' ); // Discussion
*/

// Remove the DIVI meta-box:
// Remove the DIVI meta-box:
/*
var boxId = 'csco_child_mb_layout_options';
wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'meta-box-' + boxId );
*/

