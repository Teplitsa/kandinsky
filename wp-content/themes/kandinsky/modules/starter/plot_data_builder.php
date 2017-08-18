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
        $this->build_all_posts();
        $this->build_logo();
        $this->build_about();
    }
    
    public function build_posts($post_type, $section, $posts_name_mask) {
        
        $i = 1;
        while(True) {
            
            $piece_name = $posts_name_mask . $i;
            
            if(!$this->imp->is_piece($piece_name, $section)) {
                break;
            }
            
            $piece = $this->imp->get_piece($piece_name, $section);
            $this->save_post($piece, $post_type);
            
            $i += 1;
        }
        
    }
    
    public function build_about() {
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
        $thumb_id = $this->imp->get_thumb_att_id($piece);
        
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
    
    public function build_all_posts() {
        $this->build_posts('post', 'articles', 'article');
        $this->build_posts('project', 'projects', 'project');
    }
    
}

class KND_Right2city_Data_Builder extends KND_Plot_Data_Builder {

    public function build_all_posts() {
    
    }
    
}

class KND_Withyou_Data_Builder extends KND_Plot_Data_Builder {

    public function build_all_posts() {
    
    }
    
}

