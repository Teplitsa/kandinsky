<?php
/**
 * Kandinsky Events Metaboxes
 *
 * @package Kandinsky
 */

/**
 * Adds a meta box to post type event
 */
class Knd_Event_Meta_Box {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'event_meta',
			esc_html__( 'Additional settings', 'knd' ),
			array( $this, 'render_metabox' ),
			'event',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {
		
		/* Add nonce for security and authentication */
		wp_nonce_field( 'event_meta_action', 'event_meta_nonce' );

		// Retrieve an existing value from the database.
		$is_sticky      = get_post_meta( $post->ID, '_is_sticky', true );
		$booking_header = get_post_meta( $post->ID, '_booking_header', true );
		$booking_desc   = get_post_meta( $post->ID, '_booking_desc', true );

		// Set default values.
		if( empty( $is_sticky ) ) $is_sticky = '';
		if( $booking_header ) $booking_header = esc_html__( 'Seat reservation', 'knd' );
		if( empty( $booking_desc) ) $booking_desc = '';

		// Form fields.
		echo '<table class="form-table mcwallet-form-table">';
		
		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Sticky on top', 'knd' ) . '</label></th>';
		echo '		<td>';
		echo '			<label><input type="checkbox" name="_is_sticky" value="1" ' . checked( 1, $is_sticky, false ) . '> ' . esc_html__( 'Sticky', 'knd' ) . '</label>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Booking form header', 'knd' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="_booking_header" value="' . esc_attr( $booking_header ) . '" class="large-text">';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Booking form description', 'knd' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" name="_booking_desc" value="' . esc_attr( $booking_desc ) . '" class="large-text">';
		echo '		</td>';
		echo '	</tr>';
		
		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Speakers', 'knd' ) . '</label></th>';
		echo '		<td>';

		$speakers = get_post_meta( $post->ID, '_speakers', true );

		if ( empty( $speakers ) ) $speakers = array();

		$speakers_args = array(
			'post_type'      => 'person',
			'posts_per_page' => -1,
		);
		$speakers_query = new WP_Query( $speakers_args );
		if ( $speakers_query->have_posts() ) :
			while ( $speakers_query->have_posts() ) : $speakers_query->the_post();

			echo '<label><input type="checkbox" name="_speakers[]" value="' . get_the_ID() . '" ' . checked( true, in_array( get_the_ID(), $speakers ), false ) . '> ' . get_the_title() . '</label><br>';
			endwhile;
			echo '<p class="description"><em><a href="' . admin_url( 'edit.php?post_type=person' ) . '" target="_blank">' . esc_html__( 'Manage speakers.', 'knd' ) . '</a>';
			echo '</em></p>';
		else:
			echo '<p class="description">' . esc_html__( 'There are no speakers on the site.', 'knd' ) . ' ';
			echo '<a href="' . admin_url( 'edit.php?post_type=person' ) . '" target="_blank">' . esc_html__( 'Add a new speaker.', 'knd' ) . '</a>';
			echo '</p>';
		endif;
		wp_reset_postdata();

		echo '		</td>';
		echo '	</tr>';

		$schedules = get_post_meta( $post->ID, '_schedule', true );
		if ( ! $schedules ) {
			$schedules = array();
		}

		echo '	<tr>';
		echo '		<th><label>' . esc_html__( 'Schedule', 'knd' ) . '</label></th>';
		echo '		<td>';

		$schedules_html = '<div class="knd-event-admin-schedule">';

		foreach( $schedules as $key => $schedule ) {

			$schedules_html .= '<div class="knd-event-admin-schedule-group">';

				$title = '';
				if ( isset( $schedule['title'] ) ) {
					$title = $schedule['title'];
				}

				$schedules_html .= '<div class="knd-event-admin-schedule-name"><input type="text" name="_schedule[' . $key . '][title]" value="' . $title . '" class="regular-text"><a href="#" class="button button-link button-link-delete knd-event-remove-group">' . esc_html__( 'Remove schedule', 'knd' ) . '</a></div>';

				$schedules_html .= '<div class="knd-event-admin-schedule-list">';

				if ( isset( $schedule['list'] ) ) {

					foreach( $schedule['list'] as $id => $item ) {

						$hour_start = $item['hour_start'];
						$hour_end   = $item['hour_end'];
						$desc       = $item['desc'];

						$schedules_html .= '<div class="knd-event-admin-schedule-item">
							<div class="knd-event-admin-schedule-times">
								<input type="text" name="_schedule[' . $key . '][list][' . $id . '][hour_start]" class="em-time-input ui-em_timepicker-input" maxlength="8" size="8" value="' . $hour_start . '" autocomplete="off">
								<input type="text" name="_schedule[' . $key . '][list][' . $id . '][hour_end]" class="em-time-input ui-em_timepicker-input" maxlength="8" size="8" value="' . $hour_end . '" autocomplete="off">

								<a href="#" class="button button-link button-link-delete button-small knd-event-remove-item">' . esc_html__( 'Remove time', 'knd' ) . '</a>
							</div>
							<textarea class="large-text" name="_schedule[' . $key . '][list][' . $id . '][desc]" cols="2">' . $desc . '</textarea>
						</div>';

					}
				}

				$schedules_html .= '</div>';

				$schedules_html .= '<a href="#" class="button button-secondary knd-event-add-time">' . esc_html__( 'Add time', 'knd' ) . '</a>';

			$schedules_html .= '</div>';

		}

		$schedules_html .= '</div>';

		$schedules_html .= '<div><a href="#" class="button button-primary knd-event-add-schedule">' . esc_html__( 'Add schedule', 'knd' ) . '</a></div>';

		echo $schedules_html;

		
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {
		
		/* Add nonce for security and authentication */
		$nonce_name   = isset( $_POST['event_meta_nonce'] ) ? $_POST['event_meta_nonce'] : '';
		$nonce_action = 'event_meta_action';

		/* Check if a nonce is set */
		if ( ! isset( $nonce_name ) )
			return;

		/* Check if a nonce is valid */
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		/* Check if the user has permissions to save data */
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		/* Check if it's not an autosave */
		if ( wp_is_post_autosave( $post_id ) )
			return;

		/* Sanitize user input */
		$is_sticky = isset( $_POST[ '_is_sticky' ] ) ? sanitize_text_field( $_POST[ '_is_sticky' ] ) : '';
		$booking_header = isset( $_POST[ '_booking_header' ] ) ? sanitize_text_field( $_POST[ '_booking_header' ] ) : '';
		$booking_desc = isset( $_POST[ '_booking_desc' ] ) ? sanitize_text_field( $_POST[ '_booking_desc' ] ) : '';
		$speakers  = isset( $_POST[ '_speakers' ] ) ? (array) $_POST[ '_speakers' ] : array();

		$schedule  = isset( $_POST[ '_schedule' ] ) ? (array) $_POST[ '_schedule' ] : array();

		/* Update the meta field in the database */
		update_post_meta( $post_id, '_is_sticky', $is_sticky );
		update_post_meta( $post_id, '_booking_header', $booking_header );
		update_post_meta( $post_id, '_booking_desc', $booking_desc );
		update_post_meta( $post_id, '_speakers', $speakers );
		update_post_meta( $post_id, '_schedule', $schedule );

	}

}

new Knd_Event_Meta_Box;
