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
