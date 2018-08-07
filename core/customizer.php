<?php /** Customizer options **/

if ( ! defined( 'WPINC' ) )
	die();

add_action( 'customize_register', 'knd_customize_register', 15 );

function knd_customize_register( WP_Customize_Manager $wp_customize ) {
	
	// Theme important links started
	class Knd_Important_Links extends WP_Customize_Control {

		public $type = "knd-important-links";

		public function render_content() {
			$important_links = array( 
				'theme-info' => array( 
					'link' => esc_url( TST_OFFICIAL_WEBSITE_URL ),
					'text' => esc_html__( 'Theme Info', 'knd' ) ), 
				'support' => array( 
					'link' => esc_url( KND_SUPPORT_EMAIL ),
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
			'sanitize_callback' => 'esc_url'
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
	
	// titles and captions
	$wp_customize->add_section(
		'knd_titles_and_captions',
		array( 'priority' => 160, 'title' => esc_html__( 'Titles and captions', 'knd' ) ) );
	
	// Common settings
	$wp_customize->add_setting(
		'text_in_header',
		array(
			'sanitize_callback' => 'knd_sanitize_text',
			'default' => knd_get_theme_mod('text_in_header', ''),
		)
	);
	
	$wp_customize->add_control( 
		'text_in_header', 
		array( 
			'type' => 'textarea', 
			'label' => esc_html__( 'Header text', 'knd' ), 
			'section' => 'title_tagline', 
			'settings' => 'text_in_header', 
			'priority' => 30 ) );
	

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
		'knd_main_color', 
		array( 'default' => knd_get_theme_color('knd_main_color'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_color_second', 
		array( 'default' => knd_get_theme_color('knd_color_second'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_color_third', 
		array( 'default' => knd_get_theme_color('knd_color_third'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_text1_color', 
		array( 'default' => knd_get_theme_mod('knd_text1_color', '#000000'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_text2_color', 
		array( 'default' => knd_get_theme_mod('knd_text2_color', '#000000'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_text3_color', 
		array( 'default' => knd_get_theme_mod('knd_text3_color', '#000000'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_page_bg_color', 
		array( 'default' => knd_get_theme_mod('knd_page_bg_color', '#ffffff'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_page_text_color', 
		array( 'default' => knd_get_theme_mod('knd_page_text_color', '#000000'), 'sanitize_callback' => 'sanitize_hex_color' ) );
	
	$wp_customize->add_setting( 
		'knd_custom_logo', 
		array( 'default' => knd_get_theme_mod('knd_custom_logo', ''), 'sanitize_callback' => 'absint' ) );
	
	$wp_customize->add_setting( 'knd_custom_logo_mod', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('knd_custom_logo_mod', 'image_only' ) ) );
	

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
			'knd_color_second', 
			array( 
				'label' => esc_html__( 'Complimentary Color', 'knd' ), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_color_second', 
				'priority' => 11 ) ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_color_third', 
			array( 
				'label' => esc_html__( 'Dark Color', 'knd' ), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_color_third', 
				'priority' => 12 ) ) );

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
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_page_text_color', 
			array( 
				'label' => esc_html__( 'Main Text Color', 'knd' ),
				'description' => __('Recommended - black', 'knd'),  
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_page_text_color', 
				'priority' => 17 ) ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_text1_color', 
			array( 
				'label' => esc_html__( 'Inverse Text Color', 'knd' ),
				'description' => __('Recommended - white', 'knd'), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_text1_color', 
				'priority' => 20 ) ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_text2_color', 
			array( 
				'label' => esc_html__( 'Accent Text Color - 1', 'knd' ), 
				'description' => __('Applicable only for Dubrovino template', 'knd'), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_text2_color', 
				'priority' => 24 ) ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
			$wp_customize, 
			'knd_text3_color', 
			array( 
				'label' => esc_html__( 'Accent Text Color - 2', 'knd' ), 
				'description' => __('Applicable only for Dubrovino template', 'knd'), 
				'section' => 'knd_decoration_colors', 
				'settings' => 'knd_text3_color', 
				'priority' => 26 ) ) );

	//logo controlls
	$wp_customize->add_control( 
		'knd_custom_logo_mod', 
		array( 
			'type' => 'radio', 
			'label' => esc_html__( 'Logo mode', 'knd' ), 
			'section' => 'knd_decoration_logo', 
			'settings' => 'knd_custom_logo_mod', 
			'priority' => 20, 
			'choices' => array( 
				'image_only' => esc_html__( 'Image only', 'knd' ), 
				'image_text' => esc_html__( 'Image with site name', 'knd' ), 
				'text_only' => esc_html__( 'Site name only', 'knd' ), 
				'nothing' => esc_html__( 'Do not show', 'knd' ) ) ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Cropped_Image_Control( 
			$wp_customize, 
			'knd_custom_logo', 
			array( 
				'label' => esc_html__( 'Logo', 'knd' ), 
				'description' => esc_html__( 
					'Recommended size 315x66px for Image only mode and 66x66px for Image with site name', 
					'knd' ), 
				'section' => 'knd_decoration_logo', 
				'settings' => 'knd_custom_logo', 
				'flex_width' => true, 
				'flex_height' => false, 
				'width' => 315, 
				'height' => 66, 
				'priority' => 30 ) ) );
	
	/* homepage */
	
	$wp_customize->add_panel( 
		'knd_homepage', 
		array( 
			'priority' => 25, 
			'capability' => 'edit_theme_options', 
			'theme_supports' => '', 
			'title' => esc_html__( 'Homepage settings', 'knd' ), 
			'description' => esc_html__( 'Homepage settings and blocks', 'knd' ) ) );
	
	$wp_customize->add_section( 
		'knd_homepage_hero', 
		array( 
			'title' => esc_html__( 'Hero Image', 'knd' ), 
			'priority' => 20, 
			'capability' => 'edit_theme_options', 
			'panel' => 'knd_homepage' ) );
	
	$wp_customize->get_section( 'static_front_page' )->panel = 'knd_homepage';
	$wp_customize->get_section( 'static_front_page' )->title = esc_html__( 'Static page settings', 'knd' );
	
	// move widgets in home
	$homepage_sidebar = $wp_customize->get_section( 'sidebar-widgets-knd-homepage-sidebar' );
	if ( $homepage_sidebar ) {
		$homepage_sidebar->panel = 'knd_homepage';
	}
	
	// hero image
	$wp_customize->add_setting( 
		'knd_hero_image', 
		array( 'default' => knd_get_theme_mod('knd_hero_image', ''), 'sanitize_callback' => 'absint' ) );
	
	$wp_customize->add_setting( 'knd_hero_image_support_text', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('knd_hero_image_support_text', '') ) );
	
	$wp_customize->add_setting( 'knd_hero_image_support_title', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('knd_hero_image_support_title', '') ) );
	
	$wp_customize->add_setting( 'knd_hero_image_support_url', array( 'sanitize_callback' => 'esc_url', 'default' => knd_get_theme_mod('knd_hero_image_support_url', '') ) );
	
	$wp_customize->add_setting( 'knd_hero_image_support_button_caption', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('knd_hero_image_support_button_caption', '') ) );
	
	$wp_customize->add_control( 
		new WP_Customize_Cropped_Image_Control( 
			$wp_customize, 
			'knd_hero_image', 
			array( 
				'label' => esc_html__( 'Hero Image', 'knd' ), 
				'description' => esc_html__( 'Recommended size 1600x663px', 'knd' ), 
				'section' => 'knd_homepage_hero', 
				'settings' => 'knd_hero_image', 
				'flex_width' => true, 
				'flex_height' => false,
				//'default' => knd_get_theme_mod('knd_hero_image', ''),
				'width' => 1600, 
				'height' => 663, 
				'priority' => 40 ) ) );
	
	$wp_customize->add_control( 
		'knd_hero_image_support_title', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Call to action title', 'knd' ), 
			'section' => 'knd_homepage_hero', 
			'settings' => 'knd_hero_image_support_title', 
			'priority' => 45 ) );
	
	$wp_customize->add_control( 
		'knd_hero_image_support_url', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Call to action URL', 'knd' ), 
			'section' => 'knd_homepage_hero', 
			'settings' => 'knd_hero_image_support_url', 
			'priority' => 45 ) );
	
	$wp_customize->add_control( 
		'knd_hero_image_support_text', 
		array( 
			'type' => 'textarea', 
			'label' => esc_html__( 'Call to action text', 'knd' ), 
			'section' => 'knd_homepage_hero', 
			'settings' => 'knd_hero_image_support_text', 
			'priority' => 50 ) );
	
	$wp_customize->add_control( 
		'knd_hero_image_support_button_caption', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Action button caption', 'knd' ), 
			'section' => 'knd_homepage_hero', 
			'settings' => 'knd_hero_image_support_button_caption', 
			'priority' => 55 ) );
	
	// ourorg columns
	$wp_customize->add_section( 
		'knd_ourorg_columns_settings', 
		array( 'priority' => 57, 'title' => esc_html__( 'Our organization columns settings', 'knd' ), 'panel' => 'knd_homepage' ) );
	
	for ( $i = 1; $i <= 3; $i++ ) {
		
		$wp_customize->add_setting( 
			'home-subtitle-col' . $i . '-title', 
			array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('home-subtitle-col' . $i . '-title', '') ) );
		
		$wp_customize->add_setting( 
			'home-subtitle-col' . $i . '-content', 
			array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('home-subtitle-col' . $i . '-content', '') ) );
		
		$wp_customize->add_setting( 
			'home-subtitle-col' . $i . '-link-text', 
			array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('home-subtitle-col' . $i . '-link-text', '') ) );
		
		$wp_customize->add_setting( 
			'home-subtitle-col' . $i . '-link-url', 
			array( 'sanitize_callback' => 'esc_url', 'default' => knd_get_theme_mod('home-subtitle-col' . $i . '-link-url', '') ) );
		
		$wp_customize->add_control( 
			'home-subtitle-col' . $i . '-title', 
			array( 
				'type' => 'text', 
				'label' => esc_html__( 'Column title', 'knd' ), 
				'section' => 'knd_ourorg_columns_settings', 
				'settings' => 'home-subtitle-col' . $i . '-title', 
				'priority' => $i * 10 ) );
		
		$wp_customize->add_control( 
			'home-subtitle-col' . $i . '-content', 
			array( 
				'type' => 'textarea', 
				'label' => esc_html__( 'Column content', 'knd' ), 
				'section' => 'knd_ourorg_columns_settings', 
				'settings' => 'home-subtitle-col' . $i . '-content', 
				'priority' => $i * 10 + 2 ) );
		
		$wp_customize->add_control( 
			'home-subtitle-col' . $i . '-link-text', 
			array( 
				'type' => 'text', 
				'label' => esc_html__( 'Column link caption', 'knd' ), 
				'section' => 'knd_ourorg_columns_settings', 
				'settings' => 'home-subtitle-col' . $i . '-link-text', 
				'priority' => $i * 10 + 4 ) );
		
		$wp_customize->add_control( 
			'home-subtitle-col' . $i . '-link-url', 
			array( 
				'type' => 'text', 
				'label' => esc_html__( 'Column link URL', 'knd' ), 
				'section' => 'knd_ourorg_columns_settings', 
				'settings' => 'home-subtitle-col' . $i . '-link-url', 
				'priority' => $i * 10 + 6 ) );
	}
	
	// cta options
	$wp_customize->add_section( 
		'knd_cta_block_settings', 
		array( 'priority' => 40, 'title' => esc_html__( 'CTA block settings', 'knd' ) ) );
	
	$wp_customize->add_setting( 'cta-title', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('cta-title', '') ) );
	
	$wp_customize->add_setting( 'cta-description', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('cta-description', '') ) );
	
	$wp_customize->add_setting( 'cta-button-caption', array( 'sanitize_callback' => 'knd_sanitize_text', 'default' => knd_get_theme_mod('cta-button-caption', '' ) ) );
	
	$wp_customize->add_setting( 'cta-url', array( 'sanitize_callback' => 'esc_url', 'default' => knd_get_theme_mod('cta-url', '') ) );
	
	$wp_customize->add_control( 
		'cta-title', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Call to action title', 'knd' ), 
			'section' => 'knd_cta_block_settings', 
			'settings' => 'cta-title', 
			'priority' => 40 ) );
	
	$wp_customize->add_control( 
		'cta-url', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Call to action URL', 'knd' ), 
			'section' => 'knd_cta_block_settings', 
			'settings' => 'cta-url', 
			'priority' => 45 ) );
	
	$wp_customize->add_control( 
		'cta-description', 
		array( 
			'type' => 'textarea', 
			'label' => esc_html__( 'Call to action text', 'knd' ), 
			'section' => 'knd_cta_block_settings', 
			'settings' => 'cta-description', 
			'priority' => 50 ) );
	
	$wp_customize->add_control( 
		'cta-button-caption', 
		array( 
			'type' => 'text', 
			'label' => esc_html__( 'Action button caption', 'knd' ), 
			'section' => 'knd_cta_block_settings', 
			'settings' => 'cta-button-caption', 
			'priority' => 55 ) );
	
	// Social media links
	$wp_customize->add_section( 
		'knd_social_links', 
		array( 'priority' => 60, 'title' => esc_html__( 'Social networks links', 'knd' ) ) );
	
	foreach ( knd_get_social_media_supported() as $id => $data ) {
		
		$wp_customize->add_setting( 
			'knd_social_links_' . $id, 
			array( 'sanitize_callback' => 'esc_url', 'capability' => 'edit_theme_options', 'default' => knd_get_theme_mod('knd_social_links_' . $id, '') ) );
		
		$wp_customize->add_control( 
			'knd_social_links_' . $id, 
			array( 
				'label' => $data['label'], 
				'description' => $data['description'], 
				'type' => 'url', 
				'section' => 'knd_social_links' ) );
	}
	// Social links ended
}

