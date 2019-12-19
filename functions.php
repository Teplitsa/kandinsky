<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

if ( ! defined( 'WPINC' ) )
	die();

define('KND_VERSION', '1.3.3');
define('KND_DOC_URL', 'https://github.com/Teplitsa/kandinsky/wiki/');
define('KND_OFFICIAL_WEBSITE_URL', 'https://knd.te-st.ru/');
define('KND_SOURCES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/');
define('KND_SOURCES_ISSUES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/issues/');
define('TST_OFFICIAL_WEBSITE_URL', 'https://te-st.ru/');
define('TST_PASEKA_OFFICIAL_WEBSITE_URL', 'https://paseka.te-st.ru/');
define('KND_SUPPORT_EMAIL', 'support@te-st.ru');
define('KND_SUPPORT_TELEGRAM', 'https://t.me/joinchat/AAAAAENN3prSrvAs7KwWrg');
define('KND_SETUP_WIZARD_URL', admin_url('themes.php?page=knd-setup-wizard'));
define('KND_DISTR_ARCHIVE_URL', 'https://github.com/Teplitsa/kandinsky/archive/master.zip');
#define('KND_DISTR_ARCHIVE_URL', 'https://github.com/Teplitsa/kandinsky/archive/dev.zip');
define('KND_MIN_PHP_VERSION', '5.6.0');
define('KND_PHP_VERSION_ERROR_MESSAGE', '<strong>Внимание:</strong> версия PHP ниже <strong>5.6.0</strong>. Кандинский нуждается в PHP хотя бы <strong>версии 5.6.0</strong>, чтобы работать корректно.<br /><br />Пожалуйста, направьте вашему хостинг-провайдеру запрос на повышение версии PHP для этого сайта.');

if( !defined('PHP_VERSION') || version_compare(PHP_VERSION, KND_MIN_PHP_VERSION, '<') ) {

  function sample_admin_notice__success() {
    ?>
    <div class="notice notice-error">
        <p><?php echo KND_PHP_VERSION_ERROR_MESSAGE;?></p>
    </div>
    <?php
  }
  add_action( 'admin_notices', 'sample_admin_notice__success' );
}

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

    // Support automatic feed links
	add_theme_support( 'automatic-feed-links' );

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

get_template_part('/core/class-cssjs');

get_template_part('/core/media'); // customize media behavior and add images sizes

get_template_part('/core/cards'); // layout of cards, list items etc.

get_template_part('/core/extras'); // default WP behavior customization

get_template_part('/core/shortcodes'); // shortcodes core
get_template_part('/core/shortcodes-ui'); // shortcodes layout

get_template_part('/core/template-tags'); // independent pages parts layout

get_template_part('/core/widgets'); // setup widgets
get_template_part('/core/customizer'); // WP theme customizer setup

// import data utils
get_template_part('/core/class-mediamnt'); // tools for work with files

get_template_part('/core/class-import'); // import files into site media lib
get_template_part('/core/import'); // import files into site media lib

// Include modules
foreach (glob(get_template_directory() . '/modules/*') as $module_file) {
    if(is_dir($module_file)) {
        $php_filename = basename($module_file) . '.php';
        $php_file = $module_file . '/' . $php_filename;
    } else {
        $php_file = $module_file;
    }

    if(is_file($php_file) && preg_match('/.*\.php$/', $php_file)) {
        require( $php_file );
    }
}

if(is_admin() || current_user_can('manage_options')) {
    get_template_part('/core/admin-update-theme');
    get_template_part('/core/admin');
    get_template_part('/vendor/class-tgm-plugin-activation');
}

if((is_admin() && !empty($_GET['page']) && $_GET['page'] == 'knd-setup-wizard' ) || wp_doing_ajax()) {
    get_template_part('/vendor/envato_setup/envato_setup'); // Run the wizard after all modules included
}

// Service lines (to localize):
__('Kandinsky', 'knd');
__('Teplitsa', 'knd');
__('The beautiful design and useful features for nonprofit website', 'knd');