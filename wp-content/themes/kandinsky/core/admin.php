<?php
/**
 * Admin customization
 **/

function knd_get_admin_menu_items() {
    return array(
        'section_knd-settings-content' => array(
            'title' => __('Theme settings & content'),
            'link' => '#knd-admin-menu-settings',
            'items' => array(
                'site-title-description' => array(
                    'class' => '',
                    'icon' => 'dashicons-editor-insertmore',
                    'text' => __('Site title and description', 'knd'),
                    'link' => admin_url('/customize.php?autofocus[section]=title_tagline'),
                ),
                'decoration' => array(
                    'class' => '',
                    'icon' => 'dashicons-admin-appearance',
                    'text' => __('Decoration', 'knd'),
                    'link' => admin_url('/customize.php?autofocus[panel]=knd_decoration'),
                ),
                'social-media-links' => array(
                    'class' => '',
                    'icon' => 'dashicons-share',
                    'text' => __('Social media links', 'knd'),
                    'link' => admin_url('/customize.php?autofocus[section]=knd_social_links'),
                ),
                'cta-block' => array(
                    'class' => '',
                    'icon' => 'dashicons-thumbs-up',
                    'text' => __('"Call to action" block', 'knd'),
                    'link' => admin_url('/customize.php?autofocus[section]=knd_cta_block_settings'),
                ),
                'page-list' => array(
                    'class' => '',
                    'icon' => 'dashicons-admin-page',
                    'text' => __('Static pages', 'knd'),
                    'link' => admin_url('/edit.php?post_type=page'),
                ),
                'news-list' => array(
                    'class' => '',
                    'icon' => 'dashicons-admin-post',
                    'text' => __('News', 'knd'),
                    'link' => admin_url('/edit.php'),
                ),
                'person-list' => array(
                    'class' => '',
                    'icon' => 'dashicons-groups',
                    'text' => __('Team', 'knd'),
                    'link' => admin_url('/edit.php?post_type=person'),
                ),
                'project-list' => array(
                    'class' => '',
                    'icon' => 'dashicons-category',
                    'text' => __('Projects', 'knd'),
                    'link' => admin_url('/edit.php?post_type=project'),
                ),
                'search-engines-social-display' => array(
                    'class' => '',
                    'icon' => 'dashicons-facebook',
                    'text' => __('Search engines & social networks display', 'knd'),
                    'link' => admin_url('/admin.php?page=wpseo_dashboard#top#knowledge-graph'),
                ),
                'donation-list' => array(
                    'class' => '',
                    'icon' => 'dashicons-chart-area',
                    'text' => __('Donations', 'knd'),
                    'link' => admin_url('/edit.php?post_type=leyka_donation'),
                ),
            ),
        ),

        'section_other' => array(
            'title' => __('Other', 'knd'),
            'link' => '#knd-admin-menu-other',
            'items' => array(
                'knd-wizard' => array(
                    'class' => '',
                    'icon' => 'dashicons-admin-generic',
                    'text' => __('Theme setup wizard', 'knd'),
                    'link' => KND_SETUP_WIZARD_URL,
                ),
                'user-docs' => array(
                    'class' => '',
                    'icon' => 'dashicons-book-alt',
                    'text' => __('User documentation', 'knd'),
                    'link' => KND_DOC_URL,
                ),
                'email-to-support' => array(
                    'class' => '',
                    'icon' => 'dashicons-email',
                    'text' => __('Email to the tech support', 'knd'),
                    'link' => 'mailto:'.KND_SUPPORT_EMAIL,
                ),
//                '' => array(
//                    'class' => '',
//                    'icon' => '',
//                    'text' => __('', 'knd'),
//                    'link' => ,
//                ),
            ),
        ),
    );
}

function knd_add_admin_pages($items = array(), $is_inital_call = true) {

    $items = empty($items) || !is_array($items) ? knd_get_admin_menu_items() : $items;

    if( !!$is_inital_call ) {

        add_menu_page(__('Kandinsky settings', 'knd'), __('Kandinsky', 'knd'), 'manage_options', 'customize.php');
        add_submenu_page('themes.php', __('Kandinsky setup wizard', 'knd'), __('Kandinsky setup wizard', 'knd'), 'manage_options', 'knd-setup-wizard', 'envato_theme_setup_wizard');

    }

    foreach($items as $key => $item) {

        if(stristr($key, 'section_') !== false) { // Section

            if( !empty($item['items']) ) { // Just display all section items
                knd_add_admin_pages($item['items'], false);
            }

        } else {

            global $submenu;
            $submenu['customize.php'][] = array($item['text'], 'manage_options', $item['link']);

        }
    }

}
add_action('admin_menu', 'knd_add_admin_pages');

