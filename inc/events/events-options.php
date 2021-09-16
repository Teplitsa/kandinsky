<?php
/**
 * Kandinsky Events Options
 *
 * @package Kandinsky
 */

/**
 * Update Events Options On Activate Theme
 */
function knd_events_update_default_options() {

	$options = array();

	if ( ! get_option( 'dbem_options_updated' ) ) {

		$em_options = array(
			'dbem_tags_enabled'       => false,// disable tags. Events > General > General Options - Enable tags?
			'dbem_categories_enabled' => false, // disable categories. Events > General > General Options - Enable categories?
			'dbem_credits'            => false, // Disable plugin credits.

			'dbem_location_default_country' => 'RU', // Set default location country
			'dbem_timezone_default'         => 'Europe/Moscow', // Set default timezone
			'dbem_bookings_submit_button'   =>  esc_html__( 'Book Now', 'knd' ),
			'dbem_cp_events_comments'       => false, // disable comments.
			'dbem_events_page_search_form'  => false, // disable search form on events page.
			'dbem_events_archive_scope'     => 'all', // Event archives scope to all.
			'dbem_time_format'              => 'g:i', // Time Format.
			'dbem_location_default_country' => 'RU', // Set default location country to RU

			'dbem_cp_events_custom_fields'  => false,
			// 'dbem_css_evlist' => false,
			'dbem_cp_events_formats'        => false, // Disable auto load content in the_content.
			'dbem_options_updated'          => true,

			// Custom theme additional options.
			'dbem_event_schedule_heading'   => esc_html__( 'Schedule', 'knd' ),
			'dbem_event_question_heading'   => esc_html__( 'Still have questions?', 'knd' ),
			'dbem_event_question_content'   => esc_html__( 'Write to Alisa Tsvetkova in Telegram @itisalise or email alisetsvetkova@te-st.ru', 'knd' ),
			'dbem_event_related_heading'    => esc_html__( 'More events', 'knd' ),

		);

		$options = array_merge( $options, $em_options );
	}

	foreach ( $options as $option => $value ) {
		update_option( $option, $value );
	}

}
add_action( 'after_switch_theme', 'knd_events_update_default_options' );
