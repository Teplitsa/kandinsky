<?php
/**
 * Customizer Deprecated
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Customizer
 *
 * @param object $wp_customize Customizer.
 */
function knd_customize_register( $wp_customize ) {

	// Theme important links started.
	class Knd_Important_Links extends WP_Customize_Control {

		public $type = "knd-important-links";

		public function render_content() {
			$important_links = array( 
				'theme-info' => array( 
					'link' => esc_url( KND_OFFICIAL_WEBSITE_URL ),
					'text' => esc_html__( 'Theme Info', 'knd' ) ), 
				'support' => array( 
					'link' => esc_url( KND_SUPPORT_URL ),
					'text' => esc_html__( 'Support', 'knd' ) ), 
				'documentation' => array( 
					'link' => esc_url( KND_DOC_URL ),
					'text' => esc_html__( 'Documentation', 'knd' ) ) );
			foreach ( $important_links as $important_link ) {
				echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_attr( 
					$important_link['text'] ) . ' </a></p>';
			}
		}
	}

	$wp_customize->add_section(
		'knd_important_links', 
		array( 'priority' => 150, 'title' => esc_html__( 'Important Links', 'knd' ) ) );

	$wp_customize->add_setting(
		'knd_important_links', 
		array(
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url',
		)
	);

	$wp_customize->add_control( 
		new Knd_Important_Links( 
			$wp_customize, 
			'important_links', 
			array( 
				'label' => esc_html__( 'Important Links', 'knd' ), 
				'section' => 'knd_important_links', 
				'settings' => 'knd_important_links' ) ) );
	// Theme Important Links Ended

	// Common settings.
	// $wp_customize->add_setting(
	// 	'text_in_header',
	// 	array(
	// 		'sanitize_callback' => 'wp_kses_post',
	// 		'default'           => knd_get_theme_mod('text_in_header', ''),
	// 	)
	// );

	// $wp_customize->add_control(
	// 	'text_in_header',
	// 	array(
	// 		'type'     => 'textarea',
	// 		'label'    => esc_html__( 'Header text', 'knd' ),
	// 		'section'  => 'title_tagline',
	// 		'settings' => 'text_in_header',
	// 		'priority' => 30,
	// 	)
	// );

	// Design section
	$wp_customize->add_panel( 
		'knd_decoration', 
		array( 
			'priority' => 25, 
			'capability' => 'edit_theme_options', 
			'theme_supports' => '', 
			'title' => esc_html__( 'Decoration', 'knd' ) ) );

	$wp_customize->add_section( 
		'knd_decoration_colors', 
		array( 
			'title' => esc_html__( 'Color scheme', 'knd' ), 
			'priority' => 20, 
			'capability' => 'edit_theme_options', 
			'panel' => 'knd_decoration' ) );

	$wp_customize->add_section( 
		'knd_decoration_logo', 
		array( 
			'title' => esc_html__( 'Logo', 'knd' ), 
			'priority' => 30, 
			'capability' => 'edit_theme_options', 
			'panel' => 'knd_decoration' ) );


	$wp_customize->add_setting(
		'knd_page_bg_color',
		array(
			'default'           => knd_get_theme_mod('knd_page_bg_color', '#ffffff'),
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);


	//color controlls
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_main_color', 
			array( 
				'label' => esc_html__( 'Action Color', 'knd' ), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_main_color', 
				'priority' => 10 ) ) );



	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_page_bg_color', 
			array( 
				'label' => esc_html__( 'Page Background Color', 'knd' ), 
				'description' => __('Recommended - white', 'knd'), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_page_bg_color', 
				'priority' => 15 ) ) );

	// Logo image.
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'knd_custom_logo',
			array(
				'label'       => esc_html__( 'Logo', 'knd' ),
				'description' => esc_html__( 'We recommend to use logos in the .svg format (vector graphics). If your logo is in rastre format, we recommend to upload pictures with the dimensions of 630x132 px for the option &quot;Only Image&quot; or 132x13px for the option &quot;Image and Title&quot;.', 'knd' ),
				'section'     => 'knd_decoration_logo',
				'settings'    => 'knd_custom_logo',
				'mime_type'   => 'image',
				'priority'    => 30,
			)
		)
	);

	/* homepage */

	$wp_customize->add_panel( 
		'knd_homepage', 
		array( 
			'priority' => 25, 
			'capability' => 'edit_theme_options', 
			'theme_supports' => '', 
			'title' => esc_html__( 'Homepage settings', 'knd' ), 
			'description' => esc_html__( 'Homepage settings and blocks', 'knd' ) ) );

	$wp_customize->get_section( 'static_front_page' )->panel = 'knd_homepage';
	$wp_customize->get_section( 'static_front_page' )->title = esc_html__( 'Static page settings', 'knd' );

	// move widgets in home
	$homepage_sidebar = $wp_customize->get_section( 'sidebar-widgets-knd-homepage-sidebar' );
	if ( $homepage_sidebar ) {
		$homepage_sidebar->panel = 'knd_homepage';
	}

	/**
	 * Section titles and captions
	 */
	$wp_customize->add_section(
		'knd_titles_and_captions',
		array(
			'priority' => 160,
			'title'    => esc_html__( 'Titles and captions', 'knd' ),
		)
	);

	$wp_customize->add_setting(
		'knd_projects_archive_title',
		array(
			'default' => esc_html__( 'Our projects', 'knd' ),
		)
	);

	$wp_customize->add_control(
		'knd_projects_archive_title',
		array(
			'label'   => esc_html__('Projects archive title', 'knd'),
			'type'    => 'text',
			'section' => 'knd_titles_and_captions',
		)
	);

	// Register controls if plugin leyka is active.
	if ( defined( 'LEYKA_VERSION' ) ) {

		$wp_customize->add_setting(
			'knd_active_campaigns_archive_title',
			array(
				'default' => esc_html__( 'They need help', 'knd' ),
			)
		);

		$wp_customize->add_control(
			'knd_active_campaigns_archive_title',
			array(
				'label'   => esc_html__('Active campaigns archive title', 'knd'),
				'type'    => 'text',
				'section' => 'knd_titles_and_captions',
			)
		);

		$wp_customize->add_setting(
			'knd_completed_campaigns_archive_title',
			array(
				'default' => esc_html__( 'They alredy got help', 'knd' ),
			)
		);

		$wp_customize->add_control(
			'knd_completed_campaigns_archive_title',
			array(
				'label'   => esc_html__('Completed campaigns archive title', 'knd'),
				'type'    => 'text',
				'section' => 'knd_titles_and_captions',
			)
		);
	}

}
add_action( 'customize_register', 'knd_customize_register' );

