<?php

add_action('init', 'knd_project_custom_content', 20);
function knd_project_custom_content() {

    register_post_type('project', array(
        'labels' => array(
            'name'               => 'Проекты',
            'singular_name'      => 'Проект',
            'menu_name'          => 'Проекты',
            'name_admin_bar'     => 'Добавить проект',
            'add_new'            => 'Добавить новую',
            'add_new_item'       => 'Добавить проект',
            'new_item'           => 'Новый проект',
            'edit_item'          => 'Редактировать проект',
            'view_item'          => 'Просмотр проектов',
            'all_items'          => 'Все проекты',
            'search_items'       => 'Искать проекты',
            'parent_item_colon'  => 'Родительский проект:',
            'not_found'          => 'Проекты не найдены',
            'not_found_in_trash' => 'В Корзине проекты не найдены'
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