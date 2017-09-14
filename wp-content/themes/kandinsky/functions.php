<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('KND_VERSION', '0.2');
define('TST_DOC_URL', 'https://kms.te-st.ru/site-help/');
define('KND_OFFICIAL_WEBSITE_URL', 'https://te-st.ru/'); /** @todo Change this URL */
define('TST_OFFICIAL_WEBSITE_URL', 'https://te-st.ru/');
define('KND_SUPPORT_EMAIL', 'support@te-st.ru');
define('KND_SETUP_WIZARD_URL', admin_url('themes.php?page=knd-setup-wizard'));

if( !isset($content_width) ) {
	$content_width = 800; /* pixels */
}

function knd_setup() {

	// Inits
	load_theme_textdomain('knd', get_template_directory().'/lang');
	//add_theme_support( 'automatic-feed-links' );	
	add_theme_support('title-tag');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption',));

	// Thumbnails:
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(640, 395, true ); // regular thumbnails	
	add_image_size('square', 450, 450, true ); // square thumbnail 
	add_image_size('medium-thumbnail', 790, 488, true ); // poster in widget	
	add_image_size('landscape-mini', 300, 185, true ); // fixed size for embedding
	//add_image_size('cover', 400, 567, true ); // long thumbnail for pages

	// Menus
	$menus = array(
		'primary'   => __('Primary menu', 'knd'),
		//'social'    => 'Социальные кнопки',
		//'sitemap'   => 'Карта сайта'
	);

	register_nav_menus($menus);

	// Editor style
	//add_editor_style(array('css/editor-style.css'));
}
add_action( 'after_setup_theme', 'knd_setup', 9 ); // Theme wizard initialize at 10, this init should occur befure it

/* Function for init setting that should be runned at init hook */
function knd_content_init() {
    add_post_type_support('page', 'excerpt');
}
add_action( 'init', 'knd_content_init', 30 );


/** Custom image size for medialib **/
add_filter('image_size_names_choose', 'rdc_medialib_custom_image_sizes');
function rdc_medialib_custom_image_sizes($sizes) {
	
	$addsizes = apply_filters('rdc_medialib_custom_image_sizes', array(
		'landscape-mini' 	=> __('Landscape thumbnail', 'knd'),
		'post-thumbnail' 	=> __('Post thumbnail', 'knd'),
		'medium-thumbnail' 	=> __('Fixed for embed', 'knd')
	));
		
	return array_merge($sizes, $addsizes);
}



/**
 * Includes
 */

require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );


require get_template_directory().'/core/class-cssjs.php';

require get_template_directory().'/core/aq_resizer.php';
require get_template_directory().'/core/author.php';
require get_template_directory().'/core/cards.php';
require get_template_directory().'/core/extras.php';
require get_template_directory().'/core/forms.php';
require get_template_directory().'/core/request.php';
require get_template_directory().'/core/shortcodes.php';
require get_template_directory().'/core/shortcodes-ui.php';
require get_template_directory().'/core/template-tags.php';
require get_template_directory().'/core/widgets.php';
require get_template_directory().'/core/settings.php';
require get_template_directory().'/core/customizer.php';
require get_template_directory().'/vendor/class-tgm-plugin-activation.php';

// import data utils
require get_template_directory().'/core/class-mediamnt.php';
require get_template_directory().'/core/class-import.php';
require get_template_directory().'/core/import.php';

foreach (glob(get_template_directory() . "/modules/*") as $module_file) {
    
    if(is_dir($module_file)) {
        $php_filename = basename($module_file) . '.php';
        $php_file = $module_file . "/" . $php_filename;
    }
    else {
        $php_file = $module_file;
    }
    
    if(is_file($php_file) && preg_match('/.*\.php$/', $php_file)) {
        require( $php_file );
    }
}

if(is_admin() || wp_doing_ajax()) {

	require get_template_directory() . '/core/admin.php';

	if(wp_doing_ajax() || ( !empty($_GET['page']) && $_GET['page'] == 'knd-setup-wizard' )) {
        require get_template_directory().'/vendor/envato_setup/envato_setup.php'; // Run the wizard after all modules included
    }

}