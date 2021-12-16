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
		),

		'option_files'    => array(
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
			// 'site_name'           => esc_html__( 'Line of Color', 'knd' ),
			// 'site_description'    => esc_html__( 'We help people to fight alcohol addiction', 'knd' ),
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

	}

}
