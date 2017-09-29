<?php

class KND_Cta_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => esc_html__( 'Call To Action block', 'knd' ), 'customize_selective_refresh' => true );
		
		$control_ops = array( 'width' => 400, 'height' => 350 );
		
		parent::__construct( 'knd_cta', esc_html__( 'CTA block', 'knd' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		if ( isset( $args['id'] ) && in_array( $args['id'], array( 'knd-footer-sidebar', '' ) ) ) {
			return;
		}
		knd_show_cta_block();
	}

	public function form( $instance ) {
		?>
<p>
    <?php esc_html_e( 'You can change CTA text and link in ', 'knd' ); ?>
    <a href="<?php echo admin_url('/customize.php?autofocus%5Bsection%5D=knd_cta_block_settings')?>"><?php esc_html_e( 'Appearence / Customize / CTA block settings', 'knd' ); ?></a>
</p>
        <?php 
		
	}

	public function update( $new_instance, $old_instance ) {
	}
} // class end

add_action( 'widgets_init', 'knd_cta_widgets', 25 );

function knd_cta_widgets() {
	register_widget( 'KND_Cta_Widget' );
}
