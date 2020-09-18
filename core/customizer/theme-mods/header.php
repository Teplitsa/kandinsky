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
	'default'  => '0',
	'priority' => 10,
	'choices'  => array(
		'0' => get_template_directory_uri() . '/core/customizer/images/header-0.png',
		'1' => get_template_directory_uri() . '/core/customizer/images/header-1.png',
		'2' => get_template_directory_uri() . '/core/customizer/images/header-2.png',
		'3' => get_template_directory_uri() . '/core/customizer/images/header-3.png',
		'4' => get_template_directory_uri() . '/core/customizer/images/header-4.png',
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'image',
	'settings' => 'header_logo_image',
	'label'    => esc_html__( 'Logo', 'knd' ),
	'section'  => 'header',
	'default'  => get_theme_mod( 'knd_custom_logo' ),
	'choices'  => array(
		'save_as' => 'id',
	),
) );

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
		'font-family' => 'Exo 2',
		'variant'     => '800',
		'font-size'   => '22px',
	),
	'choices'         => array(
		'fonts' => array(
			'google' => knd_cyrillic_fonts(),
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
	'settings'  => 'font_logo_color',
	'label'     => esc_html__( 'Logo Color', 'knd' ),
	'section'   => 'header',
	'default'   => '#000000',
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => ':root',
			'property' => '--knd-color-logo',
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
	'default'           => get_theme_mod( 'text_in_header' ),
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'header_type',
			'operator' => 'in',
			'value'    => array( '0', '1' ),
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
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_type',
			'operator' => '==',
			'value'    => '3',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'header_menu',
	'label'           => esc_html__( 'Menu', 'knd' ),
	'description'     => '<a href="#" class="knd-customize-focus" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to the menu "In the header"', 'knd' ) . '</a>',
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
	'default'           => get_theme_mod( 'knd_hero_image_support_button_caption' ),
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
	'default'         => get_theme_mod( 'knd_hero_image_support_url' ),
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
	'default'         => '1',
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
			'value'    => '1',
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
			'value'    => '1',
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
	'label'    => esc_html__( 'Display Off-Canvas', 'knd' ),
	'section'  => 'header',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_menu',
	'label'           => esc_html__( 'Menu', 'knd' ),
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_offcanvas',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_search',
	'label'           => esc_html__( 'Search', 'knd' ),
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_offcanvas',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_button',
	'label'           => esc_html__( 'Button', 'knd' ),
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_offcanvas',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'header_button',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'toggle',
	'settings'        => 'offcanvas_social',
	'label'           => esc_html__( 'Social networks', 'knd' ),
	'section'         => 'header',
	'default'         => '1',
	'active_callback' => array(
		array(
			'setting'  => 'header_offcanvas',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'header',
	'default'  => '<div class="knd-customizer-heading">' . esc_html__( 'Social networks links', 'knd' ) . '</div>',
) );

foreach ( knd_get_social_media_supported() as $id => $data ) {

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'url',
		'settings' => 'knd_header_social_' . esc_attr( $id ),
		'label'    => esc_html( $data['label'] ),
		'section'  => 'header',
	) );

}
