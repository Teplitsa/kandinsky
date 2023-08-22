<?php
/**
 * Project Settings
 *
 * @package Kandinsky
 */

/* Prject section */
Kirki::add_section(
	'project_settings',
	array(
		'panel'    => 'pages',
		'title'    => esc_html__( 'Project Settings', 'knd' ),
		'priority' => 13,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'project_featured_image',
	'label'    => esc_html__( 'Featured image', 'knd' ),
	'section'  => 'project_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'project_social_shares',
	'label'    => esc_html__( 'Social Shares', 'knd' ),
	'section'  => 'project_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'project_tags',
	'label'    => esc_html__( 'Tags', 'knd' ),
	'section'  => 'project_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'project_related',
	'label'    => esc_html__( 'Related Projects', 'knd' ),
	'section'  => 'project_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'project_related_title',
	'label'             => esc_html__( 'Related Projects Title', 'knd' ),
	'section'           => 'project_settings',
	'default'           => esc_html__( 'Related projects', 'knd' ),
	'active_callback'   => array(
		array(
			'setting'  => 'project_related',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'project_bottom_block',
	'label'       => esc_html__( 'Bottom Blocks', 'knd' ),
	'section'     => 'project_settings',
	'default'     => '0',
	'choices'     => knd_get_blocks_option(),
) );