/**
 * Add admin notice if Kirki plugin not activated.
 */
function knd_kirki_admin_notices() {

	if ( get_option( 'knd_disable_kirki_notice' ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( 'appearance_page_knd-install-plugins' !== $screen->id ) {

		?>
		<div class="notice notice-info knd-kirki-notice">
			<p><?php esc_html_e('In order to make Kandinsky work full speed, you will need few plugins. WordPress will install or update the following plugins:', 'knd');?></p>
			<ul>
				<li><strong>Kirki Customizer Framework</strong><br>
					<i><?php esc_html_e('Plugin that allows web-font customization.', 'knd');?></i>
				</li>
			</ul>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=knd-install-plugins' ) ); ?>">
					<?php esc_html_e( 'Install', 'knd' ); ?>
				</a>
				<a class="button-secondary button delete-theme knd-kirki-notice-dismiss" href="#">
					<?php esc_html_e( 'No, I’ll do it some other time', 'knd' );?>
				</a>
			</p>
			<button type="button" class="knd-kirki-notice-dismiss notice-dismiss"></button>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'knd_kirki_admin_notices' );

/**
 * Save dismiss notice status
 */
function knd_save_dismiss_notice() {

	/* Check nonce */
	check_ajax_referer( 'knd-nonce', 'nonce' );

	/* Stop if the current user is not an admin or do not have administrative access */
	if ( ! current_user_can( 'manage_options' ) ) {
		die();
	}

	update_option( 'knd_disable_kirki_notice', true );

	$result = get_option( 'knd_disable_kirki_notice' );

	wp_send_json( $result );

}
add_action( 'wp_ajax_knd_dismiss_notice', 'knd_save_dismiss_notice' );
