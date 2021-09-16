<?php
/**
 * Homepage Settings
 *
 * @package Kandinsky
 */

/* Static Front Page */
Kirki::add_section(
	'static_front_page',
	array(
		'panel'    => 'pages',
		'title'    => esc_html__( 'Main page', 'knd' ),
		'priority' => 1,
	)
);
