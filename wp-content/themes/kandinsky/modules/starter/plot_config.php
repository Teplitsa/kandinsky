<?php

/**
 * WP content srtuctures builder for color-line plot.
 * The major part of the class is a config, named $this->data_routes.
 *
 */
class KND_Colorline_Data_Builder extends KND_Plot_Data_Builder {
    
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
    protected $data_routes = array(
        
        'pages' => array(
            'about' => array(
                'template' => 'page-about',
                'post_type' => 'page',
                'post_slug' => 'about',
                
            ),
        ),
        
        'posts' => array(
            'articles' => array(
                'post_type' => 'post',
                'pieces' => array('article1', 'article2', 'article3', 'article4', 'article5', 'article6' ),
            ),
            'projects' => array(
                'post_type' => 'project',
                'pieces' => array('project1', 'project2', 'project3', 'project4', 'project5', ),
            ),
        ),
        
        'theme_files' => array(
            'knd_custom_logo' => array('file' => 'master-logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => '5.jpg'),
        ),
        
        'option_files' => array(
            'site_icon' => array('file' => 'favicon.png'),
        ),
        
        'theme_options' => array(
            
            'knd_main_color' => '#00bcd4',
            'knd_color_second' => '#ffc914',
            'knd_color_third' => '#0e0a2b',
            
            'knd_text1_color' => '#000000',
            'knd_text2_color' => '#000000',
            'knd_text3_color' => '#000000',
            
            'knd_custom_logo_mod' => 'image_and_text',
            
            'knd_hero_image_support_title' => 'Помоги бороться с алкогольной зависимостью!',
            'knd_hero_image_support_text' => 'В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваша поддержка поможет организовать для них реабилитационную программу.',
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
            
            'subtitle_org' => "Благотворительная организация  «Линия Цвета»",
            'subtitle_slogan' => "Более 10 лет мы помогаем людям, страдающим алкоголизмом в нашем городе,  организуя реабилитационные программы и группы",
            
            'home-subtitle-col1-title' => array('section' => 'homepage', 'piece' => 'whoweare-who', 'field' => 'title'),
            'home-subtitle-col1-content' => array('section' => 'homepage', 'piece' => 'whoweare-who'),
            'home-subtitle-col1-link-text' => array('section' => 'homepage', 'piece' => 'whoweare-who', 'field' => 'lead'),
            'home-subtitle-col1-link-url' => array('section' => 'homepage', 'piece' => 'whoweare-who', 'field' => 'url'),
            
            'home-subtitle-col2-title' => array('section' => 'homepage', 'piece' => 'whoweare-whatwedo', 'field' => 'title'),
            'home-subtitle-col2-content' => array('section' => 'homepage', 'piece' => 'whoweare-whatwedo'),
            'home-subtitle-col2-link-text' => array('section' => 'homepage', 'piece' => 'whoweare-whatwedo', 'field' => 'lead'),
            'home-subtitle-col2-link-url' => array('section' => 'homepage', 'piece' => 'whoweare-whatwedo', 'field' => 'url'),
            
            'home-subtitle-col3-title' => array('section' => 'homepage', 'piece' => 'whoweare-breakfree', 'field' => 'title'),
            'home-subtitle-col3-content' => array('section' => 'homepage', 'piece' => 'whoweare-breakfree'),
            'home-subtitle-col3-link-text' => array('section' => 'homepage', 'piece' => 'whoweare-breakfree', 'field' => 'lead'),
            'home-subtitle-col3-link-url' => array('section' => 'homepage', 'piece' => 'whoweare-breakfree', 'field' => 'url'),
        ),
        
        'general_options' => array(
            'site_name' => 'Линия Цвета',
            'site_description' => 'Помощь людям с алькогольной зависимостью',
        ),
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
        
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
    
}

/**
 * WP content srtuctures builder for right2city plot.
 * The major part of the class is a config, named $this->data_routes.
 *
 */
class KND_Right2city_Data_Builder extends KND_Plot_Data_Builder {
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
    protected $data_routes = array(
    
        'pages' => array(
            'aboutus' => array(
                'template' => 'page-about',
                'post_type' => 'page',
                'post_slug' => 'about',
        
            ),
        ),
        
        'posts' => array(
            'chronics' => array(
                'post_type' => 'post',
                'pieces' => array('news1', 'news2', 'news3', ),
            ),
        ),
        
        'theme_files' => array(
            'knd_custom_logo' => array('file' => 'logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => 'hero_img.jpg'),
        ),
        
        'option_files' => array(
            'site_icon' => array('file' => 'favicon.png'),
        ),
        
        'theme_options' => array(
            'knd_main_color' => '#F02C80',
            'knd_color_second' => '#4a4a4a',
            'knd_color_third' => '#000000',
            
            'knd_text1_color' => '#ffffff',
            'knd_text2_color' => '#740635',
            'knd_text3_color' => '#362cf0',
            
            'knd_custom_logo_mod' => 'image_only',
            
            'knd_hero_image_support_title' => array('section' => 'homepage', 'piece' => 'hero_heading'),
            'knd_hero_image_support_text' => array('section' => 'homepage', 'piece' => 'hero_description'),
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
            
            'subtitle_slogan' => array('section' => 'homepage', 'piece' => 'subtitle_slogan'),
            'subtitle_org' => array('section' => 'homepage', 'piece' => 'subtitle_org'),
            
            'home-subtitle-col1-title' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'title'),
            'home-subtitle-col1-content' => array('section' => 'homepage', 'piece' => 'subtitle_who'),
            'home-subtitle-col1-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'lead'),
            'home-subtitle-col1-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'url'),
            
            'home-subtitle-col2-title' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'title'),
            'home-subtitle-col2-content' => array('section' => 'homepage', 'piece' => 'subtitle_what'),
            'home-subtitle-col2-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'lead'),
            'home-subtitle-col2-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'url'),
            
            'home-subtitle-col3-title' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'title'),
            'home-subtitle-col3-content' => array('section' => 'homepage', 'piece' => 'subtitle_act'),
            'home-subtitle-col3-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'lead'),
            'home-subtitle-col3-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'url'),
            
        ),
        
        'general_options' => array(
            'site_name' => 'Защитим Дубровино!',
            'site_description' => 'Градозащитная инициатива',
        ),
        
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
    
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
}

class KND_Withyou_Data_Builder extends KND_Plot_Data_Builder {
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
    protected $data_routes = array(
    
        'pages' => array(
            'about' => array(
                'template' => 'page-about',
                'post_type' => 'page',
                'post_slug' => 'about',
        
            ),
        ),
        
        'posts' => array(
            'newsfeed' => array(
                'post_type' => 'post',
                'pieces' => array('news1', 'news2', 'news3', ),
            ),
        ),
    
        'theme_files' => array(
            'knd_custom_logo' => array('file' => 'logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => 'twokidsmain.jpg'),
        ),
        
        'option_files' => array(
            'site_icon' => array('file' => 'favicon.png'),
        ),
        
        'theme_options' => array(
            
            'knd_main_color' => '#DE0055',
            'knd_color_second' => '#ffbe2c',
            'knd_color_third' => '#008ceb',
            
            'knd_text1_color' => '#000000',
            'knd_text2_color' => '#000000',
            'knd_text3_color' => '#000000',
            
            'knd_custom_logo_mod' => 'image_only',
            
            'knd_hero_image_support_title' => array('section' => 'homepage', 'piece' => 'hero_heading'),
            'knd_hero_image_support_text' => array('section' => 'homepage', 'piece' => 'hero_description'),
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
            
            'subtitle_slogan' => array('section' => 'homepage', 'piece' => 'subtitle_slogan'),
            'subtitle_org' => array('section' => 'homepage', 'piece' => 'subtitle_org'),
            
            'home-subtitle-col1-title' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'title'),
            'home-subtitle-col1-content' => array('section' => 'homepage', 'piece' => 'subtitle_who'),
            'home-subtitle-col1-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'lead'),
            'home-subtitle-col1-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_who', 'field' => 'url'),
            
            'home-subtitle-col2-title' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'title'),
            'home-subtitle-col2-content' => array('section' => 'homepage', 'piece' => 'subtitle_what'),
            'home-subtitle-col2-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'lead'),
            'home-subtitle-col2-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_what', 'field' => 'url'),
            
            'home-subtitle-col3-title' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'title'),
            'home-subtitle-col3-content' => array('section' => 'homepage', 'piece' => 'subtitle_act'),
            'home-subtitle-col3-link-text' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'lead'),
            'home-subtitle-col3-link-url' => array('section' => 'homepage', 'piece' => 'subtitle_act', 'field' => 'url'),
        ),
        
        'general_options' => array(
            'site_name' => 'Мы с тобой',
            'site_description' => 'Благотворительный фонд помощи детям из малоимущих семей',
        ),
        
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
    
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
}
