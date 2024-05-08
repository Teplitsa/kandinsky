<?php
/**
 * Register post type testimonials
 */
function knd_register_post_type_testimonials() {

	register_post_type('testimonials', array(
		'labels' => array(
			'name'                  => esc_html__( 'Testimonials', 'knd' ),
			'singular_name'         => esc_html__( 'Testimonial', 'knd' ),
			'menu_name'             => esc_html__( 'Testimonials', 'knd' ),
			'name_admin_bar'        => esc_html__( 'Testimonials', 'knd' ),
			'add_new'               => esc_html__( 'Add new', 'knd' ),
			'add_new_item'          => esc_html__( 'Add new testimonial', 'knd' ),
			'new_item'              => esc_html__( 'New testimonial', 'knd' ),
			'edit_item'             => esc_html__( 'Edit testimonial', 'knd' ),
			'view_item'             => esc_html__( 'Preview testimonial', 'knd' ),
			'all_items'             => esc_html__( 'All testimonials', 'knd' ),
			'search_items'          => esc_html__( 'Search testimonial', 'knd' ),
			'featured_image'        => esc_html__( 'Author photo', 'knd' ),
			'set_featured_image'    => esc_html__( 'Set author photo', 'knd' ),
			'remove_featured_image' => esc_html__( 'Remove author photo', 'knd' ),
			'use_featured_image'    => esc_html__( 'Use as author photo', 'knd' ),
		),
		'public'              => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'capability_type'     => 'page',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 23,
		'menu_icon'           => 'dashicons-testimonial',
		'supports'            => array( 'title', 'excerpt', 'thumbnail' ),
	));

}
add_action('init', 'knd_register_post_type_testimonials' );
