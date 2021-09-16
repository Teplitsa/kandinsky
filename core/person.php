<?php

function knd_person_custom_content() {

	register_taxonomy('person_cat', array('person'), array(
		'labels' => array(
			'name'                       => esc_html__( 'Persons categories', 'knd' ),
			'singular_name'              => esc_html__( 'Category', 'knd' ),
			'menu_name'                  => esc_html__( 'Categories', 'knd' ),
			'all_items'                  => esc_html__( 'All categories', 'knd' ),
			'edit_item'                  => esc_html__( 'Edit category', 'knd' ),
			'view_item'                  => esc_html__( 'Preview category', 'knd' ),
			'update_item'                => esc_html__( 'Update category', 'knd' ),
			'add_new_item'               => esc_html__( 'Add new category', 'knd' ),
			'new_item_name'              => esc_html__( 'New category name', 'knd' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_rest'      => true,
		'rewrite'           => array('slug' => 'people', 'with_front' => false),
	));

	register_post_type('person', array(
		'labels' => array(
			'name'               => esc_html__( 'People\'s profiles', 'knd' ),
			'singular_name'      => esc_html__( 'Profile', 'knd' ),
			'menu_name'          => esc_html__( 'People', 'knd' ),
			'name_admin_bar'     => esc_html__( 'People', 'knd' ),
			'add_new'            => esc_html__( 'Add new', 'knd' ),
			'add_new_item'       => esc_html__( 'Add new profile', 'knd' ),
			'new_item'           => esc_html__( 'New profile', 'knd' ),
			'edit_item'          => esc_html__( 'Edit profile', 'knd' ),
			'view_item'          => esc_html__( 'Preview profile', 'knd' ),
			'all_items'          => esc_html__( 'All profiles', 'knd' ),
			'search_items'       => esc_html__( 'Search profile', 'knd' ),
		),
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'post',
		'has_archive'         => false,
		'rewrite'             => array('slug' => 'profile', 'with_front' => false),
		'hierarchical'        => false,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
		'show_in_rest'        => true,
	));

}
add_action('init', 'knd_person_custom_content', 20);
