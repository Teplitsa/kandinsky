<?php
/**
 * Widgets
 **/

add_action( 'init', 'knd_sidebars_init', 25 );
function knd_sidebars_init() {
    
    register_sidebar(array(
        'name' => __('Homepage widgets', 'knd'),
        'id' => 'knd-homepage-sidebar',
        'description' => __('Homepage custom content area', 'knd'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<section class="heading"><div class="container"><h1 class="section-title archive">',
        'after_title' => '</h1></div></section>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer custom columns', 'knd'),
        'id' => 'knd-footer-sidebar',
        'description' => __('Footer custom columns area', 'knd'),
        'before_widget' => '<div class="widget-bottom">',
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
	unregister_widget('WP_Widget_Search');
	
	//Most of widgets do not perform well with MDL as for now
	unregister_widget('Leyka_Donations_List_Widget');
	unregister_widget('Leyka_Campaign_Card_Widget');
	unregister_widget('Leyka_Campaigns_List_Widget');
	
}
