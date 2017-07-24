<?php

class KND_Ourorg_Widget extends WP_Widget {

    function __construct() {
        
        $widget_ops = array(
            'description' => __('Our organization description on main page', 'knd'),
            'customize_selective_refresh' => true,
        );
        
        $control_ops = array(
            'width' => 400,
            'height' => 350,
        );
        
        parent::__construct('knd_ourorg', __('Our organization', 'knd'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        
?>
<div class="container knd-ourorg-widget">
<div class="entry-content">

<div id="panel-72-0-0-1" class="so-panel widget widget_sow-editor panel-last-child" data-index="1">
<div class="content-center container-extended-colored panel-widget-style">

<h2><?php echo $title?></h2>
<p><?php echo $text?></p>

</div>
</div>

</div>
</div>
<?php 
    }
    
    public function form( $instance ) {
        $instance = wp_parse_args(
                (array) $instance,
                array(
                    'title' => '',
                    'text' => '',
                )
            );
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Content:' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea>
		</p>
<?php
	}
    
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        
        if ( current_user_can( 'unfiltered_html' ) ) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = $new_instance['text'];#wp_kses_post( $new_instance['text'] );
        }
        
        return $instance;
    }
    
} //class end

add_action('widgets_init', 'knd_ourorg_widgets', 25);
function knd_ourorg_widgets(){
    
    register_widget('KND_Ourorg_Widget');
    
}
