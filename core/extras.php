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

	$allowed_html['img']['aria-hidden'] = true;

	return $allowed_html;
}
add_filter( 'wp_kses_allowed_html', 'knd_kses_allowed_html' );

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
function knd_auto_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'knd_auto_excerpt_more' );

function knd_custom_excerpt_length( $l ) {
	return 30;
}
add_filter( 'excerpt_length', 'knd_custom_excerpt_length' );

/** inject */
function knd_custom_excerpt_more( $output ) {
	
	if ( is_singular() || is_search() )
		return $output;
	
	$output .= knd_continue_reading_link();
	return $output;
}
add_filter( 'get_the_excerpt', 'knd_custom_excerpt_more' );

/** Current URL */
if ( ! function_exists( 'knd_current_url' ) ) {

	function knd_current_url( ) {

		$request_uri = '';

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = $_SERVER['REQUEST_URI'];
		}

		$page_url = home_url( $request_uri );

		return $page_url;
	}
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
add_action( 'after_switch_theme', 'knd_after_theme_activation' );

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

/**
 * Add support taxonomy post_tag for post type leyka_campaign
 */
function knd_add_tags_to_leyka_campaign() {
	register_taxonomy_for_object_type('post_tag', 'leyka_campaign');
}
add_action('init','knd_add_tags_to_leyka_campaign');

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
	$mimes['svgz'] = 'image/svg+xml';
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

/**
 * Filters the list of allowed file extensions when sideloading an image from a URL.
 *
 * @param string[] $allowed_extensions Array of allowed file extensions.
 * @param string   $file               The URL of the image to download.
 */
function knd_image_sideload_extensions( $allowed_extensions ){
	$allowed_extensions[] = 'svg';
	return $allowed_extensions;
}
add_filter( 'image_sideload_extensions', 'knd_image_sideload_extensions' );

add_action( 'knd_before_wp_footer', 'knd_button_totop' );
add_action( 'knd_before_wp_footer', 'knd_screen_reader_alert' );
