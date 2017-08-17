<?php if( !defined('WPINC') ) die;
/**
 * Shortcodes UI. Shortcake plugin needed.
 */

if( !defined('SHORTCODE_UI_VERSION') ) {
    return;
}

add_action('init', function() { // Plugin custom localization
    load_textdomain('shortcode-ui', get_template_directory()."/vendor/shortcode-ui-ru_RU.mo");
}, 100);

add_action('register_shortcode_ui', 'knd_add_shortcodes_ui');
function knd_add_shortcodes_ui() {

    /** @todo Quote markup need CSS styles */
    shortcode_ui_register_for_shortcode('knd_quote', array(
        'label' => __('A quote', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-editor-quote', // Dashicon class or full <img> HTML
//        'post_type' => array('post'), // Limit this shortcode UI to specific posts. Optional

        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('Quote origin', 'knd'),
                'attr'   => 'name',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A quote origin (or author)', 'knd'),),
            ),
        ),
    ));

    shortcode_ui_register_for_shortcode('knd_button', array(
        'label' => __('A "call to action" button', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-external', // Dashicon class or full <img> HTML

//        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
//            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
//        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('Button label', 'knd'),
                'attr'   => 'txt',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A text for button', 'knd'),),
            ),
            array(
                'label'  => __('Button URL', 'knd'),
                'attr'   => 'url',
                'type'   => 'url',
                'encode' => false,
                'meta'   => array('placeholder' => __('An URL for button', 'knd'),),
            ),
            array(
                'label'  => __('Open link in a new window', 'knd'),
                'attr'   => 'in_new_window',
                'type'   => 'checkbox',
                'default' => false,
                'encode' => false,
            ),
        ),
    ));

    shortcode_ui_register_for_shortcode('knd_background_text', array(
        'label' => __('A text with image or colored background', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-format-image', // Dashicon class or full <img> HTML
//        'post_type' => array('post'), // Limit this shortcode UI to specific posts. Optional

//        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
//            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
//        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('Background image', 'knd'),
                'attr'   => 'bg-image',
                'type'   => 'attachment',
                'libraryType' => array('image'),
                'addButton'   => __('Select image', 'knd'),
                'frameTitle'  => __('Select section background image', 'knd'),
            ),
            array(
                'label'  => __('Title', 'knd'),
                'attr'   => 'title',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A title text', 'knd'),),
            ),
            array(
                'label'  => __('Subtitle', 'knd'),
                'attr'   => 'subtitle',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A subtitle text', 'knd'),),
            ),
            array(
                'label'  => __('Button label', 'knd'),
                'attr'   => 'cta-label',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A label for a "call to action" button', 'knd'),),
            ),
            array(
                'label'  => __('Button URL', 'knd'),
                'attr'   => 'cta-url',
                'type'   => 'url',
                'encode' => false,
                'meta'   => array('placeholder' => __('An URL for a "call to action" button', 'knd'),),
            ),
            array(
                'label'  => __('Section CSS class', 'knd'),
                'attr'   => 'class',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('An additional CSS class (or several) for the section', 'knd'),),
            ),
        ),
    ));

    shortcode_ui_register_for_shortcode('knd_persons_list', array(
        'label' => __('A list of persons', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-groups', // Dashicon class or full <img> HTML

//        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
//            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
//        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('List title', 'knd'),
                'attr'   => 'title',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('E.g., "Our team"', 'knd'),),
            ),
            array(
                'label'    => __('Person categories', 'knd'),
                'attr'     => 'person-categories',
                'type'     => 'term_select',
                'taxonomy' => 'person_cat',
                'multiple' => true,
            ),
            array(
                'label'    => __('Only particular persons in the list', 'knd'),
                'attr'     => 'persons',
                'type'     => 'post_select',
                'query'    => array('post_type' => 'person'),
                'multiple' => true,
            ),
            array(
                'label'  => __('Section CSS class', 'knd'),
                'attr'   => 'class',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('An additional CSS class (or several) for the section', 'knd'),),
            ),
        ),
    ));

    shortcode_ui_register_for_shortcode('knd_orgs_list', array(
        'label' => __('A list of organizations', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-networking', // Dashicon class or full <img> HTML

//        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
//            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
//        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('List title', 'knd'),
                'attr'   => 'title',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('E.g., "Our partners"', 'knd'),),
            ),
            array(
                'label'    => __('Organization categories', 'knd'),
                'attr'     => 'org-categories',
                'type'     => 'term_select',
                'taxonomy' => 'org_cat',
                'multiple' => true,
            ),
            array(
                'label'    => __('Only particular organizations in the list', 'knd'),
                'attr'     => 'persons',
                'type'     => 'post_select',
                'query'    => array('post_type' => 'person'),
                'multiple' => true,
            ),
            array(
                'label'  => __('Section CSS class', 'knd'),
                'attr'   => 'class',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('An additional CSS class (or several) for the section', 'knd'),),
            ),
        ),
    ));

    shortcode_ui_register_for_shortcode('knd_columns', array(
        'label' => __('A columns text markup', 'knd'), // Shortcode label in the UI. Required
        'listItemImage' => 'dashicons-welcome-widgets-menus', // Dashicon class or full <img> HTML

//        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
//            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
//        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('Section title', 'knd'),
                'attr'   => 'title',
                'type'   => 'text',
                'encode' => false,
            ),
            array(
                'label'  => __('First column title', 'knd'),
                'attr'   => '1-title',
                'type'   => 'text',
                'encode' => false,
            ),
            array(
                'label'  => __('First column content', 'knd'),
                'attr'   => '1-text',
                'type'   => 'textarea',
                'encode' => true,
                'meta' => array('class' => 'shortcake-richtext',),
            ),
            array(
                'label'  => __('Second column title', 'knd'),
                'attr'   => '2-title',
                'type'   => 'text',
                'encode' => false,
            ),
            array(
                'label'  => __('Second column content', 'knd'),
                'attr'   => '2-text',
                'type'   => 'textarea',
                'encode' => true,
                'meta' => array('class' => 'shortcake-richtext',),
            ),
            array(
                'label'  => __('Third column title', 'knd'),
                'attr'   => '3-title',
                'type'   => 'text',
                'encode' => false,
            ),
            array(
                'label'  => __('Third column content', 'knd'),
                'attr'   => '3-text',
                'type'   => 'textarea',
                'encode' => true,
                'meta' => array('class' => 'shortcake-richtext',),
            ),
            array(
                'label'  => __('Section CSS class', 'knd'),
                'attr'   => 'class',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('An additional CSS class (or several) for the section', 'knd'),),
            ),
        ),
    ));

}