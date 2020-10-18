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

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'image',
	'settings'    => 'knd_hero_image',
	'label'       => esc_html__( 'Hero Background Image', 'knd' ),
	'section'     => 'knd_homepage_hero',
	'description' => esc_html__( 'Recommended size 1600x663px', 'knd' ),
	'priority'    => 1,
	'choices'     => array(
		'save_as' => 'id',
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'        => 'image',
	'settings'    => 'knd_hero_cta_image',
	'label'       => esc_html__( 'Call to action Image', 'knd' ),
	'section'     => 'knd_homepage_hero',
	'description' => esc_html__( 'Displayed on the right side of the Call to action text', 'knd' ),
	'priority'    => 2,
	'choices'     => array(
		'save_as' => 'id',
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_title',
	'label'    => esc_html__( 'Call to action title', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 3,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'textarea',
	'settings' => 'knd_hero_image_support_text',
	'label'    => esc_html__( 'Call to action text', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 4,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_button_caption',
	'label'    => esc_html__( 'Action button caption', 'knd' ),
	'default'  => esc_html__( 'Help now', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 5,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'knd_hero_image_support_url',
	'label'    => esc_html__( 'Call to action URL', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'priority' => 6,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'header_' . wp_unique_id( 'divider_' ),
	'section'  => 'knd_homepage_hero',
	'default'  => '<div class="knd-customizer-divider"></div>',
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'color',
	'settings' => 'knd_hero_overlay_start',
	'label'    => esc_html__( 'Overlay Gradient Start Color', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'default'  => 'rgba(0,0,0,0)',
	'choices'     => array(
		'alpha' => true,
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'color',
	'settings' => 'knd_hero_overlay_end',
	'label'    => esc_html__( 'Overlay Gradient End Color', 'knd' ),
	'section'  => 'knd_homepage_hero',
	'default'  => 'rgba(0,0,0,.8)',
	'choices'     => array(
		'alpha' => true,
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'knd_hero_text_color',
	'label'     => esc_html__( 'Text Color', 'knd' ),
	'section'   => 'knd_homepage_hero',
	'default'   => '#ffffff',
	'choices'   => array(
		'alpha' => true,
	),
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.hero-title, .hero-text',
			'property' => 'color',
		),
	),
) );

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

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'custom',
		'settings' => 'home-subtitle-col-separator-' . $i . '',
		'section'  => 'knd_ourorg_columns_settings',
		'default'  => '<hr><span class="customize-control-title">' . esc_html__( 'Column ', 'knd' ) . $i . '</span><hr>',
		'priority' => 1,
	) );

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-title',
		'label'    => esc_html__( 'Column title', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	) );

	Kirki::add_field( 'knd_theme_mod', array(
		'type'              => 'textarea',
		'settings'          => 'home-subtitle-col' . $i . '-content',
		'label'             => esc_html__( 'Column content', 'knd' ),
		'section'           => 'knd_ourorg_columns_settings',
		'priority'          => 1,
		'sanitize_callback' => 'wp_kses_post',
	) );

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-link-text',
		'label'    => esc_html__( 'Column link caption', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	) );

	Kirki::add_field( 'knd_theme_mod', array(
		'type'     => 'text',
		'settings' => 'home-subtitle-col' . $i . '-link-url',
		'label'    => esc_html__( 'Column link URL', 'knd' ),
		'section'  => 'knd_ourorg_columns_settings',
		'priority' => 1,
	) );

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

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'cta-title',
	'label'    => esc_html__( 'Call to action title', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 1,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'cta-url',
	'label'    => esc_html__( 'Call to action URL', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 2,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'textarea',
	'settings' => 'cta-description',
	'label'    => esc_html__( 'Call to action text', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 3,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'text',
	'settings' => 'cta-button-caption',
	'label'    => esc_html__( 'Action button caption', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 4,
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'image',
	'settings' => 'knd_cta_image',
	'label'    => esc_html__( 'Call to action Image', 'knd' ),
	'section'  => 'knd_cta_block_settings',
	'priority' => 5,
	'choices'  => array(
		'save_as' => 'id',
	),
) );

Kirki::add_field( 'knd_theme_mod', array(
	'type'      => 'color',
	'settings'  => 'knd_cta_background',
	'label'     => esc_html__( 'Background Color', 'knd' ),
	'section'   => 'knd_cta_block_settings',
	'priority'  => 6,
	'default'   => get_theme_mod( 'knd_color_second' ),
	'choices'   => array(
		'alpha' => true,
	),
	'transport' => 'auto',
	'output'    => array(
		array(
			'element'  => '.knd-joinus-widget',
			'property' => '--knd-color-second',
		),
	),
) );

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
