<?php
/**
 * Global Settings
 *
 * @package Kandinsky
 */

/* Other Pages Section */
Kirki::add_section(
	'other_pages',
	array(
		'title'    => esc_html__( 'Create other pages', 'knd' ),
		'priority' => 5,
	)
);

$other_pages_desc = '<p>' . sprintf( __( 'If you need to add new page click to a button below. You can manage pages in the «<a href="%s">Menu > Pages</a>» section. You can edit, add and delete pages.', 'knd' ), admin_url( 'edit.php?post_type=page' ) ) . '</p>

<p><a href="' . admin_url( 'post-new.php?post_type=page' ) . '" class="button">' . esc_html__( 'Add new page', 'knd' ) . '</a></p>

<p>' . esc_html__( 'Besides pages, there are Posts in WordPress, use them to add news.', 'knd' ) . '</p>

<p><a href="' . get_admin_url( null, 'post-new.php?post_type=page' ) . '" class="button">' . esc_html__( 'Go to posts page', 'knd' ) . '</a></p>

<p>' . __( 'If you need new entities (for example "Requests" or "Events"), you can add custom post types using plugin such as Custom Post Type UI. <a href="https://wordpress.org/plugins/custom-post-type-ui/" target="_blank">https://wordpress.org/plugins/custom-post-type-ui/</a>', 'knd' ) . '</p>

';

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'other_pages_create',
	'section'  => 'other_pages',
	'default'  => $other_pages_desc,
	'priority' => 1,
) );
