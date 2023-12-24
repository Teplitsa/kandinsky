<?php
/**
 * Kandinsky Events Functions
 *
 * @package Kandinsky
 */

/**
 * Prevents booking form showing below post
 */
//define( 'EM_DISABLE_AUTO_BOOKINGSFORM', true );

/**
 * Remove auto adding event to below post
 */
remove_action( 'get_the_excerpt', array('EM_Event_Post', 'enable_the_content'), 100 );

/**
 * Remove auto adding custom elements to post content
 */
remove_filter( 'the_content',  array('EM_Event_Post','the_content') );

/**
 * Remove auto adding events in the_content.
 */
function knd_em_remove_content_filter_after_head(){
	if ( is_page( get_option( 'dbem_events_page' ) ) ) {
		remove_action( 'wp_head', 'em_add_content_filter_after_head', 1000 );
	}
}
add_action( 'wp_loaded', 'knd_em_remove_content_filter_after_head' );

/**
 * Init all event metas
 */
function knd_init_event_metas( $post_id = null ) {

	if ( is_null( $post_id ) ) {
		$post_id = get_the_ID();
	}
	$this_event = new EM_Event( $post_id, 'post_id');
	if (! $this_event ) {
		return false;
	}
	return $this_event;
}

/**
 * Get event meta
 * 
 *     only day date: #j            15
 *       short month: #M            jun
 *        start date: #_{d-m-Y}     15-07-2021
 *          end date: #@_{d-m-Y}    16-07-2021
 * dates start - end: #_EVENTDATES  15-07-2021 - 16-07-2021
 * #_24HSTARTTIME
 * #_24HENDTIME
 * #_EVENTTIMES
 * #_EVENTTIMEZONE
 */
function knd_get_event_meta( $key = '',  $this_event = null ) {

	if ( null ===  $this_event ) {
		global $this_event;
	}

	if ( ! $this_event ) {
		return false;
	}
	if ( ! $key ) {
		return false;
	}

	$meta = false;

	if ( '#' === $key[0] ) {
		$meta = $this_event->output( $key );
	} else {
		if ( isset( $this_event->$key ) ) {
			$meta = $this_event->$key;
		}
	}

	if ( ! $meta ) {
		return false;
	}

	return $meta;
}

/**
 * Is booking open
 */
function knd_is_booking_open() {

	global $this_event;

	$is_open = $this_event->get_bookings()->is_open();

	return $is_open;
}

/**
 * Get event remained
 */
function knd_get_event_remained() {


	if ( knd_is_booking_open() ) {
		$current_date = current_time( 'timestamp' );
		$start_date   = strtotime( knd_get_event_meta( '#_BOOKINGSCUTOFFDATE' ) . ' ' . knd_get_event_meta( '#_BOOKINGSCUTOFFTIME' ) );
		$time_diff    = human_time_diff( $start_date, $current_date );
		$remained     = sprintf( esc_html__( '%s before registration closes', 'knd' ), $time_diff ); 
	} else {
		$remained = esc_html__( 'Registration is closed', 'knd' );
	}

	return $remained;
}

/**
 * Get event type
 */
function knd_get_event_type() {

	$event_type = false;

	if ( 'url' === knd_get_event_meta( 'event_location_type') ) {
		$event_type = '<span class="knd-event__details-location-type">' . esc_html__( 'online', 'knd' ) . '</span>';

		if ( knd_get_booking_status() || ! knd_get_event_meta( 'event_rsvp') ) {
			$event_type = knd_get_event_meta( '#_EVENTLOCATION' );
		}
	} else if ( knd_get_event_meta( '#_LOCATIONPOSTID' ) ) {
		$locations = array(
			knd_get_event_meta( '#_LOCATIONCOUNTRY' ),
			knd_get_event_meta( '#_LOCATIONTOWN' ),
			knd_get_event_meta( '#_LOCATIONADDRESS' )
		);

		$event_type = implode( ', ', $locations );
	}

	// #_LOCATIONMAP

	return $event_type;
}

/**
 * Get event booking status
 */
function knd_get_booking_status() {
	global $this_event;

	$this_booking = $this_event->get_bookings()->has_booking();

	$booking_status = false;

	if ( isset( $this_booking->status ) && '1' === $this_booking->status ) {
		$booking_status = true;
	}

	return $booking_status;
}

/**
 * Check if archive grid
 */
function knd_get_archive_type(){
	$type = 'list';
	if ( isset( $_COOKIE['kndArchiveType'] ) && 'grid' ===  $_COOKIE['kndArchiveType'] ) {
		$type = 'grid';
	}
	return $type;
}

