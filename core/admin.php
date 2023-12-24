<?php /**
 * Admin customization
 **/

if ( ! defined( 'WPINC' ) )
	die();

function knd_get_admin_menu_items( $place = '' ) {
	$items = array(
		'adminBar' => array(
			'section_knd-theme-settings' => array(
				'title' => esc_attr__( 'Theme settings', 'knd' ),
				'link' => '',
				'items' => array(
					'site-title-description' => array(
						'class' => '',
						'icon' => 'dashicons-editor-insertmore',
						'text' => esc_attr__( 'Site title', 'knd' ),
						'link' => admin_url( '/customize.php?autofocus[section]=title_tagline' )
					),
					'decoration' => array(
						'class' => '',
						'icon' => 'dashicons-admin-appearance',
						'text' => esc_attr__( 'Fonts and colors', 'knd' ),
						'link' => admin_url( '/customize.php?autofocus[section]=fonts_colors' )
					),
					'social-media-links' => array(
						'class' => '',
						'icon' => 'dashicons-share',
						'text' => esc_attr__( 'Social media links', 'knd' ),
						'link' => admin_url( '/customize.php?autofocus[section]=socials' )
					),
					'header' => array(
						'class' => '',
						'icon' => 'dashicons-upload',
						'text' => esc_attr__( 'Site header', 'knd' ),
						'link' => admin_url( '/customize.php?autofocus[section]=header' )
					),
					'footer' => array(
						'class' => '',
						'icon' => 'dashicons-download',
						'text' => esc_attr__( 'Site footer', 'knd' ),
						'link' => admin_url( '/customize.php?autofocus[section]=footer' )
					),
					'site-template-change' => array(
						'class' => '',
						'icon' => 'dashicons-admin-generic',
						'text' => esc_attr__( 'Theme setup wizard', 'knd' ),
						'link' => KND_SETUP_WIZARD_URL
					),
				),
			),
			'section_knd-plugins' => array(
				'title' => esc_attr__( 'Plugins', 'knd' ),
				'link' => '',
				'items' => array(
					'search-engines-social-display' => array(
						'class' => '',
						'icon' => 'dashicons-facebook',
						'text' => esc_html__( 'SEO', 'knd' ),
						'link' => admin_url( '/admin.php?page=wpseo_dashboard#top#knowledge-graph' )
					),
					'donation-list' => array(
						'class' => '',
						'icon' => 'dashicons-chart-area',
						'text' => esc_html__( 'Donations', 'knd' ),
						'link' => admin_url( '/admin.php?page=leyka_donations' )
					)
				)
			),
			'section_other' => array(
				'title' => esc_html__( 'Other', 'knd' ),
				'link' => '',
				'items' => array(
					'user-docs' => array(
						'class' => '',
						'icon' => 'dashicons-book-alt',
						'text' => esc_html__( 'Documentation', 'knd' ),
						'link' => KND_DOC_URL
					),
					'support-telegram' => array(
						'class' => '',
						'icon' => 'dashicons-format-chat',
						'text' => esc_html__( 'Support Telegram channel', 'knd' ),
						'link' => esc_url( KND_SUPPORT_TELEGRAM )
					),
					'support-email' => array(
						'class' => '',
						'icon' => 'dashicons-email',
						'text' => esc_html__( 'Email to the support', 'knd' ),
						'link' => 'mailto:' . KND_SUPPORT_EMAIL
					),
					'bug-report' => array(
						'class' => '',
						'icon' => 'dashicons-warning',
						'text' => esc_html__( 'Report of an error', 'knd' ),
						'link' => esc_url( 'https://github.com/Teplitsa/kandinsky/issues/new' )
					),
					'theme-version' => array(
						'class' => '',
						'icon' => 'dashicons-tag',
						'text' => sprintf( esc_html__( 'Version %s', 'knd' ), knd_theme_version()),
						'link' => KND_SOURCES_PAGE_URL
					),
				)
			),
		),
		'adminMenu' => array(
			'section_knd-admin-menu' => array(
				'title' => '',
				'link' => '',
				'items' => array(
					'theme-settings' => array(
						'class' => '',
						'icon' => 'dashicons-admin-generic',
						'text' => esc_attr__( 'Theme Settings', 'knd' ),
						'link' => admin_url( '/customize.php' )
					),
					'theme-setup-wizard' => array(
						'class' => '',
						'icon' => 'dashicons-admin-generic',
						'text' => esc_attr__( 'Theme setup wizard', 'knd' ),
						'link' => KND_SETUP_WIZARD_URL
					),
					'user-docs' => array(
						'class' => '',
						'icon' => 'dashicons-book-alt',
						'text' => esc_html__( 'User documentation', 'knd' ),
						'link' => KND_DOC_URL
					),
					'email-to-support' => array(
						'class' => '',
						'icon' => 'dashicons-email',
						'text' => esc_html__( 'Email to the tech support', 'knd' ),
						'link' => 'mailto:' . KND_SUPPORT_EMAIL
					),
					'theme-version' => array(
						'class' => '',
						'icon' => 'dashicons-tag',
						'text' => sprintf( esc_html__( 'Version %s', 'knd' ), knd_theme_version()),
						'link' => KND_SOURCES_PAGE_URL
					),
				),
			),
		),
	);

	switch( $place ) {
		case 'adminbar':
		case 'adminBar':
		case 'dashboard_metabox':
		case 'dashboardMetabox':
		case 'dashboard_widget':
		case 'dashboardWidget':
			$items = $items['adminBar'];
			break;
		case 'adminmenu':
		case 'adminMenu':
			$items = $items['adminMenu'];
			break;
		default:
	}

	return $items;
}

