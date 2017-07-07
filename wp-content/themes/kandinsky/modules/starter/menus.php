<?php

class KND_StarterMenus {

//     public static function knd_setup_main_menu() {
//         $main_menu_name = __( 'Kandinsky main menu', 'knd' );
    
//         if(is_nav_menu($main_menu_name)){
//             wp_delete_nav_menu($main_menu_name);
//         }
//         $main_menu_id = wp_create_nav_menu( $main_menu_name );
    
//         $item_data = array(
//             'menu-item-parent-id' => 0,
//             'menu-item-position' => 0,
//             'menu-item-title' => __( 'Home page', 'knd' ),
//             'menu-item-status' => 'publish',
//             'menu-item-url' => home_url('/'),
//         );
//         wp_update_nav_menu_item($main_menu_id, 0, $item_data);
    
//         $locations = get_theme_mod('nav_menu_locations');
//         $locations['primary'] = $main_menu_id;
//         set_theme_mod('nav_menu_locations', $locations );
//     }
    
    public static function knd_setup_our_work_menu() {
        $menu_name = __( 'Kandinsky our work footer menu', 'knd' );
        
        if(is_nav_menu($menu_name)){
            wp_delete_nav_menu($menu_name);
        }
        $menu_id = wp_create_nav_menu( $menu_name );
        
        $page = knd_get_post( 'projects', 'page' );
        if($page) {
            self::add_post2menu($page, $menu_id);
        }
        
        $page = knd_get_post( 'volunteers', 'page' );
        if($page) {
            self::add_post2menu($page, $menu_id);
        }
    }
    
    public static function knd_setup_news_menu() {
        $menu_name = __( 'Kandinsky news footer menu', 'knd' );
        
        if(is_nav_menu($menu_name)){
            wp_delete_nav_menu($menu_name);
        }
        $menu_id = wp_create_nav_menu( $menu_name );
        
        $page = knd_get_post( 'news', 'page' );
        if($page) {
            self::add_post2menu($page, $menu_id);
        }
        
        $page = knd_get_post( 'reports', 'page' );
        if($page) {
            self::add_post2menu($page, $menu_id);
        }
    }
    
    public static function add_post2menu($post, $menu_id) {
        
        $item_data = array(
            'menu-item-object-id' => $post->ID,
            'menu-item-object' => 'page',
            'menu-item-parent-id' => 0,
            'menu-item-position' => 1,
            'menu-item-type' => 'post_type',
            'menu-item-title' => $post->post_title,
            'menu-item-url' => get_permalink( $post ),
            'menu-item-classes' => 'menu-item menu-item-type-post_type menu-item-object-page menu-item-' . $post->ID,
            'menu-item-status' => 'publish',
        );
        
        wp_update_nav_menu_item($menu_id, 0, $item_data);
    }
}