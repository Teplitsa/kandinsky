<?php

/**
 * Admin customization
 **/
function knd_get_admin_menu_items() {
	return array( 
		'section_knd-settings-content' => array( 
			'title' => __( 'Theme settings & content', 'knd' ), 
			'link' => '#knd-admin-menu-settings', 
			'items' => array( 
				'site-title-description' => array( 
					'class' => '', 
					'icon' => 'dashicons-editor-insertmore', 
					'text' => __( 'Site title and description', 'knd' ), 
					'link' => admin_url( '/customize.php?autofocus[section]=title_tagline' ) ), 
				'decoration' => array( 
					'class' => '', 
					'icon' => 'dashicons-admin-appearance', 
					'text' => __( 'Decoration', 'knd' ), 
					'link' => admin_url( '/customize.php?autofocus[panel]=knd_decoration' ) ), 
				'social-media-links' => array( 
					'class' => '', 
					'icon' => 'dashicons-share', 
					'text' => __( 'Social media links', 'knd' ), 
					'link' => admin_url( '/customize.php?autofocus[section]=knd_social_links' ) ), 
				'cta-block' => array( 
					'class' => '', 
					'icon' => 'dashicons-thumbs-up', 
					'text' => __( '"Call to action" block', 'knd' ), 
					'link' => admin_url( '/customize.php?autofocus[section]=knd_cta_block_settings' ) ), 
				'page-list' => array( 
					'class' => '', 
					'icon' => 'dashicons-admin-page', 
					'text' => __( 'Static pages', 'knd' ), 
					'link' => admin_url( '/edit.php?post_type=page' ) ), 
				'news-list' => array( 
					'class' => '', 
					'icon' => 'dashicons-admin-post', 
					'text' => __( 'News', 'knd' ), 
					'link' => admin_url( '/edit.php' ) ), 
				'person-list' => array( 
					'class' => '', 
					'icon' => 'dashicons-groups', 
					'text' => __( 'Team', 'knd' ), 
					'link' => admin_url( '/edit.php?post_type=person' ) ), 
				'project-list' => array( 
					'class' => '', 
					'icon' => 'dashicons-category', 
					'text' => __( 'Projects', 'knd' ), 
					'link' => admin_url( '/edit.php?post_type=project' ) ), 
				'search-engines-social-display' => array( 
					'class' => '', 
					'icon' => 'dashicons-facebook', 
					'text' => __( 'Search engines & social networks display', 'knd' ), 
					'link' => admin_url( '/admin.php?page=wpseo_dashboard#top#knowledge-graph' ) ), 
				'donation-list' => array( 
					'class' => '', 
					'icon' => 'dashicons-chart-area', 
					'text' => __( 'Donations', 'knd' ), 
					'link' => admin_url( '/edit.php?post_type=leyka_donation' ) ) ) ), 
		
		'section_other' => array( 
			'title' => __( 'Other', 'knd' ), 
			'link' => '#knd-admin-menu-other', 
			'items' => array( 
				'knd-wizard' => array( 
					'class' => '', 
					'icon' => 'dashicons-admin-generic', 
					'text' => __( 'Theme setup wizard', 'knd' ), 
					'link' => KND_SETUP_WIZARD_URL ), 
				'user-docs' => array( 
					'class' => '', 
					'icon' => 'dashicons-book-alt', 
					'text' => __( 'User documentation', 'knd' ), 
					'link' => KND_DOC_URL ), 
				'remove-theme' => array(
					'class' => 'knd-remove-theme',
					'icon' => 'dashicons-no',
					'text' => __( 'Remove theme', 'knd' ),
					'link' => admin_url( 'admin.php?page=remove-kandinsky-theme' ) ),
				'email-to-support' => array( 
					'class' => '', 
					'icon' => 'dashicons-email', 
					'text' => __( 'Email to the tech support', 'knd' ), 
					'link' => 'mailto:' . KND_SUPPORT_EMAIL ) ) ) );
}

function knd_cancel_remove_theme() {
	if(isset($_POST['knd-cancel-remove-theme'])) {
		wp_redirect( admin_url( '/index.php' ) );
	}
}
add_action( 'admin_init', 'knd_cancel_remove_theme' );

