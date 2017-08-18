<?php

class KND_Plot_Data_Builder {
    
    protected $imp = NULL;
    protected $data_routes = array();
    
    function __construct($imp) {
        $this->imp = $imp;
    }
    
    public static function produce_builder($imp) {
        $plot_name = $imp->plot_name;
        $plot_name_cap = preg_replace("/[-_]*/", "", ucfirst($plot_name));
        $class_name = "KND_{$plot_name_cap}_Data_Builder";
        if(class_exists($class_name)) {
            $builder = new $class_name($imp);
        }
        else {
            $builder = NULL;
        }
        
        return $builder;
    }
    
    public function build_all() {
        
        $this->build_posts();
        $this->build_shortcodes();
        $this->build_logo();
        
    }
    
    public function build_posts() {
        
        foreach(array_keys($this->data_routes['posts']) as $section) {
            $this->build_section_posts($section);
        }
        
    }
    
    public function build_shortcodes() {
        
        foreach(array_keys($this->data_routes['shortcodes']) as $section) {
            $this->build_section_shortcodes($section);
        }
        
    }
    
    public function build_section_posts($section) {
        
        $post_type = $this->data_routes['posts'][$section]['post_type'];
        $pieces = $this->data_routes['posts'][$section]['pieces'];
        
        if(preg_match('/^root_.*/', $section)) {
            $section = '';
        }
        
        foreach($pieces as $piece_name) {
            $piece = $this->imp->get_piece($piece_name, $section);
            if($piece) {
                $this->save_post($piece, $post_type);
            }
        }
    }
    
    public function build_section_shortcodes($section) {
    
        $post_type = $this->data_routes['shortcodes'][$section]['post_type'];
        $post_slug = $this->data_routes['shortcodes'][$section]['post_slug'];
        $pieces = $this->data_routes['shortcodes'][$section]['pieces'];
    
        if(preg_match('/^root_.*/', $section)) {
            $section = '';
        }
        
        $post = knd_get_post( $post_slug, $post_type );
    
        foreach($pieces as $piece_name) {
            $piece = $this->imp->get_piece($piece_name, $section);
            if($piece) {
                $this->save_shortcode($piece, $post); 
            }
        }
    }
    
    public function save_shortcode($piece, $post) { // remove $post param, if useless
        
    }
    
    public function save_post($piece, $post_type) {
        
        $post_title = trim( $piece->title );
        $post_name = $piece->get_post_slug();
        if(!$post_name) {
            $post_name = sanitize_title($post_title);
        }
        $exist_page = knd_get_post( $post_name, $post_type );
        
        $page_data = array();
        
        $page_data['ID'] = $exist_page ? $exist_page->ID : 0;
        $page_data['post_type'] = $post_type;
        $page_data['post_status'] = 'publish';
        $page_data['post_excerpt'] = empty($piece->lead) ? '' : trim($piece->lead);
        
        $page_data['post_title'] = $post_title;
        $page_data['post_name'] = $post_name;
        $page_data['menu_order'] = 0;
        $page_data['post_content'] = trim($piece->content);
        $page_data['post_parent'] = 0;
        
//         foreach($post_meta as $meta_name => $value_index) {
//             $page_data['meta_input'][$meta_name] = empty($line[$value_index]) ? '' : trim($line[$value_index]);
//         }
        
        //thumbnail
        $thumb_id = $this->imp->get_thumb_attachment_id($piece);
        
        if($thumb_id){
            $page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;
        }
        
        $uid = wp_insert_post($page_data);
        
        // add to tax
//         if($taxonomy) {
//             $term_slug = knd_clean_csv_slug( trim( $line[6] ) );
//             if(!empty($line[6]) && $line[6] != 'none') {
//                 wp_set_object_terms((int)$uid, $term_slug, $taxonomy, false);
//                 wp_cache_flush();
//             }
        
//         }
    }
    
    public function build_logo() {
        $logo_fdata = $this->imp->get_fdata('logo.svg');
        if($logo_fdata && isset($logo_fdata['att_id']) && $logo_fdata['att_id']) {
            set_theme_mod('knd_custom_logo', $logo_fdata['att_id']);
        }
    }
}

class KND_Colorline_Data_Builder extends KND_Plot_Data_Builder {
    
    protected $data_routes = array(
        
        'shortcodes' => array(
            'about' => array(
                'page' => 'about',
                'pieces' => array('about', 'activity', 'history', 'introduction', 'legal', 'reports', 'staff', 'whoweare'),
            ),
        ),
        
        'posts' => array(
            'articles' => array(
                'post_type' => 'post',
                'pieces' => array('article1', 'article2', 'article3', 'article4', 'article5', ),
            ),
            'projects' => array(
                'post_type' => 'project',
                'pieces' => array('project1', 'project2', 'project3', 'project4', 'project5', ),
            ),
        ),
    );
    
}

class KND_Right2city_Data_Builder extends KND_Plot_Data_Builder {

    public function build_all_posts() {
    
    }
    
    public function build_about() {
    }
    
}

class KND_Withyou_Data_Builder extends KND_Plot_Data_Builder {

    public function build_all_posts() {
    
    }
    
    public function build_about() {
    }
    
}

