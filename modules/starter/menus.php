<?php

class KND_StarterMenus {

	public static function add_post2menu($post, $menu_id, $order) {
		
		$item_data = array(
			'menu-item-object-id' => $post->ID,
			'menu-item-object' => 'page',
			'menu-item-parent-id' => 0,
			'menu-item-position' => $order,
			'menu-item-type' => 'post_type',
			'menu-item-title' => $post->post_title,
			'menu-item-url' => get_permalink( $post ),
			'menu-item-classes' => 'menu-item menu-item-type-post_type menu-item-object-page menu-item-' . $post->ID,
			'menu-item-status' => 'publish',
		);
		
		wp_update_nav_menu_item($menu_id, 0, $item_data);
	}
	
	public static function add_link2menu($title, $url, $menu_id, $order) {
	
		$item_data = array(
			'menu-item-title' => $title,
			'menu-item-url' => $url,
			'menu-item-status' => 'publish',
			'menu-item-type' => 'custom',
			'menu-item-position' => $order,
		);
	
		wp_update_nav_menu_item($menu_id, 0, $item_data);
	}
	
}