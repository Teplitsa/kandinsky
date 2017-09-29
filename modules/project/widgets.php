<?php

class KND_Projects_Widget extends WP_Widget {

    public function __construct() {

        parent::__construct('knd_projects', __('Projects', 'knd'), array(
            'description' => __('Projects short list', 'knd'),
        ));
    }

    public function widget($args, $instance) {
        
        if(isset($args['id']) && in_array($args['id'], array('knd-footer-sidebar', ''))) {
            return;
        }

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $num = empty($instance['num']) ? 3 : (int)$instance['num'];

        $projects = KND_Project::get_short_list($num);
        
        if(count($projects)) {
            $this->print_widget($projects, $args, $title);
        }

    }

    public function print_widget($orgs, $args, $title){

        extract($args);

        /** @var $before_widget */
        /** @var $after_widget */
        echo $before_widget;
        echo $this->print_widget_content($title, $orgs);
		echo $after_widget;

	}
	
	public function form($instance) {

		$instance = wp_parse_args((array)$instance, array('title' => '', 'num' => 3,));?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php esc_html_e('Title:', 'knd');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('num');?>"><?php esc_html_e('Number:', 'knd');?></label>
			<input id="<?php echo $this->get_field_id('num');?>" name="<?php echo $this->get_field_name('num');?>" type="text" value="<?php echo intval($instance['num']);?>">
		</p>

	<?php
	}
	
	public function show_widget($title, $num) {
	    $projects = KND_Project::get_short_list($num);
	    $this->print_widget_content($title, $projects);
	}

    public function print_widget_content($title, $projects) {
        
        $menu_items = wp_get_nav_menu_items( esc_html__( 'Kandinsky projects block menu', 'knd' ) );
        $project_menu_items = array();
        if($menu_items && is_array($menu_items)) {
            foreach($menu_items as $k => $v) {
                $project_menu_items[] = array(
                    'title' => $v->title,
                    'url' => $v->url,
                );
            }
        }
        
        knd_show_posts_shortlist($projects, $title, $project_menu_items);
        
	}

    public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field($new_instance['title']);		
		$instance['num'] = intval($new_instance['num']);

		return $instance;
	}

} //class end

add_action('widgets_init', 'knd_projects_widgets', 25);
function knd_projects_widgets(){
    register_widget('KND_Projects_Widget');
}
