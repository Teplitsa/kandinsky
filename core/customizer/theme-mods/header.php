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

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'custom',
	'settings' => 'header_desc',
	'label'    => esc_html__( 'Go to the menu "In the header"', 'knd' ),
	'section'  => 'header',
	'default'  => '<a href="#" class="knd-customize-focus button" data-toggle="panel" data-focus="nav_menus">' . esc_html__( 'Go to Menu', 'knd' ) . '</a>',
	'priority' => 10,
] );
