<?php if( !defined('WPINC') ) die;

add_action('init', 'knd_org_custom_content', 20);
function knd_org_custom_content() {

	register_taxonomy('org_cat', array('org'), array(
		'labels' => array(
			'name'                       => esc_html__( 'Partner Categories', 'knd' ),
			'singular_name'              => esc_html__( 'Category', 'knd' ),
			'menu_name'                  => esc_html__( 'Categories', 'knd' ),
			'all_items'                  => esc_html__( 'All Categories', 'knd' ),
			'edit_item'                  => esc_html__( 'Edit category', 'knd' ),
			'view_item'                  => esc_html__( 'Preview', 'knd' ),
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
		'rewrite'           => array('slug' => 'orgs', 'with_front' => false),
	));

	register_post_type('org', array(
		'labels' => array(
			'name'                  => esc_html__( 'Partners', 'knd' ),
			'singular_name'         => esc_html__( 'Partner', 'knd' ),
			'menu_name'             => esc_html__( 'Partners', 'knd' ),
			'name_admin_bar'        => esc_html__( 'Add partner', 'knd' ),
			'add_new'               => esc_html__( 'Add new', 'knd' ),
			'add_new_item'          => esc_html__( 'Add partner', 'knd' ),
			'new_item'              => esc_html__( 'New partner', 'knd' ),
			'edit_item'             => esc_html__( 'Edit partner', 'knd' ),
			'view_item'             => esc_html__( 'Preview', 'knd' ),
			'all_items'             => esc_html__( 'All partners', 'knd' ),
			'featured_image'        => esc_html__( 'Partner logo', 'knd' ),
			'set_featured_image'    => esc_html__( 'Set partner logo', 'knd' ),
			'remove_featured_image' => esc_html__( 'Remove partner logo', 'knd' ),
			'use_featured_image'    => esc_html__( 'Use as partner logo', 'knd' ),
		),
		'public'              => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'page',
		'has_archive'         => false,
		'rewrite'             => array('slug' => 'org', 'with_front' => false),
		'hierarchical'        => false,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-networking',
		'show_in_rest'        => true,
		'supports'            => array('title', 'excerpt', 'editor', 'thumbnail', 'custom-fields'),
	));

}

add_action('knd_save_demo_content', array('KND_OrgCategory', 'setup_starter_data'));
add_action('knd_save_demo_content', array('KND_Org', 'setup_starter_data'));