function knd_add_admin_pages( $items = array(), $is_inital_call = true ) {
	$items = empty( $items ) || ! is_array( $items ) ? knd_get_admin_menu_items('adminMenu') : $items;

	if ( ! ! $is_inital_call ) {
		
		add_menu_page( esc_html__( 'Kandinsky settings', 'knd' ), esc_html__( 'Kandinsky', 'knd' ), 'manage_options', 'customize.php' );
		add_submenu_page( 
			'themes.php',
			esc_html__( 'Kandinsky setup wizard', 'knd' ),
			esc_html__( 'Kandinsky setup wizard', 'knd' ),
			'manage_options', 
			'knd-setup-wizard', 
			'envato_theme_setup_wizard' );
	}
	
	foreach ( $items as $key => $item ) {
		
		if ( stristr( $key, 'section_' ) !== false ) { // Section
			
			if ( ! empty( $item['items'] ) ) { // Just display all section items
				knd_add_admin_pages( $item['items'], false );
			}
		} else {
			if ( current_user_can( 'manage_options' ) ) {
				global $submenu;
				$submenu['customize.php'][] = array( $item['text'], 'manage_options', $item['link'] );
			}
		}
	}
}
add_action( 'admin_menu', 'knd_add_admin_pages' );

function knd_add_adminbar_menu( WP_Admin_Bar $admin_bar, $items = array(), $is_initial_call = true, $parent_item = false ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$items = empty( $items ) || ! is_array( $items ) ?
		knd_get_admin_menu_items( 'adminBar' ) : $items;
	$parent_item = $parent_item ? $parent_item : 'root';
	
	if ( ! ! $is_initial_call ) {
		
		$root_node_id = 'knd-adminbar-main';
		$admin_bar->add_menu( 
			array( // Parent node
'id' => $root_node_id, 'title' => esc_html__( 'Kandinsky', 'knd' ), 'href' => admin_url( 'customize.php' ) ) );
		$parent_item = $root_node_id;
	}
	
	foreach ( $items as $key => $item ) {
		
		if ( stristr( $key, 'section_' ) !== false ) {
			$section_id = str_replace( 'section_', '', $key ); // Section
			
			$admin_bar->add_menu( 
				array( 'id' => $section_id, 'title' => $item['title'], 'parent' => $parent_item ) );
			
			if ( ! empty( $item['items'] ) ) {
				knd_add_adminbar_menu( $admin_bar, $item['items'], false, $section_id );
			}
		} else { // Normal item
			$admin_bar->add_node( 
				array( 'id' => $key, 'title' => $item['text'], 'href' => $item['link'], 'parent' => $parent_item ) );
		}
	}
}
add_action( 'admin_bar_menu', 'knd_add_adminbar_menu', 111 );

function knd_admin_notice() {
	global $pagenow;
	
	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
		
		add_action( 'admin_notices', 'knd_welcome_notice' );
		update_option( 'knd_admin_notice_welcome', 1 );
		// update_option('knd_test_content_installed', 0);
	} elseif ( ! get_option( 'knd_admin_notice_welcome' ) ) {
		add_action( 'admin_notices', 'knd_welcome_notice' );
	}
}
add_action( 'load-themes.php', 'knd_admin_notice' );