add_filter( 'add_menu_classes', 'knd_show_notification_bubble' );

function knd_show_notification_bubble( $menu ) {
	$notif_count = knd_get_admin_notif_count();
	
	if ( $notif_count > 0 ) {
		foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_data[2] == 'knd-setup-wizard' ) {
				$menu[$menu_key][0] .= " <span class='update-plugins' title='" .
                    esc_html__( 'Recommended plugins to install', 'knd' ) . "'><span class='plugin-count'>" . $notif_count .
					 '</span></span>';
			}
		}
	}
	
	return $menu;
}

function knd_get_admin_notif_count() {
	$not_installed_plugins = 0;
	
	if ( is_admin() ) {
		
		if ( ! is_plugin_active( 'leyka/leyka.php' ) ) {
			$not_installed_plugins += 1;
		}
		
		if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			$not_installed_plugins += 1;
		}
		
		if ( ! is_plugin_active( 'cyr3lat/cyr-to-lat.php' ) ) {
			$not_installed_plugins += 1;
		}
		
		if ( ! is_plugin_active( 'disable-comments/disable-comments.php' ) ) {
			$not_installed_plugins += 1;
		}
	}
	
	return $not_installed_plugins;
}

function knd_sanitize_text($text) {
	return sanitize_text_field($text);
}