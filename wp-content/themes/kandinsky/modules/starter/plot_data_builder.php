<?php

/**
 * Build WP content structures using imported data.
 * Usage:
 * $pdb = KND_Plot_Data_Builder::produce_builder($importer);
 * $pdb->build_all();
 *
 *
 */
class KND_Plot_Data_Builder {
    
    protected $imp = NULL;
    protected $parsedown = NULL;
    protected $data_routes = array();
    protected $cta_list = array();
    
    function __construct($imp) {
        $this->imp = $imp;
        $this->shortcode_builder = new KND_Shortcode_Builder($this, $this->imp);
    }
    
    /**
     * Produce specific builder depends on importer.
     *
     * @param string    $imp    KND_Import_Remote_Content
     * 
     * @return extended KND_Plot_Data_Builder
     */
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
    
    /**
     * Create WP content structures using imported data.
     *
     */
    public function build_all() {
        
        $this->build_posts();
        $this->build_pages();
        $this->build_theme_files();
        $this->build_theme_options();
    }
    
    /**
     * Create WP posts, according to builder config, using imported files as content.
     *
     */
    public function build_posts() {
        
        foreach(array_keys($this->data_routes['posts']) as $section) {
            $this->build_section_posts($section);
        }
        
    }
    
    /**
     * Create WP posts, according to builder config, using imported files as templates.
     *
     */
    public function build_pages() {
        
        foreach(array_keys($this->data_routes['pages']) as $section) {
            $this->build_section_page($section);
        }
        
    }
    
    /**
     * Create WP posts, according to section config, using imported files as content.
     *
     */
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
    
    /**
     * Create WP posts, according to section config, using imported files as templates.
     *
     */
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
    
    /**
     * Fill template with data from importer.
     *
     * @param string    $template_content    template file name
     * @param string    $section             section, to search content in
     * 
     * @return string   template, where all tags replaces with proper content
     */
    public function fill_template_with_pieces($template_content, $section) { // remove $post param, if useless
        
        $template_content = $this->fill_content_tags($template_content, $section);
        $template_content = $this->fill_shortcode_tags($template_content, $section);
        
        return $template_content;
    }
    
    /**
     * Replace content tags with proper content.
     *
     * @param string    $template_content    template file name
     * @param string    $section             section, to search content in
     * 
     * @return string   template, where content tags replaces with proper content
     */
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
    
    /**
     * Replace shortcodes tags with proper content.
     *
     * @param string    $template_content    template file name
     * @param string    $section             section, to search content in
     * 
     * @return string   template, where shortcodes tags replaces with proper shortcodes
     */
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
    
    /**
     * Parse template tag attributes.
     *
     * @param string    $attributes_str    attributes string
     * 
     * @return string   array with key - value attributes
     */
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
    
    /**
     * Save WP post using imported piece as data source.
     *
     * @param KND_Piece       $piece      imported piece
     * @param string          $post_type  WP post type
     * 
     * @return int|WP_Error   WP post ID or error
     */
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
        
        return $uid;
    }
    
    /**
     * Get taxonomy terms by names list.
     *
     * @param array      $terms_names  terms names
     * @param string     $taxonomy     taxonomy name
     *
     * @return array     WP terms_id list
     */
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
    
    /**
     * Import files from imported data.
     *
     */
    public function build_theme_files() {
        
        foreach(array_keys($this->data_routes['files']) as $theme_option_name) {
            
            $file = $this->data_routes['files'][$theme_option_name]['file'];
            $section = isset($this->data_routes['files'][$theme_option_name]['section']) ? $this->data_routes['files'][$theme_option_name]['section'] : '';
            
            $logo_fdata = $this->imp->get_fdata($file, $section);
            
            if($logo_fdata && isset($logo_fdata['attachment_id']) && $logo_fdata['attachment_id']) {
                set_theme_mod($theme_option_name, $logo_fdata['attachment_id']);
            }
            
        }
        
    }
    
    /**
     * Get call to action URL depends on builder config.
     *
     * @param string     $cta_key     CTA key, extracted from tempalate
     *
     * @return string    CTA URL
     */
    public function get_cta_url($cta_key) {
        return isset($this->cta_list[$cta_key]) ? $this->cta_list[$cta_key] : '';
    }

    public function build_theme_options() {
        
//         print_r($this->data_routes['theme_options']);

        foreach($this->data_routes['theme_options'] as $theme_option_name => $theme_option_piece_data) {
        
            if(is_array($theme_option_piece_data)) {
                
                $piece = $theme_option_piece_data['piece'];
                $field = isset($theme_option_piece_data['field']) ? $theme_option_piece_data['field'] : 'content';
                $section = isset($theme_option_piece_data['section']) ? $theme_option_piece_data['section'] : '';
                
//                 echo "<br />" . $section . " - " . $piece . ' - ' . $this->imp->get_val($piece, $field, $section) . "<br />";
                
                set_theme_mod($theme_option_name, $this->imp->get_val($piece, $field, $section));
                
            }
            else {
                
                set_theme_mod($theme_option_name, $theme_option_piece_data);
                
            }
        }
        
    }
}