function knd_welcome_notice() {
	?>

<div id="message" class="updated knd-message">
	<a class="knd-message-close notice-dismiss"
		href="<?php echo esc_url(wp_nonce_url(remove_query_arg(array('activated'), add_query_arg('knd-hide-notice', 'welcome')), 'knd_hide_notices_nonce', '_knd_notice_nonce'));?>">
			<?php esc_html_e('Dismiss', 'knd');?>
		</a>
	<p><?php printf(esc_html__('Welcome! Thank you for choosing Kandinsky! To fully take advantage of the best our theme can offer please make sure you configured %snecessary theme settings%s.', 'knd'), '<a href="'.admin_url('customize.php').'" target="_blank">', '</a>');?></p>
	<p class="submit">
		<a class="button-secondary"
			href="<?php echo esc_url(remove_query_arg(array('activated'), add_query_arg('page', 'knd-setup-wizard')));?>">
				<?php esc_html_e('Open the theme setup wizard', 'knd');?>
			</a> <a class="button-secondary"
			href="<?php echo admin_url('customize.php');?>">
				<?php esc_html_e('Open theme settings', 'knd');?>
			</a>
	</p>
</div>
<?php
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function knd_register_required_plugins() {

	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array( 
		array( 
			'name'        => esc_html__( 'Yoast SEO', 'knd' ),
			'slug'        => 'wordpress-seo', 
			'is_callable' => '', 
			'required'    => true, 
			'description' => esc_html__( 'A great tool to boost your website SEO positions.', 'knd' )
		),
		array(
			'name'        => esc_html__( 'Cyr-To-Lat', 'knd' ),
			'slug'        => 'cyr2lat', 
			'is_callable' => '', 
			'required'    => true, 
			'description' => esc_html__( 'Small helper to seamlessly convert cyrillic pages slugs into latin ones.', 'knd' ),
		),
		array(
			'name'        => esc_html__( 'Disable Comments', 'knd' ),
			'slug'        => 'disable-comments', 
			'is_callable' => array( 'Disable_Comments', 'get_instance' ),
			'required'    => false,
			'description' => esc_html__( 'Comments on the website may be harmful, so this small plugin turns them off.', 'knd' )
		),
		array( 
			'name'        => esc_html__( 'Events Manager', 'knd' ),
			'slug'        => 'events-manager', 
			'is_callable' => '',
			'required'    => false,
			'description' => esc_html__( 'Events Manager is a full-featured event registration plugin for WordPress based on the principles of flexibility, reliability and powerful features!', 'knd' )
		),
		array(
			'name'        => esc_html__( 'Kirki Customizer Framework', 'knd' ),
			'slug'        => 'kirki',
			'is_callable' => '',
			'required'    => true,
			'version'     => '4.0.0',
			'description' => esc_html__( 'The Ultimate WordPress Customizer Framework.', 'knd' ),
		),
		array( 
			'name'        => esc_html__( 'Leyka', 'knd' ),
			'slug'        => 'leyka', 
			'is_callable' => '', 
			'required'    => get_option( 'knd_setup_install_leyka', true ),
			'description' => esc_html__( 'This plugin will add means for donations collection to your website.', 'knd' )
		),
	);
	
	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array( 
		'id' => 'knd',  // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',  // Default absolute path to bundled plugins.
		'menu' => 'knd-install-plugins',  // Menu slug.
		'has_notices' => false,  // Show admin notices or not.
		'dismissable' => false,  // If false, a user cannot dismiss the nag message.
		'dismiss_msg' => '',  // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,  // Automatically activate plugins after installation or not.
		'message' => '',  // Message to output right before the plugins table.
		
		'strings' => array( 
			'page_title' => esc_html__( 'Install Required Plugins', 'knd' ),
			'menu_title' => esc_html__( 'Install Plugins', 'knd' ),
			/* translators: %s: plugin name. */
			'installing' => esc_html__( 'Installing Plugin: %s', 'knd' ),
			/* translators: %s: plugin name. */
			'updating' => esc_html__( 'Updating Plugin: %s', 'knd' ),
			'oops' => esc_html__( 'Something went wrong with the plugin API.', 'knd' ),
			'notice_can_install_required' => _n_noop( 
				'This theme requires the following plugin: %1$s.', 
				'This theme requires the following plugins: %1$s.', 
				'knd' ), 
			'notice_can_install_recommended' => _n_noop( 
				'This theme recommends the following plugin: %1$s.', 
				'This theme recommends the following plugins: %1$s.', 
				'knd' ), 
			'notice_ask_to_update' => _n_noop( 
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 
				'knd' ), 
			'notice_ask_to_update_maybe' => _n_noop( 
				'There is an update available for: %1$s.', 
				'There are updates available for the following plugins: %1$s.', 
				'knd' ), 
			'notice_can_activate_required' => _n_noop( 
				'The following required plugin is currently inactive: %1$s.', 
				'The following required plugins are currently inactive: %1$s.', 
				'knd' ), 
			'notice_can_activate_recommended' => _n_noop( 
				'The following recommended plugin is currently inactive: %1$s.', 
				'The following recommended plugins are currently inactive: %1$s.', 
				'knd' ), 
			'install_link' => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'knd' ), 
			'update_link' => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'knd' ), 
			'activate_link' => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'knd' ), 
			'return' => esc_html__( 'Return to Required Plugins Installer', 'knd' ),
			'plugin_activated' => esc_html__( 'Plugin activated successfully.', 'knd' ),
			'activated_successfully' => esc_html__( 'The following plugin was activated successfully:', 'knd' ),
			/* translators: 1: plugin name. */
			'plugin_already_active' => esc_html__( 'No action taken. Plugin %1$s was already active.', 'knd' ),
			/* translators: 1: plugin name. */
			'plugin_needs_higher_version' => esc_html__(
				'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 
				'knd' ),
			/* translators: 1: dashboard link. */
			'complete' => esc_html__( 'All plugins installed and activated successfully. %1$s', 'knd' ),
			'dismiss' => esc_html__( 'Dismiss this notice', 'knd' ),
			'notice_cannot_install_activate' => esc_html__(
				'There are one or more required or recommended plugins to install, update or activate.', 
				'knd' ), 
			'contact_admin' => esc_html__( 'Please contact the administrator of this site for help.', 'knd' ),
			
			'nag_type' => '' ) ); // Determines admin notice type - can only be one of the typical WP notice classes, such
			   // as 'updated',
			   // 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work
			   // as
			   // expected in older WP
			   // versions.
	
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'knd_register_required_plugins' );

