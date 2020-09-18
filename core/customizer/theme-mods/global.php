<?php
/**
 * Global Settings
 *
 * @package Kandinsky
 */

/* Global Panel */
Kirki::add_panel(
	'global',
	array(
		'title'    => esc_html__( 'Global', 'knd' ),
		'priority' => 1,
	)
);

/* Site Properties Section */
Kirki::add_section(
	'title_tagline',
	array(
		'title'    => esc_html__( 'Site Properties', 'knd' ),
		'panel'    => 'global',
		'priority' => 1,
	)
);

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'radio',
	'settings' => 'knd_custom_logo_mod',
	'label'    => esc_html__( 'Logo mode', 'knd' ),
	'section'  => 'title_tagline',
	'default'  => 'text_only',
	'priority' => 1,
	'choices'  => array( 
		'image_only' => esc_html__( 'Image only', 'knd' ), 
		'image_text' => esc_html__( 'Image with site name', 'knd' ), 
		'text_only'  => esc_html__( 'Site name only', 'knd' ), 
		'nothing'    => esc_html__( 'Do not show', 'knd' ),
	)
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'              => 'textarea',
	'settings'          => 'text_in_header',
	'label'             => esc_html__( 'Header text', 'knd' ),
	'section'           => 'title_tagline',
	'sanitize_callback' => 'wp_kses_post',
	'priority'          => 10,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'image',
	'settings'    => 'knd_custom_logo',
	'label'       => esc_html__( 'Logo', 'knd' ),
	'section'     => 'title_tagline',
	'description' => esc_html__( 'We recommend to use logos in the .svg format (vector graphics). If your logo is in rastre format, we recommend to upload pictures with the dimensions of 630x132 px for the option "Only Image" or 132x13px for the option "Image and Title".', 'knd' ),
	'priority'    => 11,
	'choices'     => [
		'save_as' => 'id',
	],
] );

