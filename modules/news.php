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

		$title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$num       = intval( $instance['num'] );
		$more_text = isset( $instance['more_text'] ) ? $instance['more_text'] : '';
		$more_url  = isset( $instance['more_url'] ) ? $instance['more_url'] : '';
		// num.
		if ( $num <= 0 ) {
			$num = 3;
		}

		// query.
		$posts = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => $num,
		) );

		self::print_widget( $posts, $args, $title, $more_text, $more_url );
	}

	public static function print_widget( $posts, $args, $title, $more_text, $more_url ) {
		extract( $args );

		echo $before_widget;
		?>

<div class="knd-news-widget">

	<div class="container">

		<div class="section-heading">
			<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php if ( $more_text && $more_url ) { ?>
				<div class="section-links">
					<a href="<?php echo esc_url( $more_url ); ?>"><?php echo esc_html( $more_text ); ?>
						<svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12.0303 6.53033C12.3232 6.23744 12.3232 5.76256 12.0303 5.46967L7.25736 0.696699C6.96447 0.403806 6.48959 0.403806 6.1967 0.696699C5.90381 0.989593 5.90381 1.46447 6.1967 1.75736L10.4393 6L6.1967 10.2426C5.90381 10.5355 5.90381 11.0104 6.1967 11.3033C6.48959 11.5962 6.96447 11.5962 7.25736 11.3033L12.0303 6.53033ZM0 6.75H11.5V5.25H0V6.75Z" fill="currentColor"/>
						</svg>
					</a>
				</div>
			<?php } ?>
		</div>

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
		$defaults = array(
			'title'     => '',
			'num'       => 3,
			'more_text' => '',
			'more_url'  => '',
		);
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

<p>
	<label for="<?php echo $this->get_field_id( 'more_text' );?>"><?php esc_html_e( 'More Text:','knd' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'more_text' ); ?>"
		name="<?php echo $this->get_field_name( 'more_text' ); ?>" type="text"
		value="<?php echo esc_attr( $instance['more_text'] ); ?>">
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'more_url' );?>"><?php esc_html_e( 'More Url:','knd' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'more_url' ); ?>"
		name="<?php echo $this->get_field_name( 'more_url' ); ?>" type="text"
		value="<?php echo esc_attr( $instance['more_url'] ); ?>">
</p>

<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['num']       = intval( $new_instance['num'] );
		$instance['more_text'] = sanitize_text_field( $new_instance['more_text'] );
		$instance['more_url']  = sanitize_text_field( $new_instance['more_url'] );

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
