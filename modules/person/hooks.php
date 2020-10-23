<?php

add_action('init', 'knd_person_custom_content', 20);
function knd_person_custom_content() {

	register_taxonomy('person_cat', array('person',), array(
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
		'menu_position'       => 10,
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
		'taxonomies'          => array('person_cat'),
	));

}

add_action('knd_before_build_plot_posts', 'knd_person_cat_defaults');
function knd_person_cat_defaults(){

	if(!term_exists('volunteers', 'person_cat')) {
		wp_insert_term( esc_html__('Volonteers', 'knd'), 'person_cat', array('slug' => 'volunteers'));
	}

	if(!term_exists('team', 'person_cat')) {
		wp_insert_term( esc_html__('Team', 'knd'), 'person_cat', array('slug' => 'team'));
	}

	if(!term_exists('board', 'person_cat')) {
		wp_insert_term( esc_html__('Board', 'knd'), 'person_cat', array('slug' => 'board'));
	}
}
