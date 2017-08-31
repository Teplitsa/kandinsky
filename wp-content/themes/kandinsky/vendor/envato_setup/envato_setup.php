<?php
/**
 * Envato Theme Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their ThemeForest theme.
 *
 * @author      dtbaker
 * @author      vburlak
 * @package     envato_wizard
 * @version     1.3.0
 *
 *
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
 *
 * Based off the WooThemes installer.
 *
 *
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
	/**
	 * Envato_Theme_Setup_Wizard class
	 */
	class Envato_Theme_Setup_Wizard {

		/**
		 * The class version number.
		 *
		 * @since 1.1.1
		 * @access private
		 *
		 * @var string
		 */
		protected $version = '1.3.0';

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
		 *
		 * @var string
		 */
		protected $plugin_path = '';

		/**
		 * Relative plugin url for this plugin folder, used when enquing scripts
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_url = '';

		/**
		 * The slug name to refer to this menu
		 *
		 * @since 1.1.1
		 *
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
		 *
		 * @var string
		 */
		protected $page_parent;

		/**
		 * Complete URL to Setup Wizard
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $page_url;

		/**
		 * @since 1.1.8
		 *
		 */
		public $site_scenarios = array();

		/**
		 * Holds the current instance of the theme manager
		 *
		 * @since 1.1.3
		 * @var Envato_Theme_Setup_Wizard
		 */
		private static $instance = null;

		/**
		 * @since 1.1.3
		 *
		 * @return Envato_Theme_Setup_Wizard
		 */
		public static function get_instance() {

			if( !self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.1
		 * @access private
		 */
		public function __construct() {

			$this->init_globals();
			$this->init_actions();

		}

		/**
		 * Get the default scenario id. Can be overriden by theme init scripts.
		 * @see Envato_Theme_Setup_Wizard::instance()
		 */
		public function get_default_site_scenario_id() {

		    $tmp = array_keys($this->site_scenarios);

			return $this->site_scenarios ? reset($tmp) : false;

		}

		/**
		 * Setup the class globals.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_globals() {

			$current_theme         = wp_get_theme();
			$this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
//			$this->envato_username = apply_filters( $this->theme_name . '_theme_setup_wizard_username', 'dtbaker' );
//			$this->oauth_script    = apply_filters( $this->theme_name . '_theme_setup_wizard_oauth_script', 'http://dtbaker.net/files/envato/wptoken/server-script.php' );
			$this->page_slug       = 'knd-setup-wizard';
			$this->parent_slug     = apply_filters( $this->theme_name . '_theme_setup_wizard_parent_slug', '' );

			$this->site_scenarios = array(
                'problem-org' => array(
                    'name' => __('Color Line', 'knd'),
                    'description' => __('An example of a social problem oriented charity organization.', 'knd'),
                ),
                'fundraising-org' => array(
                    'name' => __('We Are With You', 'knd'),
                    'description' => __('An example of a crowdfunding oriented charity organization.', 'knd'),
                ),
                'public-campaign' => array(
                    'name' => __('Protect Dubrovino!', 'knd'),
                    'description' => __('An example of a public campaign to protect a park from deconstruction.', 'knd'),
                ),
            );

			//If we have parent slug - set correct url
//			if($this->parent_slug) {
//				$this->page_url = 'admin.php?page=' . $this->page_slug;
//			} else {
//				$this->page_url = 'themes.php?page=' . $this->page_slug;
//			}
			$this->page_url = 'themes.php?page=knd-setup-wizard'; //apply_filters( $this->theme_name . '_theme_setup_wizard_page_url', $this->page_url );

			// Set relative plugin path url:
			$this->plugin_path = trailingslashit($this->cleanFilePath(dirname(__FILE__)));
			$relative_url      = str_replace($this->cleanFilePath(get_template_directory()), '', $this->plugin_path);
			$this->plugin_url  = trailingslashit(get_template_directory_uri().$relative_url);

		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_actions() {
			if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {
				add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );

				if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
					add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
					add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
				}

//				add_action( 'admin_menu', array( $this, 'admin_menus' ) );
				add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action('admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action('admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action('admin_init', array( $this, 'setup_wizard' ), 30 );
				add_filter('tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
				add_action('wp_ajax_knd_wizard_setup_plugins', array($this, 'ajax_plugins'));
				add_action('wp_ajax_knd_wizard_setup_content', array($this, 'ajax_content'));
				add_action('wp_ajax_knd_wizard_update_settings', array($this, 'ajax_settings'));
			}
		}

		/**
		 * After a theme update we clear the setup_complete option. This prompts the user to visit the update page again.
		 *
		 * @since 1.1.8
		 * @access public
		 */
//		public function upgrader_post_install( $return, $theme ) {
//			if ( is_wp_error( $return ) ) {
//				return $return;
//			}
//			if ( $theme != get_stylesheet() ) {
//				return $return;
//			}
//			update_option( 'knd_setup_complete', false );
//
//			return $return;
//		}

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

		public function switch_theme() {
			set_transient('_'.$this->theme_name.'_activation_redirect', 1);
		}

		public function admin_redirects() {

		    if( !get_transient('_'.$this->theme_name.'_activation_redirect') || get_option('knd_setup_complete', false)) {
				return;
			}

			delete_transient('_'.$this->theme_name.'_activation_redirect');
			wp_safe_redirect(admin_url( $this->page_url));

			exit;

		}

		/**
		 * Get configured TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		/**
		 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );

		}

		/**
		 * Add admin menus/screens.
		 */
//		public function admin_menus() {
//
//			if ( $this->is_submenu_page() ) {
//				//prevent Theme Check warning about "themes should use add_theme_page for adding admin pages"
//				$add_subpage_function = 'add_submenu' . '_page';
//				$add_subpage_function( $this->parent_slug, esc_html__( 'Setup Wizard' ), esc_html__( 'Setup Wizard' ), 'manage_options', $this->page_slug, array(
//					$this,
//					'setup_wizard',
//				) );
//			} else {
//				add_theme_page( esc_html__( 'Setup Wizard' ), esc_html__( 'Setup Wizard' ), 'manage_options', $this->page_slug, array(
//					$this,
//					'setup_wizard',
//				) );
//			}
//
//		}


		/**
		 * Setup steps.
		 *
		 * @since 1.1.1
		 * @access public
		 * @return array
		 */
		public function init_wizard_steps() {

			$this->steps = array(
				'introduction' => array(
					'name'    => esc_html__('Introduction', 'knd'),
					'view'    => array($this, 'step_intro_view'),
					'handler' => '',
				),
			);
            $this->steps['scenario'] = array(
                'name'    => esc_html__('Scenario', 'knd'),
                'view'    => array($this, 'step_scenario_view'),
                'handler' => array($this, 'step_scenario_handler'),
            );
			$this->steps['default_content'] = array(
				'name'    => esc_html__('Content', 'knd'),
				'view'    => array($this, 'step_content_view'),
				'handler' => '',
			);
            $this->steps['design'] = array(
                'name'    => esc_html__('Logo'),
                'view'    => array($this, 'step_logo_design_view'),
                'handler' => array($this, 'step_logo_design_handler'),
            );
            $this->steps['settings'] = array(
                'name'    => esc_html__('Settings'),
                'view'    => array($this, 'step_settings_view'),
                'handler' => array($this, 'step_settings_handler'),
            );
            if(class_exists('TGM_Plugin_Activation') && isset($GLOBALS['tgmpa'])) {
                $this->steps['default_plugins'] = array(
                    'name'    => esc_html__('Plugins'),
                    'view'    => array($this, 'step_default_plugins_view'),
                    'handler' => '',
                );
            }
			$this->steps['support'] = array(
				'name'    => _x('Support', 'One word "support service" variant', 'knd'),
				'view'    => array($this, 'step_support_view'),
				'handler' => '', //array($this, 'step_support_handler'),
			);
			$this->steps['next_steps'] = array(
				'name'    => esc_html__('Ready!', 'knd'),
				'view'    => array($this, 'step_ready_view'),
				'handler' => '', //array($this, 'step_ready_handler'),
			);

			$this->steps = apply_filters($this->theme_name.'_theme_setup_wizard_steps', $this->steps);

		}

		public function setup_wizard() {

			if(empty($_GET['page']) || $this->page_slug !== $_GET['page']) {
				return;
			}
//			ob_end_clean();

			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

			wp_register_script('jquery-blockui', $this->plugin_url . 'js/jquery.blockUI.js', array( 'jquery' ), '2.70', true );
			wp_register_script('envato-setup', $this->plugin_url . 'js/envato-setup.js', array(
				'jquery',
				'jquery-blockui',
			), $this->version);
			wp_localize_script('envato-setup', 'envato_setup_params', array(
				'tgm_plugin_nonce' => array(
					'update'  => wp_create_nonce('tgmpa-update'),
					'install' => wp_create_nonce('tgmpa-install'),
				),
				'tgm_bulk_url'     => admin_url($this->tgmpa_url),
				'ajaxurl'          => admin_url('admin-ajax.php'),
				'wpnonce'          => wp_create_nonce('knd-setup-nonce'),
				'verify_text'      => __('...verifying', 'knd'),
                'processing_text'  => __('Processing...', 'knd'),
			));

			//wp_enqueue_style( 'envato_wizard_admin_styles', $this->plugin_url . '/css/admin.css', array(), $this->version );
			wp_enqueue_style( 'envato-setup', $this->plugin_url . 'css/envato-setup.css', array(
				'wp-admin',
				'dashicons',
				'install',
			), $this->version );

			//enqueue style for admin notices
			wp_enqueue_style('wp-admin');

			wp_enqueue_media();
			wp_enqueue_script('media');

			ob_start();
			$this->display_wizard_header();
			$this->display_wizard_steps();
			$show_content = true;

			echo '<div class="envato-setup-content">';
			if( !empty($_REQUEST['save_step']) && isset($this->steps[$this->step]['handler']) ) {
				$show_content = call_user_func($this->steps[$this->step]['handler']);
			}
			if($show_content) {
				$this->display_wizard_current_step_content();
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

			return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps))+1], remove_query_arg('translation_updated'));

		}

	    public function display_wizard_header() {?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes();?>>
		<head>
			<meta name="viewport" content="width=device-width"/>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<?php // To avoid theme check issues...
			echo '<t'; echo 'itle>'.__('Kandinsky - setup wizard', 'knd').'</ti'.'tle>';?>
			<?php wp_print_scripts('envato-setup');?>
			<?php do_action('admin_print_styles');?>
			<?php do_action('admin_print_scripts');?>
			<?php do_action('admin_head');?>
		</head>
		<body class="envato-setup wp-core-ui">
		<h1 id="wc-logo">
			<a href="<?php echo KND_OFFICIAL_WEBSITE_URL;?>" target="_blank">
                <?php echo '<img class="wizard-header-logo" src="'.get_template_directory_uri().'/knd-logo.svg" alt="'.__('Kandinsky theme setup wizard', 'knd').'" style="width:100%; height:auto;">';?>
            </a>
		</h1>
		<?php
		}

		/**
		 * Setup Wizard Footer
		 */
		public function display_wizard_footer() {?>
            <?php if($this->step == 'next_steps') {?>
            <a class="wc-return-to-dashboard" href="<?php echo esc_url(admin_url());?>">
                <?php esc_html_e('Return to the WordPress Dashboard', 'knd');?>
            </a>
            <?php }?>
        </body>
            <?php @do_action('admin_footer'); // This was spitting out some errors in some admin templates. quick @ fix until I have time to find out what's causing errors
            do_action('admin_print_footer_scripts');?>
        </html>
            <?php
        }

		/**
		 * Output the steps
		 */
		public function display_wizard_steps() {

			$ouput_steps = $this->steps;
			array_shift($ouput_steps);?>

			<ol class="envato-setup-steps">
            <?php foreach ( $ouput_steps as $step_key => $step ) : ?>
                <li class="<?php $show_link = false;
                if ( $step_key === $this->step ) {
                    echo 'active';
                } elseif ( array_search( $this->step, array_keys( $this->steps ) ) > array_search( $step_key, array_keys( $this->steps ) ) ) {
                    echo 'done';
                    $show_link = true;
                }
                ?>"><?php
                    if ( $show_link ) {
                        ?>
                        <a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
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
			isset($this->steps[$this->step]) ? call_user_func($this->steps[$this->step]['view']) : false;
		}

		public function step_intro_view() {?>

            <h1><?php printf(esc_html__('Welcome to the %s setup wizard', 'knd'), wp_get_theme());?></h1>
            <p><?php printf(esc_html__("Hello! Let's set up your organization website together. With few simple steps we will configure minimal necessary settings, like installing of required plugins, setting up default website content and the logo. It should only take 5 minutes. You can always change any of these settings later on, in the Plugins admin folder.", 'knd')); ?></p>

            <p class="envato-setup-actions step">
                <a href="<?php echo esc_url($this->get_next_step_link());?>" class="button-primary button button-large button-next"><?php esc_html_e("Let's go!", 'knd'); ?></a>
                <a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url(''));?>" class="button button-large"><?php esc_html_e('Not right now', 'knd');?></a>
            </p>
        <?php
		}

		public function filter_options( $options ) {
			return $options;
		}

		private function _get_plugins() {

			$instance = call_user_func(array(get_class($GLOBALS['tgmpa']), 'get_instance'));
			$plugins  = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}

			return $plugins;

		}

		public function step_default_plugins_view() {

			tgmpa_load_bulk_installer();
			if( !class_exists('TGM_Plugin_Activation') || !isset($GLOBALS['tgmpa']) ) {
				die(__('Failed to find TGM plugin', 'knd'));
			}
			$url = wp_nonce_url(add_query_arg(array('plugins' => 'go')), 'envato-setup');

			$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
			$fields = array_keys($_POST); // Extra fields to pass to WP_Filesystem.

			if(false === ($creds = request_filesystem_credentials(esc_url_raw($url), $method, false, false, $fields))) {
				return true; // Stop the normal page form from displaying, credential request form will be shown.
			}

			// Now we have some credentials, setup WP_Filesystem
			if ( !WP_Filesystem($creds) ) { // Our credentials were no good, ask the user for them again

				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
				return true;

			}?>

			<h1><?php esc_html_e('Default Plugins', 'knd');?></h1>
			<form method="post">

				<?php $plugins = $this->_get_plugins();
				if($plugins['all']) {

                    $plugins_required = $plugins_recommended = array();

				    foreach($plugins['all'] as $slug => $plugin) {
				        if(empty($plugin['required'])) {
				            $plugins_recommended[$slug] = $plugin;
                        } else {
				            $plugins_required[$slug] = $plugin;
                        }
                    }

                    if($plugins_required) {?>

					<p><?php esc_html_e('Your website needs a few essential plugins. The following plugins will be installed or updated:', 'knd');?></p>
                    <p><?php esc_html_e('You can add and remove plugins later on, in the Plugins admin folder.', 'knd');?></p>

					<ul class="envato-wizard-plugins">
						<?php foreach($plugins_required as $slug => $plugin) {?>
                        <li data-slug="<?php echo esc_attr($slug);?>"><?php echo esc_html($plugin['name']);?>
                            <span>
                            <?php $plugin_status = '';

                            if(isset($plugins['install'][$slug])) {
                                $plugin_status = __('Installation required', 'knd');
                            } else if(isset($plugins['update'][$slug])) {
                                $plugin_status = isset($plugins['activate'][$slug]) ?
                                    __('Update and activation required', 'knd') : __('Update required', 'knd');
                            } else if(isset($plugins['activate'][$slug])) {
                                $plugin_status = __('Activation required', 'knd');
                            }

                            echo $plugin_status;?>
                            </span>
                            <div class="spinner"></div>
                            
                            <div class="knd-plugin-description"><?php echo $plugin['description']?></div>
                        </li>
						<?php }?>
					</ul>
                    <?php }

                    if($plugins_recommended) {

                        if($plugins_required) {?>
                    <p><?php esc_html_e('We also recommend to add several more:', 'knd');?></p>
                        <?php } else {?>
                    <p><?php esc_html_e('We recommend to add or update the following plugins:', 'knd');?></p>
                        <?php }?>

                    <ul class="envato-wizard-plugins-recommended">
                    <?php foreach($plugins_recommended as $slug => $plugin) {?>
                        <li data-slug="<?php echo esc_attr($slug);?>"><?php echo esc_html($plugin['name']);?><span>

                        <?php $plugin_status = '';

                        if(isset($plugins['install'][$slug])) {
                            $plugin_status = __('Install', 'knd');
                        } else if(isset($plugins['update'][$slug])) {
                            $plugin_status = isset($plugins['activate'][$slug]) ?
                                __('Update and activate', 'knd') : __('Update', 'knd');
                        } else if(isset($plugins['activate'][$slug])) {
                            $plugin_status = __('Activate', 'knd');
                        }?>

                        <label>
                            <input type="checkbox" class="plugin-accepted" name="knd-recommended-plugin-<?php echo $slug;?>">
                            <?php echo $plugin_status;?>
                        </label>

                    </span><div class="spinner"></div>
                    
                    <div class="knd-plugin-description"><?php echo $plugin['description']?></div>
                    
                    </li>
                    <?php }?>
                    </ul>

                <?php }

                } else {
					echo '<p><strong>'.esc_html_e("Good news! All plugins are already installed and up to date. Let's proceed further.", 'knd').'</strong></p>';
				}?>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_next_step_link());?>" class="button-primary button button-large button-next" data-callback="install_plugins">
                        <?php esc_html_e('Continue', 'knd'); ?>
                    </a>
					<a href="<?php echo esc_url($this->get_next_step_link());?>" class="button button-large button-next">
                        <?php esc_html_e('Skip this step', 'knd');?>
                    </a>
					<?php wp_nonce_field('envato-setup');?>
				</p>
			</form>
			<?php return true;
		}

		public function ajax_plugins() {

			if( !check_ajax_referer('knd-setup-nonce', 'wpnonce') || empty($_POST['slug']) ) {
				wp_send_json_error(array('error' => 1, 'message' => esc_html__('No slug found', 'knd')));
			}
			$json = array(); // Send back some json we use to hit up TGM

			$plugins = $this->_get_plugins();

			// what are we doing with this plugin?
			foreach($plugins['all'] as $slug => $plugin) {

                if( !empty($plugins['activate'][$slug]) && $_POST['slug'] == $slug) {

					$json = array(
						'url'           => admin_url($this->tgmpa_url),
						'plugin'        => array($slug),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce('bulk-plugins'),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => -1,
						'message'       => esc_html__('Activating Plugin', 'knd'),
					);
					break;

				} else if( !empty($plugins['update'][$slug]) && $_POST['slug'] == $slug ) {

                    $json = array(
                        'url'           => admin_url( $this->tgmpa_url ),
                        'plugin'        => array( $slug ),
                        'tgmpa-page'    => $this->tgmpa_menu_slug,
                        'plugin_status' => 'all',
                        '_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
                        'action'        => 'tgmpa-bulk-update',
                        'action2'       => -1,
                        'message'       => esc_html__('Updating Plugin', 'knd'),
                    );
                    break;

                } else if( !empty($plugins['install'][$slug]) && $_POST['slug'] == $slug ) {

                    $json = array(
                        'url'           => admin_url( $this->tgmpa_url ),
                        'plugin'        => array( $slug ),
                        'tgmpa-page'    => $this->tgmpa_menu_slug,
                        'plugin_status' => 'all',
                        '_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
                        'action'        => 'tgmpa-bulk-install',
                        'action2'       => -1,
                        'message'       => esc_html__('Installing Plugin', 'knd'),
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

            $scenario_id = get_theme_mod('knd_site_scenario');
            $scenario_data = empty($this->site_scenarios[$scenario_id]) ? false : $this->site_scenarios[$scenario_id];

            if($scenario_data) {
                knd_set_sitename_settings($scenario_data);
            }
            return true;

        }
        public function _content_install_logo_favicon() {

            $scenario_id = get_theme_mod('knd_site_scenario');

//            if($scenario_data) {
//                knd_set_sitename_settings($scenario_data);
//            }
            return true;

        }
		public function _content_install_posts() {

// 		    knd_import_starter_data_from_csv('posts.csv', 'post');
// 		    knd_update_posts();
		    
		    $plot_name = get_theme_mod('knd_site_scenario');
		    $imp = new KND_Import_Remote_Content($plot_name);
		    $imp->import_downloaded_content();
		    
		    $pdb = KND_Plot_Data_Builder::produce_builder($imp);
		    $pdb->build_posts();

            return true;

        }
        public function _content_install_pages() {
            
//             do_action('knd_save_demo_content');
            
            $plot_name = get_theme_mod('knd_site_scenario');
            $imp = new KND_Import_Remote_Content($plot_name);
            $imp->import_downloaded_content();
            
            $pdb = KND_Plot_Data_Builder::produce_builder($imp);
            $pdb->build_pages();
            
            return true;

        }
        public function _content_install_settings() {
            
            $plot_name = get_theme_mod('knd_site_scenario');
            $imp = new KND_Import_Remote_Content($plot_name);
            $imp->import_downloaded_content();
            
            $pdb = KND_Plot_Data_Builder::produce_builder($imp);
            $pdb->build_theme_options();
            
            return true;

        }
        public function _content_install_menu() {

            knd_setup_menus();  // all menus except main nav menu
            return true;

        }

		private function _content_default_get() {

			$content = array();

            $content['site_title_desc'] = array(
                'title'            => esc_html__('Website title and description', 'knd'),
                'description'      => esc_html__('Insert default website title and description as seen in the demo.', 'knd'),
                'pending'          => esc_html__('Pending', 'knd'),
                'installing'       => esc_html__('Installing...', 'knd'),
                'success'          => esc_html__('Success!', 'knd'),
                'install_callback' => array($this, '_content_install_site_title_desc'),
                'checked'          => $this->is_default_content_installed(),
            );
            $content['pages'] = array(
                'title'            => esc_html__('Pages', 'knd'),
                'description'      => esc_html__('Insert default website pages as seen in the demo.', 'knd'),
                'pending'          => esc_html__('Pending', 'knd'),
                'installing'       => esc_html__('Installing...', 'knd'),
                'success'          => esc_html__('Success!', 'knd'),
                'install_callback' => array($this, '_content_install_pages'),
                'checked'          => $this->is_default_content_installed(),
            );
            $content['posts'] = array(
                'title'            => esc_html__('Posts', 'knd'),
                'description'      => esc_html__('Insert default website posts as seen in the demo.', 'knd'),
                'pending'          => esc_html__('Pending', 'knd'),
                'installing'       => esc_html__('Installing...', 'knd'),
                'success'          => esc_html__('Success!', 'knd'),
                'install_callback' => array($this, '_content_install_posts'),
                'checked'          => $this->is_default_content_installed(),
            );
			$content['settings'] = array(
                'title'            => esc_html__('Settings', 'knd'),
                'description'      => esc_html__('Insert default website settings as seen in the demo.', 'knd'),
                'pending'          => esc_html__('Pending', 'knd'),
                'installing'       => esc_html__('Installing...', 'knd'),
                'success'          => esc_html__('Success!', 'knd'),
                'install_callback' => array($this, '_content_install_settings'),
                'checked'          => $this->is_default_content_installed(),
			);
            $content['menu'] = array(
                'title'            => esc_html__('Menu', 'knd'),
                'description'      => esc_html__('Insert default website menu as seen in the demo.', 'knd'),
                'pending'          => esc_html__('Pending', 'knd'),
                'installing'       => esc_html__('Installing...', 'knd'),
                'success'          => esc_html__('Success!', 'knd'),
                'install_callback' => array($this, '_content_install_menu'),
                'checked'          => $this->is_default_content_installed(),
            );

			return apply_filters($this->theme_name.'_theme_setup_wizard_content', $content);

		}

		public function step_content_view() {?>

			<h1><?php esc_html_e('Theme default content', 'knd');?></h1>
			<form method="post">
				<?php if($this->is_default_content_installed()) {?>
					<p><?php esc_html_e('It looks like you already have content installed on this website. If you would like to install the default demo content as well you can select it below. Otherwise just choose the upgrade option to ensure everything is up to date.', 'knd');?></p>
				<?php } else {?>
					<p><?php esc_html_e("It's time to insert some default content for your new WordPress website. Choose what you would like inserted below and click Install. We recommend to select everything. Once inserted, this content can be managed from the WordPress admin dashboard.", 'knd');?></p>
				<?php }?>
				<table class="envato-setup-pages" cellspacing="0">
					<thead>
                        <tr>
                            <td class="check"></td>
                            <th class="item"><?php esc_html_e('Item', 'knd');?></th>
                            <th class="description"><?php esc_html_e('Description', 'knd');?></th>
                            <th class="status"><?php esc_html_e('Status', 'knd'); ?></th>
                        </tr>
					</thead>
					<tbody>
					<?php foreach($this->_content_default_get() as $slug => $default) {?>
						<tr class="envato_default_content" data-content="<?php echo esc_attr($slug);?>">
							<td>
								<input type="checkbox" name="default_content[<?php echo esc_attr($slug);?>]" class="envato_default_content" id="default_content_<?php echo esc_attr($slug);?>" value="1" <?php echo !isset($default['checked']) || $default['checked'] ? 'checked="checked"' : '';?>>
							</td>
							<td>
                                <label for="default_content_<?php echo esc_attr($slug);?>">
                                    <?php echo esc_html($default['title']);?>
                                </label>
							</td>
							<td class="description"><?php echo esc_html($default['description']);?></td>
							<td class="status"><span><?php echo esc_html($default['pending']);?></span>
								<div class="spinner"></div>
							</td>
						</tr>
					<?php }?>
					</tbody>
				</table>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url($this->get_next_step_link());?>" class="button-primary button button-large button-next" data-callback="install_content">
                        <?php esc_html_e('Set up', 'knd');?>
                    </a>
					<a href="<?php echo esc_url($this->get_next_step_link());?>" class="button button-large button-next">
                        <?php esc_html_e('Skip this step', 'knd');?>
                    </a>
					<?php wp_nonce_field('knd-setup-content');?>
				</p>
			</form>
        <?php
		}

		public function ajax_content() {

			$content = $this->_content_default_get();
			if(
                !check_ajax_referer('knd-setup-nonce', 'wpnonce') ||
                empty($_POST['content']) &&
                isset($content[$_POST['content']])
            ) {
				wp_send_json_error(array('error' => 1, 'message' => esc_html__('No content Found', 'knd')));
			}

			$json = false;
			$this_content = $content[$_POST['content']];

			if(empty($_POST['proceed'])) {
                $json = array(
                    'url'      => admin_url('admin-ajax.php'),
                    'action'   => 'knd_wizard_setup_content',
                    'proceed'  => 'true',
                    'content'  => $_POST['content'],
                    'wpnonce' => wp_create_nonce('knd-setup-nonce'),
                    'message'  => $this_content['installing'],
                    'logs'     => $this->logs,
                    'errors'   => $this->errors,
                );
			} else {

                $this->log(' -!! STARTING SECTION for '.$_POST['content']);

                $this->delay_posts = get_transient('delayed_posts');
                if ( !is_array($this->delay_posts) ) {
                    $this->delay_posts = array();
                }

                if( !empty($this_content['install_callback']) ) {
                    if($result = call_user_func($this_content['install_callback'])) {

                        $this->log(' -- FINISH. Writing '.count($this->delay_posts, COUNT_RECURSIVE).' delayed posts to transient ');
                        set_transient('delayed_posts', $this->delay_posts, 60 * 60 * 24);

                        if(is_array($result) && isset($result['retry'])) {
                            $json = array(
                                'url'         => admin_url('admin-ajax.php'),
                                'action'      => 'knd_wizard_setup_content',
                                'proceed'     => 'true',
                                'retry'       => time(),
                                'retry_count' => $result['retry_count'],
                                'content'     => $_POST['content'],
                                'wpnonce'    => wp_create_nonce('knd-setup-nonce'),
                                'message'     => $this_content['installing'],
                                'logs'        => $this->logs,
                                'errors'      => $this->errors,
                            );
                        } else {
                            $json = array(
                                'done'    => 1,
                                'message' => $this_content['success'],
                                'debug'   => $result,
                                'logs'    => $this->logs,
                                'errors'  => $this->errors,
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
					'error'   => 1,
					'message' => esc_html__('Error', 'knd'),
					'logs'    => $this->logs,
					'errors'  => $this->errors,
				));
			}

			exit;

		}


		private function _imported_term_id( $original_term_id, $new_term_id = false ) {
//			$terms = get_transient( 'importtermids' );
//			if ( ! is_array( $terms ) ) {
//				$terms = array();
//			}
//			if ( $new_term_id ) {
//				if ( ! isset( $terms[ $original_term_id ] ) ) {
//					$this->log( 'Insert old TERM ID ' . $original_term_id . ' as new TERM ID: ' . $new_term_id );
//				} else if ( $terms[ $original_term_id ] != $new_term_id ) {
//					$this->error( 'Replacement OLD TERM ID ' . $original_term_id . ' overwritten by new TERM ID: ' . $new_term_id );
//				}
//				$terms[ $original_term_id ] = $new_term_id;
//				set_transient( 'importtermids', $terms, 60 * 60 * 24 );
//			} else if ( $original_term_id && isset( $terms[ $original_term_id ] ) ) {
//				return $terms[ $original_term_id ];
//			}

			return false;
		}


		public function vc_post( $post_id = false ) {

			$vc_post_ids = get_transient( 'import_vc_posts' );
			if ( ! is_array( $vc_post_ids ) ) {
				$vc_post_ids = array();
			}
			if ( $post_id ) {
				$vc_post_ids[ $post_id ] = $post_id;
				set_transient( 'import_vc_posts', $vc_post_ids, 60 * 60 * 24 );
			} else {

				$this->log( 'Processing vc pages 2: ' );

				return;
				if ( class_exists( 'Vc_Manager' ) && class_exists( 'Vc_Post_Admin' ) ) {
					$this->log( $vc_post_ids );
					$vc_manager = Vc_Manager::getInstance();
					$vc_base    = $vc_manager->vc();
					$post_admin = new Vc_Post_Admin();
					foreach ( $vc_post_ids as $vc_post_id ) {
						$this->log( 'Save ' . $vc_post_id );
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
						//twice? bug?
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
					}
				}
			}

		}


		public function elementor_post( $post_id = false ) {

			// regenrate the CSS for this Elementor post
			if( class_exists( 'Elementor\Post_CSS_File' ) ) {
                $post_css = new Elementor\Post_CSS_File($post_id);
				$post_css->update();
			}

		}



		private function _imported_post_id( $original_id = false, $new_id = false ) {
			if ( is_array( $original_id ) || is_object( $original_id ) ) {
				return false;
			}
			$post_ids = get_transient( 'importpostids' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $new_id ) {
				if ( ! isset( $post_ids[ $original_id ] ) ) {
					$this->log( 'Insert old ID ' . $original_id . ' as new ID: ' . $new_id );
				} else if ( $post_ids[ $original_id ] != $new_id ) {
					$this->error( 'Replacement OLD ID ' . $original_id . ' overwritten by new ID: ' . $new_id );
				}
				$post_ids[ $original_id ] = $new_id;
				set_transient( 'importpostids', $post_ids, 60 * 60 * 24 );
			} else if ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} else if ( $original_id === false ) {
				return $post_ids;
			}

			return false;
		}

		private function _post_orphans( $original_id = false, $missing_parent_id = false ) {
			$post_ids = get_transient( 'postorphans' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $missing_parent_id ) {
				$post_ids[ $original_id ] = $missing_parent_id;
				set_transient( 'postorphans', $post_ids, 60 * 60 * 24 );
			} else if ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} else if ( $original_id === false ) {
				return $post_ids;
			}

			return false;
		}

		private function _cleanup_imported_ids() {
			// loop over all attachments and assign the correct post ids to those attachments.

		}

		private $delay_posts = array();

		private function _delay_post_process( $post_type, $post_data ) {
			if ( ! isset( $this->delay_posts[ $post_type ] ) ) {
				$this->delay_posts[ $post_type ] = array();
			}
			$this->delay_posts[ $post_type ][ $post_data['post_id'] ] = $post_data;

		}


		// return the difference in length between two strings
		public function cmpr_strlen( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}

		private function _parse_gallery_shortcode_content($content){
			// we have to format the post content. rewriting images and gallery stuff
			$replace      = $this->_imported_post_id();
			$urls_replace = array();
			foreach ( $replace as $key => $val ) {
				if ( $key && $val && ! is_numeric( $key ) && ! is_numeric( $val ) ) {
					$urls_replace[ $key ] = $val;
				}
			}
			if ( $urls_replace ) {
				uksort( $urls_replace, array( &$this, 'cmpr_strlen' ) );
				foreach ( $urls_replace as $from_url => $to_url ) {
					$content = str_replace( $from_url, $to_url, $content );
				}
			}
			if ( preg_match_all( '#\[gallery[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#ids="([^"]+)"#', $string, $ids_matches ) ) {
						$ids = explode( ',', $ids_matches[1] );
						foreach ( $ids as $key => $val ) {
							$new_id = $val ? $this->_imported_post_id( $val ) : false;
							if ( ! $new_id ) {
								unset( $ids[ $key ] );
							} else {
								$ids[ $key ] = $new_id;
							}
						}
						$new_ids                   = implode( ',', $ids );
						$content = str_replace( $ids_matches[0], 'ids="' . $new_ids . '"', $content );
					}
				}
			}
			// contact form 7 id fixes.
			if ( preg_match_all( '#\[contact-form-7[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#id="(\d+)"#', $string, $id_match ) ) {
						$new_id = $this->_imported_post_id( $id_match[1] );
						if ( $new_id ) {
							$content = str_replace( $id_match[0], 'id="' . $new_id . '"', $content );
						} else {
							// no imported ID found. remove this entry.
							$content = str_replace( $matches[0], '(insert contact form here)', $content );
						}
					}
				}
			}
			return $content;
		}

		private function _handle_delayed_posts( $last_delay = false ) {

			$this->log( ' ---- Processing ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts' );
			for ( $x = 1; $x < 4; $x ++ ) {
				foreach ( $this->delay_posts as $delayed_post_type => $delayed_post_datas ) {
					foreach ( $delayed_post_datas as $delayed_post_id => $delayed_post_data ) {
						if ( $this->_imported_post_id( $delayed_post_data['post_id'] ) ) {
							$this->log( $x . ' - Successfully processed ' . $delayed_post_type . ' ID ' . $delayed_post_data['post_id'] . ' previously.' );
							unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
							$this->log( ' ( ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts remain ) ' );
						} else if ( $this->_process_post_data( $delayed_post_type, $delayed_post_data, $last_delay ) ) {
							$this->log( $x . ' - Successfully found delayed replacement for ' . $delayed_post_type . ' ID ' . $delayed_post_data['post_id'] . '.' );
							// successfully inserted! don't try again.
							unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
							$this->log( ' ( ' . count( $this->delay_posts, COUNT_RECURSIVE ) . ' delayed posts remain ) ' );
						}
					}
				}
			}
		}

		private function _fetch_remote_file( $url, $post ) {
			// extract the file name and extension from the url
			$file_name  = basename( $url );
			$local_file = trailingslashit( get_template_directory() ) . 'images/stock/' . $file_name;
			$upload     = false;
			if ( is_file( $local_file ) && filesize( $local_file ) > 0 ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				WP_Filesystem();
				global $wp_filesystem;
				$file_data = $wp_filesystem->get_contents( $local_file );
				$upload    = wp_upload_bits( $file_name, 0, $file_data, $post['upload_date'] );
				if ( $upload['error'] ) {
					return new WP_Error( 'upload_dir_error', $upload['error'] );
				}
			}

			if ( ! $upload || $upload['error'] ) {
				// get placeholder file in the upload dir with a unique, sanitized filename
				$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
				if ( $upload['error'] ) {
					return new WP_Error( 'upload_dir_error', $upload['error'] );
				}

				// fetch the remote url and write it to the placeholder file
				//$headers = wp_get_http( $url, $upload['file'] );

				$max_size = (int) apply_filters( 'import_attachment_size_limit', 0 );

				// we check if this file is uploaded locally in the source folder.
				$response = wp_remote_get( $url );
				if ( is_array( $response ) && ! empty( $response['body'] ) && $response['response']['code'] == '200' ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
					$headers = $response['headers'];
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $upload['file'], $response['body'] );
					//
				} else {
					// required to download file failed.
					@unlink( $upload['file'] );

					return new WP_Error( 'import_file_error', esc_html__( 'Remote server did not respond' ) );
				}

				$filesize = filesize( $upload['file'] );

				if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
					@unlink( $upload['file'] );

					return new WP_Error( 'import_file_error', esc_html__( 'Remote file is incorrect size' ) );
				}

				if ( 0 == $filesize ) {
					@unlink( $upload['file'] );

					return new WP_Error( 'import_file_error', esc_html__( 'Zero size file downloaded' ) );
				}

				if ( ! empty( $max_size ) && $filesize > $max_size ) {
					@unlink( $upload['file'] );

					return new WP_Error( 'import_file_error', sprintf( esc_html__( 'Remote file is too large, limit is %s' ), size_format( $max_size ) ) );
				}
			}

			// keep track of the old and new urls so we can substitute them later
			$this->_imported_post_id( $url, $upload['url'] );
			$this->_imported_post_id( $post['guid'], $upload['url'] );
			// keep track of the destination if the remote url is redirected somewhere else
			if ( isset( $headers['x-final-location'] ) && $headers['x-final-location'] != $url ) {
				$this->_imported_post_id( $headers['x-final-location'], $upload['url'] );
			}

			return $upload;
		}

		private function _content_install_widgets() {
			// todo: pump these out into the 'content/' folder along with the XML so it's a little nicer to play with
//			$import_widget_positions = $this->_get_json( 'widget_positions.json' );
//			$import_widget_options   = $this->_get_json( 'widget_options.json' );
//
//			// importing.
//			$widget_positions = get_option( 'sidebars_widgets' );
//			if ( ! is_array( $widget_positions ) ) {
//				$widget_positions = array();
//			}
//
//			foreach ( $import_widget_options as $widget_name => $widget_options ) {
//				// replace certain elements with updated imported entries.
//				foreach ( $widget_options as $widget_option_id => $widget_option ) {
//
//					// replace TERM ids in widget settings.
//					foreach ( array( 'nav_menu' ) as $key_to_replace ) {
//						if ( ! empty( $widget_option[ $key_to_replace ] ) ) {
//							// check if this one has been imported yet.
//							$new_id = $this->_imported_term_id( $widget_option[ $key_to_replace ] );
//							if ( ! $new_id ) {
//								// do we really clear this out? nah. well. maybe.. hmm.
//							} else {
//								$widget_options[ $widget_option_id ][ $key_to_replace ] = $new_id;
//							}
//						}
//					}
//					// replace POST ids in widget settings.
//					foreach ( array( 'image_id', 'post_id' ) as $key_to_replace ) {
//						if ( ! empty( $widget_option[ $key_to_replace ] ) ) {
//							// check if this one has been imported yet.
//							$new_id = $this->_imported_post_id( $widget_option[ $key_to_replace ] );
//							if ( ! $new_id ) {
//								// do we really clear this out? nah. well. maybe.. hmm.
//							} else {
//								$widget_options[ $widget_option_id ][ $key_to_replace ] = $new_id;
//							}
//						}
//					}
//				}
//				$existing_options = get_option( 'widget_' . $widget_name, array() );
//				if ( ! is_array( $existing_options ) ) {
//					$existing_options = array();
//				}
//				$new_options = $existing_options + $widget_options;
//				update_option( 'widget_' . $widget_name, $new_options );
//			}
//			update_option( 'sidebars_widgets', array_merge( $widget_positions, $import_widget_positions ) );

			return true;

		}

		public function _get_json( $file ) {

			$theme_style = __DIR__.'/content/'.basename(get_theme_mod('dtbwp_site_scenario', $this->get_default_site_scenario_id())).'/';
			if ( is_file( $theme_style . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = $theme_style . basename( $file );
				if ( file_exists( $file_name ) ) {
					return json_decode( $wp_filesystem->get_contents( $file_name ), true );
				}
			}
            // backwards compat:
			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return json_decode( $wp_filesystem->get_contents( $file_name ), true );
				}
			}

			return array();
		}

		private function _get_sql( $file ) {

			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return $wp_filesystem->get_contents( $file_name );
				}
			}

			return false;

		}

		public $logs = array();

		public function log($message) {
			$this->logs[] = $message;
		}

		public $errors = array();

		public function error($message) {
			$this->logs[] = 'ERROR: '.$message;
		}

		public function step_scenario_view() {?>

            <h1><?php esc_html_e('Website scenario', 'knd');?></h1>
            <form method="post">
                <p><?php esc_html_e('Please choose your website scenario.', 'knd');?></p>

                <div class="theme-presets">
                    <ul>
                    <?php $current_scenario_id = get_theme_mod('knd_site_scenario', $this->get_default_site_scenario_id());
                    foreach($this->site_scenarios as $scenario_id => $data) {?>
                        <li <?php echo $scenario_id == $current_scenario_id ? 'class="current" ' : '';?>>
                            <a href="#" data-scenario-id="<?php echo esc_attr($scenario_id);?>">
                                <img src="<?php echo esc_url(get_template_directory_uri().'/vendor/envato_setup/images/'.$scenario_id.'/style.png');?>">
                                <span class="plot-data">
                                    <h3 class="plot-title"><?php echo $data['name'];?></h3>
                                    <div class="plot-info">
                                        <?php echo empty($data['description']) ? '' : $data['description'];?>
                                    </div>
                                </span>
                            </a>
                        </li>
                    <?php }?>
                    </ul>
                </div>

                <input type="hidden" name="new_scenario_id" id="new_scenario_id" value="<?php echo $current_scenario_id ? $current_scenario_id : '';?>">

                <p class="envato-setup-actions step">
                    <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd');?>" name="save_step">
                    <a href="<?php echo esc_url($this->get_next_step_link());?>" class="button button-large button-next">
                        <?php esc_html_e('Skip this step', 'knd');?>
                    </a>
					<?php wp_nonce_field('knd-setup');?>
                </p>
            </form>
			<?php
		}

		/**
		 * Save logo & design options
		 */
		public function step_scenario_handler() {

			check_admin_referer('knd-setup');

			if($_POST['new_scenario_id']) {
			    $plot_name = trim($_POST['new_scenario_id']);
			    
				set_theme_mod('knd_site_scenario', $plot_name);
				
				if($plot_name) {
				    knd_setup_starter_data($plot_name);
				}
			}

			wp_redirect(esc_url_raw($this->get_next_step_link()));
			exit;

		}

		public function step_logo_design_view() {?>

			<h1><?php esc_html_e('Logo');?></h1>
			<form method="post">

				<p><?php _e('Please add your organization main logo below. The recommended size is <strong>315 x 66 px</strong> (for "Image only" mode) and <strong>66 x 66 px</strong> (for "Image with site name" mode). The logo can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd');?></p>

                <p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new logo designed.', 'knd'), '<a href="https://paseka.te-st.ru/" target="_blank">', '</a>');?></p>
				<table>
					<tr>
						<td>
							<div id="current-logo"><?php echo knd_get_logo_img();?></div>
						</td>
						<td>
							<a href="#" class="button button-upload"><?php esc_html_e('Upload new logo', 'knd');?></a>
						</td>
					</tr>
				</table>

				<input type="hidden" name="new_logo_id" id="new_logo_id" value="">

				<p class="envato-setup-actions step">
					<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd');?>" name="save_step">
					<a href="<?php echo esc_url($this->get_next_step_link());?>" class="button button-large button-next">
                        <?php esc_html_e('Skip this step', 'knd');?>
                    </a>
					<?php wp_nonce_field('knd-setup-design');?>
				</p>
			</form>
			<?php
		}

		public function step_logo_design_handler() {

			check_admin_referer('knd-setup-design');

            $_POST['new_logo_id'] = (int)$_POST['new_logo_id'];
			if($_POST['new_logo_id']) {

				$attr = wp_get_attachment_image_src($_POST['new_logo_id'], 'full');
				if($attr && !empty($attr[1]) && !empty($attr[2])) {
					set_theme_mod('knd_custom_logo', $_POST['new_logo_id']);
				}

			}

			wp_redirect(esc_url_raw($this->get_next_step_link()));
			exit;

		}

		public function step_settings_view() {?>

			<h1><?php _e('NGO settings', 'knd');?></h1>

            <form method="post" class="knd-wizard-step settings-step">
                <p>
                    <input type="text" name="knd_org_name" id="knd-org-name" value="<?php echo get_option('blogname');?>" class="knd-setup-wizard-control">
                    <label for="knd-org-name"><?php _e('The website title', 'knd');?></label>
                </p>

                <p>
                    <input type="text" name="knd_org_description" id="knd-org-description" value="<?php echo get_option('blogdescription');?>" class="knd-setup-wizard-control">
                    <label for="knd-org-description"><?php _e('The website description', 'knd');?></label>
                </p>

                <p><?php _e('Please add your site icon below. The recommended size is <strong>64 x 64 px</strong>. The site icon can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd');?></p>
                
                <p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new site icon designed.', 'knd'), '<a href="https://paseka.te-st.ru/" target="_blank">', '</a>');?></p>
                <table>
                    <tr>
                        <td>
                            <div id="current-site-icon">
                            <?php $image_url = knd_get_site_icon_img_url();
                            if($image_url) {
                                printf('<img class="site-logo" src="%s" style="width: 32px; height: auto;">', $image_url);
                            }?>
                            </div>
                        </td>
                        <td>
                            <a href="#" class="button button-upload"><?php esc_html_e('Upload new site icon', 'knd');?></a>
                        </td>
                    </tr>
                </table>

                <input type="hidden" name="new_logo_id" id="new_logo_id" value="">


                <p class="envato-setup-actions step">
                    <input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd');?>" name="save_step"> <!-- data-callback="update_settings" -->
                    <a href="<?php echo esc_url($this->get_next_step_link());?>" class="button button-large button-next">
                        <?php esc_html_e('Skip this step', 'knd');?>
                    </a>
                    <?php wp_nonce_field('knd-setup-settings');?>
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
                    update_option( 'site_icon', $new_favicon_id );
                }
            
            }
            
            wp_redirect(esc_url_raw($this->get_next_step_link()));
            exit;

		}

		public function step_support_view() {?>

			<h1><?php _e('Help and support', 'knd');?></h1>

			<p>
                <?php printf(__('Thank you for using “Kandinsky” theme on your website.<br>“Kandinsky” — is a free and open-source project supported by <a href="%s" target="_blank">Teplitsa. Technologies for Social Good</a> together with the community of independent developers.', 'knd'), TST_OFFICIAL_WEBSITE_URL);?>
            </p>
            <p><?php _e('In case you encounter any questions or issues, we recommend you the following links:', 'knd');?></p>
            <ul class="knd-wizard-support-variants">
                <li><?php _e('Documentation and FAQ — <a href="https://github.com/Teplitsa/kandinsky/wiki/" target="_blank">GitHub Wiki</a>', 'knd');?></li>
                <li><?php _e('Source code — <a href="https://github.com/Teplitsa/kandinsky/" target="_blank">GitHub</a>', 'knd');?></li>
                <li><?php _e('Developers’ <a href="https://t.me/joinchat/AAAAAENN3prSrvAs7KwWrg">Telegram-channel</a> (in Russian)', 'knd');?></li>
            </ul>
            <p><?php _e('If you need personalized (free during the testing period) consultations from the theme developers, please feel free to write at <a href="mailto:support@te-st.ru" target="_blank">support@te-st.ru</a> or <a href="https://github.com/Teplitsa/kandinsky/issues/" target="_blank">leave a ticket at GitHub</a>.', 'knd');?></p>

            <p class="envato-setup-actions step">
                <a href="<?php echo esc_url($this->get_next_step_link());?>" class="button-primary button button-large button-next"><?php esc_html_e("OK, I've got it!", 'knd'); ?></a>
            </p>

			<?php
		}

		public function step_ready_view() {

			update_option('knd_setup_complete', time());?>

			<h1><?php _e('Yay! Your website is ready!', 'knd');?></h1>

			<p><?php _e('Congratulations! You’re doing really good #success! You’ve successfully installed and set up your Kandinsky theme. You need, however, to do a little bit more.', 'knd');?></p>
            <p><?php _e('As a part of the installation process, we’ve added some test content of an imaginary organization that you will need to edit (we’ve provided the recommendations on how to make great content).', 'knd');?></p>
            <p><?php _e('Moreover, you need to set up few additional plug-ins for the optimal work of your site (don’t worry, our recommendations will help you).', 'knd');?></p>

			<div class="envato-setup-next-steps">
				<div class="envato-setup-next-steps-first">
					<ul>
						<li class="setup-product">
                            <a class="button button-primary button-large" href="<?php echo admin_url();?>">
                                <?php _e('Continue the set-up', 'knd');?>
                            </a>
						</li>
						<li class="setup-product">
                            <a class="button button-next button-large" href="<?php echo home_url();?>">
                                <?php _e('View your new website!', 'knd');?>
                            </a>
						</li>
					</ul>
				</div>
				<div class="envato-setup-next-steps-last">
					<img src="https://media.giphy.com/media/XreQmk7ETCak0/giphy.gif" alt="<?php _e('#success', 'knd');?>">
				</div>
			</div>
			<?php
		}

		/*public function ajax_notice_handler() {
			check_ajax_referer( 'dtnwp-ajax-nonce', 'security' );
		}

		public function admin_theme_auth_notice() {


			if(function_exists('envato_market')) {
				$option = envato_market()->get_options();

				$envato_items = get_option( 'envato_setup_wizard', array() );

				if ( !$option || empty($option['oauth']) || empty( $option['oauth'][ $this->envato_username ] ) || empty($envato_items) || empty($envato_items['items']) || !envato_market()->items()->themes( 'purchased' )) {

					// we show an admin notice if it hasn't been dismissed
					$dissmissed_time = get_option('dtbwp_update_notice', false );

					if ( ! $dissmissed_time || $dissmissed_time < strtotime('-7 days') ) {
						// Added the class "notice-my-class" so jQuery pick it up and pass via AJAX,
						// and added "data-notice" attribute in order to track multiple / different notices
						// multiple dismissible notice states ?>
                        <div class="notice notice-warning notice-dtbwp-themeupdates is-dismissible">
                            <p><?php
                            _e( 'Please activate ThemeForest updates to ensure you have the latest version of this theme.' );
                                ?></p>
                            <p>
                            <?php printf( __( '<a class="button button-primary" href="%s">Activate Updates</a>' ),  esc_url($this->get_oauth_login_url( admin_url( 'admin.php?page=' . envato_market()->get_slug() . '' ) ) ) ); ?>
                            </p>
                        </div>
                        <script type="text/javascript">
                            jQuery(function($) {
                                $( document ).on( 'click', '.notice-dtbwp-themeupdates .notice-dismiss', function () {
                                    $.ajax( ajaxurl,
                                        {
                                            type: 'POST',
                                            data: {
                                                action: 'dtbwp_update_notice_handler',
                                                security: '<?php echo wp_create_nonce( "dtnwp-ajax-nonce" ); ?>'
                                            }
                                        } );
                                } );
                            });
                        </script>
					<?php }

				}
			}



		}*/
		/**
		 * @param $array1
		 * @param $array2
		 *
		 * @return mixed
		 *
		 *
		 * @since    1.1.4
		 */
		private function _array_merge_recursive_distinct( $array1, $array2 ) {
			$merged = $array1;
			foreach ( $array2 as $key => &$value ) {
				if ( is_array( $value ) && isset( $merged [ $key ] ) && is_array( $merged [ $key ] ) ) {
					$merged [ $key ] = $this->_array_merge_recursive_distinct( $merged [ $key ], $value );
				} else {
					$merged [ $key ] = $value;
				}
			}

			return $merged;
		}