function knd_remove_theme() {
	$is_theme_data_removed = false;
	
	if(isset($_POST['knd-remove-theme'])) {
		$nonce = $_REQUEST['_wpnonce'];
		if(wp_verify_nonce( $nonce, 'knd-remove-theme' )) {
			
			get_theme_mod('knd_site_scenario');
			
			$imp = new KND_Import_Remote_Content(get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();
			
			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->remove_all_content();
				
			$is_theme_data_removed = true;
		}
	}
	
?>
<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

<?php if($is_theme_data_removed): ?>
        <div class="notice notice-success is-dismissible">
          <p><?php esc_html_e( 'Kandinsky theme removed', 'knd' ); ?></p>
        </div>
<?php else:?>

	<form action="" method="POST" class="knd-confirm-remove-theme">
		<?php
			wp_nonce_field('knd-remove-theme');
			?>
            <h2><?php esc_html_e("Are you sure you want to remove Kandinsky theme?", 'knd'); ?></h2>
            <p>
            <?php
			submit_button(__("Yes, remove Kandinsky theme", 'knd'), 'primary', 'knd-remove-theme', false);
			submit_button(__("Cancel", 'knd'), 'secondary', 'knd-cancel-remove-theme', false, array('class' => 'knd-cancel-remove-theme-button'));
		?>
            </p>
	</form>
    
</div>
<?php
	endif;
}

function knd_add_admin_pages( $items = array(), $is_inital_call = true ) {
	$items = empty( $items ) || ! is_array( $items ) ? knd_get_admin_menu_items() : $items;

	if ( ! ! $is_inital_call ) {
		
		add_menu_page( __( 'Kandinsky settings', 'knd' ), __( 'Kandinsky', 'knd' ), 'manage_options', 'customize.php' );
		add_submenu_page( 
			'themes.php', 
			__( 'Kandinsky setup wizard', 'knd' ), 
			__( 'Kandinsky setup wizard', 'knd' ), 
			'manage_options', 
			'knd-setup-wizard', 
			'envato_theme_setup_wizard' );
		
		add_menu_page(
			__( 'Remove theme', 'knd' ),
			__( 'Remove theme', 'knd' ),
			'manage_options',
			'remove-kandinsky-theme',
			'knd_remove_theme' );
					
	}
	
	foreach ( $items as $key => $item ) {
		
		if ( stristr( $key, 'section_' ) !== false ) { // Section
			
			if ( ! empty( $item['items'] ) ) { // Just display all section items
				knd_add_admin_pages( $item['items'], false );
			}
		} else {
			
			global $submenu;
			$submenu['customize.php'][] = array( $item['text'], 'manage_options', $item['link'] );
		}
	}
}
add_action( 'admin_menu', 'knd_add_admin_pages' );

function knd_add_adminbar_menu( WP_Admin_Bar $admin_bar, $items = array(), $is_initial_call = true, $parent_item = false ) {
	$items = empty( $items ) || ! is_array( $items ) ? knd_get_admin_menu_items() : $items;
	$parent_item = $parent_item ? $parent_item : 'root';
	
	if ( ! ! $is_initial_call ) {
		
		$knd_get_admin_notif_count = knd_get_admin_notif_count();
		
		$root_node_id = 'knd-adminbar-main';
		$admin_bar->add_menu( 
			array( // Parent node
'id' => $root_node_id, 'title' => __( 'Kandinsky', 'knd' ), 'href' => admin_url( 'customize.php' ) ) );
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
			'name' => __( 'Yoast SEO', 'knd' ), 
			'slug' => 'wordpress-seo', 
			'is_callable' => 'wpseo_init', 
			'required' => true, 
			'description' => __( 'A great tool to boost your website SEO positions.', 'knd' ) ), 
		array( 
			'name' => __( 'Cyr to Lat enhanced', 'knd' ), 
			'slug' => 'cyr3lat', 
			'is_callable' => 'ctl_sanitize_title', 
			'required' => true, 
			'description' => __( 'Small helper to seamlessly convert cyrillic pages slugs into latin ones.', 'knd' ) ), 
		array( 
			'name' => __( 'Disable Comments', 'knd' ), 
			'slug' => 'disable-comments', 
			'is_callable' => array( 'Disable_Comments', 'get_instance' ), 
			'required' => true, 
			'description' => __( 'Comments on the website may be harmful, so this small plugin turns them off.', 'knd' ) ), 
		array( 
			'name' => __( 'Shortcake (Shortcodes UI)', 'knd' ), 
			'slug' => 'shortcode-ui', 
			'is_callable' => 'shortcode_ui_init', 
			'required' => true, 
			'description' => __( 
				'A visual editing for shortcodes to enrich your content management experience.', 
				'knd' ) ), 
		// array(
		// 'name' => __('Shortcake Richtext', 'knd'),
		// 'slug' => 'shortcode-ui-richtext',
		// 'is_callable' => array('ShortcodeUiRichtext\Plugin', '__construct'),
		// 'required' => true,
		// 'description' => __('Rich text fields for shortcodes UI.', 'knd'),
		// ),
		array( 
			'name' => __( 'Leyka', 'knd' ), 
			'slug' => 'leyka', 
			'is_callable' => 'leyka', 
			'required' => get_option( 'knd_setup_install_leyka' ),  // get_theme_mod('knd_site_scenario') ==
			                                                        // 'fundraising-org',
			'description' => __( 'This plugin will add means for donations collection to your website.', 'knd' ) ) );
	
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
			'page_title' => __( 'Install Required Plugins', 'knd' ), 
			'menu_title' => __( 'Install Plugins', 'knd' ),
            /* translators: %s: plugin name. */
            'installing' => __( 'Installing Plugin: %s', 'knd' ),
            /* translators: %s: plugin name. */
            'updating' => __( 'Updating Plugin: %s', 'knd' ), 
			'oops' => __( 'Something went wrong with the plugin API.', 'knd' ), 
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
			'return' => __( 'Return to Required Plugins Installer', 'knd' ), 
			'plugin_activated' => __( 'Plugin activated successfully.', 'knd' ), 
			'activated_successfully' => __( 'The following plugin was activated successfully:', 'knd' ),
            /* translators: 1: plugin name. */
            'plugin_already_active' => __( 'No action taken. Plugin %1$s was already active.', 'knd' ),
            /* translators: 1: plugin name. */
            'plugin_needs_higher_version' => __( 
				'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 
				'knd' ),
            /* translators: 1: dashboard link. */
            'complete' => __( 'All plugins installed and activated successfully. %1$s', 'knd' ), 
			'dismiss' => __( 'Dismiss this notice', 'knd' ), 
			'notice_cannot_install_activate' => __( 
				'There are one or more required or recommended plugins to install, update or activate.', 
				'knd' ), 
			'contact_admin' => __( 'Please contact the administrator of this site for help.', 'knd' ), 
			
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
			wp_die( __( 'Action failed. Please refresh the page and retry.', 'knd' ) );
		}
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Action failed.', 'knd' ) );
		}
		
		update_option( 'knd_admin_notice_' . sanitize_text_field( $_GET['knd-hide-notice'] ), 1 );
	}
}
add_action( 'wp_loaded', 'knd_hide_notices' );

