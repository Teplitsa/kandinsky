<?php /**
 * Class for CSSJS handling
 **/

if ( ! defined( 'WPINC' ) )
	die();

class KND_CssJs {

	private static $_instance = null;

	private $manifest = null;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ), 30 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 30 );
		add_action( 'init', array( $this, 'disable_wp_emojicons' ) );

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

	/** revisions **/
	private function get_manifest() {
		if ( null === $this->manifest ) {
			$manifest_path = get_template_directory() . '/assets/rev/rev-manifest.json';

			if ( file_exists( $manifest_path ) ) {
				$this->manifest = json_decode( file_get_contents( $manifest_path ), TRUE );
			} else {
				$this->manifest = array();
			}
		}

		return $this->manifest;
	}

	public function get_rev_filename( $filename ) {
		$manifest = $this->get_manifest();
		if ( array_key_exists( $filename, $manifest ) ) {
			return $manifest[$filename];
		}

		return $filename;
	}

	/** Load css */
	public function load_styles() {
		$url = get_template_directory_uri();
		$style_dependencies = array();

		// design.
		wp_enqueue_style( 'frl-design', $url . '/assets/rev/' . $this->get_rev_filename( 'bundle.css' ), $style_dependencies, null );
		wp_dequeue_style( 'leyka-plugin-styles' );
	}

	/* front */
	public function load_scripts() {
		$url = get_template_directory_uri();

		// jQuery.
		$script_dependencies[] = 'jquery'; // adjust gulp if we want it in footer.

		// front
		wp_enqueue_script(
			'frl-front',
			$url . '/assets/rev/' . $this->get_rev_filename( 'bundle.js' ),
			$script_dependencies,
			null,
			true );

		wp_localize_script( 'frl-front', 'frontend',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'langContinue' => esc_html__('Continue', 'knd'),
			)
		);
	}

	/** disable emojji **/
	public function disable_wp_emojicons() {

		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}

	/* admin styles - moved to news system also */
	public function load_admin_scripts() {
		$url = get_template_directory_uri();

		wp_enqueue_script( 'knd-admin', $url . '/assets/rev/' . $this->get_rev_filename( 'admin.js' ), array( 'jquery' ), null );
		wp_enqueue_style( 'knd-admin', $url . '/assets/rev/' . $this->get_rev_filename( 'admin.css' ), array(), null );

		/* Translatable string */
		wp_localize_script('knd-admin', 'knd',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'knd-nonce' ),
			)
		);

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