//		public function render_oauth_login_description_callback() {
//			echo 'If you have purchased items from ' . esc_html( $this->envato_username ) . ' on ThemeForest or CodeCanyon please login here for quick and easy updates.';
//
//		}

    /*
		public function render_oauth_login_fields_callback() {
			$option = envato_market()->get_options();
			?>
			<div class="oauth-login" data-username="<?php echo esc_attr( $this->envato_username ); ?>">
				<a href="<?php echo esc_url( $this->get_oauth_login_url( admin_url( 'admin.php?page=' . envato_market()->get_slug() . '#settings' ) ) ); ?>"
				   class="oauth-login-button button button-primary">Login with Envato to activate updates</a>
			</div>
			<?php
		}
    */

		/// a better filter would be on the post-option get filter for the items array.
		// we can update the token there.

//		public function get_oauth_login_url( $return ) {
//			return $this->oauth_script . '?bounce_nonce=' . wp_create_nonce( 'envato_oauth_bounce_' . $this->envato_username ) . '&wp_return=' . urlencode( $return );
//		}

		public static function cleanFilePath( $path ) {
			$path = str_replace( '', '', str_replace( array( '\\', '\\\\', '//' ), '/', $path ) );
			if ( $path[ strlen( $path ) - 1 ] === '/' ) {
				$path = rtrim( $path, '/' );
			}

			return $path;
		}

		public function is_submenu_page() {
			return !!$this->parent_slug;
		}
	}

}// if !class_exists

/**
 * Loads the main instance of Envato_Theme_Setup_Wizard to have
 * ability extend class functionality
 *
 * @since 1.1.1
 * @return object Envato_Theme_Setup_Wizard
 */
add_action('after_setup_theme', 'envato_theme_setup_wizard');
if( !function_exists('envato_theme_setup_wizard') ) {
    function envato_theme_setup_wizard() {

        if( !is_admin() ) {
            return;
        }

        Envato_Theme_Setup_Wizard::get_instance();

    }
}
//add_action('init', 'envato_theme_setup_wizard', 1); // No admin_init here!

// To remove the notice from Disable Comments plugin:
add_action('wp_loaded', function(){
    if(
        class_exists('Disable_Comments') &&
        has_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'))
    ) {
        remove_action('admin_print_footer_scripts', array(Disable_Comments::get_instance(), 'discussion_notice'));
    }
}, 100);