/* Fonts and Colors Section */
Kirki::add_section(
	'fonts_colors',
	array(
		'title'    => esc_html__( 'Fonts and colors', 'knd' ),
		'panel'    => 'global',
		'priority' => 2,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'typography',
	'settings' => 'font_base',
	'label'    => esc_html__( 'Main text', 'knd' ),
	'section'  => 'fonts_colors',
	'default'  => array(
		'font-family' => 'Jost',
		'variant'     => 'regular',
		'color'       => '#000000',
		'font-size'   => '16px',
	),
	'choices'  => array(
		'fonts'   => array(
			'google' => knd_cyrillic_fonts(),
		),
		'variant' => array(
			'300',
			'regular',
			'italic',
			'500',
			'600',
			'700',
			'700italic',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'typography',
	'settings' => 'font_headings',
	'label'    => esc_html__( 'Headings', 'knd' ),
	'section'  => 'fonts_colors',
	'default'  => array(
		'font-family' => 'Exo 2',
		'variant'     => '800',
		'color'       => '#000000',
	),
	'output'     => array(
		array(
			'element' => array(
				'.the-content h1',
				'.the-content h2',
				'.the-content h3',
				'.the-content h4',
				'.the-content h5',
				'.the-content h6',
			),
		),
	),
	'choices'  => array(
		'fonts' => array(
			'google' => knd_cyrillic_fonts(),
		),
	),
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_page_text_color',
	'label'       => esc_html__( 'Main Text Color', 'knd' ),
	'description' => esc_html__( 'Recommended - black', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#000000',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-color-base',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_page_bg_color',
	'label'       => esc_html__( 'Page Background color', 'knd' ),
	'description' => esc_html__( 'Recommended - white', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#ffffff',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-page-bg-color',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_main_color',
	'label'       => esc_html__( 'Buttons and links color', 'knd' ),
	'section'     => 'fonts_colors',
	'default'     => '#52b9d1',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-color-main',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_color_second',
	'label'       => esc_html__( 'Complimentary Color', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#4494bb',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-color-second',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_text1_color',
	'label'       => esc_html__( 'Inverse Text Color', 'knd' ),
	'description' => esc_html__( 'Recommended - white', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#000000',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-text1-color',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_text2_color',
	'label'       => esc_html__( 'Accent Text Color - 1', 'knd' ), 
	'description' => esc_html__( 'Applicable only for Dubrovino template', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#000000',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-text2-color',
		],
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'color',
	'settings'    => 'knd_text3_color',
	'label'       => esc_html__( 'Accent Text Color - 2', 'knd' ), 
	'description' => esc_html__( 'Applicable only for Dubrovino template', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#000000',
	'transport'   => 'auto',
	'output'      => [
		[
			'element' => ':root',
			'property' => '--knd-text3-color',
		],
	],
] );

/* Analitics Section */
Kirki::add_section(
	'analitics',
	array(
		'title'    => esc_html__( 'Analytics', 'knd' ),
		'panel'    => 'global',
		'priority' => 3,
	)
);

Kirki::add_field( 'knd_theme_mod', [
	'type'      => 'custom',
	'settings'  => 'analitics_desc',
	'default'     => '<p>' . esc_html__( 'Here you can insert the code for user analytics systems, Google Analytics or Yandex.Metrica. Below are 3 areas of the site where you can paste the code, depending on the requirements of the systems being installed. Just copy it here.', 'knd' ) . '</p>',
	'section'   => 'analitics',
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'textarea',
	'settings' => 'analitics_head',
	'label'    => esc_html__( 'Before </head>', 'knd' ),
	'section'  => 'analitics',
	'input_attrs' => array(
		'placeholder' => esc_html__( 'Code can be copied here.', 'knd' ),
	),
	'transport' => 'postMessage',
	'sanitize_callback' => 'knd_kses_metrics',
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'textarea',
	'settings' => 'analitics_body_open',
	'label'    => esc_html__( 'At the beginning of <body>', 'knd' ),
	'section'  => 'analitics',
	'input_attrs' => array(
		'placeholder' => esc_html__( 'Code can be copied here.', 'knd' ),
	),
	'transport' => 'postMessage',
	'sanitize_callback' => 'knd_kses_metrics',
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'textarea',
	'settings' => 'analitics_body',
	'label'    => esc_html__( 'Before </body>', 'knd' ),
	'section'  => 'analitics',
	'input_attrs' => array(
		'placeholder' => esc_html__( 'Code can be copied here.', 'knd' ),
	),
	'transport' => 'postMessage',
	'sanitize_callback' => 'knd_kses_metrics',
] );

/* Titles and captions Section */
Kirki::add_section(
	'knd_titles_and_captions',
	array(
		'title'    => esc_html__( 'Titles and captions', 'knd' ),
		'panel'    => 'global',
		'priority' => 4,
	)
);

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'knd_news_archive_title',
	'label'    => esc_html__( 'News archive title', 'knd' ),
	'section'  => 'knd_titles_and_captions',
	'default'  => esc_html__('News', 'knd')
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'knd_projects_archive_title',
	'label'    => esc_html__( 'Projects archive title', 'knd' ),
	'section'  => 'knd_titles_and_captions',
	'default'  => esc_html__( 'Our projects', 'knd' )
] );

// Register controls if plugin leyka is active.
if ( defined( 'LEYKA_VERSION' ) ) {

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'knd_active_campaigns_archive_title',
		'label'    => esc_html__( 'Active campaigns archive title', 'knd' ),
		'section'  => 'knd_titles_and_captions',
		'default'  => esc_html__( 'They need help', 'knd' ),
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'knd_completed_campaigns_archive_title',
		'label'    => esc_html__( 'Completed campaigns archive title', 'knd' ),
		'section'  => 'knd_titles_and_captions',
		'default'  => esc_html__( 'They alredy got help', 'knd' ),
	] );

}

/* Important Links Section */
Kirki::add_section(
	'knd_important_links',
	array(
		'title'    => esc_html__( 'Important Links', 'knd' ),
		'panel'    => 'global',
		'priority' => 5,
	)
);

$important_links_arr = array( 
	'theme-info' => array( 
		'link' => esc_url( TST_OFFICIAL_WEBSITE_URL ),
		'text' => esc_html__( 'Theme Info', 'knd' ),
	),
	'support' => array( 
		'link' => esc_url( KND_SUPPORT_EMAIL ),
		'text' => esc_html__( 'Support', 'knd' ),
	), 
	'documentation' => array( 
		'link' => esc_url( KND_DOC_URL ),
		'text' => esc_html__( 'Documentation', 'knd' ),
	),
);

$important_links = '';

foreach ( $important_links_arr as $important_link ) {
	$important_links .= '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr( 
		$important_link['text'] ) . ' </a></p>';
}

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'custom',
	'settings' => 'knd_important_links_custom',
	'section'  => 'knd_important_links',
	'default'  => wp_kses_post( $important_links ),
	'priority' => 1,
] );

/* Custom CSS Section */
Kirki::add_section(
	'custom_css',
	array(
		'title'    => esc_html__( 'Additional CSS' ),
		'panel'    => 'global',
		'priority' => 10,
	)
);
