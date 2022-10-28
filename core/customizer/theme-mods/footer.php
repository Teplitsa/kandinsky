<?php
/**
 * Footer Settings
 *
 * @package Kandinsky
 */

/* Footer Section */
Kirki::add_section(
	'footer',
	array(
		'title'    => esc_html__( 'Footer', 'knd' ),
		'priority' => 7,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_separator',
	'section'  => 'footer',
	'default'  => '',
	'priority' => 1,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_desc',
	'label'    => esc_html__( 'Go to the menu &quot;In the footer&quot;', 'knd' ),
	'section'  => 'footer',
	'default'  => '<a href="#" class="knd-customize-focus button" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to Menu', 'knd' ) . '</a>',
	'priority' => 1,
) );

/* Footer widgets */
Kirki::add_section(
	'footer_logo',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Logo', 'knd' ),
		'priority' => 2,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_logo',
	'label'    => esc_html__( 'Logo', 'knd' ),
	'section'  => 'footer_logo',
	'default'  => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'image',
	'settings'        => 'footer_logo_image',
	'section'         => 'footer_logo',
	'default'         => knd_get_logo_id(),
	'choices'         => array(
		'save_as' => 'id',
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'footer_logo_title',
	'label'             => esc_html__( 'Name', 'knd' ),
	'section'           => 'footer_logo',
	'default'           => get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ),
	'sanitize_callback' => 'wp_kses_post',
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'textarea',
	'settings'          => 'footer_logo_text',
	'label'             => esc_html__( 'Description', 'knd' ),
	'section'           => 'footer_logo',
	'default'           => get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ),
	'sanitize_callback' => 'wp_kses_post',
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'checkbox',
	'settings' => 'font_footer_logo_default',
	'label'    => esc_html__( 'Use Default Font', 'knd' ),
	'section'  => 'footer_logo',
	'default'  => true,
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'typography',
	'settings'        => 'font_footer_logo',
	'section'         => 'footer_logo',
	'default'         => array(
		'font-family' => 'Raleway',
		'variant'     => '700',
		'font-size'   => '22px',
	),
	'choices'         => array(
		'fonts' => array(
			'google' => knd_cyrillic_fonts(),
		),
		'variant' => array(
			'regular',
			'italic',
			'500',
			'600',
			'700',
			'700italic',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'font_footer_logo_default',
			'operator' => '==',
			'value'    => false,
		),
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_logo_color',
	'label'     => esc_html__( 'Logo Color', 'knd' ),
	'section'   => 'footer_logo',
	'default'   => '#183343',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-footer-logo',
			'property' => '--knd-color-logo',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_logo_desc_color',
	'label'     => esc_html__( 'Logo Description Color', 'knd' ),
	'section'   => 'footer_logo',
	'default'   => '#4d606a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-footer-logo',
			'property' => '--knd-color-logo-desc',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

/* Footer widgets */
Kirki::add_section(
	'sidebar-widgets-knd-footer-sidebar',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Footer - Columns', 'knd' ),
	)
);

/* Footer Columns */
Kirki::add_section(
	'footer_columns',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Footer - Columns', 'knd' ),
	)
);

// Footer about.
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_columns',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'About Us', 'knd' ) . '</div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'footer_about_title',
	'section'           => 'footer_columns',
	'sanitize_callback' => 'wp_kses_post',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'editor',
	'settings'          => 'footer_about',
	'section'           => 'footer_columns',
	'sanitize_callback' => 'knd_kses',
) );

// Footer menu our work.
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_columns',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Menu our work', 'knd' ) . '</div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'footer_menu_ourwork_title',
	'section'           => 'footer_columns',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'footer_menu_ourwork',
	'section'     => 'footer_columns',
	'default'     => '0',
	'choices'     => knd_get_menus_option(),
) );

// Footer menu news.
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_columns',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Menu news', 'knd' ) . '</div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'footer_menu_news_title',
	'section'           => 'footer_columns',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'footer_menu_news',
	'section'     => 'footer_columns',
	'default'     => '0',
	'choices'     => knd_get_menus_option(),
) );

// Footer policy.
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_columns',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Security policy', 'knd' ) . '</div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'footer_policy_title',
	'section'           => 'footer_columns',
	'sanitize_callback' => 'wp_kses_post',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'editor',
	'settings'          => 'footer_policy',
	'section'           => 'footer_columns',
	'sanitize_callback' => 'knd_kses',
) );

/* Footer copyright */
Kirki::add_section(
	'footer_copyright',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Copyright, license, etc.', 'knd' ),
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_copyright',
	'label'    => esc_html__( 'License information', 'knd' ),
	'section'  => 'footer_copyright',
	'default'  => true,
) );

$copyright = sprintf( __( 'All materials of the site are avaliabe under license %s', 'knd' ), '<a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">' . esc_html__( 'Creative Commons СС-BY-SA 3.0', 'knd' ) . '</a>' );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'editor',
	'settings'          => 'footer_copyright_text',
	'section'           => 'footer_copyright',
	'default'           => $copyright,
	'sanitize_callback' => 'knd_kses',
	'active_callback'   => array(
		array(
			'setting'  => 'footer_copyright',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_copyright',
	'default'  => esc_html__( 'The creators of the constructor will be grateful if you leave the information about Kandinsky included.', 'knd' ),
	'active_callback'   => array(
		array(
			'setting'  => 'footer_copyright',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_creator',
	'label'    => esc_html__( 'Display the Kandinsky logo', 'knd' ),
	'section'  => 'footer_copyright',
	'default'  => true,
	'active_callback'   => array(
		array(
			'setting'  => 'footer_copyright',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_background',
	'label'     => esc_html__( 'Footer Background', 'knd' ),
	'section'   => 'footer',
	'default'   => '#f7f8f8',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-footer',
			'property' => '--knd-footer-background',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_heading_color',
	'label'     => esc_html__( 'Footer Headings Color', 'knd' ),
	'section'   => 'footer',
	'default'   => '#183343',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => ':root',
			'property' => '--knd-footer-heading-color',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_color',
	'label'     => esc_html__( 'Footer Text Color', 'knd' ),
	'section'   => 'footer',
	'default'   => '#4d606a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => ':root',
			'property' => '--knd-footer-color',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_color_link',
	'label'     => esc_html__( 'Footer Links Color', 'knd' ),
	'section'   => 'footer',
	'default'   => '#d30a6a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => ':root',
			'property' => '--knd-footer-link-color',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_color_link_hover',
	'label'     => esc_html__( 'Footer Links Color Hover', 'knd' ),
	'section'   => 'footer',
	'default'   => '#ab0957',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => ':root',
			'property' => '--knd-footer-link-color-hover',
		),
	),
) );

/**
 * Social Networks
 */
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'toggle',
	'settings'    => 'footer_social',
	'label'       => esc_html__( 'Social networks', 'knd' ),
	'description' => '<a href="#" class="knd-customize-focus" data-toggle="section" data-focus="socials">' . esc_html__( 'Manage social networks', 'knd' ) . '</a>.',
	'section'     => 'footer',
	'default'     => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_color_social',
	'label'     => esc_html__( 'Footer Social Color', 'knd' ),
	'section'   => 'footer',
	'default'   => '#183343',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-footer',
			'property' => '--knd-social-color',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_social',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'footer_color_social_hover',
	'label'     => esc_html__( 'Footer Social Color Hover', 'knd' ),
	'section'   => 'footer',
	'default'   => '#4d606a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-footer',
			'property' => '--knd-social-color-hover',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_social',
			'operator' => '==',
			'value'    => true,
		),
	),
) );
