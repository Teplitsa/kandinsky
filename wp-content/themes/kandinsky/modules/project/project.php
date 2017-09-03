<?php
require get_template_directory().'/modules/project/hooks.php';
require get_template_directory().'/modules/project/widgets.php';

class KND_Project {
    
    public static function setup_starter_data() {
    }
    
    public static function get_short_list($num = 3) {
        
        //query
        $posts = get_posts(array('post_type' => 'post', 'posts_per_page' => $num));
        
        return $posts;
    }
    
}

