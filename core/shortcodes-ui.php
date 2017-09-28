<?php /**
 * Shortcodes UI. Shortcake plugin needed.
 */

if ( ! defined( 'WPINC' ) )
	die();

if ( ! defined( 'SHORTCODE_UI_VERSION' ) ) {
	return;
}

add_action( 
	'init', 
	function () { // Plugin custom localization
		load_textdomain( 'shortcode-ui', get_template_directory() . "/vendor/shortcode-ui-ru_RU.mo" );
	}, 
	100 );

add_action( 'register_shortcode_ui', 'knd_add_shortcodes_ui' );

function knd_add_shortcodes_ui() {
	shortcode_ui_register_for_shortcode( 
		'knd_key_phrase', 
		array( 
			'label' => esc_html__( 'Key phrase', 'knd' ),  // Shortcode label in the UI. Required
			'listItemImage' => 'dashicons-editor-aligncenter',  // Dashicon class or full <img> HTML
			                                                   // 'post_type' => array('post'), // Limit this shortcode UI
			                                                   // to specific posts. Optional
			
			'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
'label' => esc_html__( 'Phrase text', 'knd' ) ),
			// 'description' => __('A text of the quote.', 'knd'),
			
			'attrs' => array(  // Define & bind the UI for shortcode attributes. Optional
				array( 
					'label' => esc_html__( 'Subtitle', 'knd' ),
					'attr' => 'subtitle', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array() ) ) ) );
	
	shortcode_ui_register_for_shortcode( 
		'knd_image_section', 
		array( 
			'label' => esc_html__( 'Image section', 'knd' ),  // Shortcode label in the UI. Required
			'listItemImage' => 'dashicons-format-image',  // Dashicon class or full <img> HTML
			                                             // 'post_type' => array('post'), // Limit this shortcode UI to
			                                             // specific posts. Optional
			
			'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
'label' => esc_html__( 'Description', 'knd' ) ),
			// 'description' => __('A text of the quote.', 'knd'),
			
			'attrs' => array(  // Define & bind the UI for shortcode attributes. Optional
				array( 'label' => esc_html__( 'Title', 'knd' ), 'attr' => 'title', 'type' => 'text', 'encode' => false, 'meta' => array() ),
				array( 
					'label' => esc_html__( 'Image', 'knd' ),
					'attr' => 'img', 
					'type' => 'attachment', 
					'libraryType' => array( 'image' ), 
					'addButton' => esc_html__( 'Select image', 'knd' ),
					'frameTitle' => esc_html__( 'Select section image', 'knd' ) ),
				array( 
					'label' => esc_html__( 'Text placement', 'knd' ),
					'attr' => 'text_place', 
					'type' => 'select', 
					'options' => array( 
						array( 'value' => 'ontop', 'label' => 'Поверх изображения' ), 
						array( 'value' => 'under', 'label' => 'Под изображением' ), 
						array( 'value' => 'color', 'label' => 'Цветной блок поверх изображения' ) ) ) ) ) );
	
	shortcode_ui_register_for_shortcode( 
		'knd_cta_section', 
		array( 
			'label' => esc_html__( 'CTA block', 'knd' ),  // Shortcode label in the UI. Required
			'listItemImage' => 'dashicons-controls-volumeoff',  // Dashicon class or full <img> HTML
			                                                   // 'post_type' => array('post'), // Limit this shortcode UI
			                                                   // to specific posts. Optional
			
			'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
'label' => esc_html__( 'CTA text', 'knd' ) ),
			// 'description' => __('A text of the quote.', 'knd'),
			
			'attrs' => array(  // Define & bind the UI for shortcode attributes. Optional
				array( 
					'label' => esc_html__( 'Subtitle', 'knd' ),
					'attr' => 'subtitle', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array() ), 
				array( 
					'label' => esc_html__( 'Link', 'knd' ),
					'attr' => 'link', 
					'type' => 'url', 
					'encode' => false, 
					'meta' => array() ), 
				array( 
					'label' => esc_html__( "Button label", 'knd' ),
					'attr' => 'button', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array() ) ) ) );
	
	shortcode_ui_register_for_shortcode( 
		'knd_video_caption', 
		array( 
			'label' => esc_html__( 'Video caption', 'knd' ),
			'listItemImage' => 'dashicons-format-video', 
			'inner_content' => array( 'label' => esc_html__( 'A caption text', 'knd' ) ) ) );
	
	shortcode_ui_register_for_shortcode( 
		'knd_people_list', 
		array( 
			'label' => esc_html__( 'A list of people', 'knd' ),  // Shortcode label in the UI. Required
			'listItemImage' => 'dashicons-groups',  // Dashicon class or full <img> HTML
			                                       
			// 'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
			                                       // 'label' => __('A quote text', 'knd'),
			                                       // 'description' => __('A text of the quote.', 'knd'),
			                                       // ),
			
			'attrs' => array(  // Define & bind the UI for shortcode attributes. Optional
				array( 
					'label' => esc_html__( 'List title', 'knd' ),
					'attr' => 'title', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array( 'placeholder' => esc_html__( 'E.g., "Our team"', 'knd' ) ) ),
				array( 'label' => esc_html__( 'Nubmer', 'knd' ), 'attr' => 'num', 'type' => 'text', 'encode' => false ),
				array( 
					'label' => esc_html__( 'Person categories', 'knd' ),
					'attr' => 'category', 
					'type' => 'term_select', 
					'taxonomy' => 'person_cat', 
					'multiple' => true ), 
				array( 
					'label' => esc_html__( 'Only particular people in the list', 'knd' ),
					'attr' => 'ids', 
					'type' => 'post_select', 
					'query' => array( 'post_type' => 'person' ), 
					'multiple' => true ), 
				array( 
					'label' => esc_html__( 'Custom CSS class', 'knd' ),
					'attr' => 'class', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array( 
					'placeholder' => esc_html__( 'An additional CSS class (or several) for the section', 'knd' ) ) ) ) ) );
	
	shortcode_ui_register_for_shortcode( 
		'knd_orgs_list', 
		array( 
			'label' => esc_html__( 'A list of organizations', 'knd' ),  // Shortcode label in the UI. Required
			'listItemImage' => 'dashicons-networking',  // Dashicon class or full <img> HTML
			                                           
			// 'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
			                                           // 'label' => __('A quote text', 'knd'),
			                                           // 'description' => __('A text of the quote.', 'knd'),
			                                           // ),
			
			'attrs' => array(  // Define & bind the UI for shortcode attributes. Optional
				array( 
					'label' => esc_html__( 'List title', 'knd' ),
					'attr' => 'title', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array( 'placeholder' => esc_html__( 'E.g., "Our partners"', 'knd' ) ) ),
				array( 
					'label' => esc_html__( 'Organization categories', 'knd' ),
					'attr' => 'org-categories', 
					'type' => 'term_select', 
					'taxonomy' => 'org_cat', 
					'multiple' => true ), 
				array( 
					'label' => esc_html__( 'Only particular organizations in the list', 'knd' ),
					'attr' => 'persons', 
					'type' => 'post_select', 
					'query' => array( 'post_type' => 'person' ), 
					'multiple' => true ), 
				array( 
					'label' => esc_html__( 'Section CSS class', 'knd' ),
					'attr' => 'class', 
					'type' => 'text', 
					'encode' => false, 
					'meta' => array( 
					'placeholder' => esc_html__( 'An additional CSS class (or several) for the section', 'knd' ) ) ) ) ) );
}