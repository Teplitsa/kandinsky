<?php
/**
 * Header Settings
 *
 * @package Kandinsky
 */

/* Header Section */
Kirki::add_section(
	'header',
	array(
		'title'    => esc_html__( 'Header', 'knd' ),
		'priority' => 3,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'radio-image',
	'settings' => 'header_type',
	'label'    => esc_html__( 'Select header template', 'knd' ),
	'section'  => 'header',
	'default'  => '2',
	'priority' => 1,
	'choices'  => array(
		'1' => get_template_directory_uri() . '/core/customizer/images/header-1.png',
		'2' => get_template_directory_uri() . '/core/customizer/images/header-2.png',
		'3' => get_template_directory_uri() . '/core/customizer/images/header-3.png',
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'dimension',
	'settings' => 'header_height',
	'label'    => esc_html__( 'Header Height', 'knd' ),
	'section'  => 'header',
	'default'  => '124px',
	'transport' => 'auto',
	'priority' => 2,
	'output'   => array(
		array(
			'element'  => ':root',
			'property' => '--knd-header-height',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'header_background',
	'label'     => esc_html__( 'Header Background', 'knd' ),
	'section'   => 'header',
	'default'   => '#ffffff',
	'transport' => 'auto',
	'priority'  => 3,
	'output'    => array(
		array(
			'element'  => '.knd-header',
			'property' => '--knd-header-background',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-divider"></div>',
	'priority' => 4,
) );

/* Here is the custom_logo control */

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'header_logo_title',
	'label'             => esc_html__( 'Name', 'knd' ),
	'section'           => 'header',
	'default'           => get_bloginfo( 'name' ),
	'sanitize_callback' => 'wp_kses_post',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'textarea',
	'settings'          => 'header_logo_text',
	'label'             => esc_html__( 'Description', 'knd' ),
	'section'           => 'header',
	'default'           => get_bloginfo( 'description' ),
	'sanitize_callback' => 'wp_kses_post',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'textarea',
	'settings'          => 'header_custom_text',
	'label'             => esc_html__( 'Custom Text', 'knd' ),
	'section'           => 'header',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '1' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'custom',
	'settings' => 'font_logo_header',
	'section'  => 'header',
	'default'  => '<span class="customize-control-title">' . esc_html__( 'Font For Logo', 'knd' ) . '</span>',
] );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'checkbox',
	'settings' => 'font_logo_default',
	'label'    => esc_html__( 'Use Default Font', 'knd' ),
	'section'  => 'header',
	'default'  => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'typography',
	'settings'        => 'font_logo',
	'section'         => 'header',
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
			'setting'  => 'font_logo_default',
			'operator' => '==',
			'value'    => false,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'header_logo_color',
	'label'     => esc_html__( 'Logo Color', 'knd' ),
	'section'   => 'header',
	'default'   => '#183343',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-header-logo, .knd-header-mobile-logo',
			'property' => '--knd-color-logo',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'header_logo_desc_color',
	'label'     => esc_html__( 'Logo Description Color', 'knd' ),
	'section'   => 'header',
	'default'   => '#4d606a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-header-logo',
			'property' => '--knd-color-logo-desc',
		),
	),
) );

// Menu
Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-divider"></div>',
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'header_menu',
	'label'           => esc_html__( 'Menu', 'knd' ),
	'description'     => '<a href="#" class="knd-customize-focus" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to the menu &quot;In the header&quot;', 'knd' ) . '</a>',
	'section'         => 'header',
	'default'         => true,
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
	),
) );

Kirki::add_field( 'theme_config_id', [
	'type'        => 'dimension',
	'settings'    => 'header_menu_size',
	'label'       => esc_html__( 'Menu Font Size', 'knd' ),
	'section'     => 'header',
	'default'     => '18px',
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
		array(
			'setting'  => 'header_menu',
			'operator' => '==',
			'value'    => true,
		),
	),
] );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'header_menu_color',
	'label'     => esc_html__( 'Menu Links Color', 'knd' ),
	'section'   => 'header',
	'default'   => '#4d606a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-header-nav',
			'property' => '--knd-color-menu',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
		array(
			'setting'  => 'header_menu',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'header_menu_color_hover',
	'label'     => esc_html__( 'Menu Links Color Hover', 'knd' ),
	'section'   => 'header',
	'default'   => '#d30a6a',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-header-nav',
			'property' => '--knd-color-menu-hover',
		),
	),
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
		array(
			'setting'  => 'header_menu',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'header_email',
	'label'             => esc_html__( 'Email', 'knd' ),
	'section'           => 'header',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_type',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'header_phone',
	'label'             => esc_html__( 'Phone', 'knd' ),
	'section'           => 'header',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_type',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'textarea',
	'settings'          => 'header_address',
	'label'             => esc_html__( 'Address', 'knd' ),
	'section'           => 'header',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '1' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'header_search',
	'label'           => esc_html__( 'Search', 'knd' ),
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2', '3', '4' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'header_social',
	'label'           => esc_html__( 'Social networks', 'knd' ),
	'description'     => '<a href="#" class="knd-customize-focus" data-toggle="section" data-focus="socials">' . esc_html__( 'Manage social networks', 'knd' ) . '</a>.',
	'section'         => 'header',
	'default'         => false,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'header_button',
	'label'    => esc_html__( 'Button', 'knd' ),
	'section'  => 'header',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'header_button_text',
	'label'             => esc_html__( 'Button Text', 'knd' ),
	'section'           => 'header',
	'default'           => esc_html__( 'Button Text', 'knd' ),
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_button',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'link',
	'settings'        => 'header_button_link',
	'label'           => esc_html__( 'Button Link', 'knd' ),
	'section'         => 'header',
	'default'         => '',
	'active_callback' => array(
		array(
			'setting'  => 'header_button',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'header_additional_button',
	'label'           => esc_html__( 'Additional Button', 'knd' ),
	'section'         => 'header',
	'default'         => false,
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'header_additional_button_text',
	'label'             => esc_html__( 'Additional Button Text', 'knd' ),
	'section'           => 'header',
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_additional_button',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'link',
	'settings'        => 'header_additional_button_link',
	'label'           => esc_html__( 'Additional Button Link', 'knd' ),
	'section'         => 'header',
	'active_callback' => array(
		array(
			'setting'  => 'header_additional_button',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '2' ),
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Off-Canvas', 'knd' ) . '</div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'header_offcanvas',
	'label'    => esc_html__( 'Display Off-Canvas Toggle Button', 'knd' ),
	'section'  => 'header',
	'default'  => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_menu',
	'label'           => esc_html__( 'Menu', 'knd' ),
	'section'         => 'header',
	'default'         => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_search',
	'label'           => esc_html__( 'Search', 'knd' ),
	'section'         => 'header',
	'default'         => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_button',
	'label'           => esc_html__( 'Button', 'knd' ),
	'section'         => 'header',
	'default'         => true,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'offcanvas_button_text',
	'label'             => esc_html__( 'Button Text', 'knd' ),
	'section'           => 'header',
	'default'           => esc_html__( 'Button Text', 'knd' ),
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'offcanvas_button',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'link',
	'settings'        => 'offcanvas_button_link',
	'label'           => esc_html__( 'Button Link', 'knd' ),
	'section'         => 'header',
	'default'         => '',
	'active_callback' => array(
		array(
			'setting'  => 'offcanvas_button',
			'operator' => '==',
			'value'    => true,
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_social',
	'label'           => esc_html__( 'Social networks', 'knd' ),
	'section'         => 'header',
	'default'         => false,
) );
