<?php
/**
 * Class for CSSJS handling
 **/

class FRL_CssJs {
	
	private static $_instance = null;	
	private $manifest = null;
	
	private function __construct() {
		
		
		add_action('wp_enqueue_scripts', array($this, 'load_styles'), 30);
		add_action('wp_enqueue_scripts', array($this, 'load_scripts'), 30);
		add_action('init', array($this, 'disable_wp_emojicons'));
		
		add_action('admin_enqueue_scripts',  array($this, 'load_admin_scripts'), 30);
		add_action('login_enqueue_scripts',  array($this, 'load_login_scripts'), 30);
		
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }
		
        return self::$_instance;
    }
	
	/** revisions **/
	private function get_manifest() {
		
		if(null === $this->manifest) {
			$manifest_path = get_template_directory().'/assets/rev/rev-manifest.json';

			if (file_exists($manifest_path)) {
				$this->manifest = json_decode(file_get_contents($manifest_path), TRUE);
			} else {
				$this->manifest = array();
			}
		}
		
		return $this->manifest;
	}
	
	
	public function get_rev_filename($filename) {
		
		$manifest = $this->get_manifest();
		if (array_key_exists($filename, $manifest)) {
			return $manifest[$filename];
		}
	
		return $filename;
	}

	/* load css */
	function load_styles() {

		$url = get_template_directory_uri();
		$style_dependencies = array();

		// fonts
		wp_enqueue_style(
			'rdc-fonts',
			'//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,700,700italic|Roboto+Condensed:400,700&subset=latin,cyrillic',
			$style_dependencies,
			null
		);
		$style_dependencies[] = 'rdc-fonts';

		// design
		wp_enqueue_style(
			'frl-design',
			$url.'/assets/rev/'.$this->get_rev_filename('bundle.css'),
			$style_dependencies,
			null
		);

		wp_dequeue_style('leyka-plugin-styles');		
	}

	/* front */
	public function load_scripts() {		

		$url = get_template_directory_uri();

		// jQuery
		$script_dependencies[] = 'jquery'; //adjust gulp if we want it in footer	

		/*if(defined('LEYKA_VERSION') && wp_script_is('leyka-public', 'enqueued' )) {

			wp_dequeue_script('leyka-cp');		
			wp_dequeue_script('leyka-public');
			wp_dequeue_script('leyka-modal');

			wp_deregister_script('leyka-public');
			wp_enqueue_script(
			   'leyka-public',
				LEYKA_PLUGIN_BASE_URL.'js/public.js', array('jquery'),
				LEYKA_VERSION,
				true
			);
			wp_enqueue_script('leyka-cp');
			
			
            leyka()->localize_scripts(); // localize leyka scripts anew
		}*/

		// front
		wp_enqueue_script(
			'frl-front',
			$url.'/assets/rev/'.$this->get_rev_filename('bundle.js'),
			$script_dependencies,
			null,
			true
		);

		wp_localize_script('frl-front', 'frontend', array(
			'ajaxurl' => admin_url('admin-ajax.php')			
		));
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
			
		wp_enqueue_style('rdc-admin', $url.'/assets/rev/'.$this->get_rev_filename('admin.css'), array(), null);				
	}
	
	/* login style - make it inline ? */
	public function load_login_scripts(){
	
	?>
		<style>
			#login h1 a {
				background-size: contain;
				background-image: url('<?php echo get_template_directory_uri();?>/assets/img/tree.png');
				background-image: url('<?php echo get_template_directory_uri();?>/assets/img/tree.svg');
				width: 110px;
				height: 110px;
			}
			.login input[type="text"]:focus,
			.login input[type="password"]:focus {
				border-color: #03E21C;
				-webkit-box-shadow: 0 0 2px 0 rgba(3,226,28, 0.75);
				box-shadow: 0 0 2px 0 rgba(3,226,28, 0.75);
			}
			#wp-submit {
				background: #099724;
				border-color: #099724;
				text-shadow: 0 0 0 transparent;				
				-webkit-box-shadow: 1px 1px 0 0 #127818;
				box-shadow: 1px 1px 0 0 #127818;
			}
			#wp-submit:hover, #wp-submit:focus, #wp-submit:active {
				background: #2BCC4A;
				border-color: #2BCC4A;
				text-shadow: 0 0 0 transparent;
			}
			
			#login .message {
				border-left-color: #03E21C;
			}
			
			#login #backtoblog a:hover, .login h1 a:hover,
			#login #backtoblog a:focus, .login h1 a:focus,
			#login #backtoblog a:active, .login h1 a:active {
				outline: none;
				color: #2BCC4A;
				-webkit-box-shadow: 0 0 0 rgba(255,255,255, 0);
				box-shadow: 0 0 0 rgba(255,255,255, 0);				
			}
			
			#login #nav a {
				display: inline-block;
				color: #444;
				border-bottom: 1px solid #2BCC4A;
				-webkit-box-shadow: inset 0 0 0 rgba(255,255,255, 0);
				box-shadow: inset 0 -2px 0 #2BCC4A;
				-webkit-transition: all 0.3s ease;
				-moz-transition: all 0.3s ease;
				-ms-transition: all 0.3s ease;
				-o-transition: all 0.3s ease;
				transition: all 0.3s ease;
			}
			
			.login #nav a:hover, .login #nav a:focus, .login #nav a:active {
				color: #111;
				background: #2BCC4A;
			}
			
			#login_error a {
				color: #dd3d36;
			}
		</style>
	<?php
	}
	
	
	function dequeue_wpcf7_styles(){
		wp_dequeue_style( 'contact-form-7' );
	}
} //class

FRL_CssJs::get_instance();
