<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Kandinsky
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

/**
 * Add allowed html iframe
 *
 * @param array $allowed_html Allowed HTML elements.
 */
function knd_kses_allowed_html( $allowed_html ) {
	$allowed_html['iframe'] = array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'class'           => true,
		'id'              => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
		'style'           => true,
	);
	return $allowed_html;
}
add_filter( 'wp_kses_allowed_html', 'knd_kses_allowed_html' );

/* jpeg compression */
add_filter('jpeg_quality', function(){ return 95; });

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

/** Current URL */
if ( ! function_exists( 'knd_current_url' ) ) {

	function knd_current_url() {
		$page_url = 'http';

		if ( isset( $_SERVER['HTTPS'] ) && ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 'On' ) ) {
			$page_url .= 's';
		}
		$page_url .= '://';

		if ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$page_url .= $_SERVER['SERVER_NAME'];
		}

		if ( isset( $_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80' ) {
			$page_url .= ':' . $_SERVER['SERVER_PORT'];
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$page_url .= $_SERVER['REQUEST_URI'];
		}

		return $page_url;
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

	// Disable wizard on activate theme version 2 after version 1.
	// Deprecated. Must be deleted in future.
	if ( get_option( 'theme_mods_kandinsky-master' ) ) {
		set_transient( '_knd_activation_redirect_done', true );
	}

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

function knd_show_notification_bubble( $menu ) {
	$notif_count = knd_get_admin_notif_count();

	if ( $notif_count > 0 ) {
		foreach ( $menu as $menu_key => $menu_data ) {
			if ( $menu_data[2] == 'knd-setup-wizard' ) {
				$menu[$menu_key][0] .= " <span class='update-plugins' title='" . esc_attr__( 'Recommended plugins to install', 'knd' ) . "'><span class='plugin-count'>" . esc_html( $notif_count ) . '</span></span>';
			}
		}
	}

	return $menu;
}
add_filter( 'add_menu_classes', 'knd_show_notification_bubble' );

function knd_get_admin_notif_count() {
	$not_installed_plugins = 0;

	if ( is_admin() ) {

		if ( ! is_plugin_active( 'leyka/leyka.php' ) ) {
			$not_installed_plugins += 1;
		}

		if ( ! is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			$not_installed_plugins += 1;
		}

		if ( ! is_plugin_active( 'cyr3lat/cyr-to-lat.php' ) ) {
			$not_installed_plugins += 1;
		}

		if ( ! is_plugin_active( 'disable-comments/disable-comments.php' ) ) {
			$not_installed_plugins += 1;
		}
	}

	return $not_installed_plugins;
}

function knd_sanitize_text($text) {
	return sanitize_text_field($text);
}

/**
 * Allow SVG file upload
 *
 * @param array $mimes Mime types.
 */
function knd_svg_upload_mimes( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_action( 'upload_mimes', 'knd_svg_upload_mimes' );

/**
 * Display SVG in attachment
 *
 * @param array $response Response.
 * @param array $attachment Attachment.
 */
function knd_svgs_response_for_svg( $response, $attachment ) {

	if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

		$svg_path = get_attached_file( $attachment->ID );

		if ( ! file_exists( $svg_path ) ) {
			// If SVG is external, use the URL instead of the path.
			$svg_path = $response['url'];
		}

		$response['sizes'] = array(
			'full' => array(
				'url' => $response['url'],
			),
		);

	}

	return $response;

}
add_filter( 'wp_prepare_attachment_for_js', 'knd_svgs_response_for_svg', 10, 2 );
