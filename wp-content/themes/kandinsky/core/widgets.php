<?php
/**
 * Widgets
 **/

add_action( 'init', 'knd_sidebars_init', 25 );
function knd_sidebars_init() {
    
    register_sidebar(array(
        'name' => __('Homepage - Content', 'knd'),
        'id' => 'knd-homepage-sidebar',
        'description' => __('Homepage custom content area', 'knd'),
        'before_widget' => '<div id="%1$s" class="widget-full %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-full-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('News - bottom panel', 'knd'),
        'id' => 'knd-news-archive-sidebar',
        'description' => __('Bottom blocks for news archive page', 'knd'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3 class="widget-full-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Archives - bottom panel', 'knd'),
        'id' => 'knd-projects-archive-sidebar',
        'description' => __('Bottom blocks for archive and search pages', 'knd'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3 class="widget-full-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer - Columns', 'knd'),
        'id' => 'knd-footer-sidebar',
        'description' => __('Footer columns area accepts 4 widgets. Does not accept wide widgets.', 'knd'),
        'before_widget' => '<div id="%1$s" class="widget-bottom %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
}

add_action('widgets_init', 'knd_custom_widgets', 20);
function knd_custom_widgets() {

	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Recent_Comments');
	
	//Most of widgets do not perform well with MDL as for now
	//unregister_widget('Leyka_Donations_List_Widget');
	unregister_widget('Leyka_Campaign_Card_Widget');
	unregister_widget('Leyka_Campaigns_List_Widget');
	
}