/**
 * Event List Limits
 */
function knd_pre_get_events( $query ) {

	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'event' ) ){

		$posts_per_page = (int) get_option( 'dbem_events_default_limit' );

		$query->set( 'posts_per_page', $posts_per_page );

		$sticky_args = array(
			'post_type'      => 'event',
			'posts_per_page' => -1,
			'meta_key'       => '_is_sticky',
			'meta_value'     => '1',
		);

		$sticky_posts = get_posts( $sticky_args );

		$exclude_ids = wp_list_pluck( $sticky_posts, 'ID' );

		if ( $exclude_ids ) {
			$query->set('post__not_in', $exclude_ids );
		}

	}
}
add_action( 'pre_get_posts', 'knd_pre_get_events' );

/**
 * Event List Pagination
 */
function knd_em_paginate( $paginate ) {
	$paginate = strip_tags( str_replace( 'class="em-pagination"', 'class="em-pagination nav-links"', $paginate ), '<div><a><span>' );
	return '<div class="knd-pagination">' . $paginate . '</div>';
}
add_filter( 'em_paginate', 'knd_em_paginate' );

/**
 * Load Cities translate.
 */
function knd_load_textdomain_cities(){
	$locale_loaded = get_locale();
	$mofile        = WP_LANG_DIR . '/continents-cities-' . $locale_loaded . '.mo';
	load_textdomain( 'continents-cities', $mofile );
}
add_action( 'knd_load_textdomain', 'knd_load_textdomain_cities' );


// event_rsvp

/**
 * Add title to boking form header.
 */
function knd_em_booking_form_header( $event ){
	$event;
	if ( knd_get_event_meta( 'event_rsvp' ) ) { ?>
		<?php if ( get_post_meta( get_the_ID(), '_booking_header', true ) || get_post_meta( get_the_ID(), '_booking_desc', true ) ) { ?>
		<div class="knd-event__booking-header">
			<?php if ( get_post_meta( get_the_ID(), '_booking_header', true ) ) { ?>
				<h5><?php echo get_post_meta( get_the_ID(), '_booking_header', true ); ?></h5>
			<?php } ?>
			<?php if ( get_post_meta( get_the_ID(), '_booking_desc', true ) ) { ?>
				<p><?php echo get_post_meta( get_the_ID(), '_booking_desc', true ); ?></p>
			<?php } ?>
		</div>
	<?php }
	}
}
add_action( 'em_booking_form_header', 'knd_em_booking_form_header' );


//do_action('em_options_page_tab_'. $_REQUEST['em_tab']);

function knd_em_options_page_tabs(){
	$tabs = array(
		'custom' => esc_html__( 'Custom Settings', 'knd' ),
	);
	return $tabs;
}
add_filter( 'em_options_page_tabs', 'knd_em_options_page_tabs' );


function knd_em_options_page_tab_custom(){
	global $save_button;

?>

	<div  class="postbox" id="em-opt-custom" >
		<h3><span><?php esc_html_e( 'Additional settings for the single event page', 'knd'); ?></span></h3>
		<div class="inside">
			
			<table class="form-table">
				<tbody class="form-table">
					<?php
					em_options_input_text( __( 'Schedule heading', 'knd' ), 'dbem_event_schedule_heading' );
					em_options_input_text( __( 'Question heading', 'knd' ), 'dbem_event_question_heading' );
					em_options_textarea( __( 'Question content', 'knd'), 'dbem_event_question_content' );
					em_options_input_text( __( 'Related heading', 'knd' ), 'dbem_event_related_heading' );
					?>
				</tbody>
				<?php
					echo wp_kses_post( $save_button );
				?>
			</table>
		</div> <!-- . inside -->
	</div> <!-- .postbox -->

<?php
}
add_action('em_options_page_tab_custom', 'knd_em_options_page_tab_custom' );

function knd_em_booking_form_default_fields(){
	$original_fields = array(
		array(
			'label' => esc_attr__('Name','events-manager'),
			'slug' => 'user_name',
			'order' => 0,
		),
		array(
			'label' => esc_attr__('Phone','events-manager'),
			'slug' => 'dbem_phone',
			'order' => 1,
		),
		array(
			'label' => esc_attr__('E-mail','events-manager'),
			'slug' => 'user_email',
			'order' => 2,
		),
	);
	return $original_fields;
}

