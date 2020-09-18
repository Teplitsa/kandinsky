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
		protected $version = '1.3.1';

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
					'name' => esc_attr__('Introduction', 'knd'),
					'view' => array($this, 'step_intro_view'),
					'handler' => '',
				),
			);
			$this->steps['scenario'] = array(
				'name' => esc_attr__('Template', 'knd'),
				'view' => array($this, 'step_scenario_view'),
				'handler' => array($this, 'step_scenario_handler'),
			);
			$this->steps['default_content'] = array(
				'name' => esc_attr__('Content', 'knd'),
				'view' => array($this, 'step_content_view'),
				'handler' => '',
			);
			$this->steps['design'] = array(
				'name' => esc_attr__('Logo', 'knd'),
				'view' => array($this, 'step_logo_design_view'),
				'handler' => array($this, 'step_logo_design_handler'),
			);
			$this->steps['settings'] = array(
				'name' => esc_attr__('Settings', 'knd'),
				'view' => array($this, 'step_settings_view'),
				'handler' => array($this, 'step_settings_handler'),
			);
			if(class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
				$this->steps['default_plugins'] = array(
					'name' => esc_attr__('Plugins', 'knd'),
					'view' => array($this, 'step_default_plugins_view'),
					'handler' => '',
				);
			}
			$this->steps['support'] = array(
				'name' => esc_attr_x('Support', 'One word "support service" variant', 'knd'),
				'view' => array($this, 'step_support_view'),
				'handler' => '', //array($this, 'step_support_handler'),
			);
			$this->steps['next_steps'] = array(
				'name' => esc_attr__('Ready!', 'knd'),
				'view' => array($this, 'step_ready_view'),
				'handler' => '',
			);

			$this->steps = apply_filters($this->theme_name.'_theme_setup_wizard_steps', $this->steps);

		}

		public function setup_wizard() {

			if(empty($_GET['page']) || $this->page_slug !== $_GET['page']) {
				return;
			}

			$this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

			wp_register_script('jquery-blockui', $this->plugin_url.'js/jquery.blockUI.js', array('jquery'), '2.70', true);
			wp_register_script('envato-setup', $this->plugin_url.'js/envato-setup.js', array(
				'jquery',
				'jquery-blockui',
			), $this->version);
			wp_localize_script('envato-setup', 'envatoSetupParams', array(
				'tgm_plugin_nonce' => array(
					'update' => wp_create_nonce('tgmpa-update'),
					'install' => wp_create_nonce('tgmpa-install'),
				),
				'tgm_bulk_url' => admin_url($this->tgmpa_url),
				'ajaxurl' => admin_url('admin-ajax.php'),
				'wpnonce' => wp_create_nonce('knd-setup-nonce'),
				'verify_text' => esc_html__('...verifying', 'knd'),
				'processing_text' => esc_html__('Processing...', 'knd'),
				'upload_logo_text' => esc_html__('Upload Logo', 'knd'),
				'select_logo_text' => esc_html__('Select Logo', 'knd'),
			));

			wp_enqueue_style('envato-setup', $this->plugin_url.'css/envato-setup.css', array(
				'wp-admin',
				'dashicons',
				'install',
			), $this->version);

			wp_enqueue_style('wp-admin'); // Styles for admin notices

			wp_enqueue_media();
			wp_enqueue_script('media');

			ob_start();
			$this->display_wizard_header();
			$this->display_wizard_steps();
			$show_content = true;

			echo '<div class="envato-setup-content">';
			try {

				if( !defined('PHP_VERSION') || version_compare(PHP_VERSION, KND_MIN_PHP_VERSION, '<') ) {
					echo '<div class="wizard-error">';
					echo '<span class="error-begin">'.__('Error:', 'knd').'</span><span class="error-text">'.KND_PHP_VERSION_ERROR_MESSAGE.'.</span><div class="wizard-error-support-text"></div>';
					echo '<p class="envato-setup-actions error step">
						<a href="'.admin_url().'" class="button button-large button-error">'.__('Back to the Dashboard', 'knd').'</a>
						</p>';
					echo '</div>';
				}
				else {
					if( !empty($_REQUEST['save_step']) && isset($this->steps[ $this->step ]['handler'])) {
						$show_content = call_user_func($this->steps[ $this->step ]['handler']);
					}
					if($show_content) {
						$this->display_wizard_current_step_content();
					}
				}

			} catch(Exception $ex) {
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

		public function display_wizard_header() {?>

			<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width"/>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<?php // To avoid theme check issues...
				echo '<t';
				echo 'itle>'.__('Kandinsky - setup wizard', 'knd').'</ti'.'tle>'; ?>
				<?php wp_print_scripts('envato-setup'); ?>
				<?php do_action('admin_print_styles'); ?>
				<?php do_action('admin_print_scripts'); ?>
				<?php do_action('admin_head'); ?>
			</head>
			<body class="envato-setup wp-core-ui">
			<h1 id="wc-logo">
				<a href="<?php echo KND_OFFICIAL_WEBSITE_URL; ?>" target="_blank">
					<?php echo '<img class="wizard-header-logo" src="'.get_template_directory_uri().'/knd-logo.svg" alt="'.__('Kandinsky theme setup wizard', 'knd').'" style="width:100%; height:auto;">'; ?>
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
			</body>
			<?php @do_action('admin_footer'); // This was spitting out some errors in some admin templates. quick @ fix until I have time to find out what's causing errors
			do_action('admin_print_footer_scripts'); ?>
			</html>
			<?php
		}

		/**
		 * Output the steps
		 */
		public function display_wizard_steps() {

			$ouput_steps = $this->steps;
			array_shift($ouput_steps); ?>

			<ol class="envato-setup-steps">
				<?php foreach($ouput_steps as $step_key => $step) : ?>
					<li class="<?php $show_link = false;
					if($step_key === $this->step) {
						echo 'active';
					} elseif(array_search($this->step, array_keys($this->steps)) > array_search($step_key, array_keys($this->steps))) {
						echo 'done';
						$show_link = true;
					}
					?>"><?php
						if($show_link) {
							?>
							<a href="<?php echo esc_url($this->get_step_link($step_key)); ?>"><?php echo esc_html($step['name']); ?></a>
							<?php
						} else {
							echo esc_html($step['name']);
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

		public function step_intro_view() {
			
			// Remove the old scenario import data:
			$is_show_hello = true;
			$current_site_scenario = knd_get_theme_mod('knd_site_scenario');
			if($current_site_scenario) {
				$is_show_hello = false;
				
				$destination = wp_upload_dir();
				$unzipped_dir = "{$destination['path']}/kandinsky-text-"
					.knd_get_wizard_plot_names($current_site_scenario).'-master';

				$knd_fs = Knd_Filesystem::get_instance();
				if($knd_fs) {
					$is_show_hello = true;
				}
				if($knd_fs && $knd_fs->is_dir($unzipped_dir)) {
					$knd_fs->rmdir($unzipped_dir, true);
				}
				
			}
			
			
			if($is_show_hello):?>
			
			<h1><?php printf(esc_html__('Welcome to the %s setup wizard', 'knd'), wp_get_theme()); ?></h1>
			<p><?php printf(esc_html__("Hello! Let's set up your organization website together. With few simple steps we will configure minimal necessary settings, like installing of required plugins, setting up default website content and the logo. It should only take 5 minutes. You can always change any of these settings later on, in the Plugins admin folder.", 'knd')); ?></p>

			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next"><?php esc_html_e("Let's go!", 'knd'); ?></a>
				<a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url('')); ?>" class="button button-large"><?php esc_html_e('Not right now', 'knd'); ?></a>
			</p>
			
			<?php endif;
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

		public function step_default_plugins_view() {

			tgmpa_load_bulk_installer();
			if( !class_exists('TGM_Plugin_Activation') || !isset($GLOBALS['tgmpa'])) {
				die(__('Failed to find TGM plugin', 'knd'));
			}

			// Prevent start Leyka wizard if is activated via Theme wizard.
			if ( ! get_option( 'leyka_last_ver' ) ) {
				update_option( 'leyka_last_ver', '3.10');
			}
			?>

			<h1><?php esc_html_e('Default Plugins', 'knd'); ?></h1>
			<form method="post">

				<?php $plugins = $this->_get_plugins();
				if($plugins['all']) {

					$plugins_required = $plugins_recommended = array();

					foreach($plugins['all'] as $slug => $plugin) {
						if(empty($plugin['required'])) {
							$plugins_recommended[ $slug ] = $plugin;
						} else {
							$plugins_required[ $slug ] = $plugin;
						}
					}

					if($plugins_required) { ?>

						<p><?php esc_html_e('Your website needs a few essential plugins. The following plugins will be installed or updated:', 'knd'); ?></p>
						<p><?php esc_html_e('You can add and remove plugins later on, in the Plugins admin folder.', 'knd'); ?></p>

						<ul class="envato-wizard-plugins">
							<?php foreach($plugins_required as $slug => $plugin) { ?>
								<li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?>
									<span>
							<?php $plugin_status = '';

							if(isset($plugins['install'][ $slug ])) {
								$plugin_status = __('Installation required', 'knd');
							} else if(isset($plugins['update'][ $slug ])) {
								$plugin_status = isset($plugins['activate'][ $slug ]) ?
									__('Update and activation required', 'knd') : __('Update required', 'knd');
							} else if(isset($plugins['activate'][ $slug ])) {
								$plugin_status = __('Activation required', 'knd');
							}

							echo $plugin_status; ?>
									</span>
									<div class="spinner"></div>

									<div class="knd-plugin-description"><?php echo $plugin['description'] ?></div>
								</li>
							<?php } ?>
						</ul>
					<?php }

					if($plugins_recommended) {

						if($plugins_required) { ?>
							<p><?php esc_html_e('We also recommend to add several more:', 'knd'); ?></p>
						<?php } else { ?>
							<p><?php esc_html_e('We recommend to add or update the following plugins:', 'knd'); ?></p>
						<?php } ?>

						<ul class="envato-wizard-plugins-recommended">
							<?php foreach($plugins_recommended as $slug => $plugin) { ?>
								<li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?><span>

						<?php $plugin_status = '';

						if(isset($plugins['install'][ $slug ])) {
							$plugin_status = __('Install', 'knd');
						} else if(isset($plugins['update'][ $slug ])) {
							$plugin_status = isset($plugins['activate'][ $slug ]) ?
								__('Update and activate', 'knd') : __('Update', 'knd');
						} else if(isset($plugins['activate'][ $slug ])) {
							$plugin_status = __('Activate', 'knd');
						} ?>

										<label>
							<input type="checkbox" class="plugin-accepted" name="knd-recommended-plugin-<?php echo $slug; ?>">
											<?php echo $plugin_status; ?>
						</label>

					</span>
									<div class="spinner"></div>

									<div class="knd-plugin-description"><?php echo $plugin['description'] ?></div>

								</li>
							<?php } ?>
						</ul>

					<?php }

				} else {
					echo '<p><strong>'.esc_html_e("Good news! All plugins are already installed and up to date. Let's proceed further.", 'knd').'</strong></p>';
				} ?>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next" data-callback="installPlugins">
						<?php esc_html_e('Continue', 'knd'); ?>
					</a>
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
						<?php esc_html_e('Skip this step', 'knd'); ?>
					</a>
					<?php wp_nonce_field('envato-setup'); ?>
				</p>
			</form>
			<?php return true;
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

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_title_and_description();

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

		public function _content_install_settings() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);

			$pdb->build_theme_options();
			$pdb->build_general_options();

			return true;

		}

		public function _content_install_menu() {

			$imp = new KND_Import_Remote_Content(knd_get_theme_mod('knd_site_scenario'));
			$imp->import_downloaded_content();

			$pdb = KND_Plot_Data_Builder::produce_builder($imp);
			$pdb->build_menus();
			$pdb->build_sidebars();

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

			$pdb->build_menus();
			$pdb->build_sidebars();

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
				'checked'          => true, //$this->is_default_content_installed(),
			);
//             $content['pages'] = array(
//                 'title' => esc_html__('Pages', 'knd'),
//                 'description' => esc_html__('Insert default website pages as seen in the demo.', 'knd'),
//                 'pending' => esc_html__('Pending', 'knd'),
//                 'installing' => esc_html__('Installing...', 'knd'),
//                 'success' => esc_html__('Success!', 'knd'),
//                 'install_callback' => array($this, '_content_install_pages'),
//                 'checked' => $this->is_default_content_installed(),
//             );
//             $content['posts'] = array(
//                 'title' => esc_html__('Posts', 'knd'),
//                 'description' => esc_html__('Insert default website posts as seen in the demo.', 'knd'),
//                 'pending' => esc_html__('Pending', 'knd'),
//                 'installing' => esc_html__('Installing...', 'knd'),
//                 'success' => esc_html__('Success!', 'knd'),
//                 'install_callback' => array($this, '_content_install_posts'),
//                 'checked' => $this->is_default_content_installed(),
//             );
				$content['settings'] = array(
					'title'            => esc_html__( 'Settings', 'knd' ),
					'description'      => esc_html__( 'Insert default website settings as seen in the demo.', 'knd' ),
					'pending'          => esc_html__( 'Pending', 'knd' ),
					'installing'       => esc_html__( 'Installing...', 'knd' ),
					'success'          => esc_html__( 'Success!', 'knd' ),
					'install_callback' => array( $this, '_content_install_settings' ),
					'checked'          => true, //$this->is_default_content_installed(),
				);
//             $content['menu'] = array(
//                 'title' => esc_html__('Menu', 'knd'),
//                 'description' => esc_html__('Insert default website menu as seen in the demo.', 'knd'),
//                 'pending' => esc_html__('Pending', 'knd'),
//                 'installing' => esc_html__('Installing...', 'knd'),
//                 'success' => esc_html__('Success!', 'knd'),
//                 'install_callback' => array($this, '_content_install_menu'),
//                 'checked' => $this->is_default_content_installed(),
//             );
				$content['content'] = array(
					'title'            => esc_html__( 'Content', 'knd' ),
					'description'      => esc_html__( 'Install default website posts, pages and menus.', 'knd' ),
					'pending'          => esc_html__( 'Pending', 'knd' ),
					'installing'       => esc_html__( 'Installing...', 'knd' ),
					'success'          => esc_html__( 'Success!', 'knd' ),
					'install_callback' => array( $this, '_content_install_content' ),
					'checked'          => true, //$this->is_default_content_installed(),
				);
				$content['donations'] = array(
					'title'            => esc_html__( 'Donations', 'knd' ),
					'description'      => esc_html__( 'Install donations components.', 'knd' ),
					'pending'          => esc_html__( 'Pending', 'knd' ),
					'installing'       => esc_html__( 'Installing...', 'knd' ),
					'success'          => esc_html__( 'Success!', 'knd' ),
					'install_callback' => array( $this, '_content_install_donations' ),
					'checked'          => true, //$this->is_default_content_installed(),
				);

			return apply_filters($this->theme_name.'_theme_setup_wizard_content', $content);

		}

		public function step_content_view() { 
			?>

			<h1><?php esc_html_e('Theme default content', 'knd'); ?></h1>
			<form method="post">
				<?php if($this->is_default_content_installed()) { ?>
					<p><?php esc_html_e('It looks like you already have content installed on this website. If you would like to install the default demo content as well you can select it below. Otherwise just choose the upgrade option to ensure everything is up to date.', 'knd'); ?></p>
				<?php } else { ?>
					<p><?php esc_html_e("It's time to insert some default content for your new WordPress website. Choose what you would like inserted below and click Install. We recommend to select everything. Once inserted, this content can be managed from the WordPress admin dashboard.", 'knd'); ?></p>
				<?php } ?>
				<table class="envato-setup-pages" cellspacing="0">
					<thead>
					<tr>
						<td class="check"></td>
						<th class="item"><?php esc_html_e('Item', 'knd'); ?></th>
						<th class="description"><?php esc_html_e('Description', 'knd'); ?></th>
						<th class="status"><?php esc_html_e('Status', 'knd'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($this->_content_default_get() as $slug => $default) { ?>
						<tr class="envato_default_content" data-content="<?php echo esc_attr($slug); ?>">
							<td>
								<input type="checkbox" name="default_content[<?php echo esc_attr($slug); ?>]" class="envato_default_content" id="default_content_<?php echo esc_attr($slug); ?>" value="1" <?php echo !isset($default['checked']) || $default['checked'] ? 'checked="checked"' : ''; ?>>
							</td>
							<td>
								<label for="default_content_<?php echo esc_attr($slug); ?>">
									<?php echo esc_html($default['title']); ?>
								</label>
							</td>
							<td class="description"><?php echo esc_html($default['description']); ?></td>
							<td class="status"><span><?php echo esc_html($default['pending']); ?></span>
								<div class="spinner"></div>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next" data-callback="installContent">
						<?php esc_html_e('Set up', 'knd'); ?>
					</a>
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
						<?php esc_html_e('Skip this step', 'knd'); ?>
					</a>
					<?php wp_nonce_field('knd-setup-content'); ?>
				</p>
			</form>
			<?php
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

		public function step_scenario_view() { ?>

			<h1><?php esc_html_e('Choose website template', 'knd'); ?></h1>
			<form method="post">
				
				<div class="wizard-error" id="knd-download-plot-error" style="display: none;">
					<span class="error-begin"><?php esc_html_e('Error:', 'knd')?></span>
					<span class="error-text"><?php esc_html_e('Downloading theme file failed!', 'knd')?></span>
					<div class="wizard-error-support-text"></div>
					<p class="envato-setup-actions error step">
						<a href="<?php echo admin_url()?>" class="button button-large button-error"><?php esc_html_e('Back to the Dashboard', 'knd')?></a>
						<a href="mailto:<?php echo KND_SUPPORT_EMAIL?>" class="button button-error button-large button-primary"><?php esc_html_e('Email to the theme support', 'knd')?></a>
					</p>
				</div>

				<p><?php esc_html_e('For your convenience, we’ve created several templates for NGOs. Select the one that you fits you best. You will be able to change colours, content (text and images).', 'knd'); ?></p>
				
				<div class="theme-presets">
					<ul>
						<?php $current_scenario_id = knd_get_theme_mod('knd_site_scenario', $this->get_default_site_scenario_id());

						if(empty($this->site_scenarios)) {
							throw new Exception(__('No scenarios detected', 'knd'), 1);
						}

						foreach($this->site_scenarios as $scenario_id => $data) { ?>
							<li <?php echo $scenario_id == $current_scenario_id ? 'class="current" ' : ''; ?>>
								<a href="#" data-scenario-id="<?php echo esc_attr($scenario_id); ?>">
									<img src="<?php echo esc_url(get_template_directory_uri().'/vendor/envato_setup/images/'.$scenario_id.'/style.png'); ?>">
									<span class="plot-data">
									<h3 class="plot-title"><?php echo $data['name']; ?></h3>
									<div class="plot-info">
										<?php echo empty($data['description']) ? '' : $data['description']; ?>
									</div>
								</span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>

				<input type="hidden" name="new_scenario_id" id="new_scenario_id" value="<?php echo $current_scenario_id ? $current_scenario_id : ''; ?>">

				<p class="envato-setup-actions step">
					<a class="button button-large button-wizard-back" href="<?php echo esc_url( admin_url() ); ?>">
						<?php esc_html_e( 'Exit', 'knd'); ?>
					</a>
					<input type="submit" class="button-primary button button-large button-next" id="knd-install-scenario" data-callback="kndDownloadPlotStep" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next knd-download-plot-skip">
						<?php esc_html_e('Skip this step', 'knd'); ?>
					</a>
					<span id="knd-download-status-explain" style="display: none;"><?php esc_html_e('Downloading template archive...', 'knd')?></span>
					<?php wp_nonce_field('knd-setup'); ?>
				</p>
			</form>
			<?php
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

						//$imp = new KND_Import_Remote_Content($plot_name);
						//$imp->import_content();
						
						//$pdb = KND_Plot_Data_Builder::produce_builder($imp);
						//if( !$pdb) { // Show some user-friendly error
						//    throw new Exception(sprintf(__('Plot data builder was not produced for plot: %s', 'knd'), $plot_name));
						//}
						//$pdb->build_theme_files();
						//$pdb->build_option_files();
						//$pdb->build_theme_colors();
						//
						//update_option('knd_setup_install_leyka', false);
						//
						//wp_redirect(esc_url_raw($this->get_next_step_link()));
						//exit;
						
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

		public function step_logo_design_view() { ?>

			<h1><?php esc_html_e( 'Logo', 'knd' ); ?></h1>
			<form method="post">

				<p><?php _e( 'Please add your organization main logo below. The recommended size is <strong>315 x 66 px</strong> (for "Image only" mode) and <strong>66 x 66 px</strong> (for "Image with site name" mode). The logo can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd' ); ?></p>

				<p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new logo designed.', 'knd'), '<a href="'.TST_PASEKA_OFFICIAL_WEBSITE_URL.'" target="_blank">', '</a>'); ?></p>
				<table>
					<tr>
						<td>
							<div id="current-logo"><?php echo knd_get_logo_img(); ?></div>
						</td>
						<td>
							<a href="#" class="button button-upload"><?php esc_html_e('Upload new logo', 'knd'); ?></a>
						</td>
					</tr>
				</table>

				<input type="hidden" name="new_logo_id" id="new_logo_id" value="">

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
					<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
						<?php esc_html_e('Skip this step', 'knd'); ?>
					</a>
					<?php wp_nonce_field('knd-setup-design'); ?>
				</p>
			</form>
			<?php
		}

		public function step_logo_design_handler() {

			check_admin_referer('knd-setup-design');

			$_POST['new_logo_id'] = (int)$_POST['new_logo_id'];
			if ( $_POST['new_logo_id'] ) {

				/* Deprecate code, this code preven load svg image, must be removed in future,
				$attr = wp_get_attachment_image_src($_POST['new_logo_id'], 'full');
				if($attr && !empty($attr[1]) && !empty($attr[2])) {
					set_theme_mod('knd_custom_logo', $_POST['new_logo_id']);
				}
				*/
				set_theme_mod( 'knd_custom_logo', $_POST['new_logo_id'] );

			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			exit;

		}

		public function step_settings_view() {

			if ( 'problem-org' === get_theme_mod( 'knd_site_scenario' ) ) {
				$font_base     = array(
					'font-family' => 'Jost',
					'variant'     => 'regular',
					'color'       => '#081d47',
					'font-size'   => '18px',
					'font-backup' => '',
					'font-weight' => 400,
					'font-style'  => 'normal',
				);
				$font_headings = array(
					'font-family' => 'Jost',
					'variant'     => '600',
					'color'       => '#081d47',
					'font-backup' => '',
					'font-weight' => 600,
					'font-style'  => 'normal',
				);
				set_theme_mod( 'font_base', $font_base );
				set_theme_mod( 'font_headings', $font_headings );
			}

			?>

			<h1><?php esc_html_e( 'NGO settings', 'knd' ); ?></h1>

			<form method="post" class="knd-wizard-step settings-step">
				<p>
					<input type="text" name="knd_org_name" id="knd-org-name" value="<?php echo get_option('blogname'); ?>" class="knd-setup-wizard-control">
					<label for="knd-org-name"><?php esc_html_e('The website title', 'knd'); ?></label>
				</p>

				<p>
					<input type="text" name="knd_org_description" id="knd-org-description" value="<?php echo get_option('blogdescription'); ?>" class="knd-setup-wizard-control">
					<label for="knd-org-description"><?php esc_html_e('The website description', 'knd'); ?></label>
				</p>

				<p><?php _e( 'Please add your site icon below. The recommended size is <strong>64 x 64 px</strong>. The site icon can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd' ); ?></p>

				<p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new site icon designed.', 'knd'), '<a href="'.TST_PASEKA_OFFICIAL_WEBSITE_URL.'" target="_blank">', '</a>'); ?></p>
				<table>
					<tr>
						<td>
							<div id="current-site-icon">
								<?php $image_url = knd_get_site_icon_img_url();
								if($image_url) {
									printf('<img class="site-logo-img" src="%s" style="width: 32px; height: auto;">', $image_url);
								} ?>
							</div>
						</td>
						<td>
							<a href="#" class="button button-upload"><?php esc_html_e('Upload new site icon', 'knd'); ?></a>
						</td>
					</tr>
				</table>

				<input type="hidden" name="new_logo_id" id="new_logo_id" value="">


				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
					<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
					<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
						<?php esc_html_e('Skip this step', 'knd'); ?>
					</a>
					<?php wp_nonce_field('knd-setup-settings'); ?>
				</p>
			</form>

			<?php
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
			if($new_favicon_id) {

				$attr = wp_get_attachment_image_src($new_favicon_id, 'full');
				if($attr && !empty($attr[1]) && !empty($attr[2])) {
					update_option('site_icon', $new_favicon_id);
				}

			}

			wp_redirect(esc_url_raw($this->get_next_step_link()));
			exit;

		}

		public function step_support_view() {

			if(defined('LEYKA_VERSION') && get_option('knd_setup_install_leyka')) {

				if(is_plugin_active('leyka/leyka.php')) {
					knd_activate_leyka();
					update_option('knd_setup_install_leyka', false);
					// Add current Leyka version.
					update_option( 'leyka_last_ver', LEYKA_VERSION );
				}

			}

		?>

			<h1><?php esc_html_e('Help and support', 'knd'); ?></h1>

			<p>
				<?php esc_html_e('Thank you for using “Kandinsky” theme on your website!','knd'); ?>
			</p>
			<p>
				<?php printf(__('“Kandinsky” — is a free and open-source project supported by <a href="%s" target="_blank">Teplitsa. Technologies for Social Good</a> together with the community of independent developers.', 'knd'), TST_OFFICIAL_WEBSITE_URL); ?>
			</p>
			<p><?php esc_html_e('In case you encounter any questions or issues, we recommend you the following links:', 'knd'); ?></p>
			<ul class="knd-wizard-support-variants">
				<li><?php echo sprintf( __('Documentation and FAQ — <a href="%s" target="_blank">GitHub Wiki</a>', 'knd'), esc_url( KND_DOC_URL ) ); ?></li>
				<li><?php echo sprintf( __('Source code — <a href="%s" target="_blank">GitHub</a>', 'knd'), esc_url( KND_SOURCES_PAGE_URL ) ); ?></li>
				<li><?php echo sprintf( __('Developers’ <a href="%s">Telegram-channel</a> (in Russian)', 'knd'), esc_url( KND_SUPPORT_TELEGRAM ) ); ?></li>
			</ul>
			<p><?php echo sprintf( __('If you need personalized (free during the testing period) consultations from the theme developers, please feel free to write at <a href="mailto:%s" target="_blank">%s</a> or <a href="%s" target="_blank">leave a ticket at GitHub</a>.', 'knd'), KND_SUPPORT_EMAIL, KND_SUPPORT_EMAIL, KND_SOURCES_ISSUES_PAGE_URL ); ?></p>

			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
				<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next"><?php esc_html_e("OK, I've got it!", 'knd'); ?></a>
			</p>

			<?php
		}

		public function step_ready_view() {

			update_option('knd_setup_complete', time());

		?>

			<h1><?php esc_html_e('Yay! Your website is ready!', 'knd'); ?></h1>

			<p><?php esc_html_e('Congratulations! You’re doing really good #success! You’ve successfully installed and set up your Kandinsky theme. You need, however, to do a little bit more.', 'knd'); ?></p>
			<p><?php esc_html_e('As a part of the installation process, we’ve added some test content of an imaginary organization that you will need to edit (we’ve provided the recommendations on how to make great content).', 'knd'); ?></p>
			<p><?php esc_html_e('Moreover, you need to set up few additional plug-ins for the optimal work of your site (don’t worry, our recommendations will help you).', 'knd'); ?></p>

			<div class="envato-setup-next-steps">
				<div class="envato-setup-next-steps-first">
					<ul>
						<li class="setup-product">
							<a class="button button-primary button-large" href="<?php echo admin_url(); ?>">
								<?php esc_html_e('Continue the set-up', 'knd'); ?>
							</a>
						</li>
						<li class="setup-product">
							<a class="button button-next button-large" href="<?php echo home_url(); ?>">
								<?php esc_html_e('View your new website!', 'knd'); ?>
							</a>
						</li>
						<li class="setup-product">
							<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button button-link"><?php esc_html_e( 'Back', 'knd' ); ?></a>
						</li>
					</ul>
				</div>
				<div class="envato-setup-next-steps-last">
					<?php $funny_gif_url = 'https://media.giphy.com/media/XreQmk7ETCak0/giphy.gif';?>
					<img src="<?php echo $funny_gif_url;?>" alt="<?php esc_attr_e('#success', 'knd'); ?>">
				</div>
			</div>
			<?php
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
 * Loads the main instance of Envato_Theme_Setup_Wizard to have
 * ability extend class functionality
 *
 * @since 1.1.1
 * @return object Envato_Theme_Setup_Wizard
 */
add_action('after_setup_theme', 'envato_theme_setup_wizard');
if( !function_exists('envato_theme_setup_wizard')) {
	function envato_theme_setup_wizard() {

		if( !is_admin() && !wp_doing_ajax() ) {
			return;
		}

		Envato_Theme_Setup_Wizard::get_instance();

	}
}
//add_action('init', 'envato_theme_setup_wizard', 1); // No admin_init here!

// To remove the notice from Disable Comments plugin:
add_action('wp_loaded', function() {
	if(
		class_exists('Disable_Comments') &&
		has_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'))
	) {
		remove_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'));
	}
}, 100);
