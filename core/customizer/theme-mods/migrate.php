<?php
/**
 * Migrate Settings
 *
 * @package Kandinsky
 */

/* Migrate Section */
Kirki::add_section(
	'migrate',
	array(
		'title'    => esc_html__( 'Kandinsky Migrate', 'knd' ),
		'priority' => 7,
	)
);

// https://kandinsky.loc/wp-admin/network/plugin-install.php?s=wp+migrate+db&tab=search&type=term
// https://kandinsky.loc/wp-admin/network/plugin-install.php?s=wp+migrate+db&tab=search&type=term

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'migrate_mods',
	'label'    => esc_html__( 'Import customization settings for old theme.', 'knd' ),
	'section'  => 'migrate',
	'default'  => '<a href="#" class="knd-customize-focus button">' . esc_html__( 'Go to Menu', 'knd' ) . '</a>',
	'priority' => 1,
) );
