<?php
/**
 * Envato Theme Setup Wizard Class
 * Takes new users through some basic steps to setup their ThemeForest theme.
 *
 * @author      dtbaker
 * @author      vburlak
 * @package     envato_wizard
 * @version     1.3.0
 * 1.2.0 - added custom_logo
 * 1.2.1 - ignore post revisioins
 * 1.2.2 - elementor widget data replace on import
 * 1.2.3 - auto export of content.
 * 1.2.4 - fix category menu links
 * 1.2.5 - post meta un json decode
 * 1.2.6 - post meta un json decode
 * 1.2.7 - elementor generate css on import
 * 1.2.8 - backwards compat with old meta format
 * 1.2.9 - theme setup auth
 * 1.3.0 - ob_start fix
 * Based off the WooThemes installer.
 */
if( !defined('ABSPATH')) {
	exit;
}

if( !class_exists('Envato_Theme_Setup_Wizard')) {
	/**
	 * Envato_Theme_Setup_Wizard class
	 */
	class Envato_Theme_Setup_Wizard {

		/**
		 * The class version number.
		 *
		 * @since 1.1.1
		 * @access private
		 * @var string
		 */
		protected $version = '2.1.8';

		/** @var string Current theme name, used as namespace in actions. */
		protected $theme_name = '';

		/** @var string Theme author username, used in check for oauth. */
		protected $envato_username = '';

		/** @var string Full url to server-script.php (available from https://gist.github.com/dtbaker ) */
//		protected $oauth_script = '';

		/** @var string Current Step */
		protected $step = '';

		/** @var array Steps for the setup wizard */
		protected $steps = array();

		/**
		 * Relative plugin path
		 *
		 * @since 1.1.2
		 * @var string
		 */
		protected $plugin_path = '';

		/**
		 * Relative plugin url for this plugin folder, used when enquing scripts
		 *
		 * @since 1.1.2
		 * @var string
		 */
		protected $plugin_url = '';

		/**
		 * The slug name to refer to this menu
		 *
		 * @since 1.1.1
		 * @var string
		 */
		protected $page_slug;

		/**
		 * TGMPA instance storage
		 *
		 * @var object
		 */
		protected $tgmpa_instance;

		/**
		 * TGMPA Menu slug
		 *
		 * @var string
		 */
		protected $tgmpa_menu_slug = 'knd-install-plugins';

		/**
		 * TGMPA Menu url
		 *
		 * @var string
		 */
		protected $tgmpa_url = 'themes.php?page=knd-install-plugins';

		/**
		 * The slug name for the parent menu
		 *
		 * @since 1.1.2
		 * @var string
		 */
		protected $parent_slug;

		/**
		 * Complete URL to Setup Wizard
		 *
		 * @since 1.1.2
		 * @var string
		 */
		protected $page_url;

		/**
		 * @since 1.1.8
		 */
		public $site_scenarios = array();

		/**
		 * Holds the current instance of the theme manager
		 *
		 * @since 1.1.3
		 * @var Envato_Theme_Setup_Wizard
		 */
		private static $instance = null;

		public $logs = array();
		public $errors = array();

		/**
		 * @since 1.1.3
		 * @return Envato_Theme_Setup_Wizard
		 */
		public static function get_instance() {

			if( !self::$instance) {
				self::$instance = new self;
			}

			return self::$instance;

		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 * @since 1.1.1
		 * @access private
		 */
		public function __construct() {

			$this->init_globals();
			$this->init_actions();

		}

		public function log($message) {

			$this->logs[] = $message;
		}

		public function error($message) {
			$this->logs[] = 'ERROR: '.$message;
		}

		/**
		 * Get the default scenario id. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 */
		public function get_default_site_scenario_id() {

			$tmp = array_keys($this->site_scenarios);

			return $this->site_scenarios ? reset($tmp) : false;

		}

		/** * Setup the class globals. */
		public function init_globals() {

			$current_theme = wp_get_theme();
			$this->theme_name = strtolower(preg_replace('#[^a-zA-Z]#', '', $current_theme->get('Name')));
			$this->page_slug = 'knd-setup-wizard';
			$this->parent_slug = apply_filters($this->theme_name.'_theme_setup_wizard_parent_slug', '');

			$this->site_scenarios = array(
				'problem-org' => array(
					'name' => __('Template – NGO "Color Line"', 'knd'),
					'tagline' => __('We help people with alcoholic addiction', 'knd'),
					'description' => __('Landing page for a typical non-profit. The template has legal information, projects, news, organization’s staff, partners.', 'knd'),
				),
				'fundraising-org' => array(
					'name' => __('Template – We Are With You', 'knd'),
					'tagline' => __('Charity foundation helping children from low-income families', 'knd'),
					'description' => __('Charity foundation website that is mostly focused on fundraising. Aside from basic information it includes integration with payment systems (Leyka plugin).', 'knd'),
				),
				'public-campaign' => array(
					'name' => __('Template – Protect Dubrovino! Campaign', 'knd'),
					'tagline' => __('City protection initiative', 'knd'),
					'description' => __('Urban activism campaign – it includes information on campaign (e.g. a campaign in defence of a local park), documents, call to sign a petition.', 'knd'),
				),
			);

			//If we have parent slug - set correct url
//			if($this->parent_slug) {
//				$this->page_url = 'admin.php?page=' . $this->page_slug;
//			} else {
//				$this->page_url = 'themes.php?page=' . $this->page_slug;
//			}
			$this->page_url = KND_SETUP_WIZARD_URL; //apply_filters( $this->theme_name . '_theme_setup_wizard_page_url', $this->page_url );

			// Set relative plugin path url:
			$this->plugin_path = trailingslashit($this->cleanFilePath(dirname(__FILE__)));
			$this->plugin_url = trailingslashit(
				get_template_directory_uri().
				str_replace($this->cleanFilePath(get_template_directory()), '', $this->plugin_path)
			);

		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 */
		public function init_actions() {

			if(apply_filters($this->theme_name.'_enable_setup_wizard', true) && current_user_can('manage_options')) {

				if(class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
					add_action('init', array($this, 'get_tgmpa_instanse'), 30);
					add_action('init', array($this, 'set_tgmpa_url'), 40);
				}

				add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
				add_action('admin_init', array($this, 'admin_redirects'), 30);
				add_action('admin_init', array($this, 'init_wizard_steps'), 30);
				add_action('admin_init', array($this, 'setup_wizard'), 30);
				add_filter('tgmpa_load', array($this, 'tgmpa_load'), 10, 1);
				add_action('wp_ajax_knd_wizard_setup_plugins', array($this, 'ajax_plugins'));
				add_action('wp_ajax_knd_wizard_setup_content', array($this, 'ajax_content'));
				add_action('wp_ajax_knd_wizard_update_settings', array($this, 'ajax_settings'));
				add_action('wp_ajax_knd_wizard_download_plot_step', array($this, 'ajax_download_plot_step'));

			}
		}

		/**
		 * Check if the theme default content already installed.
		 * This can happen if swapping from a previous theme or updated the current theme.
		 */
		public function is_default_content_installed() {

			return !!get_option('knd_default_content_installed');
		}

		public function enqueue_scripts() {
		}

		public function tgmpa_load($status) {

			return is_admin() || current_user_can('install_themes');
		}

		public function admin_redirects() {
			// ...
		}

		/**
		 * Get configured TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
		}

		/** * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = (property_exists($this->tgmpa_instance, 'menu')) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters($this->theme_name.'_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug);

			$tgmpa_parent_slug = (property_exists($this->tgmpa_instance, 'parent_slug') && $this->tgmpa_instance->parent_slug !== 'themes.php') ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters($this->theme_name.'_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug.'?page='.$this->tgmpa_menu_slug);

		}

		/** Wizard steps. */
		public function init_wizard_steps() {

			$this->steps = array(
				'introduction' => array(
					'name'    => esc_attr__('Introduction', 'knd'),
					'view'    => array($this, 'step_intro_view'),
					'handler' => array($this, 'step_scenario_handler'),
					'icon'    => 'controls-play',
				),
			);
			/*$this->steps['scenario'] = array(
				'name' => esc_attr__('Template', 'knd'),
				'view' => array($this, 'step_scenario_view'),
				//'handler' => array($this, 'step_scenario_handler'),
			);*/
			$this->steps['default_content'] = array(
				'name'    => esc_attr__('Content', 'knd'),
				'view'    => array($this, 'step_content_view'),
				'handler' => '',
				'icon'    => 'text',
			);
			$this->steps['design'] = array(
				'name'    => esc_attr__('Logo', 'knd'),
				'view'    => array($this, 'step_logo_design_view'),
				'handler' => array($this, 'step_logo_design_handler'),
				'icon'    => 'wordpress',
			);
			$this->steps['settings'] = array(
				'name'    => esc_attr__('Settings', 'knd'),
				'view'    => array($this, 'step_settings_view'),
				'handler' => array($this, 'step_settings_handler'),
				'icon'    => 'admin-settings',
			);
			if(class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
				$this->steps['default_plugins'] = array(
					'name'    => esc_attr__('Plugins', 'knd'),
					'view'    => array($this, 'step_default_plugins_view'),
					'handler' => '',
					'icon'    => 'admin-plugins',
				);
			}
			$this->steps['support'] = array(
				'name'    => esc_attr_x('Support', 'One word "support service" variant', 'knd'),
				'view'    => array($this, 'step_support_view'),
				'handler' => '', //array($this, 'step_support_handler'),
				'icon'    => 'editor-help',
			);
			$this->steps['next_steps'] = array(
				'name'    => esc_attr__('Ready!', 'knd'),
				'view'    => array($this, 'step_ready_view'),
				'handler' => '',
				'icon'    => 'flag',
			);

			$this->steps = apply_filters($this->theme_name.'_theme_setup_wizard_steps', $this->steps);

		}

		public function setup_wizard() {

			if(empty($_GET['page']) || $this->page_slug !== $_GET['page']) {
				return;
			}

			$this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

			wp_register_script('envato-setup', $this->plugin_url.'js/envato-setup.js', array( 'jquery' ), $this->version );

			wp_localize_script('envato-setup', 'envatoSetupParams', array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce('tgmpa-update'),
					'install' => wp_create_nonce('tgmpa-install'),
				),
				'tgm_bulk_url'     => admin_url($this->tgmpa_url),
				'ajaxurl'          => admin_url('admin-ajax.php'),
				'wpnonce'          => wp_create_nonce('knd-setup-nonce'),
				'verify_text'      => esc_html__('...verifying', 'knd'),
				'processing_text'  => esc_html__('Processing...', 'knd'),
				'upload_logo_text' => esc_html__('Upload Logo', 'knd'),
				'select_logo_text' => esc_html__('Select Logo', 'knd'),
				'ajax_error'       => esc_html__('Ajax error', 'knd'),
				'failed'           => esc_html__('Failed', 'knd'),
			));

			wp_enqueue_style('envato-setup', $this->plugin_url.'css/envato-setup.css', array(
				'wp-admin',
				'dashicons',
			), $this->version);

			wp_enqueue_style('wp-admin'); // Styles for admin notices

			wp_enqueue_media();
			wp_enqueue_script('media');

			set_current_screen( $_GET['page'] );

			ob_start();

			$screen = get_current_screen();

			$this->display_wizard_header();
			$this->display_wizard_steps();
			$show_content = true;

			echo '<div class="envato-setup-content">';
			try {

				if ( !empty($_REQUEST['save_step']) && isset($this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func($this->steps[ $this->step ]['handler']);
				}
				if ( $show_content) {
					$this->display_wizard_current_step_content();
				}

			} catch (Exception $ex) {
				knd_display_wizard_error($ex);
			}
			echo '</div>';
			$this->display_wizard_footer();

			exit;

		}

		public function get_step_link($step) {
			return add_query_arg('step', $step, admin_url('admin.php?page='.$this->page_slug));
		}

		public function get_next_step_link() {

			$keys = array_keys($this->steps);

			return add_query_arg('step', $keys[ array_search($this->step, array_keys($this->steps)) + 1 ], remove_query_arg('translation_updated'));

		}

		public function get_prev_step_link() {

			$keys = array_keys($this->steps);

			return add_query_arg('step', $keys[ array_search($this->step, array_keys($this->steps)) - 1 ], remove_query_arg('translation_updated'));

		}

		public function display_wizard_header() {
			// Remove deprecated.
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_head', 'wp_admin_bar_header' );
			?>

			<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title><?php esc_html_e( 'Kandinsky - setup wizard', 'knd' ); ?></title>
				<?php wp_print_scripts('envato-setup'); ?>
				<?php do_action('admin_print_styles'); ?>
				<?php do_action('admin_print_scripts'); ?>
				<?php do_action('admin_head'); ?>
			</head>
			<body class="envato-setup wp-core-ui knd-wizard">
				<div class="knd-wizard-wrapper">
					<h1 id="wc-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">
							<img class="wizard-header-logo" src="<?php echo get_template_directory_uri(); ?>/assets/images/knd-logo.svg" alt="<?php esc_attr_e('Kandinsky theme setup wizard', 'knd'); ?>">
						</a>
					</h1>
			<?php
		}

		/**
		 * Setup Wizard Footer
		 */
		public function display_wizard_footer() { ?>
				<?php if($this->step == 'next_steps') { ?>
					<a class="wc-return-to-dashboard" href="<?php echo esc_url(admin_url()); ?>">
						<?php esc_html_e('Return to the Dashboard', 'knd'); ?>
					</a>
				<?php } ?>
				</div><!-- .knd-wizard-wrapper -->
			</body>
			<?php
				do_action('admin_footer');
				do_action('admin_print_footer_scripts');
			?>
			</html>
			<?php
		}

		/**
		 * Output the steps
		 */
		public function display_wizard_steps() {

			$ouput_steps = $this->steps;
			?>

			<ol class="envato-setup-steps">
				<?php foreach($ouput_steps as $step_key => $step) : ?>
					<li class="<?php $show_link = false;
					if($step_key === $this->step) {
						echo 'active';
					} elseif (array_search($this->step, array_keys($this->steps)) > array_search($step_key, array_keys($this->steps))) {
						echo 'done';
						$show_link = true;
					}
					?>" data-step="<?php echo esc_attr( $step_key ); ?>"><?php
						if($show_link) {
							?>
							<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>">
								<span class="dashicons dashicons-<?php echo esc_html( $step['icon'] ); ?>"></span>
								<span class="step-name"><?php echo esc_html( $step['name'] ); ?></span>
								
							</a>
							<?php
						} else {
							?>
							<span class="dashicons dashicons-<?php echo esc_html( $step['icon'] ); ?>"></span>
							<span class="step-name"><?php echo esc_html( $step['name'] ); ?></span>
							<?php
						}
						?></li>
				<?php endforeach; ?>
			</ol>
			<?php
		}

		/** * Output the content for the current step */
		public function display_wizard_current_step_content() {
			isset($this->steps[ $this->step ]) ? call_user_func($this->steps[ $this->step ]['view']) : false;
		}

		private function _get_plugins() {

			$instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
			$plugins = array(
				'all' => array(), // Meaning: all plugins which still have open actions.
				'install' => array(),
				'update' => array(),
				'activate' => array(),
			);

			foreach($instance->plugins as $slug => $plugin) {
				if($instance->is_plugin_active($slug) && false === $instance->does_plugin_have_update($slug)) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if( !$instance->is_plugin_installed($slug)) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if(false !== $instance->does_plugin_have_update($slug)) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if($instance->can_plugin_activate($slug)) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}

			return $plugins;

		}

		public function ajax_plugins() {

			if( !check_ajax_referer('knd-setup-nonce', 'wpnonce') || empty($_POST['slug'])) {
				wp_send_json_error(array('error' => 1, 'message' => esc_html__('No slug found', 'knd')));
			}
			$json = array(); // Send back some json we use to hit up TGM

			$plugins = $this->_get_plugins();

			// what are we doing with this plugin?
			foreach($plugins['all'] as $slug => $plugin) {

				if( !empty($plugins['activate'][ $slug ]) && $_POST['slug'] == $slug) {

					$json = array(
						'url' => admin_url($this->tgmpa_url),
						'plugin' => array($slug),
						'tgmpa-page' => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce' => wp_create_nonce('bulk-plugins'),
						'action' => 'tgmpa-bulk-activate',
						'action2' => -1,
						'message' => esc_html__('Activating Plugin', 'knd'),
					);
					break;

				} else if( !empty($plugins['update'][ $slug ]) && $_POST['slug'] == $slug) {

					$json = array(
						'url' => admin_url($this->tgmpa_url),
						'plugin' => array($slug),
						'tgmpa-page' => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce' => wp_create_nonce('bulk-plugins'),
						'action' => 'tgmpa-bulk-update',
						'action2' => -1,
						'message' => esc_html__('Updating Plugin', 'knd'),
					);
					break;

				} else if( !empty($plugins['install'][ $slug ]) && $_POST['slug'] == $slug) {

					$json = array(
						'url' => admin_url($this->tgmpa_url),
						'plugin' => array($slug),
						'tgmpa-page' => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce' => wp_create_nonce('bulk-plugins'),
						'action' => 'tgmpa-bulk-install',
						'action2' => -1,
						'message' => esc_html__('Installing Plugin', 'knd'),
					);
					break;

				}

			}

			if($json) {
				$json['hash'] = md5(serialize($json)); // used for checking if duplicates happen, move to next plugin
				wp_send_json($json);
			} else {
				wp_send_json(array('done' => 1, 'message' => esc_html__('Success', 'knd')));
			}
			exit;

		}

		public function _content_install_site_title_desc() {
			update_option( 'blogname', esc_html__( 'Organization name', 'knd' ) );
			update_option( 'blogdescription', esc_html__( 'Subtitle', 'knd' ) );

			return true;
		}

		public function _content_install_settings() {

			knd_update_demo_theme_mods();

			return true;

		}

		public function _content_install_posts() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_posts();

			return true;

		}

		public function _content_install_pages() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_pages();

			return true;

		}

		public function _content_install_menu() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_menus();

			return true;

		}

		public function _content_install_content() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_posts();
			$pdb->build_pages();

			// re-setup theme options after pages created
			$pdb->build_theme_options();
			$pdb->build_general_options();

			$pdb->build_menus();

			return true;

		}

		public function _content_install_donations() {

			if(is_plugin_active('leyka/leyka.php')) {
				knd_activate_leyka();
			}
			else {
				update_option('knd_setup_install_leyka', true);
			}

			return true;
		}

		private function _content_default_get() {

			$content = array();

			$content['site_title_desc'] = array(
				'title'            => esc_html__( 'Website title and description', 'knd' ),
				'description'      => esc_html__( 'Insert default website title and description as seen in the demo.', 'knd' ),
				'pending'          => esc_html__( 'Pending', 'knd' ),
				'installing'       => esc_html__( 'Installing...', 'knd' ),
				'success'          => esc_html__( 'Success!', 'knd' ),
				'install_callback' => array( $this, '_content_install_site_title_desc' ),
				'checked'          => true,
			);
			$content['settings'] = array(
				'title'            => esc_html__( 'Settings', 'knd' ),
				'description'      => esc_html__( 'Insert default website settings as seen in the demo.', 'knd' ),
				'pending'          => esc_html__( 'Pending', 'knd' ),
				'installing'       => esc_html__( 'Installing...', 'knd' ),
				'success'          => esc_html__( 'Success!', 'knd' ),
				'install_callback' => array( $this, '_content_install_settings' ),
				'checked'          => true,
			);
			$content['content'] = array(
				'title'            => esc_html__( 'Content', 'knd' ),
				'description'      => esc_html__( 'Install default website posts, pages and menus.', 'knd' ),
				'pending'          => esc_html__( 'Pending', 'knd' ),
				'installing'       => esc_html__( 'Installing...', 'knd' ),
				'success'          => esc_html__( 'Success!', 'knd' ),
				'install_callback' => array( $this, '_content_install_content' ),
				'checked'          => true,
			);
			$content['donations'] = array(
				'title'            => esc_html__( 'Donations', 'knd' ),
				'description'      => esc_html__( 'Install donations components.', 'knd' ),
				'pending'          => esc_html__( 'Pending', 'knd' ),
				'installing'       => esc_html__( 'Installing...', 'knd' ),
				'success'          => esc_html__( 'Success!', 'knd' ),
				'install_callback' => array( $this, '_content_install_donations' ),
				'checked'          => true,
			);

			return apply_filters($this->theme_name.'_theme_setup_wizard_content', $content);

		}

		/**
		 * Steps View Template
		 */
		public function step_intro_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/intro.php';
		}

		public function step_scenario_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/scenario.php';
		}

		public function step_content_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/content.php';
		}

		public function step_logo_design_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/design.php';
		}

		public function step_settings_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/settings.php';
		}

		public function step_default_plugins_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/plugins.php';
			return true;
		}

		public function step_support_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/support.php';
		}

		public function step_ready_view() {
			require get_template_directory() . '/vendor/envato_setup/steps/ready.php';
		}

		public function ajax_content() {

			$content = $this->_content_default_get();
			if(
				!check_ajax_referer('knd-setup-nonce', 'wpnonce') ||
				empty($_POST['content']) &&
				isset($content[ $_POST['content'] ])
			) {
				wp_send_json_error(array('error' => 1, 'message' => esc_html__('No content Found', 'knd')));
			}

			$json = false;
			$this_content = $content[ $_POST['content'] ];

			if(empty($_POST['proceed'])) {
				$json = array(
					'url' => admin_url('admin-ajax.php'),
					'action' => 'knd_wizard_setup_content',
					'proceed' => 'true',
					'content' => $_POST['content'],
					'wpnonce' => wp_create_nonce('knd-setup-nonce'),
					'message' => $this_content['installing'],
					'logs' => $this->logs,
					'errors' => $this->errors,
				);
			} else {

				$this->log(' -!! STARTING SECTION for '.$_POST['content']);

				$this->delay_posts = get_transient('delayed_posts');
				if( !is_array($this->delay_posts)) {
					$this->delay_posts = array();
				}

				if( !empty($this_content['install_callback'])) {
					if($result = call_user_func($this_content['install_callback'])) {

						$this->log(' -- FINISH. Writing '.count($this->delay_posts, COUNT_RECURSIVE).' delayed posts to transient ');
						set_transient('delayed_posts', $this->delay_posts, 60 * 60 * 24);

						if(is_array($result) && isset($result['retry'])) {
							$json = array(
								'url' => admin_url('admin-ajax.php'),
								'action' => 'knd_wizard_setup_content',
								'proceed' => 'true',
								'retry' => time(),
								'retry_count' => $result['retry_count'],
								'content' => $_POST['content'],
								'wpnonce' => wp_create_nonce('knd-setup-nonce'),
								'message' => $this_content['installing'],
								'logs' => $this->logs,
								'errors' => $this->errors,
							);
						} else {
							$json = array(
								'done' => 1,
								'message' => $this_content['success'],
								'debug' => $result,
								'logs' => $this->logs,
								'errors' => $this->errors,
							);
						}
					}
				}

			}

			if($json) {
				$json['hash'] = md5(serialize($json)); // used for checking if duplicates happen, move to next plugin
				wp_send_json($json);
			} else {
				wp_send_json(array(
					'error' => 1,
					'message' => esc_html__('Error', 'knd'),
					'logs' => $this->logs,
					'errors' => $this->errors,
				));
			}

			exit;

		}

		// return the difference in length between two strings
		public function cmpr_strlen($a, $b) {
			return strlen($b) - strlen($a);
		}

		/**
		 * Save logo & design options
		 */
		public function ajax_download_plot_step() {

			$scenario_download_status_explain = array(
				0 => esc_html__('Downloading template archive...', 'knd'),
				1 => esc_html__('Extracting template content...', 'knd'),
				2 => esc_html__('Importing template content...', 'knd'),
				3 => esc_html__('Building template files...', 'knd'),
				4 => esc_html__('Building template options...', 'knd'),
				5 => esc_html__('Building template colors...', 'knd'),
			);

			check_admin_referer('knd-setup');

			if(empty($_POST['new_scenario_id'])) {

				wp_send_json(array(
					'status' => 'error',
					'no_scenario_id' => true,
					'error' => esc_html__('Please select the scenario', 'knd'),
				));
				
			}
			elseif($_POST['new_scenario_id']) {

				$plot_name = trim($_POST['new_scenario_id']);

				set_theme_mod('knd_site_scenario', $plot_name);

				if($plot_name) {

					try {
						
						$download_step = isset($_POST['knd_download_step']) ? (int) $_POST['knd_download_step'] : 0;
						
						$imp = new KND_Import_Remote_Content($plot_name);

						if($download_step == 0) {
							$imp->download_content();
						}
						elseif($download_step == 1) {
							$imp->extract_downloaded_file();
						}
						elseif($download_step == 2) {
							$imp->import_downloaded_content();
						}
						else {
							
							$imp->import_downloaded_content();
							
							$pdb = KND_Plot_Data_Builder::produce_builder($imp);
							
							if( !$pdb) { // Show some user-friendly error
								throw new Exception(sprintf(__('Plot data builder was not produced for plot: %s', 'knd'), $plot_name));
							}
							
							if($download_step == 3) {
								$pdb->build_theme_files();
							}
							elseif($download_step == 4) {
								$pdb->build_option_files();
							}
							elseif($download_step == 5) {
								$pdb->build_theme_colors();
							}
						}

						wp_send_json(array(
							'status' => 'ok',
							'knd_download_step' => $download_step,
							'status_explain' => isset($scenario_download_status_explain[$download_step + 1]) ? $scenario_download_status_explain[$download_step + 1] : '',
							'nonce' => wp_create_nonce('knd-setup'),
						));
						
					} catch(Exception $ex) {

						set_theme_mod('knd_site_scenario', false);
						
						wp_send_json(array(
							'status' => 'error',
							'error' => $ex->getMessage(),
							'error_code' => $ex->getCode(),
						));

						die();

					}
					
				}
			}
		}

		public function step_scenario_handler() {

			check_admin_referer('knd-setup');

			if($_POST['new_scenario_id']) {

				$plot_name = trim($_POST['new_scenario_id']);

				set_theme_mod('knd_site_scenario', $plot_name);

				if($plot_name) {

					try {

						wp_redirect(esc_url_raw($this->get_next_step_link()));
						exit();

					} catch(Exception $ex) {

						set_theme_mod('knd_site_scenario', false);

						knd_display_wizard_error($ex);

						die();

					}

				}
			}

		}

		// here

		public function step_logo_design_handler() {

			check_admin_referer( 'knd-setup-design' );

			$_POST['new_logo_id'] = (int)$_POST['new_logo_id'];
			if ( $_POST['new_logo_id'] ) {
				set_theme_mod( 'custom_logo', $_POST['new_logo_id'] );
				/** Deprecated, remove in version 3.0 */
				remove_theme_mod( 'knd_custom_logo' );
				remove_theme_mod( 'header_logo_image' );
			} elseif ( ! get_theme_mod( 'custom_logo' ) ) {

				/** Insert default site icon image */
				$url = knd_get_default_logo_url();
				$post_id = 0;
				$desc = get_bloginfo( 'name' );

				$img_id = media_sideload_image( $url, $post_id, $desc, 'id');

				if ( is_wp_error( $img_id ) ) {
					if ( 'http_request_failed' === $img_id->get_error_code() ) {
						// if http_request_failed.
						$url = str_replace( 'https:', 'http:', $url );
						$img_id = media_sideload_image( $url, $post_id, $desc, 'id');
						if ( ! is_wp_error( $img_id ) ) {
							set_theme_mod( 'custom_logo', $img_id );
						}
					}
				} else {
					set_theme_mod( 'custom_logo', $img_id );
				}

			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			exit;

		}

		public function step_settings_handler() {

			check_admin_referer('knd-setup-settings');

			$_POST['knd_org_name'] = empty($_POST['knd_org_name']) ? '' : esc_html($_POST['knd_org_name']);
			$_POST['knd_org_description'] = empty($_POST['knd_org_description']) ? '' : esc_html($_POST['knd_org_description']);

			if($_POST['knd_org_name']) {
				update_option('blogname', $_POST['knd_org_name']);
			}

			if($_POST['knd_org_description']) {
				update_option('blogdescription', $_POST['knd_org_description']);
			}

			$new_favicon_id = (int)$_POST['new_logo_id'];
			if ( $new_favicon_id ) {

				$attr = wp_get_attachment_image_src($new_favicon_id, 'full');
				if($attr && !empty($attr[1]) && !empty($attr[2])) {
					update_option('site_icon', $new_favicon_id);
				}

			} elseif ( ! has_site_icon() ) {

				/** Insert default site icon image */
				$url = knd_get_default_site_icon_url();
				$post_id = 0;
				$desc = get_bloginfo( 'name' );

				$img_id = media_sideload_image( $url, $post_id, $desc, 'id');

				if ( is_wp_error( $img_id ) ) {
					if ( 'http_request_failed' === $img_id->get_error_code() ) {
						// if http_request_failed.
						$url = str_replace( 'https:', 'http:', $url );
						$img_id = media_sideload_image( $url, $post_id, $desc, 'id');
						if ( ! is_wp_error( $img_id ) ) {
							update_option( 'site_icon', $img_id );
						}
					}
				} else {
					update_option( 'site_icon', $img_id );
				}

			}

			wp_redirect(esc_url_raw($this->get_next_step_link()));
			exit;

		}

		public static function cleanFilePath($path) {

			$path = str_replace('', '', str_replace(array('\\', '\\\\', '//'), '/', $path));
			if($path[ strlen($path) - 1 ] === '/') {
				$path = rtrim($path, '/');
			}

			return $path;
		}

		public function is_submenu_page() {

			return ! !$this->parent_slug;
		}
	}

}// if !class_exists