function knd_add_menu_item(WP_Admin_Bar $admin_bar)  { /** @todo */

    $knd_get_admin_notif_count = knd_get_admin_notif_count();
    $notif_html = $knd_get_admin_notif_count ? '<div class="wp-core-ui wp-ui-notification knd-adminbar-notif"><span aria-hidden="true">'.$knd_get_admin_notif_count.'</span></div>' : '';

    $admin_bar->add_menu(array( // Parent node
        'id' => 'kandinsky-main',
        'title' => __('Kandinsky', 'knd'),
        'href' => '', //admin_url('customize.php'),
        'meta' => array(
            'html' => $notif_html,
        ),
    ));

}
add_action('admin_bar_menu', 'knd_add_menu_item', 111);

function knd_admin_notice() {

    global $pagenow;

    if('themes.php' == $pagenow && isset($_GET['activated'])) {

        add_action('admin_notices', 'knd_welcome_notice');
        update_option('knd_admin_notice_welcome', 1);
//        update_option('knd_test_content_installed', 0);

    } elseif( !get_option('knd_admin_notice_welcome') ) {
        add_action('admin_notices', 'knd_welcome_notice');
    }

}
add_action('load-themes.php', 'knd_admin_notice');

function knd_welcome_notice() {?>

    <div id="message" class="updated knd-message">
        <a class="knd-message-close notice-dismiss" href="<?php echo esc_url(wp_nonce_url(remove_query_arg(array('activated'), add_query_arg('knd-hide-notice', 'welcome')), 'knd_hide_notices_nonce', '_knd_notice_nonce'));?>">
            <?php esc_html_e('Dismiss', 'knd');?>
        </a>
        <p><?php printf(esc_html__('Welcome! Thank you for choosing Kandinsky! To fully take advantage of the best our theme can offer please make sure you configured %snecessary theme settings%s.', 'knd'), '<a href="'.admin_url('customize.php').'" target="_blank">', '</a>');?></p>
        <p class="submit">
            <a class="button-secondary" href="<?php echo esc_url(remove_query_arg(array('activated'), add_query_arg('page', 'knd-setup-wizard')));?>">
                <?php esc_html_e('Open the theme setup wizard', 'knd');?>
            </a>
            <a class="button-secondary" href="<?php echo admin_url('customize.php');?>">
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
            'name'        => __('Yoast SEO', 'knd'),
            'slug'        => 'wordpress-seo',
            'is_callable' => 'wpseo_init',
            'required'    => true,
            'description' => __('A great tool to boost your website SEO positions.', 'knd'),
        ),
        array(
            'name'        => __('Cyr to Lat enhanced', 'knd'),
            'slug'        => 'cyr3lat',
            'is_callable' => 'ctl_sanitize_title',
            'required'    => true,
            'description' => __('Small helper to seamlessly convert cyrillic pages slugs into latin ones.', 'knd'),
        ),
        array(
            'name'        => __('Disable Comments', 'knd'),
            'slug'        => 'disable-comments',
            'is_callable' => array('Disable_Comments', 'get_instance'),
            'required'    => true,
            'description' => __('Comments on the website may be harmful, so this small plugin turns them off.', 'knd'),
        ),
        array(
            'name'        => __('Shortcake (Shortcodes UI)', 'knd'),
            'slug'        => 'shortcode-ui',
            'is_callable' => 'shortcode_ui_init',
            'required'    => true,
            'description' => __('A visual editing for shortcodes to enrich your content management experience.', 'knd'),
        ),
