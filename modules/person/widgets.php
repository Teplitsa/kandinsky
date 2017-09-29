<?php

class KND_Team_Widget extends WP_Widget {

    function __construct() {

        parent::__construct('knd_team', __('People list', 'knd'), array(
            'description' => __('Gallery of people - eg. team', 'knd'),
        ));
    }

    function widget($args, $instance) {
        
        if(isset($args['id']) && in_array($args['id'], array('knd-footer-sidebar', ''))) {
            return;
        }

        extract($args);

        $title = empty($instance['title']) ? '' : trim($instance['title']);
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $num = empty($instance['num']) ? 5 : (int)$instance['num'];
        $slug = empty($instance['slug']) ? '' :  trim($instance['slug']);

        $people = $this->get_persons($num, $slug);

        if(count($people)) {
            echo $before_widget;
            echo $before_title.$title.$after_title;

        ?>
        <div class="knd-people-gallery flex-row centered">
            <?php foreach($people as $person) {?>
                <div class="person flex-cell flex-sm-6 flex-md-col-5"><?php knd_person_card($person);?></div>
            <?php }?>
        </div>
        <?php

            echo $after_widget;
        }
    }
	
	
	function form($instance) {

		/* Set up some default widget settings */
		$defaults = array('title' => '', 'num' => 4, 'slug' => '');
		$instance = wp_parse_args((array)$instance, $defaults);	
        $cats = get_terms(array('taxonomy' => 'person_cat', 'hide_empty' => 0));


	?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php esc_html_e('Title', 'knd' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('num');?>"><?php esc_html_e('Number of persons to output', 'knd'); ?></label>
			<input id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num');?>" type="text" value="<?php echo intval($instance['num']);?>">
		</p>
        <?php if(!is_wp_error($cats) && !empty($cats)) { ?>
        <p>
            <label for="<?php echo $this->get_field_id('slug');?>"><?php esc_html_e('Category', 'knd');?></label>
            <select id="<?php echo $this->get_field_id('slug');?>" name="<?php echo $this->get_field_name('slug');?>">
                <option value="0"><?php esc_html_e('Select a category', 'knd'); ?></option>
                <?php foreach ($cats as $cat) { ?>
                <option value="<?php echo esc_attr($cat->slug);?>"<?php selected(trim($cat->slug), trim($instance['slug']));?>><?php echo esc_attr($cat->name);?></option>
                <?php } ?>
            </select>
        </p>
        <?php } ?>
	<?php
	}
	
	function get_persons($num, $slug) {
	    
	    //num
	    if($num <= 0) {
	        $num = 5;
	    }

	    //query
        $args = array(
            'post_type'=> 'person',
            'posts_per_page' => $num,
        );

        if(!empty($slug)){
            $args['tax_query'] = array(
                array(
                    'taxonomy'=> 'person_cat',
                    'field'   => 'slug',
                    'terms'   => $slug
                )
            );
        }

        return get_posts($args);
	    
	}


	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		
		$instance['title'] = sanitize_text_field($new_instance['title']);		
		$instance['num'] = intval($new_instance['num']);
        $instance['slug'] = sanitize_text_field($new_instance['slug']);       

		return $instance;
	}

} //class end

add_action('widgets_init', 'knd_team_widgets', 25);
function knd_team_widgets(){
    
    register_widget('KND_Team_Widget');
    
}