add_filter( 'manage_posts_columns', 'knd_common_columns_names', 50, 2 );

function knd_common_columns_names( $columns, $post_type ) {
	if ( in_array( $post_type, array( 'post', 'project', 'org', 'person', 'event' ) ) ) {
		
		if ( in_array( $post_type, array( 'event', 'programm' ) ) )
			$columns['menu_order'] = 'Порядок';
		
		if ( in_array( $post_type, array( 'event' ) ) )
			$columns['event_start'] = 'Начало';
		
		if ( ! in_array( $post_type, array( 'attachment' ) ) )
			$columns['thumbnail'] = 'Миниат.';
		
		if ( isset( $columns['author'] ) ) {
			$columns['author'] = 'Создал';
		}
		
		$columns['id'] = 'ID';
	}
	
	return $columns;
}

add_action( 'manage_pages_custom_column', 'knd_common_columns_content', 2, 2 );
add_action( 'manage_posts_custom_column', 'knd_common_columns_content', 2, 2 );

function knd_common_columns_content( $column_name, $post_id ) {
	$cpost = get_post( $post_id );
	if ( $column_name == 'id' ) {
		echo intval( $cpost->ID );
	} elseif ( $column_name == 'thumbnail' ) {
		$img = get_the_post_thumbnail( $post_id, 'thumbnail' );
		if ( empty( $img ) )
			echo "&ndash;";
		else
			echo "<div class='admin-tmb'>{$img}</div>";
	} elseif ( $column_name == 'event_start' ) {
		$event = new TST_Event( $post_id );
		echo $event->get_date_mark( 'formal' );
	} elseif ( $column_name == 'menu_order' ) {
		
		echo intval( $cpost->menu_order );
	}
}

