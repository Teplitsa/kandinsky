<?php
/**
 * Campaign Settings
 *
 * @package Kandinsky
 */

/* Prject section */
Kirki::add_section(
	'campaign_settings',
	array(
		'panel'    => 'pages',
		'title'    => esc_html_x( 'Campaign Settings', 'Customizer', 'knd' ),
		'priority' => 13,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'campaign_link_above_title',
	'label'    => esc_html__( 'Show link above title', 'knd' ),
	'section'  => 'campaign_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'campaign_links_after_content',
	'label'    => esc_html__( 'Show links after content', 'knd' ),
	'section'  => 'campaign_settings',
	'default'  => '1',
) );
