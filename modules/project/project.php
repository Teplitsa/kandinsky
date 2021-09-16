<?php
get_template_part('/modules/project/hooks');

class KND_Project {
	
	public static function setup_starter_data() {
	}
	
	public static function get_short_list($num = 3) {
		
		//query
		$posts = get_posts(array('post_type' => 'project', 'posts_per_page' => $num));
		
		return $posts;
	}
	
}

