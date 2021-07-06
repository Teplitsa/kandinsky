<?php
/**
 * Register post type project
 */
function knd_register_post_type_project() {

	register_post_type( 'project', array(
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
		'show_in_nav_menus'   => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'post',
		'has_archive'         => 'projects',
		'rewrite'             => array(
			'slug'       => 'project',
			'with_front' => false,
		),
		'hierarchical'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-category',
		'supports'            => array(
			'title',
			'excerpt',
			'editor',
			'thumbnail',
		),
	));

}
add_action( 'init', 'knd_register_post_type_project' );

/**
 * Setup Starter Data Project
 */
add_action( 'knd_save_demo_content', array( 'KND_Project', 'setup_starter_data' ) );

/**
 * Register taxonomy project tag
 */
function knd_register_project_tag_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Tags', 'taxonomy general name' ),
		'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
		'search_items'               => __( 'Search Tags' ),
		'popular_items'              => __( 'Popular Tags' ),
		'all_items'                  => __( 'All Tags' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Tag' ),
		'update_item'                => __( 'Update Tag' ),
		'add_new_item'               => __( 'Add New Tag' ),
		'new_item_name'              => __( 'New Tag Name' ),
		'separate_items_with_commas' => __( 'Separate tags with commas' ),
		'add_or_remove_items'        => __( 'Add or remove tags' ),
		'choose_from_most_used'      => __( 'Choose from the most used tags' ),
		'menu_name'                  => __( 'Tags' ),
	);

	register_taxonomy( 'project_tag', 'project', array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array(
			'slug' => 'project-tag',
		),
	));
}
add_action( 'init', 'knd_register_project_tag_taxonomy' );