function knd_display_wizard_error(Exception $ex) {

	$message = $ex->getMessage();
	$error_number = $ex->getCode();

	echo '<div class="wizard-error">';

	if($message) {
		echo '<span class="error-begin">'.__('Error:', 'knd').'</span><span class="error-text">'.lcfirst($message).($error_number ? " (#$error_number)" : '').'.</span><div class="wizard-error-support-text">'.sprintf(__("Please, send a report about it to the <a href='mailto:%s' target='_blank'>theme technical support</a>.", 'knd'), KND_SUPPORT_EMAIL).'</div>';
	} else {
		echo sprintf( __("We're sorry, but some error occured during to this wizard step :( <div class='wizard-error-support-text'>Please, send a report about it to the <a href='mailto:%s' target='_blank'>theme technical support</a>.", 'knd' ), KND_SUPPORT_EMAIL );
	}

	echo '<p class="envato-setup-actions error step">
		<a href="'.admin_url().'" class="button button-large button-error">'.__('Back to the Dashboard', 'knd').'</a>
		<a href="mailto:'.KND_SUPPORT_EMAIL.'" class="button button-error button-large button-primary">'.__('Email to the theme support', 'knd').'</a>
	</p>

	</div>';

}

/**
 * Demo theme mods
 */
function knd_demo_theme_mods() {

	$font_base = array(
		'font-family' => 'Raleway',
		'variant'     => 'regular',
		'color'       => '#4d606a',
		'font-size'   => '18px',
		'font-backup' => '',
		'font-weight' => '500',
		'font-style'  => 'normal',
	);

	$font_headings = array(
		'font-family' => 'Raleway',
		'variant'     => '700',
		'color'       => '#183343',
		'font-backup' => '',
		'font-weight' => '700',
		'font-style'  => 'normal',
	);

	$about_content = '<p><strong>ЧАСЫ РАБОТЫ:</strong><br> Мы работаем: время работы</p>
	<p><strong>КОНТАКТЫ:</strong><br> Адрес<br> Телефон<br> Email</p>';
	
	$policy_content = sprintf( '<p>' . esc_html__( 'By making a donation, the user concludes a donation agreement by accepting a %spublic offer%s.', 'knd' ) . '</p>
<p><a href="' . esc_url( home_url( 'legal' ) ) . '">' . esc_html__( 'Personal data processing policy', 'knd' ) . '</a><br>
<a href="' . esc_url( home_url( 'legal' ) ) . '">' . esc_html__( 'Privacy policy', 'knd' ) . '</a>
</p>', '<a href="' . esc_url( home_url( 'legal' ) ) . '">', '</a>' );

	$theme_mods = array(
		'font_base'             => $font_base,
		'font_headings'         => $font_headings,
		'knd_page_bg_color'     => '#ffffff',
		'knd_main_color'        => '#d30a6a',
		'knd_main_color_active' => '#ab0957',
		'header_background'     => '#ffffff',
		'header_type'           => '2',
		'header_offcanvas'         => '0',
		'header_additional_button' => '0',
		'header_search'            => false,
		'header_height'            => '124px',
		'header_logo_title'        => get_bloginfo( 'name' ),
		'header_logo_text'         => get_bloginfo( 'description' ),
		'font_logo_default'        => true,
		'header_logo_color'        => '#183343',
		'header_logo_desc_color'   => '#4d606a',
		'header_menu_color'        => '#4d606a',
		'header_menu_color_hover'  => '#dd1400',
		'header_menu_size'         => '18px',
		'header_button'            => true,
		'header_button_text'       => esc_html__( 'Button text', 'knd' ),
		'header_button_link'       => home_url( 'howtohelp' ),
		'header_additional_button' => false,
		'header_additional_button_text' => '',
		'header_additional_button_link' => '',
		'header_offcanvas'              => false,
		'offcanvas_menu'                => false,
		'offcanvas_search'              => false,
		'offcanvas_button'              => false,
		'offcanvas_button_text'         => esc_html__( 'Button text', 'knd' ),
		'offcanvas_button_link'         => home_url( 'howtohelp' ),
		'offcanvas_social'              => false,

		'knd_social_vk'        => 'https://vk.com/teplitsast',
		'knd_social_ok'        => 'https://ok.ru/profile/0123456789',
		'knd_social_facebook'  => 'https://www.facebook.com/TeplitsaST',
		'knd_social_instagram' => 'https://www.instagram.com/your-organization-page',
		'knd_social_twitter'   => 'https://twitter.com/TeplitsaST',
		'knd_social_telegram'  => 'https://telegram.me/TeplitsaPRO',
		'knd_social_youtube'   => 'https://www.youtube.com/user/teplitsast',

		'knd_news_archive_title'     => esc_html__( 'News', 'knd' ),
		'knd_projects_archive_title' => esc_html__( 'Our projects', 'knd' ),
		'post_related_title'         => esc_html__( 'Related items', 'knd' ),
		'project_related_title'      => esc_html__( 'Related projects', 'knd' ),

		'footer_logo_title'       => get_bloginfo( 'name' ),
		'footer_logo_text'        => get_bloginfo( 'description' ),
		'footer_background'       => '#f7f8f8',
		'footer_color'            => '#4d606a',
		'footer_heading_color'    => '#183343',
		'footer_color_link'       => '#dd1400',
		'footer_color_link_hover' => '#c81303',
		'font_footer_logo_default' => true,
		'footer_logo_color'        => '#183343',
		'footer_logo_desc_color'   => '#4d606a',
		'footer_social'            => true,
		'footer_color_social'       => '#183343',
		'footer_color_social_hover' => '#4d606a',

		//'footer_about_title'        => esc_html__( 'About Us', 'knd' ),
		'footer_about'              => $about_content,

		'footer_policy_title'        => esc_html__( 'Security policy', 'knd' ),
		'footer_policy'              => $policy_content,

		'footer_menu_ourwork_title' => esc_html__( 'Our Work', 'knd' ),
		'footer_menu_ourwork'       => 'kandinsky-our-work-footer-menu',
		'footer_menu_news_title'    => esc_html__( 'News', 'knd' ),
		'footer_menu_news'          => 'kandinsky-news-footer-menu',

		'archive_bottom_block'  => 'posts-bottom-blocks',
		'post_bottom_block'     => 'posts-bottom-blocks',
		'projects_bottom_block' => 'projects-bottom-blocks',
		'project_bottom_block'  => 'projects-bottom-blocks',

	);

	return $theme_mods;
}

/**
 * Demo update theme mods
 */
function knd_update_demo_theme_mods() {
	$theme_mods = knd_demo_theme_mods();
	foreach ( $theme_mods as $name => $mod ) {
		set_theme_mod( $name, $mod );
	}
}

/**
 * Loads the main instance of Envato_Theme_Setup_Wizard to have
 * ability extend class functionality
 *
 * @since 1.1.1
 * @return object Envato_Theme_Setup_Wizard
 */

if( !function_exists('envato_theme_setup_wizard')) {
	function envato_theme_setup_wizard() {

		if( !is_admin() && !wp_doing_ajax() ) {
			return;
		}

		Envato_Theme_Setup_Wizard::get_instance();

	}
}
add_action('after_setup_theme', 'envato_theme_setup_wizard');

// To remove the notice from Disable Comments plugin:
add_action('wp_loaded', function() {
	if(
		class_exists('Disable_Comments') &&
		has_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'))
	) {
		remove_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'));
	}
}, 100);


function knd_remove_all_hints() {

	check_ajax_referer( 'knd-nonce', 'nonce' );

	if ( get_option('page_on_front') ) {

		$front_page = get_post( get_option('page_on_front') );
		$front_page_id = $front_page->ID;
		$front_content = $front_page->post_content;
		$front_blocks = parse_blocks( $front_content );

		$front_new_blocks = array();

		foreach( $front_blocks as $block ) {
			if ( $block['blockName'] !== 'knd/hint' ) {
				$front_new_blocks[] = $block;
			}
		}

		$front_new_content = serialize_blocks( $front_new_blocks );

		$data = array(
			'ID' => $front_page_id,
			'post_content' => $front_new_content,
		);

		wp_update_post( $data );

	}

	$data = array(
		'request'       => $_REQUEST,
		'page_on_front' => get_option('page_on_front'),
	);

	wp_send_json_success( $data );

}
add_action( 'wp_ajax_knd_remove_all_hints', 'knd_remove_all_hints' );
