<?php
/**
 * Assets
 *
 * All enqueues of scripts and styles.
 *
 * @package Kandinsky
 */




class KND_CssJs {

	private static $_instance = null;

	private $manifest = null;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 30 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		

		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ), 30 );

		//add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ), 10 );
		add_action('wp_head', array($this, 'inline_styles_for_teplitsa_plugins'), 50);
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/** Load css */
	public function load_styles() {

		// FancyBox.
		wp_register_style( 'fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css', array(), '3.5.7' );

		// Styles.
		wp_enqueue_style( 'knd', get_template_directory_uri() . '/assets/css/style.css', array( 'fancybox' ), knd_get_theme_version() );

		// Dequeue leyka styles.
		wp_dequeue_style( 'leyka-plugin-styles' );
	}

	/* front */
	public function load_scripts() {
		$url = get_template_directory_uri();

		// jQuery.
		$dependencies = array(
			'jquery',
			'imagesloaded',
			'fancybox',
		);

		// Register theme scripts.
		wp_register_script( 'fancybox', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );

		// Scripts.
		wp_enqueue_script( 'knd', get_template_directory_uri() . '/assets/js/scripts.js', $dependencies, knd_get_theme_version(), true );

		wp_localize_script( 'knd', 'frontend',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'langContinue' => esc_html__( 'Continue', 'knd' ),
			)
		);
	}

	/* admin styles - moved to news system also */
	public function load_admin_scripts() {

		// Admin Styles.
		wp_enqueue_style( 'knd-admin', get_template_directory_uri() . '/assets/css/admin.css', null, knd_get_theme_version() );

		// Admin Scripts.
		wp_enqueue_script( 'knd-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'wp-i18n' ), knd_get_theme_version() );

		/* Translatable string */
		wp_localize_script('knd-admin', 'knd',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'knd-nonce' ),
			)
		);

		wp_set_script_translations( 'knd-admin', 'knd', get_template_directory() . '/lang' );

	}

	public function inline_styles_for_teplitsa_plugins(){

		$main_color = knd_get_main_color();
		$dark_color = knd_color_luminance($main_color, -0.1);
		$light_color = knd_color_luminance($main_color, 0.2);
		
		$extra_light_colors = array(
			'#00bcd4' => '#d1f4fa', // color line
			'#de0055' => '#ffcce0', // mstb
			'#f02c80' => '#fde7f1', // dubrovino
		);
		$extra_light_color = !empty($extra_light_colors[strtolower($main_color)]) ? $extra_light_colors[strtolower($main_color)] : $light_color;
		
		$extra_dark_colors = array(
		);
		$extra_dark_color = !empty($extra_dark_colors[strtolower($main_color)]) ? $extra_dark_colors[strtolower($main_color)] : $dark_color;
		
		?>
		<style>
			:root {
				--zoospas-color-main:  <?php echo $main_color;?>;
				--zoospas-main-dark:   <?php echo $extra_dark_color;?>;
				--zoospas-main-light:  <?php echo $extra_light_color;?>;
			}
		</style>
		<?php
	}

} // class

KND_CssJs::get_instance();


/**
 * Disable emojji.
 */
function knd_disable_wp_emojicons() {
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}
//add_action( 'init', 'knd_disable_wp_emojicons' );