function knd_em_options_booking_form_options(){

	$original_fields = knd_em_booking_form_default_fields();

	$custom_fields = get_option( 'dbem_bookings_custom_fields' );

	em_options_input_text( __( 'CTA button text', 'knd' ), 'dbem_bookings_cta_field', '', esc_html__( 'Book Now', 'knd' ) );

	if ( $custom_fields && is_array( $custom_fields ) ) {
		$has_user_name = false;
		foreach ( $custom_fields as $field ) {
			if ( $field['slug'] === 'user_name' ) {
				$has_user_name = true;
			}
		}
		if ( $has_user_name ) {
			$fields = $custom_fields;
		} else {
			$fields = array_merge( $original_fields, $custom_fields);
		}
	} else {
		$fields = $original_fields;
	}

	?>
	<tr valign="top" id="dbem_bookings_custom_fileds_row">
		<th scope="row"><?php esc_html_e( 'Custom Fields', 'knd' ); ?></th>
		<td>
			<input name="dbem_bookings_custom_fields" type="hidden" value="">
			<div class="dbem-kookings-fields">

				<?php if ( $fields ) {
					$order = 0;
					foreach ( $fields as $key => $field ) {

						$field_label = '';
						if ( isset( $field['label'] ) ) {
							$field_label = $field['label'];
						};
						$field_slug  = '';
						if ( isset( $field['slug'] ) ) {
							$field_slug = $field['slug'];
						};
						$disabled = false;
						foreach($original_fields as $original_field){
							if ( $original_field['slug'] === $field['slug']) {
								$disabled = true;
							}
						}
						?>
						<div class="dbem-kookings-fields-group">
							<div class="drag-icons-group">
								<i class="dashicons dashicons-ellipsis"></i>
								<i class="dashicons dashicons-ellipsis"></i>
							</div>
							<input name="dbem_bookings_custom_fields[<?php echo esc_attr( $key ); ?>][order]" class="bookings-custom-field-order" type="hidden" value="<?php echo esc_attr( $order++ ); ?>">
							<input name="dbem_bookings_custom_fields[<?php echo esc_attr( $key ); ?>][label]" class="bookings-custom-field-label"  type="text" value="<?php echo esc_attr( $field_label ); ?>" placeholder="<?php esc_html_e( 'Label', 'knd' ); ?>">
							<input name="dbem_bookings_custom_fields[<?php echo esc_attr( $key ); ?>][slug]" class="bookings-custom-field-slug"  type="text" value="<?php echo esc_attr( $field_slug ); ?>" placeholder="<?php esc_html_e( 'slug', 'knd' ); ?>" <?php wp_readonly( $disabled, true ); ?>>
							<?php if ( ! $disabled ) { ?>
								<a href="#" class="button button-link button-link-delete knd-booking-fields-remove"><?php esc_html_e( 'Delete', 'knd' ); ?></a>
							<?php } ?>
						</div>
						<?php
					}
				} ?>
			</div>
			<a href="#" class="button button-secondary knd-booking-add-field"><?php esc_html_e( 'Add new field', 'knd' ); ?></a>
		</td>
	</tr>
	<?php
}
add_action( 'em_options_booking_form_options', 'knd_em_options_booking_form_options' );

function knd_em_custom_form_fields(){
	$fields_option = get_option( 'dbem_bookings_custom_fields' );
	if ( ! $fields_option ) {
		$fields_option = knd_em_booking_form_default_fields();
	}
	$fields = array();
	if ( $fields_option && is_array( $fields_option ) ) {
		foreach ( $fields_option as $key => $field ) {
			if ( isset( $field['label'] ) && isset( $field['slug'] ) && $field['label'] && $field['slug'] ) {
				$fields[ $key ]['label'] = $field['label'];
				$fields[ $key ]['slug'] = $field['slug'];
			}
		}
	}
	return apply_filters( 'knd_em_custom_form_fields', $fields );
}

function knd_em_custom_clean_form_fields(){
	$fields = knd_em_custom_form_fields();
	if ( ! $fields ) {
		return;
	}
	foreach( $fields as $key => $field ) {
		if ( 'user_name' === $field['slug'] || 'dbem_phone' === $field['slug'] || 'user_email' === $field['slug'] ) {
			unset( $fields[$key] );
		}
	}
	return $fields;
}


