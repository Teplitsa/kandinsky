<?php /**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bb
 */

if ( ! defined( 'WPINC' ) )
	die();

/** Default filters **/
add_filter( 'knd_the_content', 'wptexturize' );
add_filter( 'knd_the_content', 'convert_smilies' );
add_filter( 'knd_the_content', 'convert_chars' );
add_filter( 'knd_the_content', 'wpautop' );
add_filter( 'knd_the_content', 'shortcode_unautop' );
add_filter( 'knd_the_content', 'do_shortcode' );
add_filter( 'knd_the_content', 'wp_kses_post', 5 );


add_filter( 'knd_the_title', 'wptexturize' );
add_filter( 'knd_the_title', 'convert_chars' );
add_filter( 'knd_the_title', 'trim' );

global $wp_embed;
add_filter( 'knd_entry_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'knd_entry_the_content', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'knd_entry_the_content', 'wptexturize' );
add_filter( 'knd_entry_the_content', 'convert_smilies' );
add_filter( 'knd_entry_the_content', 'convert_chars' );
add_filter( 'knd_entry_the_content', 'knd_entry_wpautop' );
add_filter( 'knd_entry_the_content', 'shortcode_unautop' );
add_filter( 'knd_entry_the_content', 'prepend_attachment' );
add_filter( 'knd_entry_the_content', 'knd_force_https' );
add_filter( 'knd_entry_the_content', 'wp_make_content_images_responsive' );
//add_filter( 'knd_entry_the_content', 'do_shortcode', 11 );
add_filter( 'knd_entry_the_content', 'wp_kses_post', 7 );

/* jpeg compression */
add_filter( 'jpeg_quality', create_function( '', 'return 95;' ) );

/* temp fix for wpautop in posts */
function knd_entry_wpautop( $content ) {
	if ( false === strpos( $content, '[page_section' ) ) {
		$content = wpautop( $content );
	}
	
	return $content;
}

/** Custom excerpts  **/

/** more link */
function knd_continue_reading_link() {
	$more = knd_get_more_text();
	return '&nbsp;<a href="' . esc_url( get_permalink() ) . '"><span class="meta-nav">' . $more . '</span></a>';
}

function knd_get_more_text() {
	return esc_html__( 'More', 'knd' ) . "&nbsp;&raquo;";
}

/** excerpt filters  */
add_filter( 'excerpt_more', 'knd_auto_excerpt_more' );

function knd_auto_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_length', 'knd_custom_excerpt_length' );

function knd_custom_excerpt_length( $l ) {
	return 30;
}

/** inject */
add_filter( 'get_the_excerpt', 'knd_custom_excerpt_more' );

function knd_custom_excerpt_more( $output ) {
	
	if ( is_singular() || is_search() )
		return $output;
	
	$output .= knd_continue_reading_link();
	return $output;
}

/** Current URL  **/
if ( ! function_exists( 'knd_current_url' ) ) {

	function knd_current_url() {
		$pageURL = 'http';
		
		if ( isset( $_SERVER["HTTPS"] ) && ( $_SERVER["HTTPS"] == "on" ) ) {
			$pageURL .= "s";
		}
		$pageURL .= "://";

		if(isset($_SERVER["SERVER_NAME"])) {
			$pageURL .= $_SERVER["SERVER_NAME"];
		}
			
		if ( isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80" ) {
			
			$pageURL .= ":" . $_SERVER["SERVER_PORT"];
			
		}
		
		if(isset($_SERVER["REQUEST_URI"])) {
			$pageURL .= $_SERVER["REQUEST_URI"];
		}
		
		return $pageURL;
	}
}

/** Extract posts IDs from query **/
function knd_get_posts_ids_from_query( WP_Query $query ) {
	$ids = array();
	if ( ! $query->have_posts() )
		return $ids;
	
	foreach ( $query->posts as $qp ) {
		$ids[] = $qp->ID;
	}
	
	return $ids;
}

function knd_get_post_id_from_posts( array $posts ) {
	$ids = array();
	foreach ( $posts as $p ) {
		if ( ! empty( $p ) && ! empty( $p->ID ) ) {
			$ids[] = $p->ID;
		}
	}
	
	return $ids;
}

function knd_get_term_id_from_terms( array $terms ) {
	$ids = array();
	foreach ( $terms as $t ) {
		if ( ! empty( $t ) && ! empty( $t->term_id ) ) {
			$ids[] = $t->term_id;
		}
	}
	
	return $ids;
}

/** Favicon **/
function knd_favicon() {
	if ( has_site_icon() ) {
		wp_site_icon();
	}
}
add_action( 'wp_head', 'knd_favicon', 1 );
add_action( 'admin_head', 'knd_favicon', 1 );
add_action( 'login_head', 'knd_favicon', 1 );

/** Add feed link **/
add_action( 'wp_head', 'knd_feed_link' );

function knd_feed_link() {
	$name = get_bloginfo( 'name' );
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( $name ) . '" href="' .
		 esc_url( get_feed_link() ) . "\" />\n";
}

/** Adds custom classes to the array of body classes **/
add_filter( 'body_class', 'knd_body_classes' );

function knd_body_classes( $classes ) {
	if ( is_page() ) {
		$qo = get_queried_object();
		$classes[] = 'slug-' . $qo->post_name;
	}
	
	$classes[] = 'plot-' . knd_get_theme_mod( 'knd_site_scenario' );
	
	$mod = knd_get_theme_mod( 'knd_custom_logo_mod', 'image_only' );
	$classes[] = 'logomod-' . $mod;
	
	return $classes;
}

/** Admin bar **/
add_action( 'wp_head', 'knd_adminbar_corrections' );
add_action( 'admin_head', 'knd_adminbar_corrections' );

function knd_adminbar_corrections() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	add_action( 'admin_bar_menu', 'knd_adminbar_logo', 10 );
}

