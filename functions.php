<?php
/**
 * Kandinsky functions and definitions
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

if( WP_DEBUG && WP_DEBUG_DISPLAY && (defined('DOING_AJAX') && DOING_AJAX) ){
	@ ini_set( 'display_errors', 1 );
}

define('KND_VERSION', '2.0.0');
define('KND_DOC_URL', 'https://github.com/Teplitsa/kandinsky/wiki/');
define('KND_OFFICIAL_WEBSITE_URL', 'https://knd.te-st.ru/');
define('KND_SOURCES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/');
define('KND_SOURCES_ISSUES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/issues/');
define('TST_OFFICIAL_WEBSITE_URL', 'https://te-st.ru/');
define('TST_PASEKA_OFFICIAL_WEBSITE_URL', 'https://paseka.te-st.ru/');
define('KND_SUPPORT_EMAIL', 'support@te-st.ru');
define('KND_SUPPORT_URL', 'https://pd.te-st.ru/forms/contacts/');
define('KND_SUPPORT_TELEGRAM', 'https://t.me/joinchat/AAAAAENN3prSrvAs7KwWrg');
define('KND_SETUP_WIZARD_URL', admin_url('themes.php?page=knd-setup-wizard'));
define('KND_DISTR_ARCHIVE_URL', 'https://knd.te-st.ru/kandinsky.zip');
define('KND_UPDATE_INFO_URL', 'https://knd.te-st.ru/kandinsky.json');

define('KND_MIN_PHP_VERSION', '5.6.0');
define('KND_PHP_VERSION_ERROR_MESSAGE', '<strong>Внимание:</strong> версия PHP ниже <strong>5.6.0</strong>. Кандинский нуждается в PHP хотя бы <strong>версии 5.6.0</strong>, чтобы работать корректно.<br /><br />Пожалуйста, направьте вашему хостинг-провайдеру запрос на повышение версии PHP для этого сайта.');

if ( ! defined('PHP_VERSION') || version_compare(PHP_VERSION, KND_MIN_PHP_VERSION, '<') ) {
	/**
	 * Admin notice
	 */
	function knd_admin_notice__success() {
		?>
		<div class="notice notice-error">
			<p><?php echo wp_kses_post( KND_PHP_VERSION_ERROR_MESSAGE ); ?></p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'knd_admin_notice__success' );
}

if ( ! isset( $content_width ) ) {
	$content_width = 800; /* pixels */
}

/**
 * Load text domain
 */
function knd_load_theme_textdomain() {
	load_theme_textdomain( 'knd', get_template_directory() . '/lang' );
	do_action( 'knd_load_textdomain' );
}

/**
 * Theme Setup
 */
function knd_setup() {

	// Inits.
	knd_load_theme_textdomain();

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Register Nav Menus locations.
	 */
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary menu', 'knd' ),
		)
	);

	/**
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	//add_theme_support( 'wp-block-styles' );

	// Editor style.
	add_editor_style( array( 'assets/css/editor.css' ) );

}
add_action( 'after_setup_theme', 'knd_setup', 9 ); // Theme wizard initialize at 10, this init should occur befure it.

/* Fix translate customizer */
if ( is_customize_preview() ) {
	knd_load_theme_textdomain();
}

/**
 * Function for init setting that should be runned at init hook
 */
function knd_content_init() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'knd_content_init', 30 );

/**
 * Includes
 */

// WP libs to operate with files and media.
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

get_template_part( '/core/extras' ); // default WP behavior customization.

// enqueue CSS and JS and compose inline CSS to set vars from settings.

/**
 * Theme Init.
 */
require get_template_directory() . '/inc/init.php';

/**
* Assets.
*/
require get_template_directory() . '/core/assets.php';

/**
 * Template functions
 */
require_once get_theme_file_path( '/core/template-functions.php' );

/**
 * Nav Menu
 */
require_once get_theme_file_path( '/core/nav-menu.php' );

/**
 * Customizer
 */
require_once get_theme_file_path( '/core/customizer/customizer.php' );

/**
 * Typography and Colors
 */
require_once get_theme_file_path( '/core/typography.php' );

/**
 * Post type person
 */
require_once get_template_directory() . '/core/person.php';

/**
 * Plugins
 */
require_once get_theme_file_path( '/core/plugins.php' );

get_template_part( '/core/media' ); // customize media behavior and add images sizes.

get_template_part(  '/core/cards' ); // layout of cards, list items etc.

get_template_part( '/core/shortcodes' ); // shortcodes core.

get_template_part( '/core/template-tags' ); // independent pages parts layout.

get_template_part( '/core/widgets' ); // setup widgets.

// import data utils.
get_template_part( '/core/class-mediamnt' ); // tools for work with files.

get_template_part( '/core/class-import' ); // import files into site media lib.
get_template_part( '/core/import' ); // import files into site media lib.

// Include modules.
foreach ( glob( get_template_directory() . '/modules/*' ) as $module_file ) {
	if ( is_dir( $module_file ) ) {
		$php_filename = basename( $module_file ) . '.php';
		$php_file     = $module_file . '/' . $php_filename;
	} else {
		$php_file = $module_file;
	}

	if ( is_file( $php_file ) && preg_match( '/.*\.php$/', $php_file ) ) {
		require $php_file;
	}
}

if ( is_admin() || current_user_can( 'manage_options' ) ) {
	/**
	* Theme Update checker
	*/
	require get_template_directory() . '/core/theme-update/theme-update-checker.php';

	get_template_part( '/core/admin-update-theme' );
	get_template_part( '/core/admin' );
	get_template_part( '/vendor/class-tgm-plugin-activation' );
}

if ( ( is_admin() && ! empty( $_GET['page'] ) && $_GET['page'] == 'knd-setup-wizard' ) || wp_doing_ajax() ) {
	get_template_part( '/vendor/envato_setup/envato_setup' ); // Run the wizard after all modules included.
}

// Service lines (to localize):.
esc_html__('Kandinsky', 'knd');
esc_html__('Teplitsa', 'knd');
esc_html__('The beautiful design and useful features for nonprofit website', 'knd');

function widgets_scripts( $hook ) {
	if ( 'widgets.php' != $hook ) {
		return;
	}
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'widgets_scripts' );

/**
 * Editor
 */
require_once get_template_directory() . '/core/editor/editor.php';
