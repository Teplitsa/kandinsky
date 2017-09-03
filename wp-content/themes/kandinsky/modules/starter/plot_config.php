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
            '' => array(
                'post_type' => 'page',
                'pieces' => array('needhelp', 'contacts', 'volunteers', ),
            ),
            array(
                'section' => 'about',
                'piece' => 'reports',
                'post_type' => 'page',
                'post_slug' => 'reports',
            ),
            array(
                'section' => 'about',
                'piece' => 'history',
                'post_type' => 'page',
                'post_slug' => 'about-history',
            ),
        ),
        
        'pages_templates' => array(
            'about' => array(
                'template' => 'page-about',
                'post_type' => 'page',
                'post_slug' => 'about',
        
            ),
            'howtohelp' => array(
                'template' => 'page-howtohelp',
                'post_type' => 'page',
                'post_slug' => 'howtohelp',
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
            
            'cta-title' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'title'),
            'cta-description' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'content'),
            'cta-button-caption' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'lead'),
            'cta-url' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'url'),
        ),
        
        'general_options' => array(
            'site_name' => 'Линия Цвета',
            'site_description' => 'Помощь людям с алькогольной зависимостью',
            
            'knd_footer_contacts' => "
<p>
Наш офис, учебные залы и помещения групп поддержки открыты ежедневно с 9:00 до 22:00 часов.
</p>
<p>
{knd_address_phone}
<br />
<a href=\"mailto:\">info@colorline.ru</a>
</p>
",
            
            'knd_address_phone' => 'Москва, 7-я улица Строителей, 17, оф.: 211-217
+7 (495) 787-87-23',
            
            'knd_footer_security_pd' => "<p>
Совершая пожертвование, пользователь заключает договор пожертвования путем акцента публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
</p>
<p>
<a href=\"{knd_url_pd_policy}\">Политика обработки персональных данных</a>
<br />
<a href=\"{knd_url_privacy_policy}\">Политика конфиденциальности</a>
</p>",
            
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
        $this->data_routes['theme_options']['knd_url_pd_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_privacy_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_public_oferta'] = '#';
        
        $this->data_routes['menus'] = array(
            __('Main menu', 'knd') => array(
                array('title' => "Главная", 'url' => home_url('/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('post_type' => 'page', 'slug' => 'contacts' ),
                array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
                array('post_type' => 'page', 'slug' => 'howtohelp' ),
                array('post_type' => 'page', 'slug' => 'reports' ),
                array('title' => 'Проекты', 'url' => home_url('/projects/') ),
                array('post_type' => 'page', 'slug' => 'volunteers' ),
            ),
            __( 'Kandinsky our work footer menu', 'knd' ) => array(
                array('post_type' => 'page', 'slug' => 'about' ),
                array('title' => "История", 'url' => home_url('/about-history/') ),
                array('post_type' => 'page', 'slug' => 'reports' ),
                array('post_type' => 'page', 'slug' => 'contacts' ),
            ),
            __( 'Kandinsky news footer menu', 'knd' ) => array(
                array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
                array('title' => 'Проекты', 'url' => home_url('/projects/') ),
                array('post_type' => 'page', 'slug' => 'volunteers' ),
                array('post_type' => 'page', 'slug' => 'howtohelp' ),
            ),
            __( 'Kandinsky projects block menu', 'knd' ) => array(
                array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('post_type' => 'page', 'slug' => 'reports' ),
            ),
        );
        
        $this->data_routes['sidebar_widgets'] = array(
            'knd-homepage-sidebar' => array(
                array('slug' => 'knd_ourorg', 'options' => array('title' => get_theme_mod('subtitle_org'), 'text' => get_theme_mod('subtitle_slogan'),) ),
                array('slug' => 'knd_news', 'options' => array('title' => __('News', 'knd'), 'num' => 6,) ),
                array('slug' => 'knd_cta', 'options' => array() ),
                array('slug' => 'knd_projects', 'options' => array('title' => "Проекты «Линии цвета»", 'num' => 3,) ),
                array('slug' => 'knd_orgs', 'options' => array('title' => 'Наши партнеры', 'num' => 4), ),
            ),
            'knd-news-archive-sidebar' => array(
                array('slug' => 'knd_cta', 'options' => array() ),
                array('slug' => 'knd_projects', 'options' => array('title' => "Проекты «Линии цвета»", 'num' => 3,) ),
                array('slug' => 'knd_orgs', 'options' => array('title' => 'Наши партнеры', 'num' => 4), ),
            ),
            'knd-projects-archive-sidebar' => array(
                array('slug' => 'knd_cta', 'options' => array() ),
                array('slug' => 'knd_news', 'options' => array('title' => 'Последние новости', 'num' => 3,) ),
                array('slug' => 'knd_orgs', 'options' => array('title' => 'Наши партнеры', 'num' => 4), ),
            ),
        );
        
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
        ),
        
        'pages_templates' => array(
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
            
            'cta-title' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'title'),
            'cta-description' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'content'),
            'cta-button-caption' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'lead'),
            'cta-url' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'url'),
            
        ),
        
        'general_options' => array(
            'site_name' => 'Защитим Дубровино!',
            'site_description' => 'Градозащитная инициатива',
            
            'knd_footer_contacts' => "
<p>
Вместе остановим уничтожение леса!
</p>
<p>
{knd_address_phone}
<br />
<a href=\"mailto:\">info@savedubrovino.ru</a>
</p>
",
            
            'knd_address_phone' => 'Москва, 7-я улица Строителей, 17, оф.: 211-217
+7 (495) 787-87-23',
            
            'knd_footer_security_pd' => "<p>
Совершая пожертвование, пользователь заключает договор пожертвования путем акцента публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
</p>
<p>
<a href=\"{knd_url_pd_policy}\">Политика обработки персональных данных</a>
<br />
<a href=\"{knd_url_privacy_policy}\">Политика конфиденциальности</a>
</p>",
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
        $this->data_routes['theme_options']['knd_url_pd_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_privacy_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_public_oferta'] = '#';
        
        $this->data_routes['menus'] = array(
            __('Main menu', 'knd') => array(
                array('title' => "Главная", 'url' => home_url('/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
            ),
            __( 'Kandinsky our work footer menu', 'knd' ) => array(
                array('post_type' => 'page', 'slug' => 'volunteers' ),
            ),
            __( 'Kandinsky news footer menu', 'knd' ) => array(
                array('post_type' => 'page', 'slug' => 'news' ),
            ),
            __( 'Kandinsky projects block menu', 'knd' ) => array(
                array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('post_type' => 'page', 'slug' => 'reports' ),
            ),
        );
        
        $this->data_routes['sidebar_widgets'] = array(
            'knd-homepage-sidebar' => array(
                array('slug' => 'knd_ourorg', 'options' => array('title' => get_theme_mod('subtitle_org'), 'text' => get_theme_mod('subtitle_slogan'),) ),
                array('slug' => 'knd_news', 'options' => array('title' => "Хроника кампании", 'num' => 6,) ),
                array('slug' => 'knd_cta', 'options' => array() ),
                array('slug' => 'knd_orgs', 'options' => array('title' => 'О нас пишут', 'num' => 4), ),
            ),
        );
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
        
        ),
        
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
            
            'cta-title' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'title'),
            'cta-description' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'content'),
            'cta-button-caption' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'lead'),
            'cta-url' => array('section' => 'homepage', 'piece' => 'cta_block', 'field' => 'url'),
        ),
        
        'general_options' => array(
            'site_name' => 'Мы с тобой',
            'site_description' => 'Благотворительный фонд помощи детям из малоимущих семей',
            
            'knd_footer_contacts' => "
<p>
{knd_address_phone}
<br />
<a href=\"mailto:\">info@withyoufund.ru</a>
</p>
",
            
            'knd_address_phone' => 'Москва, 7-я улица Строителей, 17, оф.: 211-217
+7 (495) 787-87-23
',
            
            'knd_footer_security_pd' => "<p>
Совершая пожертвование, пользователь заключает договор пожертвования путем акцента публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
</p>
<p>
<a href=\"{knd_url_pd_policy}\">Политика обработки персональных данных</a>
<br />
<a href=\"{knd_url_privacy_policy}\">Политика конфиденциальности</a>
</p>",
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
        $this->data_routes['theme_options']['knd_url_pd_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_privacy_policy'] = '#';
        $this->data_routes['theme_options']['knd_url_public_oferta'] = '#';
        
        $this->data_routes['menus'] = array(
            __('Main menu', 'knd') => array(
                array('title' => "Главная", 'url' => home_url('/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
            ),
            __( 'Kandinsky our work footer menu', 'knd' ) => array(
                array('post_type' => 'page', 'slug' => 'volunteers' ),
                array('post_type' => 'page', 'slug' => 'projects' ),
            ),
            __( 'Kandinsky news footer menu', 'knd' ) => array(
                array('post_type' => 'page', 'slug' => 'news' ),
                array('post_type' => 'page', 'slug' => 'reports' ),
            ),
            __( 'Kandinsky projects block menu', 'knd' ) => array(
                array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
                array('post_type' => 'page', 'slug' => 'about' ),
                array('post_type' => 'page', 'slug' => 'reports' ),
            ),
        );
        
        $this->data_routes['sidebar_widgets'] = array(
            'knd-homepage-sidebar' => array(
                array('slug' => 'knd_ourorg', 'options' => array('title' => get_theme_mod('subtitle_org'), 'text' => get_theme_mod('subtitle_slogan'),) ),
                array('slug' => 'knd_news', 'options' => array('title' => "Последние новости", 'num' => 6,) ),
                array('slug' => 'knd_cta', 'options' => array() ),
                array('slug' => 'knd_projects', 'options' => array('title' => "Наши проекты", 'num' => 3,) ),
                array('slug' => 'knd_orgs', 'options' => array('title' => 'Наши партнеры', 'num' => 4), ),
            ),
        );
        
    }
}