add_filter( 'manage_pages_columns', 'knd_pages_columns_names', 50 );

function knd_pages_columns_names( $columns ) {
	if ( isset( $columns['author'] ) ) {
		$columns['author'] = 'Создал';
	}
	
	// $columns['menu_order'] = 'Порядок';
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
 * Visual editor *
 */
add_filter( 'tiny_mce_before_init', 'knd_format_TinyMCE' );

function knd_format_TinyMCE( $in ) {
	$in['block_formats'] = "Абзац=p; Выделенный=pre; Заголовок 3=h3; Заголовок 4=h4; Заголовок 5=h5; Заголовок 6=h6";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';
	
	return $in;
}

/**
 * Remove leyka metabox for embedable iframe
 */
add_action( 'add_meta_boxes', 'knd_remove_leyka_wrong_metaboxes', 20 );

function knd_remove_leyka_wrong_metaboxes() {
	remove_meta_box( 'leyka_campaign_embed', 'leyka_campaign', 'normal' );
}

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
		__( 'Kandinsky — useful links', 'knd' ), 
		'knd_custom_links_dashboard_screen', 
		'dashboard', 
		'side', 
		'core' );
}

function knd_custom_links_dashboard_screen( $items = array(), $is_initial_call = true ) {
	$items = empty( $items ) || ! is_array( $items ) ? knd_get_admin_menu_items() : $items;
	
	if ( ! ! $is_initial_call ) {
		?>

<div id="knd-dashboard-card" class="knd-dashboard">

    <div class="knd-logo">
        <a href="<?php echo esc_url(KND_OFFICIAL_WEBSITE_URL);?>"
            target="_blank"> <img
            src="<?php echo esc_url(get_template_directory_uri().'/knd-logo.svg');?>">
        </a>
    </div>
    <p>
        Хотите быть в курсе всех возможностей темы?<br>Найдите
        информацию на <a href="<?php echo KND_OFFICIAL_WEBSITE_URL;?>"
            target="_blank">её официальном сайте</a>.
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
add_filter( 'admin_footer_text', 'knd_admin_fotter_text' );

function knd_admin_fotter_text( $text ) {
	$doc = defined( 'TST_DOC_URL' ) ? TST_DOC_URL : '';
	
	if ( empty( $doc ) )
		return $text;
	
	if ( ! empty( $doc ) )
		$doc = str_replace( '<a', '<a target="_blank" ', make_clickable( $doc ) );
	
	$text = '<span id="footer-thankyou">Краткое руководство по работе с сайтом - ' . $doc . '</span>';
	return $text;
}

/**
 * Notification about wront thumbnail size *
 */
add_filter( 'admin_post_thumbnail_html', 'knd_thumbnail_dimensions_check', 10, 2 );

function knd_thumbnail_dimensions_check( $thumbnail_html, $post_id ) {
	global $_wp_additional_image_sizes;
	
	if ( 'org' == get_post_type( $post_id ) )
		return $thumbnail_html;
	
	$meta = wp_get_attachment_metadata( get_post_thumbnail_id( $post_id ) );
	$needed_sizes = ( isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) ? $_wp_additional_image_sizes['post-thumbnail'] : false;
	
	if ( $meta && $needed_sizes &&
		 ( $meta['width'] < $needed_sizes['width'] || $meta['height'] < $needed_sizes['height'] ) ) {
		
		$size = "<b>" . $needed_sizes['width'] . 'x' . $needed_sizes['height'] . "</b>";
		$txt = sprintf( __( 'ATTENTION! You thumbnail image is too small. It should be at least %s px', 'kds' ), $size );
		
		echo "<p class='rdc-error'>{$txt}<p>";
	}
	
	return $thumbnail_html;
}