function knd_adminbar_logo( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( 
		array( 'id' => 'wp-logo', 'title' => '<span class="ab-icon"></span>', 'href' => '' ) );
}

add_action( 'wp_footer', 'knd_adminbar_voices' );
add_action( 'admin_footer', 'knd_adminbar_voices' );

function knd_adminbar_voices() {
	?>
<script>	
	jQuery(document).ready(function($){		
		if ('speechSynthesis' in window) {
			var speech_voices = window.speechSynthesis.getVoices(),
				utterance  = new SpeechSynthesisUtterance();
				
				function set_speach_options() {
					speech_voices = window.speechSynthesis.getVoices();
					utterance.text = "I can't lie to you about your chances, but... you have my sympathies.";
					utterance.lang = 'en-GB'; 
					utterance.volume = 0.9;
					utterance.rate = 0.9;
					utterance.pitch = 0.8;
					utterance.voice = speech_voices.filter(function(voice) { return voice.name == 'Google UK English Male'; })[0];
				}
								
				window.speechSynthesis.onvoiceschanged = function() {				
					set_speach_options();
				};
								
				$('#wp-admin-bar-wp-logo').on('click', function(e){
					
					if (!utterance.voice || utterance.voice.name != 'Google UK English Male') {
						set_speach_options();
					}
					speechSynthesis.speak(utterance);
				});
		}			
	});
</script>
<?php
}

/** == Filter to ensure https for local URLs in content == **/
function knd_force_https( $content ) {
	if ( ! is_ssl() )
		return $content;
		
		// protocol relative internal links
	$https_home = home_url( '', 'https' );
	$http_home = home_url( '', 'http' );
	$rel_home = str_replace( 'http:', '', $http_home );
	
	$content = str_replace( $http_home, $rel_home, $content );
	$content = str_replace( $https_home, $rel_home, $content );
	
	// protocol relative url in src (for external links)
	preg_match_all( '@src="([^"]+)"@', $content, $match );
	
	if ( ! empty( $match ) && isset( $match[1] ) ) {
		foreach ( $match[1] as $i => $test_url ) {
			if ( false !== strpos( $test_url, 'http:' ) ) {
				$replace_url = str_replace( 'http:', '', $test_url );
				$content = str_replace( $test_url, $replace_url, $content );
			}
		}
	}
	
	return $content;
}

/** filter search request **/
function knd_filter_search_query( $s ) {
	$s = preg_replace( "/&#?[a-z0-9]{2,8};/i", "", $s );
	$s = preg_replace( '/[^a-zA-ZА-Яа-я0-9-\s]/u', '', $s );
	$s = mb_strcut( $s, 0, 140, 'utf-8' );
	
	if ( 4 > mb_strlen( $s, 'utf-8' ) ) {
		$s = '';
	}
	
	return $s;
}

