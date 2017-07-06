<?php

class KND_Team_Widget extends WP_Widget {

    function __construct() {

        parent::__construct('knd_team', 'Команда', array(
            'description' => 'Список членов команды',
        ));
    }

    function widget($args, $instance) {

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $num = intval($instance['num']);

        //num
        if($num <= 0) {
            $num = 4;
        }
        elseif($num > 10){
            $num = 10;
        }

        //query
        $posts = get_posts(array(
            'post_type' => 'person', 
            'posts_per_page' => $num, 
            'tax_query' => array(array(
                'taxonomy' => 'person_cat',
                'field' => 'slug',
                'terms' => 'team',
            ))
        ));

        self::print_widget($posts, $args, $title);
    }

    public static function print_widget($posts, $args, $title){

        extract($args);

        echo $before_widget;
        ?>

<section class="heading">
    <div class="container"><h1 class="section-title archive"><?php echo $title;?></h1></div>
</section>


<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-2">
    <?php
        if(!empty($posts)){
            foreach($posts as $p){
                rdc_post_card($p);
            }
        }
    ?>
</div>
</div></section>

<?php 
		echo $after_widget;
	}
	
	
	function form($instance) {

		/* Set up some default widget settings */
		$defaults = array('title' => '', 'num' => 4);
		$instance = wp_parse_args((array)$instance, $defaults);		
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>">Заголовок:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('num');?>">Кол.-во:</label>
			<input id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num');?>" type="text" value="<?php echo intval($instance['num']);?>">
		</p>
	<?php
	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		
		$instance['title'] = sanitize_text_field($new_instance['title']);		
		$instance['num'] = intval($new_instance['num']);

		return $instance;
	}

} //class end

add_action('widgets_init', 'knd_team_widgets', 25);
function knd_team_widgets(){
    
    register_widget('KND_Team_Widget');
    
}
