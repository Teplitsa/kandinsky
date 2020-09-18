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
	 */
	protected $data_routes = array(

		'pages'           => array(
			'' => array(
				'post_type' => 'page',
				'pieces'    => array( 'gethelp', 'contacts' ),
			),
			array(
				'section'   => 'about',
				'piece'     => 'reports',
				'post_type' => 'page',
				'post_slug' => 'reports',
			),
			array(
				'section'    => '',
				'piece'     => 'legal',
				'post_type' => 'page',
				'post_slug' => 'legal',
			),
		),

		'pages_templates' => array(
			'about'      => array(
				'template'  => 'page-about',
				'post_type' => 'page',
				'post_slug' => 'about',
			),
			'howtohelp'  => array(
				'template'  => 'page-howtohelp',
				'post_type' => 'page',
				'post_slug' => 'howtohelp',
			),
			'volunteers' => array(
				'template'  => 'page-volunteers',
				'post_type' => 'page',
				'post_slug' => 'volunteers',
			),
			'history'    => array(
				'template'  => 'page-history',
				'post_type' => 'page',
				'post_slug' => 'history',
			),
			'demo'       => array(
				'template'  => 'page-demo',
				'post_type' => 'page',
				'post_slug' => 'demo',
			),
		),

		'posts'           => array(
			'articles' => array(
				'post_type' => 'post',
				'pieces'    => array(
					'article1',
					'article2',
					'article3',
					'article4',
					'article5',
					'article6',
				),
			),
			'projects' => array(
				'post_type' => 'project',
				'pieces'    => array(
					'project1',
					'project2',
					'project3',
					'project4',
					'project5',
				),
			),
			'partners' => array(
				'post_type' => 'org',
				'pieces'    => array(
					'partner3',
					'partner2',
					'partner4',
					'partner1',
				),
			),
			'team'     => array(
				'post_type' => 'person',
				'pieces'    => array(
					'fellow1',
					'fellow2',
					'fellow3',
					'fellow4',
					'fellow5',
					'fellow6',
					'fellow7',
				),
			),
		),

		'leyka_campaigns' => array(
			'donate' => array( 'helpfund' ),
		),

		'theme_files'     => array(
			'knd_custom_logo'    => array(
				'section' => 'images',
				'file'    => 'logo.svg',
			),
			'knd_hero_image'     => array(
				'section' => 'images',
				'file'    => 'hero.jpg',
			),
			'knd_hero_cta_image' => array(
				'section' => 'images',
				'file'    => 'hero-image.png',
			),
			'knd_cta_image'      => array(
				'section' => 'images',
				'file'    => 'cta-image.png',
			),
		),

		'option_files'    => array(
			'site_icon' => array(
				'section' => 'images',
				'file'    => 'favicon.png',
			),
		),

		'theme_colors'    => array(
			'knd_main_color'      => '#f43724',
			'knd_color_second'    => '#ffc914',
			'knd_color_third'     => '#0e0a2b',

			'knd_text1_color'     => '#081d47',
			'knd_text2_color'     => '#000000',
			'knd_text3_color'     => '#000000',

			'knd_page_bg_color'   => '#ffffff',
			'knd_page_text_color' => '#081d47',

			'knd_hero_overlay_start' => '#ffffff',
			'knd_hero_overlay_end'   => '#ffffff',
			'knd_hero_text_color'    => '#081d47',

			'knd_cta_background' => '#ffffff',

			'font_logo_color'     => '#1e2c49',
			'knd_custom_logo_mod' => 'image_and_text',
		),

		'theme_options'    => array(
			'knd_hero_image_support_title'          => 'Помогаем людям с алкогольной зависимостью',
			'knd_hero_image_support_text'           => 'В Нашей области 877 человек, которые страдают от алкогольной зависимости. Ваша поддержка поможет организовать для них реабилитационную программу.',
			'knd_hero_image_support_button_caption' => 'Помочь сейчас',

			'subtitle_org'    => "Благотворительная организация  «Линия Цвета»",
			'subtitle_slogan' => "Более 10 лет мы помогаем людям, страдающим алкоголизмом,  организуем реабилитационные программы и группы",

			'home-subtitle-col1-title'     => array('section' => 'homepage', 'piece' => 'whoweare-who', 'field' => 'title'),
			'home-subtitle-col1-content'   => array('section' => 'homepage', 'piece' => 'whoweare-who'),
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

			'knd_social_links_vk'        => 'https://vk.com/teplitsast',
			'knd_social_links_ok'        => 'https://ok.ru/profile/0123456789',
			'knd_social_links_facebook'  => 'https://www.facebook.com/TeplitsaST',
			'knd_social_links_instagram' => 'https://www.instagram.com/your-organization-page',
			'knd_social_links_twitter'   => 'https://twitter.com/TeplitsaST',
			'knd_social_links_telegram'  => 'https://telegram.me/TeplitsaPRO',
			'knd_social_links_youtube'   => 'https://www.youtube.com/user/teplitsast',

			'knd_header_social_vk'        => 'https://vk.com/teplitsast',
			'knd_header_social_ok'        => 'https://ok.ru/profile/0123456789',
			'knd_header_social_facebook'  => 'https://www.facebook.com/TeplitsaST',
			'knd_header_social_instagram' => 'https://www.instagram.com/your-organization-page',
			'knd_header_social_twitter'   => 'https://twitter.com/TeplitsaST',
			'knd_header_social_telegram'  => 'https://telegram.me/TeplitsaPRO',
			'knd_header_social_youtube'   => 'https://www.youtube.com/user/teplitsast',

			'header_type'              => '2',
			'header_offcanvas'         => '0',
			'header_additional_button' => '0',
			'header_search'            => false,

		),

		'general_options' => array(
			'site_name'           => 'Линия Цвета',
			'site_description'    => 'Помогаем людям с алкогольной зависимостью',
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
Совершая пожертвование, пользователь заключает договор пожертвования путем акцепта публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
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
			'CTA_DONATE' => home_url('/howtohelp/'),
		);

		$this->data_routes['theme_options']['knd_hero_image_support_url'] = home_url('howtohelp');
		$this->data_routes['theme_options']['knd_url_pd_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_privacy_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_public_oferta'] = home_url('legal');

		$this->data_routes['menus'] = array(
			esc_html__('Main menu', 'knd') => array(
				array('title' => "Главная", 'url' => home_url('/') ),
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
				array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('title' => 'Проекты', 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
				array('post_type' => 'page', 'slug' => 'gethelp' ),
			),
			esc_html__('Primary Menu', 'knd') => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('title' => 'Проекты', 'url' => home_url('/projects/') ),
			),
			esc_html__( 'Kandinsky our work footer menu', 'knd' ) => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'history' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
			),
			esc_html__( 'Kandinsky news footer menu', 'knd' ) => array(
				array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
				array('title' => 'Проекты', 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
			),
			esc_html__( 'Kandinsky projects block menu', 'knd' ) => array(
				array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
			),
		);

		$this->data_routes['sidebar_widgets'] = array(
			'knd-homepage-sidebar' => array(
				array('slug' => 'knd_ourorg', 'options' => array('title' => knd_get_theme_mod('subtitle_org'), 'text' => knd_get_theme_mod('subtitle_slogan'),) ),
				array('slug' => 'knd_news', 'options' => array('title' => esc_html__('News', 'knd'), 'num' => 3,) ),
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
 * WP content srtuctures builder for dubrovino plot.
 * The major part of the class is a config, named $this->data_routes.
 *
 */
class KND_Dubrovino_Data_Builder extends KND_Plot_Data_Builder {
	
	/**
	 * Configuration of building process.
	 * pages: list of pages, that are built using imported templates
	 * posts: list of pages, that are built using content from imported files
	 *
	 */
	protected $data_routes = array(
		
		'pages' => array(
			array(
				'section' => 'about',
				'piece' => 'reports',
				'post_type' => 'page',
				'post_slug' => 'reports',
			),
			array(
				'section' => 'howtojoin',
				'piece' => 'howtojoin',
				'post_type' => 'page',
				'post_slug' => 'volunteers',
			),
			array(
				'section' => '',
				'piece' => 'legal',
				'post_type' => 'page',
				'post_slug' => 'legal',
			),
			array(
				'section' => 'howtohelp',
				'piece' => 'petition',
				'post_type' => 'page',
				'post_slug' => 'gethelp',
			)
		),
		
		'pages_templates' => array(
			'about' => array(
				'template' => 'page-about',
				'post_type' => 'page',
				'post_slug' => 'about',
			),
			'contacts' => array(
				'template' => 'page-contacts',
				'post_type' => 'page',
				'post_slug' => 'contacts',
			),
			'chronics' => array(
				'template' => 'page-chronics',
				'post_type' => 'page',
				'post_slug' => 'history',
			),
			'howtohelp' => array(
				'template' => 'page-howtohelp',
				'post_type' => 'page',
				'post_slug' => 'howtohelp',
			),
			'demo' => array(
				'template' => 'page-demo',
				'post_type' => 'page',
				'post_slug' => 'demo',
			),
		),
		
		'posts' => array(
			'news' => array(
				'post_type' => 'post',
				'pieces' => array('news1', 'news2', 'news3', ),
			),
			'docs' => array(
				'post_type' => 'project',
				'pieces' => array('expert', 'lawreason', 'dictionary' ),
			),
			'partners' => array(
				'post_type' => 'org',
				'pieces' => array('partner3', 'partner2', 'partner4', 'partner1', ),
			),
			'team' => array(
				'post_type' => 'person',
				'pieces' => array('fellow1', 'fellow2', 'fellow3', 'fellow4', 'fellow5', 'fellow6', 'fellow7', 'fellow8'),
			),
		),
		
		'leyka_campaigns' => array(
			'donate' => array('helpfund')
		),
		
		'theme_files' => array(
			'knd_custom_logo' => array('file' => 'logo.svg'),
			'knd_hero_image' => array('section' => 'img', 'file' => 'hero_img.jpg'),
		),
		
		'option_files' => array(
			'site_icon' => array('file' => 'favicon.png'),
		),
		
		'theme_colors' => array(
			'knd_main_color' => '#F02C80',
			'knd_color_second' => '#4a4a4a',
			'knd_color_third' => '#000000',
			
			'knd_text1_color' => '#ffffff',
			'knd_text2_color' => '#740635',
			'knd_text3_color' => '#362cf0',
			
			'knd_page_bg_color' => '#ffffff',
			'knd_page_text_color' => '#000000',
			
			'knd_custom_logo_mod' => 'image_only',
		),
		
		'theme_options' => array(
			
			'knd_hero_image_support_title' => array('section' => 'homepage', 'piece' => 'hero_heading'),
			'knd_hero_image_support_text' => array('section' => 'homepage', 'piece' => 'hero_description'),
			'knd_hero_image_support_button_caption' => 'Начать защищать',
			
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
			
			'knd_social_links_vk' => 'https://vk.com/teplitsast',
			'knd_social_links_ok' => 'https://ok.ru/profile/0123456789',
			'knd_social_links_facebook' => 'https://www.facebook.com/TeplitsaST',
			'knd_social_links_instagram' => 'https://www.instagram.com/your-organization-page',
			'knd_social_links_twitter' => 'https://twitter.com/TeplitsaST',
			'knd_social_links_telegram' => 'https://telegram.me/TeplitsaPRO',
			'knd_social_links_youtube' => 'https://www.youtube.com/user/teplitsast',
			
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
			
			'knd_address_phone' => 'Новосибирск, улица Клары Цеткин, 8
+7 (495) 688-81-23',
			
			'knd_footer_security_pd' => "<p>
Совершая пожертвование, пользователь заключает договор пожертвования путем акцепта публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
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
			'CTA_DONATE' => site_url('/howtohelp/'),
		);
		
		$this->data_routes['theme_options']['knd_hero_image_support_url'] = home_url('howtohelp');
		$this->data_routes['theme_options']['knd_url_pd_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_privacy_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_public_oferta'] = home_url('legal');
		
		$this->data_routes['menus'] = array(
			__('Main menu', 'knd') => array(
				array('title' => "Главная", 'url' => home_url('/') ),
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'history' ),
				array('title' => __('Extertise', 'knd'), 'url' => home_url('/projects/') ),
				array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
			),
			esc_html__( 'Kandinsky our work footer menu', 'knd' ) => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
			),
			esc_html__( 'Kandinsky news footer menu', 'knd' ) => array(
				array('title' => __('Extertise', 'knd'), 'url' => home_url('/projects/') ),
				array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
				array('post_type' => 'page', 'slug' => 'history' ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
			),
			esc_html__( 'Kandinsky projects block menu', 'knd' ) => array(
				array('title' => __('Extertise', 'knd'), 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'history' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
			),
		);
		
		$this->data_routes['sidebar_widgets'] = array(
			'knd-homepage-sidebar' => array(
				array('slug' => 'knd_ourorg', 'options' => array('title' => knd_get_theme_mod('subtitle_org'), 'text' => knd_get_theme_mod('subtitle_slogan'),) ),
				array('slug' => 'knd_news', 'options' => array('title' => "Последние новости", 'num' => 6,) ),
				array('slug' => 'knd_cta', 'options' => array() ),
				array('slug' => 'knd_projects', 'options' => array('title' => "Экспертиза", 'num' => 3,) ),
				array('slug' => 'knd_orgs', 'options' => array('title' => 'О нас пишут', 'num' => 4), ),
			),
			'knd-news-archive-sidebar' => array(
				array('slug' => 'knd_cta', 'options' => array() ),
				array('slug' => 'knd_projects', 'options' => array('title' => "Экспертиза", 'num' => 3,) ),
				array('slug' => 'knd_orgs', 'options' => array('title' => 'О нас пишут', 'num' => 4), ),
			),
			'knd-projects-archive-sidebar' => array(
				array('slug' => 'knd_cta', 'options' => array() ),
				array('slug' => 'knd_news', 'options' => array('title' => 'Последние новости', 'num' => 3,) ),
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
			array(
				'section' => '',
				'piece' => 'contacts',
				'post_type' => 'page',
				'post_slug' => 'contacts',
			),
			array(
				'section' => '',
				'piece' => 'reports',
				'post_type' => 'page',
				'post_slug' => 'reports',
			),
			array(
				'section' => '',
				'piece' => 'legal',
				'post_type' => 'page',
				'post_slug' => 'legal',
			)
		),
		
		'pages_templates' => array(
			'about' => array(
				'template' => 'page-about',
				'post_type' => 'page',
				'post_slug' => 'about',
		
			),
			'howtohelp' => array(
				'template' => 'page-helpus',
				'post_type' => 'page',
				'post_slug' => 'howtohelp',
		
			),
			'volunteers' => array(
				'template' => 'page-volunteers',
				'post_type' => 'page',
				'post_slug' => 'volunteers',
		
			),
			'gethelp' => array(
				'template' => 'page-gethelp',
				'post_type' => 'page',
				'post_slug' => 'gethelp',
			),
			'demo' => array(
				'template' => 'page-demo',
				'post_type' => 'page',
				'post_slug' => 'demo',
			)
		),
		
		'posts' => array(
			'newsfeed' => array(
				'post_type' => 'post',
				'pieces' => array('news1', 'news2', 'news3', ),
			),
			'programs' => array(
				'post_type' => 'project',
				'pieces' => array('program1', 'program2', 'program3', 'program4', ),
			),
			'partners' => array(
				'post_type' => 'org',
				'pieces' => array('partner3', 'partner2', 'partner4', 'partner1', ),
			),
			'team' => array(
				'post_type' => 'person',
				'pieces' => array('fellow1', 'fellow2', 'fellow3', 'fellow4', 'fellow5', 'fellow6', 'fellow7', 'fellow8', 'fellow9', 'fellow10'),
			),
		),
	
		'theme_files' => array(
			'knd_custom_logo' => array('file' => 'logo.svg'),
			'knd_hero_image' => array('section' => 'img', 'file' => 'twokidsmain.jpg'),
		),
		
		'option_files' => array(
			'site_icon' => array('file' => 'favicon.png'),
		),
		
		'leyka_campaigns' => array(
			'kids' => array('openkid1', 'openkid2', 'openkid3', 'openkid4', 'closedkid1', 'closedkid2', 'closedkid3', 'closedkid4', 'helpfund'),
		),
		
		'theme_colors' => array(
			'knd_main_color' => '#DE0055',
			'knd_color_second' => '#ffbe2c',
			'knd_color_third' => '#008ceb',
			
			'knd_text1_color' => '#ffffff',
			'knd_text2_color' => '#000000',
			'knd_text3_color' => '#000000',
			
			'knd_page_bg_color' => '#ffffff',
			'knd_page_text_color' => '#000000',
			
			'knd_custom_logo_mod' => 'image_only',
		),
		
		'theme_options' => array(
			
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
			
			'knd_social_links_vk' => 'https://vk.com/teplitsast',
			'knd_social_links_ok' => 'https://ok.ru/profile/0123456789',
			'knd_social_links_facebook' => 'https://www.facebook.com/TeplitsaST',
			'knd_social_links_instagram' => 'https://www.instagram.com/your-organization-page',
			'knd_social_links_twitter' => 'https://twitter.com/TeplitsaST',
			'knd_social_links_telegram' => 'https://telegram.me/TeplitsaPRO',
			'knd_social_links_youtube' => 'https://www.youtube.com/user/teplitsast',
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
			
			'knd_address_phone' => 'Архангельская область, Вельский район, г. Вельск, ул. Рогозина, д. 48, каб. 12, +7 (495) 878-53-42
',
			
			'knd_footer_security_pd' => "<p>
Совершая пожертвование, пользователь заключает договор пожертвования путем акцепта публичной оферты, который находится <a href=\"{knd_url_public_oferta}\">здесь</a>
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
			'CTA_DONATE' => site_url('/howtohelp/'),
		);
		
		$this->data_routes['theme_options']['knd_hero_image_support_url'] = home_url('howtohelp');
		$this->data_routes['theme_options']['knd_url_pd_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_privacy_policy'] = home_url('legal');
		$this->data_routes['theme_options']['knd_url_public_oferta'] = home_url('legal');
		
		$this->data_routes['menus'] = array(
			__('Main menu', 'knd') => array(
				array('title' => "Главная", 'url' => home_url('/') ),
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
				array('title' => __('News', 'knd'), 'url' => home_url('/news/') ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('title' => 'Проекты', 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
				array('post_type' => 'page', 'slug' => 'gethelp' ),
			),
			esc_html__( 'Kandinsky our work footer menu', 'knd' ) => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
			),
			esc_html__( 'Kandinsky news footer menu', 'knd' ) => array(
				array('post_type' => 'page', 'slug' => 'news' ),
				array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
				array('title' => 'Им нужна помощь', 'url' => home_url('campaign/active') ),
				array('title' => 'Вы помогли', 'url' => home_url('campaign/completed') ),
			),
			esc_html__( 'Kandinsky projects block menu', 'knd' ) => array(
				array('title' => 'Все проекты', 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
			),
		);
		
		$this->data_routes['sidebar_widgets'] = array(
			'knd-homepage-sidebar' => array(
				array('slug' => 'knd_ourorg', 'options' => array('title' => knd_get_theme_mod('subtitle_org'), 'text' => knd_get_theme_mod('subtitle_slogan'),) ),
				array('slug' => 'knd_donations', 'options' => array('title' => __('They need help', 'knd'), 'num' => 4,) ),
				array('slug' => 'knd_news', 'options' => array('title' => "Последние новости", 'num' => 6,) ),
				array('slug' => 'knd_cta', 'options' => array() ),
				array('slug' => 'knd_projects', 'options' => array('title' => "Наши проекты", 'num' => 3,) ),
				array('slug' => 'knd_orgs', 'options' => array('title' => 'Наши партнеры', 'num' => 4), ),
			),
			'knd-news-archive-sidebar' => array(
				array('slug' => 'knd_cta', 'options' => array() ),
				array('slug' => 'knd_projects', 'options' => array('title' => "Наши проекты", 'num' => 3,) ),
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
