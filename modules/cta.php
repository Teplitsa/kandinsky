<?php

class KND_Cta_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( 'CTA block', 'knd' ), 'customize_selective_refresh' => true );
		
		$control_ops = array( 'width' => 400, 'height' => 350 );
		
		parent::__construct( 'knd_cta', __( 'CTA block', 'knd' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		if ( isset( $args['id'] ) && in_array( $args['id'], array( 'knd-footer-sidebar', '' ) ) ) {
			return;
		}
		knd_show_cta_block();
	}

	public function form( $instance ) {
	}

	public function update( $new_instance, $old_instance ) {
	}
} // class end

add_action( 'widgets_init', 'knd_cta_widgets', 25 );

function knd_cta_widgets() {
	register_widget( 'KND_Cta_Widget' );
}
