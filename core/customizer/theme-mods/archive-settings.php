<?php
/**
 * Archive Settings
 *
 * @package Kandinsky
 */

/* Archive section */
Kirki::add_section(
	'archive_settings',
	array(
		'panel'    => 'pages',
		'title'    => esc_html__( 'Archive Settings', 'knd' ),
		'priority' => 11,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'knd_news_archive_title',
	'label'    => esc_html__( 'Posts archive title', 'knd' ),
	'section'  => 'archive_settings',
	'default'  => esc_html__( 'Blog', 'knd' )
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'archive_bottom_block',
	'label'       => esc_html__( 'Posts Page Bottom Blocks', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => '0',
	'choices'     => knd_get_blocks_option(),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'archive_' . wp_unique_id( 'divider_' ),
	'section'  => 'archive_settings',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'knd_projects_archive_title',
	'label'    => esc_html__( 'Projects archive title', 'knd' ),
	'section'  => 'archive_settings',
	'default'  => esc_html__( 'Our projects', 'knd' )
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'projects_bottom_block',
	'label'       => esc_html__( 'Projects Page Bottom Blocks', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => '0',
	'choices'     => knd_get_blocks_option(),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'toggle',
	'settings'    => 'projects_completed',
	'label'       => esc_html__( 'Completed projects', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => '0',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'select',
	'settings'    => 'projects_completed_cat',
	'label'       => esc_html__( 'What projects do we consider completed?', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => '',
	'choices'     => knd_get_project_cats(),
	'active_callback'   => array(
		array(
			'setting'  => 'projects_completed',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'toggle',
	'settings'    => 'projects_completed_style',
	'label'       => esc_html__( 'Highlight display of completed projects', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => '0',
	'active_callback'   => array(
		array(
			'setting'  => 'projects_completed',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'projects_completed_cat',
			'operator' => '!=',
			'value'    => '',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'slider',
	'settings'    => 'projects_completed_opacity',
	'label'       => esc_html__( 'Opacity', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => 0.8,
	'choices'     => [
		'min'  => 0,
		'max'  => 1,
		'step' => 0.1,
	],
	'active_callback'   => array(
		array(
			'setting'  => 'projects_completed',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'projects_completed_cat',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'setting'  => 'projects_completed_style',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'color',
	'settings'    => 'projects_completed_color',
	'label'       => esc_html__( 'Color', 'knd' ),
	'section'     => 'archive_settings',
	'default'     => knd_typography( 'font_base', 'color', '#4d606a' ),
	'active_callback'   => array(
		array(
			'setting'  => 'projects_completed',
			'operator' => '==',
			'value'    => '1',
		),
		array(
			'setting'  => 'projects_completed_cat',
			'operator' => '!=',
			'value'    => '',
		),
		array(
			'setting'  => 'projects_completed_style',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'archive_' . wp_unique_id( 'divider_' ),
	'section'  => 'archive_settings',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

// Register controls if plugin leyka is active.
if ( defined( 'LEYKA_VERSION' ) ) {

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'knd_active_campaigns_archive_title',
		'label'    => esc_html__( 'Active campaigns archive title', 'knd' ),
		'section'  => 'archive_settings',
		'default'  => esc_html__( 'They need help', 'knd' ),
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'knd_completed_campaigns_archive_title',
		'label'    => esc_html__( 'Completed campaigns archive title', 'knd' ),
		'section'  => 'archive_settings',
		'default'  => esc_html__( 'They alredy got help', 'knd' ),
	] );

}
