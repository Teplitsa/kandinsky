<?php
/**
 * Menu Settings
 *
 * @package Kandinsky
 */

/* Menu Panel */
Kirki::add_panel(
	'nav_menus',
	array(
		'title'    => esc_html__( 'Menu' ),
		'priority' => 2,
	)
);
