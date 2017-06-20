<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package bb
 */



/** Default filters **/
add_filter( 'rdc_the_content', 'wptexturize'        );
add_filter( 'rdc_the_content', 'convert_smilies'    );
add_filter( 'rdc_the_content', 'convert_chars'      );
add_filter( 'rdc_the_content', 'wpautop'            );
add_filter( 'rdc_the_content', 'shortcode_unautop'  );
add_filter( 'rdc_the_content', 'do_shortcode' );

add_filter( 'rdc_the_title', 'wptexturize'   );
add_filter( 'rdc_the_title', 'convert_chars' );
add_filter( 'rdc_the_title', 'trim'          );

global $wp_embed;
add_filter( 'rdc_entry_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'rdc_entry_the_content', array( $wp_embed, 'autoembed' ), 8 );
add_filter( 'rdc_entry_the_content', 'wptexturize'                       );
add_filter( 'rdc_entry_the_content', 'convert_smilies'                   );
add_filter( 'rdc_entry_the_content', 'convert_chars'                     );
add_filter( 'rdc_entry_the_content', 'rdc_entry_wpautop'                 );
add_filter( 'rdc_entry_the_content', 'shortcode_unautop'                 );
add_filter( 'rdc_entry_the_content', 'prepend_attachment'                );
add_filter( 'rdc_entry_the_content', 'rdc_force_https'                   );
add_filter( 'rdc_entry_the_content', 'wp_make_content_images_responsive' );
add_filter( 'rdc_entry_the_content', 'do_shortcode', 11                  ); 


/* jpeg compression */
add_filter( 'jpeg_quality', create_function( '', 'return 95;' ) );


/* temp fix for wpautop in posts */
function rdc_entry_wpautop($content){
	
	if(false === strpos($content, '[page_section')){
		$content = wpautop($content);
	}
	
	return $content;
}

 
/** Custom excerpts  **/

/** more link */
function rdc_continue_reading_link() {
	$more = rdc_get_more_text();
	return '&nbsp;<a href="'. esc_url( get_permalink() ) . '"><span class="meta-nav">'.$more.'</span></a>';
}

function rdc_get_more_text(){
	
	return __('More', 'kds')."&nbsp;&raquo;";
}

/** excerpt filters  */
add_filter( 'excerpt_more', 'rdc_auto_excerpt_more' );
function rdc_auto_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_length', 'rdc_custom_excerpt_length' );
function rdc_custom_excerpt_length( $l ) {
	return 30;
}

/** inject */
add_filter( 'get_the_excerpt', 'rdc_custom_excerpt_more' );
function rdc_custom_excerpt_more( $output ) {
	global $post;
	
	if(is_singular() || is_search())
		return $output;
	
	$output .= rdc_continue_reading_link();
	return $output;
}


/** Current URL  **/
if(!function_exists('rdc_current_url')){
function rdc_current_url() {
   
    $pageURL = 'http';
   
    if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {$pageURL .= "s";}
    $pageURL .= "://";
   
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
   
    return $pageURL;
}
}


/** Extract posts IDs from query **/
function rdc_get_posts_ids_from_query($query){
	
	$ids = array();
	if(!$query->have_posts())
		return $ids;
	
	foreach($query->posts as $qp){
		$ids[] = $qp->ID;
	}
	
	return $ids;
}

function rdc_get_post_id_from_posts($posts){
		
	$ids = array();
	if(!empty($posts)){ foreach($posts as $p) {
		$ids[] = $p->ID;
	}}
	
	return $ids;
}

function rdc_get_term_id_from_terms($terms){
		
	$ids = array();
	if(!empty($terms)){ foreach($terms as $t) {
		$ids[] = $t->term_id;
	}}
	
	return $ids;
}


/** Favicon **/
function rdc_favicon(){
	
	$favicon_test = WP_CONTENT_DIR. '/favicon.ico'; //in the root not working don't know why
    if(!file_exists($favicon_test))
        return;
        
    $favicon = content_url('favicon.ico');
	echo "<link href='{$favicon}' rel='shortcut icon' type='image/x-icon' >";
}
add_action('wp_head', 'rdc_favicon', 1);
add_action('admin_head', 'rdc_favicon', 1);
add_action('login_head', 'rdc_favicon', 1);

/** Add feed link **/
add_action('wp_head', 'rdc_feed_link');
function rdc_feed_link(){
	
	$name = get_bloginfo('name');
	echo '<link rel="alternate" type="'.feed_content_type().'" title="'.esc_attr($name).'" href="'.esc_url( get_feed_link() )."\" />\n";
}


/** Adds custom classes to the array of body classes **/
add_filter( 'body_class', 'rdc_body_classes' );
function rdc_body_classes( $classes ) {
	
	if(is_page()){
		$qo = get_queried_object();
		$classes[] = 'slug-'.$qo->post_name;
	}
	return $classes;
}




/** Options in customizer **/
add_action('customize_register', 'rdc_customize_register', 15);
function rdc_customize_register(WP_Customize_Manager $wp_customize) {
    
	$wp_customize->add_setting('header_title_one', array(
        'default'   => '',
        'transport' => 'refresh',
		'option' => 'option'
    ));
    
    $wp_customize->add_control('header_title_one', array(
        'type'     => 'text',		
        'label'    => 'Заголовок в шапке - 1 строка',
        'section'  => 'title_tagline',
        'settings' => 'header_title_one',
        'priority' => 20,
    ));
	
	$wp_customize->add_setting('header_title_two', array(
        'default'   => '',
        'transport' => 'refresh',
		'option' => 'option'
    ));
    
    $wp_customize->add_control('header_title_two', array(
        'type'     => 'text',		
        'label'    => 'Заголовок в шапке - 2 строка',
        'section'  => 'title_tagline',
        'settings' => 'header_title_two',
        'priority' => 21,
    ));
	
    	
	$wp_customize->add_setting('header_text_top', array(
        'default'   => '',
        'transport' => 'refresh',
		'option' => 'option'
    ));
    
    $wp_customize->add_control('header_text_top', array(
        'type'     => 'textarea',		
        'label'    => 'Текст в шапке',
        'section'  => 'title_tagline',
        'settings' => 'header_text_top',
        'priority' => 25,
    ));
	
	$wp_customize->add_setting('er_text', array(
        'default'   => '',
        'transport' => 'refresh',
		'option' => 'option'
    ));
    
    $wp_customize->add_control('er_text', array(
        'type'     => 'textarea',		
        'label'    => 'Текст страницы 404',
        'section'  => 'title_tagline',
        'settings' => 'er_text',
        'priority' => 30,
    ));
	
	/*$wp_customize->add_setting('newsletter_form_id', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('newsletter_form_id', array(
        'type'     => 'text',		
        'label'    => 'ID формы подписки',
        'section'  => 'title_tagline',
        'settings' => 'newsletter_form_id',
        'priority' => 40,
    ));*/
	
	$wp_customize->add_setting('newsletter_bottom_form_id', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('newsletter_bottom_form_id', array(
        'type'     => 'text',		
        'label'    => 'ID формы подписки (подвал)',
        'section'  => 'title_tagline',
        'settings' => 'newsletter_bottom_form_id',
        'priority' => 45,
    ));
	
	//Images
	$wp_customize->add_setting('default_thumbnail', array(
        'default'   => false,
        'transport' => 'refresh', // postMessage
    ));
	
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_thumbnail', array(
        'label'    => 'Миниатюра по умолчанию',
        'section'  => 'title_tagline',
        'settings' => 'default_thumbnail',
        'priority' => 60,
    )));
	
	if(rdc_has_authors()){
		$wp_customize->add_setting('default_avatar', array(
			'default'   => false,
			'transport' => 'refresh', // postMessage
		));
		
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_avatar', array(
			'label'    => __('Default Avatar', 'tst'),
			'section'  => 'title_tagline',
			'settings' => 'default_avatar',
			'priority' => 70,
		)));
	}
	
	
	$wp_customize->remove_setting('site_icon'); //remove favicon
	$wp_customize->remove_control('blogdescription'); //remove favicon
}

