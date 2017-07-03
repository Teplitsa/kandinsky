<?php if( !defined('WPINC') ) die;
/** 
   Customizer options
**/

add_action('customize_register', 'knd_customize_register', 15);
function knd_customize_register(WP_Customize_Manager $wp_customize) {

    // Theme important links started
    class Knd_Important_Links extends WP_Customize_Control {

        public $type = "knd-important-links";

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
                    'link' => esc_url('https://drive.google.com/drive/folders/0B5-GQ-OMsbzrRzVmQnNzUm9RVGc?usp=sharing'),
                    'text' => esc_html__('Documentation', 'knd'),
                ),
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


    //Common settings
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
    

    //Design section
    $wp_customize->add_section('knd_decoration', array(
        'priority' => 20,
        'title' => __('Decoration Basics', 'knd'),
    ));

    $wp_customize->add_setting('knd_main_color', array(
        'default'           => knd_get_deault_main_color(), 
        'sanitize_callback' => 'sanitize_hex_color'        
    ));

    $wp_customize->add_setting('knd_custom_logo', array(
        'default'           => '', 
        'sanitize_callback' => 'absint'        
    ));

    $wp_customize->add_setting('knd_custom_logo_mod', array(
        'default'           => 'image_only'             
    ));

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'knd_main_color', 
            array(
                'label'      => __( 'Main Color', 'knd' ),
                'section'    => 'knd_decoration',
                'settings'   => 'knd_main_color',
                'priority'   => 10
        )) 
    );


    $wp_customize->add_control('knd_custom_logo_mod', array(
        'type'     => 'radio',       
        'label'    => __('Logo mode', 'knd'),
        'section'  => 'knd_decoration',
        'settings' => 'knd_custom_logo_mod',
        'priority' => 20,
        'choices'  => array(
            'image_only'    => __('Image only', 'knd'),
            'image_text'    => __('Image with site name', 'knd'),
            'text_only'     => __('Site name only', 'knd'),
            'nothing'       => __('Do not show', 'knd')
        )
    ));  

    $wp_customize->add_control( 
        new WP_Customize_Cropped_Image_Control( 
        $wp_customize, 
        'knd_custom_logo', 
            array(
                'label'         => __( 'Logo', 'knd' ),
                'description'   => __( 'Recommended size 315x66px for Image only mode and 66x66px for Image with site name', 'knd' ),
                'section'       => 'knd_decoration',
                'settings'      => 'knd_custom_logo',
                'flex_width'    => true, 
                'flex_height'   => false, 
                'width'         => 315,
                'height'        => 66,
                'priority'      => 30
        )) 
    );
    
    
    // hero image
    $wp_customize->add_setting('knd_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint'
    ));
    
    $wp_customize->add_setting('knd_hero_image_support_text', array(
        'default'   => '',
    ));
    
    $wp_customize->add_setting('knd_hero_image_support_title', array(
        'default'   => '',
    ));
    
    $wp_customize->add_setting('knd_hero_image_support_button_caption', array(
        'default'   => '',
    ));
    
    $wp_customize->add_control(
            new WP_Customize_Cropped_Image_Control(
            $wp_customize,
            'knd_hero_image',
            array(
                'label'         => __( 'Hero Image', 'knd' ),
                'description'   => __( 'Recommended size 1400x656px', 'knd' ),
                'section'       => 'static_front_page',
                'settings'      => 'knd_hero_image',
                'flex_width'    => true,
                'flex_height'   => false,
                'width'         => 1400,
                'height'        => 656,
                'priority'      => 40
            ))
    );
    
    $wp_customize->add_control('knd_hero_image_support_title', array(
        'type'     => 'textarea',
        'label'    => __('Support us hero title', 'knd'),
        'section'  => 'static_front_page',
        'settings' => 'knd_hero_image_support_title',
        'priority' => 45
    ));
    
    $wp_customize->add_control('knd_hero_image_support_text', array(
        'type'     => 'textarea',
        'label'    => __('Support us hero text', 'knd'),
        'section'  => 'static_front_page',
        'settings' => 'knd_hero_image_support_text',
        'priority' => 50
    ));
    
    $wp_customize->add_control('knd_hero_image_support_button_caption', array(
        'type'     => 'textarea',
        'label'    => __('Support us hero button caption', 'knd'),
        'section'  => 'static_front_page',
        'settings' => 'knd_hero_image_support_button_caption',
        'priority' => 55
    ));
    
}