//        array(
//            'name'        => __('Shortcake Richtext', 'knd'),
//            'slug'        => 'shortcode-ui-richtext',
//            'is_callable' => array('ShortcodeUiRichtext\Plugin', '__construct'),
//            'required'    => true,
//            'description' => __('Rich text fields for shortcodes UI.', 'knd'),
//        ),
        array(
            'name'        => __('Leyka', 'knd'),
            'slug'        => 'leyka',
            'is_callable' => 'leyka',
            'required'    => get_option('knd_setup_install_leyka'), //get_theme_mod('knd_site_scenario') == 'fundraising-org',
            'description' => __('This plugin will add means for donations collection to your website.', 'knd'),
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
        'id'           => 'knd',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'knd-install-plugins',  // Menu slug.
        'has_notices'  => false,                    // Show admin notices or not.
        'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                     // Message to output right before the plugins table.

        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'knd' ),
            'menu_title'                      => __( 'Install Plugins', 'knd' ),
            /* translators: %s: plugin name. */
            'installing'                      => __( 'Installing Plugin: %s', 'knd' ),
            /* translators: %s: plugin name. */
            'updating'                        => __( 'Updating Plugin: %s', 'knd' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'knd' ),
            'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'knd'),
            'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'knd'),
            'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'knd'),
            'notice_ask_to_update_maybe'      => _n_noop('There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'knd'),
            'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'knd'),
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'knd'),
            'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'knd'),
            'update_link' 					  => _n_noop('Begin updating plugin', 'Begin updating plugins', 'knd'),
            'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'knd'),
            'return'                          => __( 'Return to Required Plugins Installer', 'knd' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'knd' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'knd' ),
            /* translators: 1: plugin name. */
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'knd' ),
            /* translators: 1: plugin name. */
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'knd' ),
            /* translators: 1: dashboard link. */
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'knd' ),
            'dismiss'                         => __( 'Dismiss this notice', 'knd' ),
            'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'knd' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'knd' ),

            'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
    );

    tgmpa($plugins, $config);

}
add_action('tgmpa_register', 'knd_register_required_plugins');

function knd_hide_notices() {
    if(isset($_GET['knd-hide-notice']) && isset($_GET['_knd_notice_nonce'])) {

        if( !wp_verify_nonce($_GET['_knd_notice_nonce'], 'knd_hide_notices_nonce') ) {
            wp_die(__('Action failed. Please refresh the page and retry.', 'knd'));
        }

        if( !current_user_can( 'manage_options') ) {
            wp_die(__( 'Action failed.', 'knd'));
        }

        update_option('knd_admin_notice_'.sanitize_text_field($_GET['knd-hide-notice']), 1);

    }
}
add_action('wp_loaded', 'knd_hide_notices');

add_filter('manage_posts_columns', 'knd_common_columns_names', 50, 2);
function knd_common_columns_names($columns, $post_type) {
		
	if(in_array($post_type, array('post', 'project', 'org', 'person', 'event'))){
		
		if(in_array($post_type, array('event', 'programm')))
			$columns['menu_order'] = 'Порядок';
		
		if(in_array($post_type, array('event')))
			$columns['event_start'] = 'Начало';
		
		if(!in_array($post_type, array('attachment')))
			$columns['thumbnail'] = 'Миниат.';
		
		if(isset($columns['author'])){
			$columns['author'] = 'Создал';
		}
		
		$columns['id'] = 'ID';
	}
	
	return $columns;
}

add_action('manage_pages_custom_column', 'knd_common_columns_content', 2, 2);
add_action('manage_posts_custom_column', 'knd_common_columns_content', 2, 2);
function knd_common_columns_content($column_name, $post_id) {
	
	$cpost = get_post($post_id);
	if($column_name == 'id'){
		echo intval($cpost->ID);
		
	}
	elseif($column_name == 'thumbnail') {
		$img = get_the_post_thumbnail($post_id, 'thumbnail');
		if(empty($img))
			echo "&ndash;";
		else
			echo "<div class='admin-tmb'>{$img}</div>";			
		
	}
	elseif($column_name == 'event_start') {
		$event = new TST_Event($post_id);
		echo $event->get_date_mark('formal');
	}
	elseif($column_name == 'menu_order') {
			
		echo intval($cpost->menu_order);
	}
}


add_filter('manage_pages_columns', 'knd_pages_columns_names', 50);
function knd_pages_columns_names($columns) {
		
	if(isset($columns['author'])){
		$columns['author'] = 'Создал';
	}
	
	//$columns['menu_order'] = 'Порядок';	
	$columns['id'] = 'ID';
		
	return $columns;
}




//manage_edit-topics_columns
add_filter( "manage_edit-category_columns", 'knd_common_tax_columns_names', 10);
add_filter( "manage_edit-post_tag_columns", 'knd_common_tax_columns_names', 10);
function knd_common_tax_columns_names($columns){
	
	$columns['id'] = 'ID';
	
	return $columns;	
}

add_filter( "manage_category_custom_column", 'rdc_common_tax_columns_content', 10, 3);
add_filter( "manage_post_tag_custom_column", 'rdc_common_tax_columns_content', 10, 3);
function rdc_common_tax_columns_content($content, $column_name, $term_id){
	
	if($column_name == 'id')
		return intval($term_id);
}


