<?php

add_action('init', 'knd_person_custom_content', 20);
function knd_person_custom_content() {

    register_taxonomy('person_cat', array('person',), array(
        'labels' => array(
            'name'                       => 'Категории персон',
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
        'rewrite'           => array('slug' => 'people', 'with_front' => false),
    ));

    register_post_type('person', array(
        'labels' => array(
            'name'               => 'Профили людей',
            'singular_name'      => 'Профиль',
            'menu_name'          => 'Люди',
            'name_admin_bar'     => 'Добавить профиль',
            'add_new'            => 'Добавить новый',
            'add_new_item'       => 'Добавить профиль',
            'new_item'           => 'Новый профиль',
            'edit_item'          => 'Редактировать профиль',
            'view_item'          => 'Просмотр профиля',
            'all_items'          => 'Все профили',
            'search_items'       => 'Искать профили',
            'parent_item_colon'  => 'Родительский профиль:',
            'not_found'          => 'Профили не найдены',
            'not_found_in_trash' => 'В Корзине профили не найдены'
        ),
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => false,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        //'query_var'           => true,
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
        wp_insert_term( __('Volonteers', 'knd'), 'person_cat', array('slug' => 'volunteers'));
    }

    if(!term_exists('team', 'person_cat')) {
        wp_insert_term( __('Team', 'knd'), 'person_cat', array('slug' => 'team'));
    }

    if(!term_exists('board', 'person_cat')) {
        wp_insert_term( __('Board', 'knd'), 'person_cat', array('slug' => 'board'));
    }
}