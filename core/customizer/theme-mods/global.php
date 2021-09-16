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
			// 'property' => '--knd-color-headings',
			// 'choice'   => 'color',
		),
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
			'800',
			'800italic',
		),
	),
] );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'color',
	'settings'    => 'knd_page_bg_color',
	'label'       => esc_html__( 'Page Background color', 'knd' ),
	'description' => esc_html__( 'Recommended - white', 'knd' ),
	'section'     => 'fonts_colors',
	'default'     => '#ffffff',
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element' => ':root',
			'property' => '--knd-page-bg-color',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'color',
	'settings'    => 'knd_main_color',
	'label'       => esc_html__( 'Buttons and links color', 'knd' ),
	'description' => esc_html__( 'Also used in other decorative elements', 'knd' ),
	'section'     => 'fonts_colors',
	'default'     => '#f43724',
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element' => ':root',
			'property' => '--knd-color-main',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'color',
	'settings'    => 'knd_main_color_active',
	'label'       => esc_html__( 'Buttons and links color hover', 'knd' ),
	'description' => esc_html__( 'Also used in other active elements', 'knd' ), 
	'section'     => 'fonts_colors',
	'default'     => '#db3120',
	'transport'   => 'auto',
	'output'      => array(
		array(
			'element' => ':root',
			'property' => '--knd-color-main-active',
		),
	),
) );

/* Social networks links Section */
Kirki::add_section(
	'socials',
	array(
		'title'    => esc_html__( 'Social networks', 'knd' ),
		'panel'    => 'global',
		'priority' => 3,
	)
);

foreach ( knd_get_social_media_supported() as $id => $data ) {

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'url',
		'settings' => 'knd_social_' . esc_attr( $id ),
		'label'    => esc_html( $data['label'] ),
		'section'  => 'socials',
	) );

}

// Kirki::add_field( 'knd_theme_mod', array(
// 	'type'     => 'custom',
// 	'settings' => 'fonts_colors_' . wp_unique_id( 'divider_' ),
// 	'section'  => 'fonts_colors',
// 	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Лейка', 'knd' ) . '</div>',
// ) );

// Kirki::add_field( 'knd_theme_mod', array(
// 	'type'        => 'color',
// 	'settings'    => 'knd_leyka_color_main',
// 	'label'       => esc_html__( 'Цвет фона активных кнопок и переключателей', 'knd' ),
// 	'section'     => 'fonts_colors',
// 	'default'     => '#ff510d',
// 	'transport'   => 'auto',
// 	'output'      => array(
// 		array(
// 			'element' => ':root',
// 			'property' => '--leyka-color-main',
// 			'context'  => array( 'editor', 'front' ),
// 		),
// 	),
// ) );

/* Nav Menus */
/*Kirki::add_panel(
	'nav_menus',
	array(
		'title'    => esc_html__( 'Menus' ),
		'panel'    => 'global',
		'priority' => 10,
	)
);*/

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

/* Yoast SEO Breadcrumbs */
Kirki::add_section(
	'wpseo_breadcrumbs_customizer_section',
	array(
		'title'    => esc_html__( 'Yoast SEO Breadcrumbs', 'knd' ),
		'panel'    => 'global',
		'priority' => 10,
	)
);

/* Custom CSS Section */
Kirki::add_section(
	'custom_css',
	array(
		'title'    => esc_html__( 'Additional CSS' ),
		'panel'    => 'global',
		'priority' => 10,
	)
);