/* admin tax columns */
/*add_filter('manage_taxonomies_for_material_columns', function($taxonomies){
	$taxonomies[] = 'pr_type';
	$taxonomies[] = 'audience';
	
    return $taxonomies;
});*/



/**
* SEO UI cleaning
**/
add_action('admin_init', function(){
	foreach(get_post_types(array('public' => true), 'names') as $pt) {
		add_filter('manage_' . $pt . '_posts_columns', 'rdc_clear_seo_columns', 100);
	}	
}, 100);

function rdc_clear_seo_columns($columns){

	if(isset($columns['wpseo-score']))
		unset($columns['wpseo-score']);
	
	if(isset($columns['wpseo-title']))
		unset($columns['wpseo-title']);
	
	if(isset($columns['wpseo-metadesc']))
		unset($columns['wpseo-metadesc']);
	
	if(isset($columns['wpseo-focuskw']))
		unset($columns['wpseo-focuskw']);
	
	return $columns;
}

add_filter('wpseo_use_page_analysis', '__return_false');


/* Excerpt metabox */
//add_action('add_meta_boxes', 'rdc_correct_metaboxes', 2, 2);
function rdc_correct_metaboxes($post_type, $post ){
	
	if(post_type_supports($post_type, 'excerpt')){
		remove_meta_box('postexcerpt', null, 'normal');
		
		$label = ($post_type == 'org') ? __('Website', 'kds') : __('Excerpt', 'kds');
		add_meta_box('rdc_postexcerpt', $label, 'rdc_excerpt_meta_box', null, 'normal', 'core');
	}
	
}

function rdc_excerpt_meta_box($post){
	if($post->post_type == 'org'){
?>
<label class="screen-reader-text" for="excerpt"><?php _e('Website', 'kds'); ?></label>
<input type="text" name="excerpt" id="url-excerpt" value="<?php echo $post->post_excerpt; // textarea_escaped ?>" class="widefat">

<?php }	else { ?>

<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt', 'kds'); ?></label>
<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
<p><?php _e('Annotation for items lists (will be printed at the beginning of the single page)', 'kds'); ?></p>

<?php	
}
	
}


/**  Home page settings in admin menu */
add_action('admin_menu', 'rdc_add_menu_items', 25);
function rdc_add_menu_items(){
    
	$id = (int)get_option('page_on_front', 0);
	
    add_submenu_page('index.php',
                    'Настройки главной',
                    'Настройки главной',
                    'edit_pages',
                    'post.php?post='.$id.'&action=edit'                    
    );   
}


/** Visual editor **/
add_filter('tiny_mce_before_init', 'rdc_format_TinyMCE');
function rdc_format_TinyMCE($in){

    $in['block_formats'] = "Абзац=p; Выделенный=pre; Заголовок 3=h3; Заголовок 4=h4; Заголовок 5=h5; Заголовок 6=h6";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';

	return $in;
}

/* Menu Labels */
add_action('admin_menu', 'rdc_admin_menu_labels');
function rdc_admin_menu_labels(){ /* change adming menu labels */
    global $menu, $submenu;
	
    //lightbox   
    foreach($submenu['options-general.php'] as $order => $item){
		
        if(isset($item[2]) && $item[2] == 'responsive-lightbox'){
			$submenu['options-general.php'][$order][0] = 'Lightbox';			
		}        
    }
	
	//forms
	foreach($menu as $order => $item){
        
        if($item[2] == 'ninja-forms'){ 
            $menu[$order][0] = __('Forms', 'tst');            
            break;
        }
    }   
}

/** Remove leyka metabox for embedable iframe */
add_action( 'add_meta_boxes' , 'rdc_remove_leyka_wrong_metaboxes', 20 );
function rdc_remove_leyka_wrong_metaboxes() {
	
	remove_meta_box('leyka_campaign_embed', 'leyka_campaign', 'normal');
}


/** Dashboards widgets **/
add_action('wp_dashboard_setup', 'rdc_remove_dashboard_widgets' );
function rdc_remove_dashboard_widgets() {
	
	//remove defaults 	
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );	
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		
	
	//add ours
    add_meta_box('knd_custom_links', __('Kandinsky — useful links', 'knd'), 'knd_custom_links_dashboard_screen', 'dashboard', 'side', 'core');

}

