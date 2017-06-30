<?php if( !defined('WPINC') ) die;
/** 
   Customizer options
**/

add_action('customize_register', 'knd_customize_register', 15);
function knd_customize_register(WP_Customize_Manager $wp_customize) {

    // Theme important links started
    class Knd_Important_Links extends WP_Customize_Control {

        public $type = "colormag-important-links";

        public function render_content() {

            $important_links = array(
                'theme-info' => array(
                    'link' => esc_url('https://te-st.ru/'),
                    'text' => esc_html__('Theme Info', 'knd'),
                ),
                'support' => array(
                    'link' => esc_url('mailto:support@te-st.ru'),
                    'text' => esc_html__('Support', 'knd'),
                ),
                'documentation' => array(
                    'link' => esc_url('https://te-st.ru/'),
                    'text' => esc_html__('Documentation', 'knd'),
                ),
//                'rating' => array(
//                    'link' => esc_url('https://wordpress.org/support/view/theme-reviews/colormag?filter=5'),
//                    'text' => esc_html__('Rate this theme', 'colormag'),
//                ),
            );
            foreach ($important_links as $important_link) {
                echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr($important_link['text']) . ' </a></p>';
            }

        }

    }

    $wp_customize->add_section('knd_important_links', array(
        'priority' => 1,
        'title' => __('Important Links', 'knd'),
    ));

    $wp_customize->add_setting('knd_important_links', array(
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'knd_links_sanitize'
    ));

    $wp_customize->add_control(new Knd_Important_Links($wp_customize, 'important_links', array(
        'label' => __('Important Links', 'knd'),
        'section' => 'knd_important_links',
        'settings' => 'knd_important_links'
    )));
    // Theme Important Links Ended

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
    
    //$wp_customize->remove_setting('site_icon'); //remove favicon
    //$wp_customize->remove_control('blogdescription'); 

    //Design section
    $wp_customize->add_section('knd_decoration', array(
        'priority' => 20,
        'title' => __('Decoration Basics', 'knd'),
    ));

    $wp_customize->add_setting('knd_main_color', array(
        'default'           => knd_get_deault_main_color(), 
        'sanitize_callback' => 'sanitize_hex_color'        
    ));

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'knd_main_color', 
            array(
                'label'      => __( 'Main Color', 'knd' ),
                'section'    => 'knd_decoration',
                'settings'   => 'knd_main_color',
        )) 
    );
}