/** Humans txt **/
class TST_Humans_Txt {
	
	private static $_instance = null;		
	
	private function __construct() {	
		
		add_action('init', array( $this, 'rewrite'));
		add_filter('redirect_canonical', array( $this, 'canonical'));
		add_action('template_redirect', array( $this, 'template_redirect'));
		add_action('wp_head', array($this, 'head_link'));
	}
	
	public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }
		
        return self::$_instance;
    }
	
	
	public function rewrite() { //rewrite rules
		global $wp_rewrite, $wp;
		
		add_rewrite_rule('humans\.txt$', $wp_rewrite->index.'?humans=1', 'top');
		$wp->add_query_var('humans');
	}


	public function canonical($redirect) { //revome slash in link
		
		$humans = get_query_var( 'humans' );
		if (!empty($humans))
			return false;

		return $redirect;
	}
	
	public function head_link(){ // add link at header
					
		$url = esc_url(home_url('humans.txt'));
		echo "<link rel='author' href='{$url}'>\n";
	}

	public function template_redirect(){ //show text
	
		if(1 != get_query_var('humans'))
			return;
			
		//serve correct headers
		header( 'Content-Type: text/plain; charset=utf-8' ); 
	
		//prepare default content
		$content = "/* MADE BY */

GIGER - The Project by Teplitsa. Technologies for Social Good
www: te-st.ru

Idea & Project Lead
Gleb Suvorov
suvorov.gleb[at]gmail.com

Design & Development:
Anna Ladoshkina 
webdev[at]foralien.com

Contributors:

Denis Cherniatev
denis.cherniatev[at]gmail.com

Lev Zvyagincev
ahaenor[at]gmail.com

Denis Kulandin
kulandin[at]gmail.com

Tools we use with admiration and love to make things real:
WordPress, MDL Framework, Gulp, SASS, Leyka

       _             _    _        _   
      /\ \          /\ \ /\ \     /\_\ 
     /  \ \____     \ \ \\ \ \   / / / 
    / /\ \_____\    /\ \_\\ \ \_/ / /  
   / / /\/___  /   / /\/_/ \ \___/ /   
  / / /   / / /   / / /     \ \ \_/    
 / / /   / / /   / / /       \ \ \     
/ / /   / / /   / / /         \ \ \    
\ \ \__/ / /___/ / /__         \ \ \   
 \ \___\/ //\__\/_/___\         \ \_\  
  \/_____/ \/_________/          \/_/  
";

		//make it filterable
		$content = apply_filters('humans_txt', $content);
		
		//correct line ends
		$content = str_replace("\r\n", "\n", $content);
		$content = str_replace("\r", "\n", $content);
		
		//output
		echo $content;		
		die();		
	}
	
} //class end