function knd_hide_notices() {
	if ( isset( $_GET['knd-hide-notice'] ) && isset( $_GET['_knd_notice_nonce'] ) ) {
		
		if ( ! wp_verify_nonce( $_GET['_knd_notice_nonce'], 'knd_hide_notices_nonce' ) ) {
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'knd' ) );
		}
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Action failed.', 'knd' ) );
		}
		
		update_option( 'knd_admin_notice_' . sanitize_text_field( $_GET['knd-hide-notice'] ), 1 );
	}
}
add_action( 'wp_loaded', 'knd_hide_notices' );

function knd_common_columns_names( $columns ) {
	$post_type = 'post';
	if ( in_array( $post_type, array( 'post', 'project', 'org', 'person', 'event' ) ) ) {
		
		if ( in_array( $post_type, array( 'event', 'programm' ) ) )
			$columns['menu_order'] = esc_html__( 'Order', 'knd' );
		
		if ( in_array( $post_type, array( 'event' ) ) )
			$columns['event_start'] = esc_html__( 'Start', 'knd' );
		
		if ( ! in_array( $post_type, array( 'attachment' ) ) )
			$columns['thumbnail'] = esc_html__( 'Thumb.', 'knd' );
		
		if ( isset( $columns['author'] ) ) {
			$columns['author'] = esc_html__( 'Created', 'knd' );
		}
		
		$columns['id'] = 'ID';
	}

	return $columns;
}
add_filter( 'manage_posts_columns', 'knd_common_columns_names', 50, 2 );

add_action( 'manage_pages_custom_column', 'knd_common_columns_content', 2, 2 );

function knd_common_columns_content( $column_name, $post_id ) {
	$cpost = get_post( $post_id );
	if ( $column_name == 'id' ) {
		echo intval( $cpost->ID );
	} elseif ( $column_name == 'thumbnail' ) {
		$img = get_the_post_thumbnail( $post_id, 'thumbnail' );
		if ( empty( $img ) ) {
			echo '&ndash;';
		} else {
			echo '<div class="admin-tmb">' . wp_kses_post( $img ) . '</div>';
		}
	} elseif ( $column_name == 'menu_order' ) {
		echo intval( $cpost->menu_order );
	}
}

add_filter( 'manage_pages_columns', 'knd_pages_columns_names', 50 );

function knd_pages_columns_names( $columns ) {
	if ( isset( $columns['author'] ) ) {
		$columns['author'] = 'Создал';
	}

	$columns['id'] = 'ID';

	return $columns;
}

