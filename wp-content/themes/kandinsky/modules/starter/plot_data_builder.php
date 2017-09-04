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
        return self::produce_plot_builder($imp->plot_name, $imp);
    }
    
    public static function produce_plot_builder($plot_name, $imp) {
        
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
        $this->build_title_and_description();
        $this->build_theme_files();
        $this->build_option_files();
        $this->build_theme_colors();
        $this->build_theme_options();
        $this->build_general_options();
        $this->build_menus();
        $this->build_sidebars();
    }
    
    /**
     * Create WP posts, according to builder config, using imported files as content.
     *
     */
    public function build_posts() {
        
        $this->remove_all_other_plots_posts();
        
        foreach(array_keys($this->data_routes['posts']) as $section) {
            $this->build_section_posts($section);
        }
        
        global $wp_rewrite;
        $wp_rewrite->flush_rules( false );
    }
    
    public function remove_all_other_plots_posts() {
//         var_dump($this->imp->possible_plots);

        foreach($this->imp->possible_plots as $plot_name) {
            
//             var_dump($plot_name);
            
            if($plot_name != $this->imp->plot_name) {
                
//                 echo "deleting all posts...\n";
                
                $builder = self::produce_plot_builder($plot_name, $this->imp);
                $plot_config = $builder->data_routes;
                
//                 var_dump($plot_config['posts']);
                
                foreach($plot_config['posts'] as $section => $section_data) {
                    
                    $post_type = isset($section_data['post_type']) ? $section_data['post_type'] : 'post';
                    $post_pieces_name = $section_data['pieces'];
                    
//                     echo "deleting pt: {$post_type}...\n";
//                     var_dump($post_pieces_name);
                    
                    foreach($post_pieces_name as $piece_name) {
                        
                        $piece = new KND_Piece(array('piece_name' => $piece_name, 'piece_section' => $section));
                        $slug = $piece->get_post_slug();
                        
//                         echo "slug: {$slug} \n";
                        
                        $post = knd_get_post($slug, $post_type);
                        if($post) {
//                             echo "delete {$slug} \n";
                            wp_delete_post( $post->ID, true );
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Create WP posts, according to builder config, using imported files as templates.
     *
     */
    public function build_pages() {
        
        foreach($this->data_routes['pages'] as $section => $page_options) {
            
            if(isset($page_options['pieces'])) {
                $this->build_section_pages($section);
            }
            else {
                $this->build_section_simple_page($page_options);
            }
            
        }
        
        foreach($this->data_routes['pages_templates'] as $section => $page_options) {
            if(isset($page_options['template'])) {
                $this->build_section_template_page($section, $page_options);
            }
        }
        
        // set home page
        $piece = new KND_Piece(array('slug' => 'home', 'title' => __('Home page', 'knd')));
        $homepage_id = $this->save_post($piece, 'page');
        update_option( 'page_on_front', $homepage_id );
        update_option( 'show_on_front', 'page' );
        
        // set news page
        $piece = new KND_Piece(array('slug' => 'news', 'title' => __('News', 'knd')));
        $homepage_id = $this->save_post($piece, 'page');
        update_option( 'page_for_posts', $homepage_id );
        
        global $wp_rewrite;
        $wp_rewrite->flush_rules( false );
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
    
    public function build_section_pages($section) {
    
        $post_type = $this->data_routes['pages'][$section]['post_type'];
        $pieces = $this->data_routes['pages'][$section]['pieces'];
        
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
    
    public function build_section_simple_page($page_options) {
    
        $post_type = isset($page_options['post_type']) ? $page_options['post_type'] : 'page';
        $post_slug = isset($page_options['post_slug']) ? $page_options['post_slug'] : '';
        $piece_name = isset($page_options['piece']) ? $page_options['piece'] : '';
        $section = isset($page_options['section']) ? $page_options['section'] : '';
    
        if(!$piece_name) {
            return;
        }
        
        if(preg_match('/^root_.*/', $section)) {
            $section = '';
        }
    
        $piece = $this->imp->get_piece($piece_name, $section);
        
        if($piece) {
            $piece->content = $this->imp->parse_text($piece->content);
            $piece->slug = $post_slug;
            $this->save_post($piece, $post_type);
        }
        
    }
    
    /**
     * Create WP posts, according to section config, using imported files as templates.
     *
     */
    public function build_section_template_page($section, $page_options) {
    
        $post_type = isset($page_options['post_type']) ? $page_options['post_type'] : 'page';
        $post_slug = isset($page_options['post_slug']) ? $page_options['post_slug'] : '';
        $template = isset($page_options['template']) ? $page_options['template'] : '';
    
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
                    $content = $piece->content;
                    $template_content = str_replace($tag, $content, $template_content);
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
        
        foreach($this->data_routes['theme_files'] as $option_name => $option) {
            
            $file = $option['file'];
            $section = isset($option['section']) ? $option['section'] : '';
            $logo_fdata = $this->imp->get_fdata($file, $section);
            
            if($logo_fdata && isset($logo_fdata['attachment_id']) && $logo_fdata['attachment_id']) {
                set_theme_mod($option_name, $logo_fdata['attachment_id']);
            }
            
        }
        
    }
    
    public function build_option_files() {
        
        foreach($this->data_routes['option_files'] as $option_name => $option) {
        
            $file = $option['file'];
            $section = isset($option['section']) ? $option['section'] : '';
            $logo_fdata = $this->imp->get_fdata($file, $section);
        
            if($logo_fdata && isset($logo_fdata['attachment_id']) && $logo_fdata['attachment_id']) {
                update_option($option_name, $logo_fdata['attachment_id']);
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
        $this->save_theme_options($this->data_routes['theme_options']);
        
    }
    
    
    public function build_theme_colors() {
    
        //         print_r($this->data_routes['theme_options']);
        $this->save_theme_options($this->data_routes['theme_colors']);
    
    }
    
    public function save_theme_options($theme_options_list) {
        
//         print_r($theme_options_list);
        foreach($theme_options_list as $theme_option_name => $theme_option_piece_data) {
    
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

    public function build_general_options() {
        // header contacts
        $knd_address_phone = $this->data_routes['general_options']['knd_address_phone'];
        set_theme_mod('text_in_header', trim($knd_address_phone));
    }
    
    public function build_sidebars() {
        
        $this->build_footer_sidebar();
        $this->build_configured_sidebar();
        
        KND_StarterSidebars::setup_footer_sidebar();
        
        global $wp_rewrite;
        $wp_rewrite->flush_rules( false );
    }
    
    public function build_configured_sidebar() {
        
        if(!isset($this->data_routes['sidebar_widgets'])) {
            return;
        }
        
        foreach($this->data_routes['sidebar_widgets'] as $sidebar_name => $widgets_list) {
            
            $sidebars = get_option( 'sidebars_widgets' );
            $sidebars[$sidebar_name] = array();
            update_option( 'sidebars_widgets', $sidebars );
            
//             echo $sidebar_name . "\n";
            foreach($widgets_list as $widget) {
                
                $widget_options = $widget['options'];
                $widget_name = $widget['slug'];
//                 echo $widget_name . "\n";
//                 echo print_r($widget_options, true) . "\n";
                
                // add text on home
                $widgets = get_option('widget_' . $widget_name);
                
                $widgets[] = $widget_options;
                $widgets_keys = array_keys($widgets);
                $widget_index = end($widgets_keys);
                $sidebars[$sidebar_name][] = $widget_name . '-' . $widget_index;
                
                update_option( 'widget_' . $widget_name, $widgets );
            }
            
            update_option( 'sidebars_widgets', $sidebars );
        }
    }
    
    public function build_footer_sidebar() {
        // footer contacts
        $knd_footer_contacts = $this->data_routes['general_options']['knd_footer_contacts'];
        $knd_address_phone = nl2br(trim($this->data_routes['general_options']['knd_address_phone']));
        $knd_footer_contacts = str_replace("{knd_address_phone}", $knd_address_phone, $knd_footer_contacts);
        
        update_option('knd_footer_contacts', $knd_footer_contacts);
        update_option('knd_address_phone', $knd_address_phone);
        update_option('text_in_header', $knd_address_phone);
        
        // security and pd
        $knd_footer_security_pd = $this->data_routes['general_options']['knd_footer_security_pd'];
        $knd_url_pd_policy = $this->data_routes['theme_options']['knd_url_pd_policy'];
        $knd_url_privacy_policy = $this->data_routes['theme_options']['knd_url_privacy_policy'];
        $knd_url_public_oferta = $this->data_routes['theme_options']['knd_url_public_oferta'];
        
        $knd_footer_security_pd = str_replace("{knd_url_pd_policy}", $knd_url_pd_policy, $knd_footer_security_pd);
        $knd_footer_security_pd = str_replace("{knd_url_privacy_policy}", $knd_url_privacy_policy, $knd_footer_security_pd);
        $knd_footer_security_pd = str_replace("{knd_url_public_oferta}", $knd_url_public_oferta, $knd_footer_security_pd);
        
        update_option('knd_footer_security_pd', $knd_footer_security_pd);
    }
    
    public function build_menus() {
        
        if(!isset($this->data_routes['menus']) || !is_array($this->data_routes['menus'])) {
            return;
        }
        
        foreach($this->data_routes['menus'] as $menu_name => $menu_items) {
            
            if(is_nav_menu($menu_name)){
                wp_delete_nav_menu($menu_name);
            }
            $menu_id = wp_create_nav_menu( $menu_name );
            
            foreach($menu_items as $k => $v) {
                if(is_array($v)) {
                    if(isset($v['post_type']) && isset($v['slug'])) {
                        $page = knd_get_post( $v['slug'], $v['post_type'] );
                        if($page) {
                            KND_StarterMenus::add_post2menu($page, $menu_id, $k);
                        }
                    }
                    elseif(isset($v['url']) && isset($v['title'])) {
                        KND_StarterMenus::add_link2menu($v['title'], $v['url'], $menu_id, $k);
                    }
                }
            }
            
        }
        
        global $wp_rewrite;
        $wp_rewrite->flush_rules( false );
    }
    
    public function build_title_and_description() {
        update_option('blogname', $this->data_routes['general_options']['site_name']);
        update_option('blogdescription', $this->data_routes['general_options']['site_description']);
    }
    
}
