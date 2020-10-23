<?php

add_action('init', 'knd_project_custom_content', 20);
function knd_project_custom_content() {

	register_post_type('project', array(
		'labels' => array(
			'name'               => esc_html__( 'Projects', 'knd' ),
			'singular_name'      => esc_html__( 'Project', 'knd' ),
			'menu_name'          => esc_html__( 'Projects', 'knd' ),
			'name_admin_bar'     => esc_html__( 'Add project', 'knd' ),
			'add_new'            => esc_html__( 'Add new', 'knd' ),
			'add_new_item'       => esc_html__( 'Add project', 'knd' ),
			'new_item'           => esc_html__( 'New project', 'knd' ),
			'edit_item'          => esc_html__( 'Edit project', 'knd' ),
			'view_item'          => esc_html__( 'Preview project', 'knd' ),
			'all_items'          => esc_html__( 'All projects', 'knd' ),
			'search_items'       => esc_html__( 'Search projects', 'knd' ),
		),
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'post',
		'has_archive'         => 'projects',
		'rewrite'             => array('slug' => 'project', 'with_front' => false),
		'hierarchical'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-category',
		'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
		'taxonomies'          => array(),
	));

}

add_action('knd_save_demo_content', array('KND_Project', 'setup_starter_data'));