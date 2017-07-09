<?php if( !defined('WPINC') ) die;

add_action('init', 'knd_org_custom_content', 20);
function knd_org_custom_content() {

    register_taxonomy('org_cat', array('org',), array(
        'labels' => array(
            'name'                       => 'Категории организаций',
            'singular_name'              => 'Категория',
            'menu_name'                  => 'Категории',
            'all_items'                  => 'Все категории',
            'edit_item'                  => 'Редактировать категорию',
            'view_item'                  => 'Просмотреть',
            'update_item'                => 'Обновить категорию',
            'add_new_item'               => 'Добавить новую категорию',
            'new_item_name'              => 'Название новой категории',
            'parent_item'                => 'Родительская категория',
            'parent_item_colon'          => 'Родительская категория:',
            'search_items'               => 'Искать категории',
            'popular_items'              => 'Часто используемые',
            'separate_items_with_commas' => 'Разделять запятыми',
            'add_or_remove_items'        => 'Добавить или удалить категории',
            'choose_from_most_used'      => 'Выбрать из часто используемых',
            'not_found'                  => 'Не найдено'
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'orgs', 'with_front' => false),
        //'update_count_callback' => '',
    ));

    register_post_type('org', array(
        'labels' => array(
            'name'               => 'Организации',
            'singular_name'      => 'Организация',
            'menu_name'          => 'Партнеры',
            'name_admin_bar'     => 'Добавить организацию',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить организацию',
            'new_item'           => 'Новая организация',
            'edit_item'          => 'Редактировать организацию',
            'view_item'          => 'Просмотр организации',
            'all_items'          => 'Все организации',
            'search_items'       => 'Искать организации',
            'parent_item_colon'  => 'Родительская организация:',
            'not_found'          => 'Организации не найдены',
            'not_found_in_trash' => 'В Корзине организации не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'rewrite'             => array('slug' => 'org', 'with_front' => false),
        'hierarchical'        => false,
        'menu_position'       => 10,
        'menu_icon'           => 'dashicons-networking',
        'supports'            => array('title', 'excerpt', 'editor', 'thumbnail'),
        'taxonomies'          => array('org_cat'),
    ));

}

add_action('knd_save_demo_content', array('KND_OrgCategory', 'setup_starter_data'));
add_action('knd_save_demo_content', array('KND_Org', 'setup_starter_data'));