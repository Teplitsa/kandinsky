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

		if(isset($args['id']) && in_array($args['id'], array('knd-footer-sidebar', ''))) {
			return;
		}

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = ! empty( $instance['text'] ) ? $instance['text'] : '';

?>
<div class="container knd-ourorg-widget">

<h2><?php echo $title?></h2>

<div class="knd-whoweare-headlike-text-wrapper">
	<p class="knd-whoweare-headlike-text"><?php echo $text?></p>
</div>

</div>

<div class="container knd-whoweare-section">
	<div class="flex-row justify-between">

		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="whoweare-item flex-cell flex-mf-12 flex-sm-6 flex-md-4">
				<h2><?php echo esc_html( knd_get_theme_mod( 'home-subtitle-col' . $i . '-title') ); ?></h2>
				<p>
				<?php echo wp_kses_post( nl2br( knd_get_theme_mod( 'home-subtitle-col' . $i . '-content' ), false ) ); ?>
				</p>
				<a href="<?php echo esc_url( knd_get_theme_mod( 'home-subtitle-col' . $i . '-link-url') ); ?>"><?php echo esc_html( knd_get_theme_mod( 'home-subtitle-col' . $i . '-link-text') ); ?></a>
			</div>
		<?php endfor; ?>

	</div>
</div>

<?php 
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'text'  => '',
			)
		);
?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'knd' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php esc_html_e( 'Content:', 'knd' ); ?></label>
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
			$instance['text'] = $new_instance['text'];
		}

		return $instance;
	}

} //class end

add_action('widgets_init', 'knd_ourorg_widgets', 25);
function knd_ourorg_widgets(){
	register_widget('KND_Ourorg_Widget');
}
