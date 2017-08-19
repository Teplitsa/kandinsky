<?php

class KND_Plot_Data_Builder {
    
    protected $imp = NULL;
    protected $parsedown = NULL;
    protected $data_routes = array();
    protected $cta_list = array();
    
    function __construct($imp) {
        $this->imp = $imp;
        $this->shortcode_builder = new KND_Shortcode_Builder($this, $this->imp);
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
        $this->build_pages();
        $this->build_logo();
        
    }
    
    public function build_posts() {
        
        foreach(array_keys($this->data_routes['posts']) as $section) {
            $this->build_section_posts($section);
        }
        
    }
    
    public function build_pages() {
        
        foreach(array_keys($this->data_routes['pages']) as $section) {
            $this->build_section_page($section);
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
                $piece->content = $this->imp->parse_text($piece->content);
                $this->save_post($piece, $post_type);
            }
        }
    }
    
    public function build_section_page($section) {
    
        $post_type = isset($this->data_routes['pages'][$section]['post_type']) ? $this->data_routes['pages'][$section]['post_type'] : 'page';
        $post_slug = isset($this->data_routes['pages'][$section]['post_slug']) ? $this->data_routes['pages'][$section]['post_slug'] : '';
        $template = isset($this->data_routes['pages'][$section]['template']) ? $this->data_routes['pages'][$section]['template'] : '';;
    
        if(!$template) {
            return;
        }
        
        if(preg_match('/^root_.*/', $section)) {
            $section = '';
        }
        
        $template_piece = $this->imp->get_piece($template, $section);
        
        $template_piece->content = $this->fill_template_with_pieces( $template_piece->content, $section );
        // parsedown here escape quotes and breakes shorcodes, so disable it for now
//         $template_piece->content = $this->imp->parse_text($template_piece->content);
        $template_piece->slug = $post_slug;
        
        $this->save_post($template_piece, $post_type);
    }
    
    public function fill_template_with_pieces($template_content, $section) { // remove $post param, if useless
        
        $template_content = $this->fill_content_tags($template_content, $section);
        $template_content = $this->fill_shortcode_tags($template_content, $section);
        
        return $template_content;
    }
    
    public function fill_content_tags($template_content, $section) {
        
        preg_match_all("/\[\s*?content\s*(.*?)\]/", $template_content, $matches);
        
        foreach($matches[0] as $i => $tag) {
        
            $attributes_str = $matches[1][$i];
            $attributes = $this->parse_attributes($attributes_str);
        
            if(isset($attributes['name'])) {
                $piece_name = $attributes['name'];
                $piece = $this->imp->get_piece($piece_name, $section);
        
                if($piece) {
                    $piece->content = $this->imp->parse_text($piece->content);
                    $template_content = str_replace($tag, $piece->content, $template_content);
                }
            }
        
        }
        
        return $template_content;
    }
    
    public function fill_shortcode_tags($template_content, $section) {
        
        preg_match_all("/\[\s*?shortcode\s*(.*?)\]/", $template_content, $matches);
        
        foreach($matches[0] as $i => $tag) {
        
            $attributes_str = $matches[1][$i];
            $attributes = $this->parse_attributes($attributes_str);
        
            $shortcode_name = isset($attributes['name']) ? $attributes['name'] : '';
            
            if($shortcode_name) {
             
                if(isset($attributes['content'])) {
                    
                    if(is_array($attributes['content'])) {
                        $pieces = array();
                        foreach($attributes['content'] as $piece_name) {
                            $pieces[] = $this->imp->get_piece($piece_name, $section);
                        }
                    }
                    else {
                        $piece_name = $attributes['content'];
                        $pieces = array($this->imp->get_piece($piece_name, $section));
                    }
                    
                    unset($attributes['content']);
                    
                }
                else {
                    $piece = NULL;
                }
                
                $build_method_name = "build_{$attributes['name']}";
                
                if($build_method_name && method_exists($this->shortcode_builder, $build_method_name)) {
                    unset($attributes['name']);
                    $result_shorcode = $this->shortcode_builder->$build_method_name($shortcode_name, $pieces, $attributes);
                    $template_content = str_replace($tag, $result_shorcode, $template_content);
                }
            }
        
        }
        
        return $template_content;
    }
    
    public static function parse_attributes($attributes_str) {
        preg_match_all( "/(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[\"']))+.)[\"']?/", $attributes_str, $matches);
        
        $attrs = array();
        foreach($matches[1] as $i => $attr_name) {
            $attr_val = $matches[2][$i];
            
            if(isset($attrs[$attr_name])) {
                
                if(!is_array($attrs[$attr_name])) {
                    $attrs[$attr_name] = array($attrs[$attr_name]);
                }
                
                $attrs[$attr_name][] = $attr_val;
                
            }
            else {
                $attrs[$attr_name] = $attr_val;
            }
        }
        
        return $attrs;
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
        if(count($piece->tags)) {
            $taxonomy = 'post_tag';
            $terms_list = $this->get_terms_list($piece->tags, $taxonomy);
            
            if($terms_list) {
                wp_set_object_terms((int)$uid, $terms_list, $taxonomy, false);
                wp_cache_flush();
            }
        }
        
        if(count($piece->cat)) {
            $taxonomy = 'category';
            $terms_list = $this->get_terms_list($piece->cat, $taxonomy);
        
            if($terms_list) {
                wp_set_object_terms((int)$uid, $terms_list, $taxonomy, false);
                wp_cache_flush();
            }
        }
    }
    
    public function get_terms_list($terms_names, $taxonomy) {
        $terms_list = [];
        
        foreach($terms_names as $term_name) {
        
            $term = get_term_by( 'name', $term_name, $taxonomy );
            if($term) {
                $terms_list[] = $term->term_id;
            }
            else {
                $res = wp_insert_term( $term_name, $taxonomy );
                if(!is_wp_error($res)) {
                    $terms_list[] = $res['term_id'];
                }
            }
        
        }
        
        return $terms_list;
    }
    
    public function build_logo() {
        $logo_fdata = $this->imp->get_fdata('logo.svg');
        if($logo_fdata && isset($logo_fdata['att_id']) && $logo_fdata['att_id']) {
            set_theme_mod('knd_custom_logo', $logo_fdata['att_id']);
        }
    }
    
    public function get_cta_url($cta_key) {
        return isset($this->cta_list[$cta_key]) ? $this->cta_list[$cta_key] : '';
    }
}

