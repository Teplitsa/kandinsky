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

		$css_dependencies = array();

		// FancyBox.
		if ( ! wp_style_is( 'fancybox-for-wp' ) ) {
			wp_register_style( 'knd-fancybox', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css', array(), '3.5.7' );
			$css_dependencies[] = 'knd-fancybox';
		}

		// Styles.
		wp_enqueue_style( 'knd', get_template_directory_uri() . '/assets/css/style.css', $css_dependencies, knd_get_theme_version() );

		// Dequeue leyka styles.
		wp_dequeue_style( 'leyka-plugin-styles' );
	}

	/* front */
	public function load_scripts() {

		// jQuery.
		$dependencies = array(
			'jquery',
			'imagesloaded',
			'flickity',
		);

		// Register Flickity script.
		wp_register_script( 'flickity', get_template_directory_uri() . '/assets/js/flickity.pkgd.min.js', array( 'jquery' ), '2.2.2', true );

		// Register Fancybox script.
		if ( ! wp_script_is( 'fancybox-for-wp' ) ) {
			wp_register_script( 'knd-fancybox', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
			$dependencies[] = 'knd-fancybox';
		}

		// Scripts.
		wp_enqueue_script( 'knd', get_template_directory_uri() . '/assets/js/scripts.js', $dependencies, knd_get_theme_version(), true );

		$localize_script = array(
			'i18n' => array(
				'a11y' => array(
					'expand'            => esc_attr__('Expand child menu', 'knd'),
					'collapse'          => esc_attr__('Collapse child menu', 'knd'),
					'offCanvasIsOpen'   => esc_attr__('Off-Canvas is open', 'knd'),
					'offCanvasIsClosed' => esc_attr__('Off-Canvas is closed', 'knd'),
				),
			),
		);

		if ( is_user_logged_in() ) {
			$localize_script['ajaxurl'] = admin_url( 'admin-ajax.php' );
			$localize_script['nonce']   = wp_create_nonce( 'knd-nonce' );
		}

		wp_localize_script( 'knd', 'knd', $localize_script );

		// Threaded comment reply styles.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) && get_theme_mod( 'post_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/* admin styles - moved to news system also */
	public function load_admin_scripts() {

		// Admin Styles.
		wp_enqueue_style( 'knd-admin', get_template_directory_uri() . '/assets/css/admin.css', null, knd_get_theme_version() );

		// Admin Scripts.
		wp_enqueue_script( 'knd-admin', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery', 'wp-i18n',  'jquery-ui-core', 'jquery-ui-sortable' ), knd_get_theme_version() );

		/* Translatable string */
		wp_localize_script('knd-admin', '_knd',
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
				--zoospas-color-main:  <?php echo esc_attr( $main_color ); ?>;
				--zoospas-main-dark:   <?php echo esc_attr( $extra_dark_color );?>;
				--zoospas-main-light:  <?php echo esc_attr( $extra_light_color );?>;
			}
		</style>
		<?php
	}

} // class

KND_CssJs::get_instance();
