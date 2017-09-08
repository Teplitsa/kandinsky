<?php if( !defined('WPINC') ) die;
/** 
    Class to manage demo content actions
    to be sync between Preview and actual content
**/

class KND_Demo_Content {

    private static $_instance = null;
    protected $config = null;


    private function __construct() {

        //add_action('after_setup_theme', array($this, 'demo_content'));
        
//         add_action('knd_save_demo_content', array($this, 'save_demo_content'));
        
    }


    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    public function read_demo_data(){
        
        //Read file
        #$csv = array_map('str_getcsv', file(get_template_directory() . '/modules/starter/csv/pages.csv'));
        $pages = array();
        
        if(($handle = fopen( get_template_directory() . '/modules/starter/csv/pages.csv', "r" )) !== FALSE) {
        
        #foreach($csv as $i => $line) {
        $i = -1;
        while(( $line = fgetcsv( $handle, 1000000, "," )) !== FALSE) {
            $i += 1;
            
            if($i == 0) {
                continue;
            }
            $post_title = trim( $line[0] );
            $post_name = knd_clean_csv_slug( trim( $line[2] ) );
            $exist_page = knd_get_post( $post_name, 'page' );
        
            $page_data = array();
        
            $page_data['ID'] = $exist_page ? $exist_page->ID : 0;
            $page_data['post_type'] = 'page';        
            $page_data['post_title']    = $post_title;
            $page_data['post_name']     = $post_name;
            $page_data['menu_order']    = (int)$line[5];
            $page_data['post_content']  = trim($line[1]);
            $page_data['thumbnail_url'] = trim($line[4]);
                    
            

            $pages[$post_name] = $page_data;
        }
        
        }
        
        if($handle) {
            fclose($handle);
        }

        return $pages;
    }

    public function get_config() {

        if(!$this->config) {
            
            $pages = $this->read_demo_data();

            $this->config = array(
                // Starter content defined here
                'options' => array(    
                    'blogname'          => 'Линия цвета',
                    'blogdescription'   => 'Мы помогаем людям с алкогольной зависимостью',
                    'text_in_header'    => 'г. Псков, ул. Советская, д. 85'.chr(10).'+7 (111) 172-20-88',
                    'show_on_front'     => 'page',
                    'page_on_front'     => '{{home}}',
                    'page_for_posts'    => '{{news}}',
                    'permalink_structure' => '/%year%/%monthnum%/%postname%/'
                ),
                'nav_menus' => array(
                    'primary' => array(
                        'name' => __('Primary menu', 'knd'),
                        'items' => array(
                            'page_home' => array(
                                    'type'      => 'post_type',
                                    'object'    => 'page',
                                    'object_id' => '{{home}}',
                            ),
                            'page_about' => array(
                                    'type'      => 'post_type',
                                    'object'    => 'page',
                                    'object_id' => '{{about}}',
                            ),
                            'page_news' => array(
                                    'type'      => 'post_type',
                                    'object'    => 'page',
                                    'object_id' => '{{news}}',
                            ),
                            'page_contact' => array(
                                    'type'      => 'post_type',
                                    'object'    => 'page',
                                    'object_id' => '{{contact}}',
                            ),
                            'page_donate' => array(
                                    'type'      => 'post_type',
                                    'object'    => 'page',
                                    'object_id' => '{{donate}}',
                            )
                        ),
                    )
                ),
                'posts' => array(
                    'home' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['home']['post_title'], //@to_do add check for isset
                        'post_content' => $pages['home']['post_content'],   
                        'thumbnail_url' => $pages['home']['thumbnail_url'],                        
                        'template' => 'page-home.php'
                    ),
                    'about' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['about']['post_title'],
                        'post_content' => $pages['about']['post_content'],
                        'thumbnail_url' => $pages['about']['thumbnail_url'],     
                    ),
                    'contact' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['contact']['post_title'],
                        'post_content' => $pages['contact']['post_content'],
                        'thumbnail_url' => $pages['contact']['thumbnail_url'],     
                    ),
                    'news' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['news']['post_title'],
                        'post_content' => '',    
                    ),
                    'donate' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['donate']['post_title'],
                        'post_content' => $pages['donate']['post_content'],
                    ),
                    'reports' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['reports']['post_title'],
                        'post_content' => $pages['reports']['post_content'],
                    ),
                    'projects' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['projects']['post_title'],
                        'post_content' => $pages['projects']['post_content'],
                    ),
                    'volunteers' => array(
                        'post_type' => 'page',
                        'post_title' => $pages['volunteers']['post_title'],
                        'post_content' => $pages['volunteers']['post_content'],
                    )
                )
            );
        } //if

        return $this->config;
    }


    // demo content for preview
    function demo_content() {

        $c = $this->get_config();
        add_theme_support('starter-content', $c);
    }

    //save demo content as constant one
    function save_demo_content() {

        //options
        $c = $this->get_config();

        //create pages
        $pages = array();
        foreach($c['posts'] as $slug => $obj) {

            $post_name = trim($slug);
            $exist_page = knd_get_post( $post_name, 'page' );
        
            $page_data = array();
        
            $page_data['ID'] = $exist_page ? $exist_page->ID : 0;
            $page_data['post_title'] = $exist_page ? $exist_page->post_title : $obj['post_title'];
            $page_data['post_type'] = 'page';
            $page_data['post_status'] = 'publish';
            $page_data['post_excerpt'] = '';
        
            $page_data['post_name']    = $post_name;
            $page_data['post_content'] = $exist_page ? $exist_page->post_content : $obj['post_content'];
            $page_data['post_parent'] = $exist_page ? $exist_page->post_parent : 0;
            $page_data['meta_input']['_wp_page_template'] = !empty($obj['template']) ? $obj['template'] : 'default' ; //template data

            //thumbnail
            $thumb_id = false;

            //imported old photo
            $thumbnail_url = !empty($obj['thumbnail_url']) ? $obj['thumbnail_url'] : '';
            if( preg_match( '/^http[s]?:\/\//', $thumbnail_url ) ) {
                $thumb_id = TST_Import::get_instance()->maybe_import( $thumbnail_url );
            }
            
            if($thumb_id){
                $page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
            }

            $uid = wp_insert_post($page_data);
            if($uid)
                $pages[$slug] = $uid;

            wp_cache_flush();
        }

        //store options
        foreach($c['options'] as $key => $value) {
            if($key == 'page_on_front' || $key == 'page_for_posts') {
                $slug = trim($value, '{}');
                if(isset($pages[$slug])){
                    update_option($key, (int)$pages[$slug]); 
                }
            }
            else {
                //@to_do - store theme options in own array - req by wp.org
                update_option($key, $value); 
            }
        }

        flush_rewrite_rules();


        //menu
        $menu_name = __('Primary menu', 'knd');
        if(is_nav_menu($menu_name)){
            wp_delete_nav_menu($menu_name);
        }


        $menu_id = wp_create_nav_menu($menu_name);
        foreach ($pages as $slug => $pid) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-object-id' => $pid,
                'menu-item-object' => 'page',
                'menu-item-parent-id' => 0,
                'menu-item-position' => 0,
                'menu-item-type' => 'post_type',
                //'menu-item-title' => '',
                //'menu-item-url' => '',
                //'menu-item-description' => '',
                //'menu-item-attr-title' => '',
                //'menu-item-target' => '',
                'menu-item-classes' => 'page-'.$slug,
                //'menu-item-xfn' => '',
                'menu-item-status' => 'publish'
            ));
        }
        

        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations ); 
    }

    

} //class

//KND_Demo_Content::get_instance();