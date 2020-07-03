<?php
/**
 * Homepage Settings
 *
 * @package Kandinsky
 */

/* Global Panel */
Kirki::add_panel(
	'homepage',
	array(
		'title'    => esc_html__( 'Main page', 'knd' ),
		'priority' => 4,
	)
);

/* Hero section */
Kirki::add_section(
	'knd_homepage_hero',
	array(
		'panel'    => 'homepage',
		'title'    => esc_html__( 'Hero Image', 'knd' ),
		'priority' => 1,
	)
);

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'image',
	'settings' => 'knd_hero_image',
	'label'    => esc_html__( 'Hero Image', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'description' => esc_html__( 'Recommended size 1600x663px', 'knd' ),
	'priority' => 1,
	'choices'  => [
		'save_as' => 'id',
	],
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_title',
	'label'    => esc_html__( 'Call to action title', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 2,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_url',
	'label'    => esc_html__( 'Call to action URL', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 3,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'textarea',
	'settings' => 'knd_hero_image_support_text',
	'label'    => esc_html__( 'Call to action text', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 4,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_button_caption',
	'label'    => esc_html__( 'Action button caption', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 5,
] );

/* Our Organization section */
Kirki::add_section(
	'knd_ourorg_columns_settings',
	array(
		'panel'    => 'homepage',
		'title'    => esc_html__( 'Our organization columns settings', 'knd' ),
		'priority' => 3,
	)
);

for ( $i = 1; $i <= 3; $i++ ) {

	Kirki::add_field( 'knd_theme_mod', [
		'type'        => 'custom',
		'settings'    => 'home-subtitle-col-separator-' . $i . '',
		'section'     => 'knd_ourorg_columns_settings',
			'default'         => '<hr><span class="customize-control-title">' . esc_html__( 'Column ', 'knd' ) . $i . '</span><hr>',
		'priority'    => 1,
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-title',
		'label'    => esc_html__( 'Column title', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'textarea',
		'settings' => 'home-subtitle-col' . $i . '-content',
		'label'    => esc_html__( 'Column content', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-link-text',
		'label'    => esc_html__( 'Column link caption', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	] );

	Kirki::add_field( 'knd_theme_mod', [
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-link-url',
		'label'    => esc_html__( 'Column link URL', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	] );

}

/* Our Organization section */
Kirki::add_section(
	'knd_cta_block_settings',
	array(
		'panel'    => 'homepage',
		'title'    => esc_html__( 'CTA block settings', 'knd' ),
		'priority' => 4,
	)
);

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'cta-title',
	'label'    => esc_html__( 'Call to action title', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 1,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'cta-url',
	'label'    => esc_html__( 'Call to action URL', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 2,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'textarea',
	'settings' => 'cta-description',
	'label'    => esc_html__( 'Call to action text', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 3,
] );

Kirki::add_field( 'knd_theme_mod', [
	'type'     => 'text',
	'settings' => 'cta-button-caption',
	'label'    => esc_html__( 'Action button caption', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 4,
] );

/* Home page widgets section */
Kirki::add_section(
	'sidebar-widgets-knd-homepage-sidebar',
	array(
		'panel'    => 'homepage',
		'title'    => esc_html__( 'Homepage - Content', 'knd' ),
		'priority' => 2,
	)
);

/* Static Front Page */
Kirki::add_section(
	'static_front_page',
	array(
		'panel'    => 'homepage',
		'title'    => esc_html__( 'Static page settings', 'knd' ),
		'priority' => 10,
	)
);
