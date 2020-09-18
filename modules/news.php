<?php

class KND_News_Widget extends WP_Widget {

	function __construct() {
		parent::__construct( 
			'knd_news',
			esc_html__( 'News', 'knd' ), 
			array( 'description' => esc_html__( 'Latest news list', 'knd' ) ) );
	}

	function widget( $args, $instance ) {
		if ( isset( $args['id'] ) && in_array( $args['id'], array( 'knd-footer-sidebar', '' ) ) ) {
			return;
		}

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$num = intval( $instance['num'] );

		// num.
		if ( $num <= 0 ) {
			$num = 3;
		}

		// query.
		$posts = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => $num,
		) );

		self::print_widget( $posts, $args, $title );
	}

	public static function print_widget( $posts, $args, $title ) {
		extract( $args );

		echo $before_widget;
		?>

<div class="knd-news-widget">

	<div class="container">

		<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>

		<div class="flex-row start cards-row">
		<?php
		if ( ! empty( $posts ) ) {
			foreach ( $posts as $p ) {
				knd_post_card( $p );
			}
		}
		?>
		</div>

	</div>
</div>

<?php
		echo $after_widget;
	}

	function form( $instance ) {

		/* Set up some default widget settings */
		$defaults = array( 'title' => '', 'num' => 4 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' );?>"><?php esc_html_e( 'Title:','knd' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'num' ); ?>"><?php esc_html_e( 'Qty:','knd' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'num' ); ?>"
		name="<?php echo $this->get_field_name( 'num' ); ?>" type="text"
		value="<?php echo intval( $instance['num'] ); ?>">
</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['num'] = intval( $new_instance['num'] );

		return $instance;
	}

	static function get_short_list( $num = 3 ) {
		$posts = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => $num,
		) );
		return $posts;
	}
} // class end

add_action( 'widgets_init', 'knd_news_widgets', 25 );

function knd_news_widgets() {
	register_widget('KND_News_Widget');
}