function knd_custom_links_dashboard_screen($items = array(), $is_initial_call = true) {

    $items = empty($items) || !is_array($items) ? knd_get_admin_menu_items() : $items;

    if( !!$is_initial_call ) {?>

<div id="knd-dashboard-card" class="knd-dashboard">

    <div class="knd-logo">
        <a href="<?php echo esc_url(KND_OFFICIAL_WEBSITE_URL);?>" target="_blank">
            <img src="<?php echo esc_url(get_template_directory_uri().'/knd-logo.svg');?>">
        </a>
    </div>
    <p>Хотите быть в курсе всех возможностей темы?<br>Найдите информацию на <a href="<?php echo KND_OFFICIAL_WEBSITE_URL;?>" target="_blank">её официальном сайте</a>.</p>

    <?php }

    foreach($items as $key => $item) {

        if(stristr($key, 'section_') !== false) { $section_id = str_replace('section_', '', $key); // Section ?>

            <h3 id="<?php echo 'knd-dashboard-'.$section_id; ?>" class="knd-metabox-subtitle"><?php echo $item['title']; ?></h3>

            <?php if( !empty($item['items'])) {
                knd_custom_links_dashboard_screen($item['items'], false);
            }

        } else { // Normal item ?>

            <div class="knd-metabox-line">
                <a href="<?php echo empty($item['link']) ? '#' : esc_url($item['link']);?>" target="_blank" class="action">
                    <span class="<?php echo empty($item['icon']) ? '' : 'dashicons '.$item['icon'];?>"></span>
                    <?php echo empty($item['text']) ? '' : $item['text'];?>
                </a>
            </div>

        <?php }

    }

    if( !!$is_initial_call ) {?>
</div>
    <?php }

}

/** Doc link in footer text **/
add_filter('admin_footer_text', 'rdc_admin_fotter_text');
function rdc_admin_fotter_text($text) {

	$doc = defined('TST_DOC_URL') && !empty(TST_DOC_URL) ? TST_DOC_URL : '';
	
	if(empty($doc))
		return $text;
	
	if(!empty($doc))
		$doc = str_replace('<a', '<a target="_blank" ', make_clickable($doc));
	
	$text = '<span id="footer-thankyou">Краткое руководство по работе с сайтом - ' . $doc . '</span>';	
	return $text;

}

/** Notification about wront thumbnail size **/
add_filter('admin_post_thumbnail_html', 'rdc_thumbnail_dimensions_check', 10, 2);
function rdc_thumbnail_dimensions_check($thumbnail_html, $post_id) {
	global $_wp_additional_image_sizes;
	
	if('org' == get_post_type($post_id))
		return $thumbnail_html;
	
    $meta = wp_get_attachment_metadata(get_post_thumbnail_id($post_id));
    $needed_sizes = (isset($_wp_additional_image_sizes['post-thumbnail'])) ? $_wp_additional_image_sizes['post-thumbnail'] : false;
	
    if(
        $meta && $needed_sizes &&
        ($meta['width'] < $needed_sizes['width'] || $meta['height'] < $needed_sizes['height'])
    ) {
	
	$size = "<b>".$needed_sizes['width'].'x'.$needed_sizes['height']."</b>";
	$txt = sprintf(__('ATTENTION! You thumbnail image is too small. It should be at least %s px', 'kds'), $size);
	
    echo "<p class='rdc-error'>{$txt}<p>";
    }

    return $thumbnail_html;
}


/** == Revome unused metabox == **/
//add_action( 'add_meta_boxes' , 'rdc_remove_wrong_metaboxes', 20 );
function rdc_remove_wrong_metaboxes() {
	
	//hide section default metabox
	remove_meta_box('tagsdiv-auctor', 'post', 'side');
	
}


/** ==  Auctor Meta UI - for WP 4.4 + only == **/
add_action( 'create_auctor', 'rdc_save_auctor_meta');
add_action( 'edited_auctor', 'rdc_save_auctor_meta');
function rdc_save_auctor_meta($term_id){
		
	
	if (
		// nonce was submitted and is verified
		isset( $_POST['taxonomy-term-image-save-form-nonce'] ) &&
		wp_verify_nonce( $_POST['taxonomy-term-image-save-form-nonce'], 'taxonomy-term-image-form-save' ) &&

		// taxonomy corect
		isset( $_POST['taxonomy'] ) && $_POST['taxonomy'] == 'auctor'
	)
	{
		$img_id = (!empty($_POST['auctor_photo'])) ? intval($_POST['auctor_photo']) : null;
		update_term_meta($term_id, 'auctor_photo', $img_id);
		
		$fb = (!empty($_POST['auctor_facebook'])) ? esc_url_raw($_POST['auctor_facebook']) : '';
		update_term_meta($term_id, 'auctor_facebook', $fb);
	}
}

