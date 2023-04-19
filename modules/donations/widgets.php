<?php
/**
 * Deprecated, remove in version 3.0
 */
class KND_Donations_Widget extends WP_Widget {

	function __construct() {

		parent::__construct('knd_donations', __('Donations', 'knd'), array(
			'description' => __('Donations short list', 'knd'),
		));
	}

	function widget($args, $instance) {

		if(isset($args['id']) && in_array($args['id'], array('knd-footer-sidebar', ''))) {
			return;
		}

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
		$q_args = array(
			'post_type' => Leyka_Campaign_Management::$post_type,
			'posts_per_page' => $num,
			'post_status' => 'publish',
		);

		if( !empty($instance['exclude']) ) {
			$q_args['post__not_in'] = array_map('intval', explode(',', $instance['exclude']));
		}
		else {
			$ex = $this->exclude_prebuild_campaigns();
			if($ex) {
				$q_args['post__not_in'] = $ex;
			}
		}

		$q_args['meta_query'] = array(
			array(
				'key'     => 'is_finished',
				'value'   => 1,
				'compare' => '!=',
				'type' => 'NUMERIC',
			),
		);
		
		$campaigns = get_posts(apply_filters('leyka_campaigns_list_widget_query_args', $q_args, $instance));

		self::print_widget($campaigns, $args, $title);
	}


	protected function exclude_prebuild_campaigns(){
		//don't show default leyka campaigns in widgets

		$ids = get_transient('knd_default_campaigns');

		if(empty($ids)){
			$slugs = array('kids-helpfund', 'donate-helpfund'); //add 2 others

			foreach ($slugs as $s) {
				$ex = get_page_by_path($s, OBJECT, 'leyka_campaign' );
				if($ex){
					$ids[] = $ex->ID;
				}
			}

			if(!empty($ids)) {
				set_transient('knd_default_campaigns', implode(',', $ids));
			}
		}
		else {
			$ids = array_map('intval', explode(',', $ids));
		}

		return $ids;
	}

	public static function print_widget($posts, $args, $title){

		extract($args);

		echo $before_widget;
	?>
	<div class="knd-container knd-donations-widget">
		
		<?php
			if(!empty($title)) { 
				echo $before_title.$title.$after_title;
			}
		?>
		<div class="flex-row start cards-loop leyka-loop">
			<?php
				if(!empty($posts)){
					
					foreach($posts as $p){
						knd_donation_card($p);
					}
				}
			?>
		</div>

	</div>
	<?php 
		echo $after_widget;
	}

	function form($instance) {

		/* Set up some default widget settings */
		$defaults = array('title' => '', 'num' => 3, 'exclude' => '');
		$instance = wp_parse_args((array)$instance, $defaults);
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php esc_html_e( 'Heading', 'knd' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']);?>">
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('num');?>"><?php esc_html_e( 'Qty:', 'knd' ); ?></label>
			<input id="<?php echo $this->get_field_id('num'); ?>" name="<?php echo $this->get_field_name('num');?>" type="text" value="<?php echo intval($instance['num']);?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('exclude');?>"><?php esc_html_e( 'Exclude campaigns:', 'knd' ); ?></label>
			<input id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude');?>" type="text" value="<?php echo esc_attr($instance['exclude']);?>">
		</p>
	<?php
	}

	function update($new_instance, $old_instance) {

		$instance = $old_instance;
		
		$instance['title'] = sanitize_text_field($new_instance['title']);	
		$instance['exclude'] = sanitize_text_field($new_instance['exclude']);	
		$instance['num'] = intval($new_instance['num']);

		return $instance;
	}
	
	static function get_short_list($num = 3) {
		$posts = get_posts(array('post_type' => Leyka_Campaign_Management::$post_type, 'posts_per_page' => $num));
		return $posts;
	}
} //class end

function knd_donations_widgets(){
	register_widget('KND_Donations_Widget');
}
add_action('widgets_init', 'knd_donations_widgets', 25);