function knd_get_social_media_supported() {
	return array( 
		'vk' => array( 
			'label' => esc_html__( 'VKontakte', 'knd' ), 
			'description' => esc_html__( 'E.g., https://vk.com/club0123456789', 'knd' ) ), 
		'ok' => array( 
			'label' => esc_html__( 'Odnoklassniki', 'knd' ), 
			'description' => esc_html__( 'E.g., https://ok.ru/profile/0123456789', 'knd' ) ), 
		'facebook' => array( 
			'label' => esc_html__( 'Facebook', 'knd' ), 
			'description' => esc_html__( 'E.g., https://www.facebook.com/your-organization-page', 'knd' ) ), 
		'instagram' => array( 
			'label' => esc_html__( 'Instagram', 'knd' ), 
			'description' => esc_html__( 'E.g., https://www.instagram.com/your-organization-page', 'knd' ) ), 
		'twitter' => array( 
			'label' => esc_html__( 'Twitter', 'knd' ), 
			'description' => esc_html__( 'E.g., https://twitter.com/your-organization-page', 'knd' ) ), 
		'telegram' => array( 
			'label' => esc_html__( 'Telegram', 'knd' ), 
			'description' => esc_html__( 'E.g., https://tlgrm.ru/channels/@your-organization-page', 'knd' ) ), 
		'youtube' => array( 
			'label' => esc_html__( 'YouTube', 'knd' ), 
			'description' => esc_html__( 'E.g., https://youtube.com/channel/your-organization-channel', 'knd' ) ) );
}

add_action( 'after_switch_theme', 'knd_after_theme_activation' );

function knd_after_theme_activation() {
	flush_rewrite_rules( false );
	
	if ( ! get_transient( '_knd_activation_redirect_done' ) ) {
		
		set_transient( '_knd_activation_redirect_done', true );
		wp_safe_redirect( KND_SETUP_WIZARD_URL );
		
		exit();
	}
}

add_action( 'init', 'knd_remove_scenario_unzipped_dir' );

function knd_remove_scenario_unzipped_dir() {

	// Attempt to remove a scenario unzipped folder only if user is going from wizard to some other page:
	if ( ! is_main_query() || wp_doing_ajax() || stripos( knd_current_url(), 'themes.php' ) !== false ||
		 stripos( knd_current_url(), 'knd-setup-wizard' ) !== false || !is_admin() || !current_user_can('administrator') ) {
		return;
	}
	
	if(!function_exists('submit_button')) {
		return;
	}

	$scenario_name = knd_get_theme_mod( 'knd_site_scenario' );
	
	if ( ! $scenario_name ) {
		return;
	}

	$scenario_name = knd_get_wizard_plot_names( $scenario_name );

	if ( is_string( $scenario_name ) ) {

        $destination = wp_upload_dir();
        $unzipped_dir = "{$destination['path']}/kandinsky-text-" . $scenario_name . "-master";

		if ( !Knd_Filesystem::get_instance()->rmdir($unzipped_dir, true) ) {
            throw new Exception(sprintf(__('Old import files cleanup FAILED: %s.', 'knd'), $destination["path"]));
		}
	}
}

add_action('init','knd_add_tags_to_leyka_campaign');

function knd_add_tags_to_leyka_campaign() {
	register_taxonomy_for_object_type('post_tag', 'leyka_campaign');
}

// change hidden metaboxes
add_filter('default_hidden_meta_boxes', 'knd_change_hidden_meta_boxes', 10, 2);
function knd_change_hidden_meta_boxes($hidden, $screen) {
	
	if(($key = array_search('postexcerpt', $hidden)) !== false) {
		unset($hidden[$key]);
	}
	
	return $hidden;
}

/** A singleton class to incapsulate WP_Filesystem instance creation */
class Knd_Filesystem {

    protected static $_instance;
    public $_filesystem;

    protected function __construct() {

        $url = knd_current_url();
        $fields = array_keys($_POST); // Extra fields to pass to WP_Filesystem
        
        if(false === ($credentials = request_filesystem_credentials(esc_url_raw($url), '', false, false, $fields))) {
            return; // Stop the normal page form from displaying, credential request form will be shown.
        }

        // Now we have some credentials, setup WP_Filesystem
        if( !WP_Filesystem($credentials) ) { // Our credentials were no good, ask the user for them again

            request_filesystem_credentials(esc_url_raw($url), '', true, false, $fields);
            return;

        }

        /** @var WP_Filesystem_Base $wp_filesystem */
        global $wp_filesystem;
        $this->_filesystem = $wp_filesystem;

    }

    public static function get_instance() {
        if ( ! isset( self::$_instance ) && ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance->_filesystem; //self::$_instance;
    }
}


function knd_get_theme_mod($name, $default = false) {
	$option_val = get_theme_mod($name, $default);
	
	if($option_val === $default) {
		$mods = get_option( "theme_mods_kandinsky-master");
		if (!empty($mods) && isset($mods[$name])) {
			$option_val = $mods[$name];
		}		
	}
	return $option_val;
}