// manage_edit-topics_columns
add_filter( "manage_edit-category_columns", 'knd_common_tax_columns_names', 10 );
add_filter( "manage_edit-post_tag_columns", 'knd_common_tax_columns_names', 10 );

function knd_common_tax_columns_names( $columns ) {
	$columns['id'] = 'ID';
	
	return $columns;
}

add_filter( "manage_category_custom_column", 'knd_common_tax_columns_content', 10, 3 );
add_filter( "manage_post_tag_custom_column", 'knd_common_tax_columns_content', 10, 3 );

function knd_common_tax_columns_content( $content, $column_name, $term_id ) {
	if ( $column_name == 'id' )
		return intval( $term_id );
}

/**
 * SEO UI cleaning
 * *
 */
add_action( 
	'admin_init', 
	function () {
		foreach ( get_post_types( array( 'public' => true ), 'names' ) as $pt ) {
			add_filter( 'manage_' . $pt . '_posts_columns', 'knd_clear_seo_columns', 100 );
		}
	}, 
	100 );

function knd_clear_seo_columns( $columns ) {
	if ( isset( $columns['wpseo-score'] ) )
		unset( $columns['wpseo-score'] );
	
	if ( isset( $columns['wpseo-title'] ) )
		unset( $columns['wpseo-title'] );
	
	if ( isset( $columns['wpseo-metadesc'] ) )
		unset( $columns['wpseo-metadesc'] );
	
	if ( isset( $columns['wpseo-focuskw'] ) )
		unset( $columns['wpseo-focuskw'] );
	
	return $columns;
}

add_filter( 'wpseo_use_page_analysis', '__return_false' );

/**
 * Dashboards widgets *
 */
add_action( 'wp_dashboard_setup', 'knd_remove_dashboard_widgets' );

function knd_remove_dashboard_widgets() {
	
	// remove defaults
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	
	// add ours
	add_meta_box( 
		'knd_custom_links',
		esc_html__( 'Kandinsky — useful links', 'knd' ),
		'knd_custom_links_dashboard_screen', 
		'dashboard', 
		'side', 
		'core' );
}

function knd_custom_links_dashboard_screen( $items = array(), $is_initial_call = true ) {
	$items = empty( $items ) || ! is_array( $items ) ? knd_get_admin_menu_items('dashboardWidget') : $items;
	
	if ( ! ! $is_initial_call ) {
		?>

<div id="knd-dashboard-card" class="knd-dashboard">

	<div class="knd-logo">
		<a href="<?php echo esc_url(KND_OFFICIAL_WEBSITE_URL);?>" target="_blank">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/knd-logo.svg');?>" alt="<?php esc_attr_e( 'Kandinsky', 'knd' ); ?>">
		</a>
	</div>
	<p>
		<?php esc_html_e( 'Do you want to know all the features of the theme?', 'knd' ); ?><br>
		<?php esc_html_e( 'Find information on', 'knd' ); ?> <a href="<?php echo KND_OFFICIAL_WEBSITE_URL;?>"
			target="_blank"><?php esc_html_e( 'official website', 'knd' ); ?></a>.
	</p>
	<?php
	}
	
	foreach ( $items as $key => $item ) {
		
		if ( stristr( $key, 'section_' ) !== false ) {
			$section_id = str_replace( 'section_', '', $key ); // Section ?>

			<h3 id="<?php echo 'knd-dashboard-'.$section_id; ?>"
		class="knd-metabox-subtitle"><?php echo $item['title']; ?></h3>

			<?php
			
			if ( ! empty( $item['items'] ) ) {
				knd_custom_links_dashboard_screen( $item['items'], false );
			}
		} else { // Normal item ?>

			<div class="knd-metabox-line">
		<a
			href="<?php echo empty($item['link']) ? '#' : esc_url($item['link']);?>"
			target="_blank" class="action"> <span
			class="<?php echo empty($item['icon']) ? '' : 'dashicons '.$item['icon'];?>"></span>
					<?php echo empty($item['text']) ? '' : $item['text'];?>
				</a>
	</div>

		<?php
		}
	}
	
	if ( ! ! $is_initial_call ) {
		?>
</div>
<?php
	}
}

/**
 * Doc link in footer text *
 */
function knd_admin_footer_text( $text ) {

	return $text . '<span class="knd-admin-footer">' . sprintf ( esc_html__( 'A quick guide to working with the site - %s.', 'knd' ), links_add_target( make_clickable( KND_DOC_URL ) ) ) . '</span>';
}
add_filter( 'admin_footer_text', 'knd_admin_footer_text' );