function knd_em_register_form(){
	$fields = knd_em_custom_form_fields();
	if ( ! $fields ) {
		return;
	}

	foreach( $fields as $field ) {
		if ( isset( $field['slug'] ) ) {
			$slug = trim( $field['slug'] );
			$label = $field['label'];

			$attr = 'dbem_' . $slug;
			if ( 'user_name' === $slug || 'dbem_phone' === $slug || 'user_email' === $slug ) {
				$attr = $slug;
			}

			$value = '';
			if ( isset( $_REQUEST[ $attr ] ) && ! empty($_REQUEST[ $attr ] ) ) {
				$value = wp_unslash( $_REQUEST[ $attr ] );
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $attr ); ?>"><?php echo esc_html( $label ); ?></label>
				<input type="text" name="<?php echo esc_attr( $attr ); ?>" id="<?php echo esc_attr( $attr ); ?>" class="input" value="<?php echo esc_attr( $value ); ?>">
			</p>
			<?php
		}
	}
}
add_action( 'em_register_form', 'knd_em_register_form' );

function knd_em_registration_errors( $errors ){

	if ( isset( $_REQUEST['user_email'] ) && $_REQUEST['user_email']) {
		print_r($_REQUEST['user_email']);
		if ( isset( $_REQUEST['user_name'] ) && ! $_REQUEST['user_name'] ){
			$errors->add( 'invalid_' . $slug, __( '<strong>ERROR</strong>: Please enter a username.', 'events-manager') );
		}
	}

	if ( isset( $_REQUEST['dbem_phone'] ) && ! $_REQUEST['dbem_phone']) {
		$errors->add( 'invalid_' . $slug, __( '<strong>ERROR</strong>: Please enter your phone number.', 'knd') );
	}

	$fields = knd_em_custom_clean_form_fields();
	if ( ! $fields ) {
		return $errors;
	}

	foreach( $fields as $field ) {
		if ( isset( $field['slug'] ) ) {
			$slug = trim( $field['slug'] );
			$label = $field['label'];
			$attr = 'dbem_' . $slug;
			if ( isset( $_REQUEST[ $attr ] ) && ! $_REQUEST[ $attr ] ){
				$errors->add( 'invalid_' . $slug, __( '<strong>ERROR</strong>: Please fill in the field', 'knd') . ' ' . $label );
			}
		}
	}

	return $errors;
}
add_filter( 'em_registration_errors', 'knd_em_registration_errors' );

function knd_em_register_new_user( $user_id ){

	$fields = knd_em_custom_clean_form_fields();
	if ( $fields ) {
		foreach( $fields as $field ) {
			if ( isset( $field['slug'] ) ) {
				$slug = trim( $field['slug'] );
				$attr = 'dbem_' . $slug;
				if ( isset( $_REQUEST[ $attr ] ) ) {
						update_user_meta( $user_id, $attr, $_REQUEST[ $attr ]);
				}
			}
		}
	}

	return $user_id;
}
add_filter( 'em_register_new_user', 'knd_em_register_new_user' );

/**
 * Edit Booking
 */
function knd_em_person_display_summary( $html, $current ){

	$user_id = $current->ID;

	$fields = knd_em_custom_clean_form_fields();
	if ( $fields ) {
		foreach( $fields as $field ) {
			if ( isset( $field['slug'] ) ) {
				$slug = trim( $field['slug'] );
				$label = $field['label'];
				$attr = 'dbem_' . $slug;

				$user_meta = get_user_meta( $user_id, $attr, true );
				$html = preg_replace( '/<\/table>/', '<tr><th>' . $label . ':</th><td>' . $user_meta . '</td></tr></table>', $html, 1 );
			}
		}
	}
	return $html;
}
add_filter( 'em_person_display_summary', 'knd_em_person_display_summary', 10, 2 );


function knd_user_contactmethods( $array ) {
	$fields = knd_em_custom_clean_form_fields();
	if ( $fields ) {
		foreach( $fields as $field ) {
			if ( isset( $field['slug'] ) ) {
				$slug = trim( $field['slug'] );
				$label = $field['label'];
				$attr = 'dbem_' . $slug;
				$array[ $attr ] = $label;
			}
		}
	}
	return $array;
}
add_filter( 'user_contactmethods', 'knd_user_contactmethods' );

/**
 * Login Form
 */
function knd_em_locate_template( $located, $template_name, $load, $the_args ){
	if ( 'forms/bookingform/login.php' === $template_name ) {
		$located = get_template_directory() . '/inc/events/events-login-form.php';
	} else if ( 'forms/bookingform/booking-fields.php' === $template_name ) {
		$located = get_template_directory() . '/inc/events/events-booking-fields.php';
	}
	return $located;
}
add_filter( 'em_locate_template', 'knd_em_locate_template', 10, 4 );
