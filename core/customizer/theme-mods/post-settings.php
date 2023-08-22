<?php
/**
 * Post Settings
 *
 * @package Kandinsky
 */

/* Post section */
Kirki::add_section(
	'post_settings',
	array(
		'panel'    => 'pages',
		'title'    => esc_html__( 'Post Settings', 'knd' ),
		'priority' => 12,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_featured_image',
	'label'    => esc_html__( 'Featured image', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_social_shares',
	'label'    => esc_html__( 'Social Shares', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_tags',
	'label'    => esc_html__( 'Tags', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_related',
	'label'    => esc_html__( 'Related Posts', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '1',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_comments',
	'label'    => esc_html__( 'Comments', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '0',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'              => 'text',
	'settings'          => 'post_related_title',
	'label'             => esc_html__( 'Related Posts Title', 'knd' ),
	'section'           => 'post_settings',
	'default'           => esc_html__( 'Related posts', 'knd' ),
	'active_callback'   => array(
		array(
			'setting'  => 'post_related',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'post_bottom_block',
	'label'       => esc_html__( 'Bottom Blocks', 'knd' ),
	'section'     => 'post_settings',
	'default'     => '0',
	'choices'     => knd_get_blocks_option(),
) );