/**
 * Build shortcodes based on imported names, attributes and text content.
 * Fro use in KND_Plot_Data_Builder only.
 *
 */
class KND_Shortcode_Builder {
    
    private $imp = NULL;
    
    function __construct($data_builder, $imp) {
        $this->imp = $imp;
        $this->data_builder = $data_builder;
    }
    
    /**
     * Build knd_columns shorcode by name, pieces and attributes.
     *
     * @param string     $shortcode_name     name of shortcode
     * @param array      $pieces             pieces list that are specified in template shortcode tag
     * @param array      $attributes         array of attributes as key - value pairs
     *
     * @return string    shortcode
     */
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
    
    /**
     * Build build_knd_background_text shorcode by name, pieces and attributes.
     *
     * @param string     $shortcode_name     name of shortcode
     * @param array      $pieces             pieces list that are specified in template shortcode tag
     * @param array      $attributes         array of attributes as key - value pairs
     *
     * @return string    shortcode
     */
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
    
    /**
     * Compose shortcode from name and attributes key-value array.
     *
     * @param string     $shortcode_name     name of shortcode
     * @param array      $attributes         array of attributes as key - value pairs
     *
     * @return string    shortcode
     */
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

/**
 * WP content srtuctures builder for color-line plot.
 * The major part of the class is a config, named $this->data_routes.
 *
 */
class KND_Colorline_Data_Builder extends KND_Plot_Data_Builder {
    
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
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
        
        'files' => array(
            'knd_custom_logo' => array('file' => 'logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => '5.jpg'),
        ),
        
        'theme_options' => array(
            'knd_hero_image_support_title' => 'Помоги бороться с алкогольной зависимостью!',
            'knd_hero_image_support_text' => 'В Нашей области 777 человек, которые страдают от алкогольной зависимости. Ваша поддержка поможет организовать для них реабилитационную программу.',
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
            
        ),
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
        
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
    
}

/**
 * WP content srtuctures builder for right2city plot.
 * The major part of the class is a config, named $this->data_routes.
 *
 */
class KND_Right2city_Data_Builder extends KND_Plot_Data_Builder {
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
    protected $data_routes = array(
    
        'pages' => array(
        ),
    
        'posts' => array(
            'chronics' => array(
                'post_type' => 'post',
                'pieces' => array('news1', 'news2', 'news3', ),
            ),
        ),
        
        'files' => array(
            'knd_custom_logo' => array('file' => 'logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => 'hero_img.jpg'),
        ),
        
        'theme_options' => array(
            'knd_hero_image_support_title' => array('section' => 'homepage', 'piece' => 'hero_heading'),
            'knd_hero_image_support_text' => array('section' => 'homepage', 'piece' => 'hero_description'),
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
        ),
        
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
    
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
}

class KND_Withyou_Data_Builder extends KND_Plot_Data_Builder {
    
    /**
     * Configuration of building process.
     * pages: list of pages, that are built using imported templates
     * posts: list of pages, that are built using content from imported files
     *
     */
    protected $data_routes = array(
    
        'pages' => array(
        ),
    
        'posts' => array(
            'newsfeed' => array(
                'post_type' => 'post',
                'pieces' => array('news1', 'news2', 'news3', ),
            ),
        ),
    
        'files' => array(
            'knd_custom_logo' => array('file' => 'logo.svg'),
            'knd_hero_image' => array('section' => 'img', 'file' => 'twokidsmain.jpg'),
        ),
        
        'theme_options' => array(
            'knd_hero_image_support_title' => array('section' => 'homepage', 'piece' => 'hero_heading'),
            'knd_hero_image_support_text' => array('section' => 'homepage', 'piece' => 'hero_description'),
            'knd_hero_image_support_button_caption' => 'Помочь сейчас',
        ),
        
    );
    
    /**
     * Set CTA config.
     *
     */
    public function __construct($imp) {
        parent::__construct($imp);
    
        $this->cta_list = array(
            'CTA_DONATE' => site_url('/donate/'),
        );
        $this->data_routes['theme_options']['knd_hero_image_support_url'] = get_permalink(get_page_by_path('donate'));
    }
}