$humans = TST_Humans_Txt::get_instance();


/** Admin bar **/
add_action('wp_head', 'rdc_adminbar_corrections');
add_action('admin_head', 'rdc_adminbar_corrections');
function rdc_adminbar_corrections(){
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	add_action( 'admin_bar_menu', 'rdc_adminbar_logo', 10 );
}


function rdc_adminbar_logo($wp_admin_bar){	
	
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<span class="ab-icon"></span>',
		'href'  => '',
	) );
}

add_action('wp_footer', 'rdc_adminbar_voices');
add_action('admin_footer', 'rdc_adminbar_voices');
function rdc_adminbar_voices() {
	
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
function rdc_force_https($content){
	
	if(!is_ssl())
		return $content;
	
	//protocol relative internal links
	$https_home = home_url('', 'https');
	$http_home = home_url('', 'http');
	$rel_home = str_replace('http:', '', $http_home);
	
	$content = str_replace($http_home, $rel_home, $content);
	$content = str_replace($https_home, $rel_home, $content);
	
	//protocol relative url in src (for external links)
	preg_match_all( '@src="([^"]+)"@' , $content, $match );
	
	if(!empty($match) && isset($match[1])){
		foreach($match[1] as $i => $test_url){
			if(false !== strpos($test_url, 'http:')){
				$replace_url = str_replace('http:', '', $test_url);
				$content = str_replace($test_url, $replace_url, $content);
			}
		}
	}
	
	return $content;
}


/** filter search request **/
function rdc_filter_search_query($s){
	
	$s = preg_replace("/&#?[a-z0-9]{2,8};/i","",$s);
	$s = preg_replace('/[^a-zA-ZА-Яа-я0-9-\s]/u','',$s);
	$s = mb_strcut($s, 0, 140, 'utf-8');
	
	if(4 > mb_strlen($s, 'utf-8')){
		$s = '';
	}
	
	return $s;
}