<?php if( !defined('WPINC') ) die;
/** 
   Customizer options
**/

add_action('customize_register', 'knd_customize_register', 15);
function knd_customize_register(WP_Customize_Manager $wp_customize) {
    
    
    $wp_customize->add_setting('text_in_header', array(
        'default'   => '',
        'transport' => 'postMessage',
        'option' => 'option'
    ));
    
    $wp_customize->add_control('text_in_header', array(
        'type'     => 'textarea',       
        'label'    => __('Header text', 'knd'),
        'section'  => 'title_tagline',
        'settings' => 'text_in_header',
        'priority' => 30
    ));  
    
    //Images
    /*$wp_customize->add_setting('default_thumbnail', array(
        'default'   => false,
        'transport' => 'refresh', // postMessage
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_thumbnail', array(
        'label'    => 'Миниатюра по умолчанию',
        'section'  => 'title_tagline',
        'settings' => 'default_thumbnail',
        'priority' => 60,
    )));*/
    
    $wp_customize->remove_setting('site_icon'); //remove favicon
    //$wp_customize->remove_control('blogdescription'); 
}


