<?php

class KND_Team_Widget extends WP_Widget {

    function __construct() {

        parent::__construct('knd_team', 'Команда', array(
            'description' => 'Список членов команды',
        ));
    }

    function widget($args, $instance) {

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : trim($instance['title']), $instance, $this->id_base);
        $num = empty($instance['num']) ? 5 : (int)$instance['num'];

        $this->print_widget($this->get_persons($num), $args, $title);
    }

    function print_widget($persons, $args, $title){

        extract($args);

        echo $before_widget;
        echo $this->print_widget_content($title, $persons);
		echo $after_widget;
	}
	
	
	function form($instance) {

		/* Set up some default widget settings */
		$defaults = array('title' => '', 'num' => 5);
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
	
	function get_persons($num) {
	    
	    //num
	    if($num <= 0) {
	        $num = 5;
	    }
	    elseif($num > 10){
	        $num = 10;
	    }
	     
	    //query
	    return get_posts(array(
	        'post_type' => 'person',
	        'posts_per_page' => $num,
	        'tax_query' => array(array(
	            'taxonomy' => 'person_cat',
	            'field' => 'slug',
	            'terms' => 'team',
	        ))
	    ));
	    
	}
	
	public function show_widget($title, $num) {
	    $this->print_widget_content($title, $this->get_persons($num));
	}
	
	function print_widget_content($title, $persons) {
?>

<div class="container">
<div class="entry-content">
<div id="pl-486">

<div id="pg-486-3" class="panel-grid">
<div class="panel-row-style-no-bottom-margin no-bottom-margin panel-row-style">
<div id="pgc-486-3-0" class="panel-grid-cell">

<div id="panel-486-3-0-0" class="so-panel widget widget_ist-sectionheader panel-first-child" data-index="5">
<div class="so-widget-ist-sectionheader so-widget-ist-sectionheader-base">
<div class="pb-section-title align-center">
<h3><?php echo $title?></h3>
</div>
</div>
</div>

<div id="panel-486-3-0-1" class="so-panel widget widget_tst-blocksgroup panel-last-child" data-index="6">
<div class="so-widget-tst-blocksgroup so-widget-tst-blocksgroup-base">
<div class="frl-pb-blocks">
<div class="cards-loop sm-cols-2 md-cols-3 lg-cols-4 exlg-cols-5">

<?php
    foreach($persons as $person) {
        rdc_person_card($person);
    }
?>

</div>
</div>
</div>
</div>

</div>
</div>
</div>

</div>
</div>
</div>

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