class KND_Shortcode_Builder {
    
    private $imp = NULL;
    
    function __construct($data_builder, $imp) {
        $this->imp = $imp;
        $this->data_builder = $data_builder;
    }
    
    public function build_knd_columns($shortcode_name, $pieces, $attributes) {
        
        foreach($pieces as $i => $piece) {
            $attr_i = $i + 1;
            
            if($piece->title) {
                $attributes[$attr_i . "-title"] = $piece->title;
            }
            
            if($piece->content) {
                $piece->content = $this->imp->parse_text($piece->content);
                $attributes[$attr_i . "-text"] = $piece->content;
            }
        }
        
        return $this->pack_shortcode_with_attributes($shortcode_name, $attributes);
    }

    public function build_knd_background_text($shortcode_name, $pieces, $attributes) {
        
        $piece = $pieces[0];
        
        if($piece->content) {
            $piece->content = $this->imp->parse_text($piece->content);
            $attributes['subtitle'] = $piece->content;
        }
        
        if($piece->title) {
            $attributes['title'] = $piece->title;
        }
        
        if($piece->thumb) {
            $attributes['bg-image'] = $this->imp->get_thumb_attachment_id($piece);
        }
        
        return $this->pack_shortcode_with_attributes($shortcode_name, $attributes);
    }
    
    public function pack_shortcode_with_attributes($shortcode_name, $attributes) {
        
        $attr_str_list = array();
        foreach($attributes as $name => $value) {
        
            if($name == 'subtitle' || preg_match('/^\d+-text$/', $name)) {
                $encoded_value = urlencode($value);
            }
            elseif($name == 'cta-url') {
                $encoded_value = $this->data_builder->get_cta_url($value);
            }
            else {
                $encoded_value = str_replace("\"", "", $value);
            }
            
            $attr_str_list[] = implode("=", array($name, "\"{$encoded_value}\""));
        }
        $attr_str = implode(" ", $attr_str_list);
        
        return "[{$shortcode_name} {$attr_str}/]";
    }

}

class KND_Colorline_Data_Builder extends KND_Plot_Data_Builder {
    
    protected $data_routes = array(
        
        'pages' => array(
            'about' => array(
                'template' => 'page-about',
                'post_type' => 'page',
                'post_slug' => 'about',
                
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
    
    public function __construct($imp) {
        parent::__construct($imp);
        
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
    }
    
}

class KND_Right2city_Data_Builder extends KND_Plot_Data_Builder {
}

class KND_Withyou_Data_Builder extends KND_Plot_Data_Builder {
}