add_action( "auctor_edit_form_fields", 'rdc_auctor_edit_term_fields');
function rdc_auctor_edit_term_fields($term){
		
	$fb = get_term_meta($term->term_id, 'auctor_facebook', true);		
?>
<tr class="form-field term-auctor_facebook-wrap">
	<th scope="row"><label for="auctor_facebook"><?php _e( 'Facebook Profile', 'tst' ); ?></label></th>
	<td><input name="auctor_facebook" id="auctor_facebook" type="text" value="<?php echo esc_attr($fb);?>">
	<p class="description"><?php _e('Enter URL of author\'s Facebook profile'); ?></p></td>
</tr>
<tr class="form-field term-auctor_photo-wrap">
	<th scope="row"><label for="auctor_photo"><?php _e( 'Avatar', 'tst' ); ?></label></th>
	<td><?php rdc_auctor_photo_field($term);?></td>
</tr>
<?php
}

add_action( "auctor_add_form_fields", 'rdc_auctor_add_term_fields');
function rdc_auctor_add_term_fields($term){
	
?>
<div class="form-field term-auctor_facebook-wrap">
	<label for="auctor_facebook"><?php _e( 'Facebook Profile', 'tst' ); ?></label>
	<input name="auctor_facebook" id="auctor_facebook" type="text" value="">
	<p class="description"><?php _e('Enter URL of author\'s Facebook profile'); ?></p>
</div>
<div class="form-field term-auctor_photo-wrap">
	<label for="auctor_photo"><?php _e( 'Avatar', 'tst' ); ?></label>
	<td><?php rdc_auctor_photo_field(null);?>
</div>
<?php
}


function rdc_auctor_photo_field($term){
	
	rdc_auctor_enqueue_scripts();
	
	$image_ID = ($term) ? get_term_meta($term->term_id, 'auctor_photo', true) : '';
	$image_src = ($image_ID) ? wp_get_attachment_image_src($image_ID, 'thumbnail') : array();
	$labels = rdc_get_image_field_labels();

	wp_nonce_field('taxonomy-term-image-form-save', 'taxonomy-term-image-save-form-nonce');
?>
<input type="button" class="taxonomy-term-image-attach button" value="<?php echo esc_attr( $labels['imageButton'] ); ?>" />
<input type="button" class="taxonomy-term-image-remove button" value="<?php echo esc_attr( $labels['removeButton'] ); ?>" />
<input type="hidden" id="taxonomy-term-image-id" name="auctor_photo" value="<?php echo esc_attr( $image_ID ); ?>" />
<p id="taxonomy-term-image-container">
	<?php if ( isset( $image_src[0] ) ) : ?>
		<img class="taxonomy-term-image-attach" src="<?php print esc_attr( $image_src[0] ); ?>" />
	<?php endif; ?>
</p>
<?php
	
}

function rdc_get_image_field_labels() {
	
	return array(
		'fieldTitle'       => __( 'Taxonomy Term Image' ),
		'fieldDescription' => __( 'Select which image should represent this term.' ),
		'imageButton'      => __( 'Select Image' ),
		'removeButton'     => __( 'Remove' ),
		'modalTitle'       => __( 'Select or upload an image for this term' ),
		'modalButton'      => __( 'Attach' ),
	);
}

function rdc_auctor_enqueue_scripts(){
	
	$screen = get_current_screen();
	$labels = rdc_get_image_field_labels();
		
	if ( $screen->id == 'edit-auctor' ){
		// WP core stuff we need
		wp_enqueue_media();
		wp_enqueue_style( 'thickbox' );
		$dependencies = array( 'jquery', 'thickbox', 'media-upload' );

		// register our custom script
		$url = get_template_directory_uri().'/assets/js';
		wp_register_script( 'taxonomy-term-image-js', $url.'/taxonomy-term-image.js', $dependencies, '1.5.1', true );

		// Localize the modal window text so that we can translate it
		wp_localize_script( 'taxonomy-term-image-js', 'TaxonomyTermImageText', $labels );

		// enqueue the registered and localized script
		wp_enqueue_script( 'taxonomy-term-image-js' );
	}
}

