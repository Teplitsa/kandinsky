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

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'custom',
	'settings' => 'footer_separator',
	'section'  => 'footer',
	'default'  => '',
	'priority' => 1,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'        => 'custom',
	'settings'    => 'footer_desc',
	'label'       => esc_html__( 'Go to the menu "In the footer"', 'knd' ),
	'section'     => 'footer',
		'default'         => '<a href="#" class="knd-customize-focus button" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to Menu', 'knd' ) . '</a>',
	'priority'    => 1,
] );

/* Footer widgets */
Kirki::add_section(
	'sidebar-widgets-knd-footer-sidebar',
	array(
		'section'    => 'footer',
		'title'    => esc_html__( 'Footer - Columns', 'knd' ),
		'priority' => 2,
	)
);

/* Social media links section */
Kirki::add_section(
	'knd_social_links',
	array(
		'section'  => 'footer',
		'title'    => esc_html__( 'Social networks links', 'knd' ),
		'priority' => 3,
	)
);

foreach ( knd_get_social_media_supported() as $id => $data ) {

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'url',
		'settings' => 'knd_social_links_' . $id, 
		'label'    => $data['label'], 
		'section'  => 'knd_social_links',
	] );

}
