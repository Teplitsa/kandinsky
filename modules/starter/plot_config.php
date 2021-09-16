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
				'pieces'    => array(
					'gethelp',
					'contacts',
					'about',
					'home',
					'reports',
					'volunteers',
					'howtohelp',
					'legal',
					'history',
					'demo',
				),
			),
		),

		'pages_templates' => array(
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
			'blocks'    => array(
				'post_type' => 'wp_block',
				'pieces'    => array(
					'posts-bottom-blocks',
					'projects-bottom-blocks',
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
		),

		'option_files'    => array(
			'site_icon' => array(
				'section' => 'images',
				'file'    => 'favicon.png',
			),
		),

		'theme_colors'    => array(
		),

		'theme_options'    => array(
		),

	);

	/**
	 * Set CTA config.
	 *
	 */
	public function __construct( $imp ) {
		parent::__construct( $imp );

		$this->cta_list = array(
			'CTA_DONATE' => home_url('/howtohelp/'),
		);

		$this->data_routes['general_options'] = array(
			'site_name'           => esc_html__( 'Line of Color', 'knd' ),
			'site_description'    => esc_html__( 'We help people to fight alcohol addiction', 'knd' ),
			//'knd_footer_contacts' => '',
		);


		// $this->data_routes['theme_options']['knd_url_pd_policy'] = home_url('legal');
		// $this->data_routes['theme_options']['knd_url_privacy_policy'] = home_url('legal');
		// $this->data_routes['theme_options']['knd_url_public_oferta'] = home_url('legal');

		$this->data_routes['menus'] = array(
			esc_html__('Primary Menu', 'knd') => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('title' => esc_html__( 'Projects', 'knd' ), 'url' => home_url('/projects/') ),
			),
			esc_html__( 'Kandinsky our work footer menu', 'knd' ) => array(
				array('post_type' => 'page', 'slug' => 'about' ),
				array('post_type' => 'page', 'slug' => 'history' ),
				array('post_type' => 'page', 'slug' => 'reports' ),
				array('post_type' => 'page', 'slug' => 'contacts' ),
			),
			esc_html__( 'Kandinsky news footer menu', 'knd' ) => array(
				array('title' => esc_html__('News', 'knd'), 'url' => home_url('/news/') ),
				array('title' => esc_html__( 'Projects', 'knd' ), 'url' => home_url('/projects/') ),
				array('post_type' => 'page', 'slug' => 'volunteers' ),
				array('post_type' => 'page', 'slug' => 'howtohelp' ),
			),
		);

		$font_base = array(
			'font-family' => 'Jost',
			'variant'     => 'regular',
			'color'       => '#081d47',
			'font-size'   => '18px',
			'font-backup' => '',
			'font-weight' => '400',
			'font-style'  => 'normal',
		);

		$font_headings = array(
			'font-family' => 'Jost',
			'variant'     => '600',
			'color'       => '#081d47',
			'font-backup' => '',
			'font-weight' => '600',
			'font-style'  => 'normal',
		);

		$about_content = '<p>' . esc_html__( 'Our office, training rooms and support group rooms are open daily from 9:00 am to 10:00 pm.', 'knd' ) . '</p>
<p>' . esc_html__( 'Moscow, 7th Stroiteley Street, 17, office: 211-217
+7 (495) 787-87-23', 'knd' ) . '
<a href="mailto:info@colorline.ru">info@colorline.ru</a>
</p>';
		
		$policy_content = sprintf( '<p>' . esc_html__( 'By making a donation, the user concludes a donation agreement by accepting a public offer, which is located %shere%s.', 'knd' ) . '</p>
<p><a href="' . esc_url( home_url( 'legal' ) ) . '">' . esc_html__( 'Personal data processing policy', 'knd' ) . '</a><br>
<a href="' . esc_url( home_url( 'legal' ) ) . '">' . esc_html__( 'Privacy policy', 'knd' ) . '</a>
</p>', '<a href="' . esc_url( home_url( 'legal' ) ) . '">', '</a>' );

		$theme_mods = array(
			'font_base'             => $font_base,
			'font_headings'         => $font_headings,
			'knd_page_bg_color'     => '#ffffff',
			'knd_main_color'        => '#f43724',
			'knd_main_color_active' => '#db3120',
			'header_background'     => '#ffffff',
			'header_button_text'    =>  esc_html__( 'Help now', 'knd' ),
			'header_button_link'    => home_url( 'howtohelp' ),
			'header_type'           => '2',
			'header_offcanvas'         => '0',
			'header_additional_button' => '0',
			'header_search'            => false,
			'header_height'            => '124px',
			'header_logo_title'        => get_bloginfo( 'name' ),
			'header_logo_text'         => get_bloginfo( 'description' ),
			'font_logo_default'        => true,
			'header_logo_color'        => '#1e2c49',
			'header_logo_desc_color'   => '#585858',
			'header_menu_color'        => '#585858',
			'header_menu_color_hover'  => '#f43724',
			'header_menu_size'              => '16px',
			'header_button'            => true,
			'header_button_text'       => esc_html__( 'Help now', 'knd' ),
			'header_button_link'       => home_url( 'howtohelp' ),
			'header_additional_button' => false,
			'header_additional_button_text' => '',
			'header_additional_button_link' => '',
			'header_offcanvas'              => false,
			'offcanvas_menu'                => false,
			'offcanvas_search'              => false,
			'offcanvas_button'              => false,
			'offcanvas_button_text'         => esc_html__( 'Help now', 'knd' ),
			'offcanvas_button_link'         => home_url( 'howtohelp' ),
			'offcanvas_social'              => false,

			'knd_social_vk'        => 'https://vk.com/teplitsast',
			'knd_social_ok'        => 'https://ok.ru/profile/0123456789',
			'knd_social_facebook'  => 'https://www.facebook.com/TeplitsaST',
			'knd_social_instagram' => 'https://www.instagram.com/your-organization-page',
			'knd_social_twitter'   => 'https://twitter.com/TeplitsaST',
			'knd_social_telegram'  => 'https://telegram.me/TeplitsaPRO',
			'knd_social_youtube'   => 'https://www.youtube.com/user/teplitsast',

			'knd_news_archive_title'     => esc_html__( 'News', 'knd' ),
			'knd_projects_archive_title' => esc_html__( 'Our projects', 'knd' ),
			'post_related_title'         => esc_html__( 'Related items', 'knd' ),
			'project_related_title'      => esc_html__( 'Related projects', 'knd' ),

			'footer_logo_title'       => get_bloginfo( 'name' ),
			'footer_logo_text'        => get_bloginfo( 'description' ),
			'footer_background'       => '#eeeeee',
			'footer_color'            => '#000000',
			'footer_color_link'       => '#f43724',
			'footer_color_link_hover' => '#db3120',
			'font_footer_logo_default' => true,
			'footer_logo_color'        => '#1e2c49',
			'footer_logo_desc_color'   => '#1e2c49',
			'footer_social'            => true,
			'footer_color_social'       => '#000000',
			'footer_color_social_hover' => '#333333',

			'footer_about_title'        => esc_html__( 'About Us', 'knd' ),
			'footer_about'              => $about_content,

			'footer_policy_title'        => esc_html__( 'Security policy', 'knd' ),
			'footer_policy'              => $policy_content,

			'footer_menu_ourwork_title' => esc_html__( 'Our Work', 'knd' ),
			'footer_menu_ourwork'       => esc_html__( 'kandinsky-our-work-footer-menu', 'knd' ),
			'footer_menu_news_title'    => esc_html__( 'News', 'knd' ),
			'footer_menu_news'          => esc_html__( 'kandinsky-news-footer-menu', 'knd' ),

			'archive_bottom_block'  => 'posts-bottom-blocks',
			'post_bottom_block'     => 'posts-bottom-blocks',
			'projects_bottom_block' => 'projects-bottom-blocks',
			'project_bottom_block'  => 'projects-bottom-blocks',

		);

		foreach ( $theme_mods as $name => $mod ) {
			set_theme_mod( $name, $mod );
		}

	}

}
