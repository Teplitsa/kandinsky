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
		'font-family' => 'Raleway',
		'variant'     => '500',
		'color'       => '#4d606a',
		'font-size'   => '18px',
	),
	'choices'  => array(
		'fonts'   => array(
			'google' => knd_cyrillic_fonts(),
		),
		'variant' => array(
			'regular',
			'italic',
			'500',
			'500italic',
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
		'font-family' => 'Raleway',
		'variant'     => '700',
		'color'       => '#183343',
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
			'regular',
			'italic',
			'500',
			'500italic',
			'600',
			'700',
			'700italic',
		),
	),
] );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'fonts_colors',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

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
	'default'     => '#d30a6a',
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
	'default'     => '#ab0957',
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

$socials_default = [];
$socials_choices = [];

foreach ( knd_get_social_media_supported() as $id => $label ) {
	if ( get_theme_mod( 'knd_social_' . esc_attr( $id ) ) ) {
		$socials_default[] = array(
			'network' => $id,
			'label'   => $label,
			'url'     => get_theme_mod( 'knd_social_' . esc_attr( $id ) ),
		);
	}

	$socials_choices[ $id ] = $label;
}

$socials_choices[''] = esc_html__( 'Other', 'knd' );

if( class_exists( 'Kirki\Field\Repeater' ) ) {
	new \Kirki\Field\Repeater(
		[
			'settings' => 'knd_social',
			'label'    => esc_html__( 'Social networks', 'knd' ),
			'section'  => 'socials',
			'priority' => 10,
			'button_label' => esc_html__( 'Add social network', 'knd' ),
			'row_label'    => [
				'type'  => 'field',
				'value' => esc_html__( 'Social network name', 'knd' ),
				'field' => 'label',
			],
			'default' => $socials_default,
			'fields'   => [
				'network' => [
					'type'    => 'select',
					'label'   => esc_html__( 'Select network', 'knd' ),
					'default' => 'facebook',
					'choices' => $socials_choices,
				],
				'label'   => [
					'type'        => 'text',
					'label'       => esc_html__( 'Name', 'knd' ),
					'default'     => '',
				],
				'url'    => [
					'type'  => 'text',
					'label' => esc_html__( 'URL', 'knd' ),
				],
				'image'    => [
					'type'  => 'image',
					'label' => esc_html__( 'Icon', 'knd' ),
					'description' => esc_html__( 'The recommended icon size is 40x40 pixels, and with a transparent background.', 'knd' ),
					'choices'     => [
						'save_as' => 'id',
					],
				],
			],
		]
	);
}

/* Share Buttons */
Kirki::add_section(
	'knd_share',
	array(
		'title'    => esc_html__( 'Share Buttons', 'knd' ),
		'panel'    => 'global',
		'priority' => 4,
	)
);

foreach ( knd_social_shares() as $slug => $item ) {
	$description = '';
	if ( $item['only_mobile'] ) {
		$description = esc_html__( 'Mobile only', 'knd' );
	}
	$default = true;
	if ( 'telegram' === $slug || 'viber' === $slug ) {
		$default = false;
	}
	Kirki::add_field( 'knd_theme_mod', array(
		'type'        => 'toggle',
		'settings'    => 'social_share_' . $slug,
		'label'       => $item['label'],
		'description' => $description,
		'section'     => 'knd_share',
		'default'     => $default,
	) );
}

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'     => 'knd_share',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'radio',
		'settings' => 'social_share_location',
		'label'    => esc_html__( 'Location', 'knd' ),
		'section'  => 'knd_share',
		'default'  => 'left',
		'choices'  => array(
			'left'   => esc_html__( 'Left Side', 'knd' ),
			'bottom' => esc_html__( 'After Content', 'knd' ),
		),
) );

/* Miscellaneous Settings Section */
Kirki::add_section(
	'miscellaneous',
	array(
		'title'    => esc_html__( 'Miscellaneous Settings', 'knd' ),
		'panel'    => 'global',
		'priority' => 5,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'button_totop',
	'label'           => esc_html__( 'Scroll To Top Button', 'knd' ),
	'section'         => 'miscellaneous',
	'default'         => true,
) );

/* Analitics Section */
Kirki::add_section(
	'analitics',
	array(
		'title'    => esc_html__( 'Analytics', 'knd' ),
		'panel'    => 'global',
		'priority' => 6,
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
		'priority' => 6,
	)
);

$important_links_arr = array( 
	'theme-info' => array( 
		'link' => esc_url( KND_OFFICIAL_WEBSITE_URL ),
		'text' => esc_html__( 'Theme Info', 'knd' ),
	),
	'support' => array( 
		'link' => esc_url( KND_OFFICIAL_SUPPORT_URL ),
		'text' => esc_html__( 'Support', 'knd' ),
	), 
	'documentation' => array( 
		'link' => esc_url( KND_DOC_URL ),
		'text' => esc_html__( 'Documentation', 'knd' ),
	),
	'changelog' => array( 
		'link' => esc_url( KND_CHANGELOG_URL ),
		'text' => esc_html__( 'Changelog', 'knd' ),
	),
);

$important_links = '';

foreach ( $important_links_arr as $important_link ) {
	$important_links .= '<div class="knd-customizer-heading"><a target="_blank" href="' . $important_link['link'] . '" class="knd-customizer-link">' . esc_attr( 
		$important_link['text'] ) . ' </a></div>';
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
