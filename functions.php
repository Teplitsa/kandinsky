<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

define('KND_VERSION', '0.2');
define('TST_DOC_URL', 'https://github.com/Teplitsa/kandinsky/wiki');
define('KND_DOC_URL', 'https://github.com/Teplitsa/kandinsky/wiki/');
define('KND_OFFICIAL_WEBSITE_URL', 'https://github.com/Teplitsa/kandinsky/');
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

	

	// Menus
	$menus = array(
		'primary'   => __('Primary menu', 'knd'),
		//'social'    => 'Социальные кнопки',
		//'sitemap'   => 'Карта сайта'
	);

	register_nav_menus($menus);

	// Editor style
    add_editor_style(array('assets/css/editor.css'));
}
add_action( 'after_setup_theme', 'knd_setup', 9 ); // Theme wizard initialize at 10, this init should occur befure it

/* Function for init setting that should be runned at init hook */
function knd_content_init() {
    add_post_type_support('page', 'excerpt');
}
add_action( 'init', 'knd_content_init', 30 );







/**
 * Includes
 */

// WP libs to operate with files and media
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// enqueue CSS and JS and compose inline CSS to set vars from settings
require get_template_directory().'/core/class-cssjs.php';

require get_template_directory().'/core/media.php'; // customize media behavior and add images sizes
require get_template_directory().'/core/cards.php'; // layout of cards, list items etc.
require get_template_directory().'/core/extras.php'; // default WP behavior customization
require get_template_directory().'/core/shortcodes.php'; // shortcodes core
require get_template_directory().'/core/shortcodes-ui.php'; // shortcodes layout 
require get_template_directory().'/core/template-tags.php'; // independent pages parts layout 
require get_template_directory().'/core/widgets.php'; // setup widgets
require get_template_directory().'/core/customizer.php'; // WP theme customizer setup

// import data utils
require get_template_directory().'/core/class-mediamnt.php'; // tools for work with files
require get_template_directory().'/core/class-import.php'; // import files into site media lib
require get_template_directory().'/core/import.php'; // import helpers

// include modules
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

if(is_admin() || current_user_can('manage_options')) {
    require get_template_directory() . '/core/admin.php';
    require get_template_directory().'/vendor/class-tgm-plugin-activation.php';
}

if((is_admin() && !empty($_GET['page']) && $_GET['page'] == 'knd-setup-wizard' ) || wp_doing_ajax()) {
    require get_template_directory().'/vendor/envato_setup/envato_setup.php'; // Run the wizard after all modules included
}

// Service lines (to localize):
__('Kandinsky', 'knd');
__('Teplitsa', 'knd');
__('The beautiful design and useful features for nonprofit website', 'knd');