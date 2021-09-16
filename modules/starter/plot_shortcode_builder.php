<?php
 
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
    
    public function build_knd_key_phrase($shortcode_name, $pieces, $attributes) {

        $piece = $pieces[0];

        if($piece->content) {
            $piece->content = $this->imp->parse_text($piece->content);
        }
        
        if($piece->title) {
            $attributes['subtitle'] = $piece->title;
        }
        
        return $this->pack_shortcode_with_content($shortcode_name, $piece->content, $attributes);
    }

    public function build_knd_people_list($shortcode_name, $pieces, $attributes) {
        return $this->pack_shortcode_with_attributes($shortcode_name, $attributes);
    }
    
    public function build_knd_image_section($shortcode_name, $pieces, $attributes) {
        $piece = $pieces[0];
        
        if($piece->content) {
            $piece->content = $this->imp->parse_text($piece->content);
        }
        
        if(isset($attributes['img'])) {
            $attributes['img'] = $this->imp->get_image_attachment_id($attributes['img']);
        }
        elseif($piece->thumb) {
            $attributes['img'] = $this->imp->get_thumb_attachment_id($piece);
        }
        
        return $this->pack_shortcode_with_content($shortcode_name, $piece->content, $attributes);
    }
    
    public function build_knd_cta_section($shortcode_name, $pieces, $attributes) {
        $piece = $pieces[0];
        
        if($piece->content) {
            $piece->content = $this->imp->parse_text($piece->content);
        }
        
        return $this->pack_shortcode_with_content($shortcode_name, $piece->content, $attributes);
    }

    public function build_knd_leyka_inline_campaign($shortcode_name, $pieces, $attributes) {
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
        
        $attr_str = $this->__pack_shortcode_attrs($attributes);
        
        return "[{$shortcode_name} {$attr_str}/]";
    }
    
    public function pack_shortcode_with_content($shortcode_name, $content, $attributes) {
    
        $attr_str = $this->__pack_shortcode_attrs($attributes);
        
        return "[{$shortcode_name} {$attr_str}]{$content}[/{$shortcode_name}]";
    }
    
    private function __pack_shortcode_attrs($attributes) {
        $attr_str_list = array();
        foreach($attributes as $name => $value) {
            if($name != 'cta-url') {
                $encoded_value = str_replace("\"", "", $value);
            }
        
            $attr_str_list[] = implode("=", array($name, "\"{$encoded_value}\""));
        }
        return implode(" ", $attr_str_list);
    }

}
