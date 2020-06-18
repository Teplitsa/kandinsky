<?php

class KND_Org_Widget extends WP_Widget {

    public function __construct() {

        parent::__construct('knd_orgs', __('Partners', 'knd'), array(
            'description' => __('Partner organization banners list', 'knd'),
        ));
    }

    public function widget($args, $instance) {
        
        if(isset($args['id']) && in_array($args['id'], array('knd-footer-sidebar', ''))) {
            return;
        }

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $num = empty($instance['num']) ? 4 : (int)$instance['num'];
        $category = empty($instance['category']) ? '' : $instance['category'];

        $orgs = $this->get_orgs($num, $category);
        if(count($orgs)) {
            $this->print_widget($orgs, $args, $title);
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

		/* Set up some default widget settings */
		$instance = wp_parse_args((array)$instance, array('title' => '', 'num' => 4, 'category' => '',));?>

		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php esc_html_e('Title:', 'knd');?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('num');?>"><?php esc_html_e('Number:', 'knd');?></label>
			<input id="<?php echo $this->get_field_id('num');?>" name="<?php echo $this->get_field_name('num');?>" type="text" value="<?php echo intval($instance['num']);?>">
		</p>

        <?php
        return;
	}

    public function get_orgs($num, $category = '') {

        $params = array(
            'post_type' => 'org',
            'posts_per_page' => $num,
        );
        $category = trim($category);
        if($category) {
            $params['tax_query'] = array(array(
                'taxonomy' => 'org_cat',
                'field' => 'slug',
                'terms' => trim($category),
            ));
        }

	    return get_posts($params);

	}
	
	public function show_widget($title, $num, $category = '') {
	    $this->print_widget_content($title, $this->get_orgs($num, trim($category)));
	}

    public function print_widget_content($title, $orgs) {?>


    <section class="knd-partners-widget">
    
        <div class="container">
        
            <h2 class="section-title"><?php echo $title;?></h2>
            
            <div class="knd-news-widget-body flex-row">

            <?php foreach($orgs as $org) {?>
                <div class="flex-cell flex-mf-12 flex-sm-6 flex-md-3">
                    <?php knd_org_card($org);?>
                </div>
            <?php }?>

            </div>
            
        </div>
    </section>

<?php 
	}

    public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field($new_instance['title']);		
		$instance['num'] = intval($new_instance['num']);
		$instance['category'] = trim($new_instance['category']);

		return $instance;
	}

} //class end

add_action('widgets_init', 'knd_org_widgets', 25);
function knd_org_widgets(){
    register_widget('KND_Org_Widget');
}
