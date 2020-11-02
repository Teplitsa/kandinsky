<?php
/**
 * Footer Settings
 *
 * @package Kandinsky
 */

/* Header Section */
Kirki::add_section(
	'footer',
	array(
		'title'    => esc_html__( 'Footer', 'knd' ),
		'priority' => 6,
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
	'label'    => esc_html__( 'Go to the menu "In the footer"', 'knd' ),
	'section'  => 'footer',
	'default'  => '<a href="#" class="knd-customize-focus button" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to Menu', 'knd' ) . '</a>',
	'priority' => 1,
) );

/* Footer widgets */
Kirki::add_section(
	'footer_logo',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Logo and social networks', 'knd' ),
		'priority' => 2,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_logo',
	'label'    => esc_html__( 'Logo', 'knd' ),
	'section'  => 'footer_logo',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'            => 'image',
	'settings'        => 'footer_logo_image',
	'section'         => 'footer_logo',
	'default'         => get_theme_mod( 'header_logo_image', get_theme_mod( 'knd_custom_logo' ) ),
	'choices'         => array(
		'save_as' => 'id',
	),
	'active_callback' => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => '1',
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
	'active_callback'   => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => '1',
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
	'active_callback'   => array(
		array(
			'setting'  => 'footer_logo',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_logo',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_social',
	'label'    => esc_html__( 'Social networks links', 'knd' ),
	'section'  => 'footer_logo',
	'default'  => '1',
) );

foreach ( knd_get_social_media_supported() as $id => $data ) {

	Kirki::add_field( 'knd_theme_mod', array(
		'type'            => 'url',
		'settings'        => 'knd_social_links_' . $id,
		'label'           => $data['label'],
		'section'         => 'footer_logo',
		'active_callback' => array(
			array(
				'setting'  => 'footer_social',
				'operator' => '==',
				'value'    => '1',
			),
		),
	) );

}

/* Footer widgets */
Kirki::add_section(
	'sidebar-widgets-knd-footer-sidebar',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Footer - Columns', 'knd' ),
	)
);

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
	'default'  => '1',
) );

$copyright = sprintf( __( 'All materials of the site are avaliabe under license %s', 'knd' ), '<a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">' . esc_html__( 'Creative Commons СС-BY-SA 3.0', 'knd' ) . '</a>' );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'editor',
	'settings'          => 'footer_copyright_text',
	'section'           => 'footer_copyright',
	'default'           => $copyright,
	'sanitize_callback' => 'wp_kses_post',
	'active_callback'   => array(
		array(
			'setting'  => 'footer_copyright',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'footer_' . wp_unique_id( 'divider_' ),
	'section'  => 'footer_copyright',
	'default'  => esc_html__( 'The creators of the constructor will be grateful if you leave the information about Kandinsky included.', 'knd' ),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'footer_creator',
	'label'    => esc_html__( 'Display the Kandinsky logo', 'knd' ),
	'section'  => 'footer_copyright',
	'default'  => '1',
) );
