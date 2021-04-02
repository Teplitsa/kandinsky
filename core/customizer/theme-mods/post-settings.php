<?php
/**
 * Post Settings
 *
 * @package Kandinsky
 */

/* Hero section */
Kirki::add_section(
	'post_settings',
	array(
		'title'    => esc_html__( 'Post Settings', 'knd' ),
		'priority' => 6,
	)
);

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'toggle',
	'settings' => 'post_social_shares',
	'label'    => esc_html__( 'Social Shares', 'knd' ),
	'section'  => 'post_settings',
	'default'  => '1',
) );
