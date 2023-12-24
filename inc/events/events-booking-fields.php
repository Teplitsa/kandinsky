<?php
/**
 * Events Booking Fields Template
 */

$booking_comment = '';
if ( isset( $_REQUEST['booking_comment'] && ! empty( $_REQUEST['booking_comment'] ) ) ) {
	$booking_comment = wp_unslash( $_REQUEST['booking_comment'] );
}
?>
<?php if( !is_user_logged_in() && apply_filters('em_booking_form_show_register_form',true) ): ?>
	<?php //User can book an event without registering, a username will be created for them based on their email and a random password will be created. ?>
	<input type="hidden" name="register_user" value="1" />
	<?php do_action('em_register_form'); //careful if making an add-on, this will only be used if you're not using custom booking forms ?>
<?php endif; ?>
<p>
	<label for='booking_comment'><?php _e('Comment', 'events-manager') ?></label>
	<textarea name='booking_comment' rows="2" cols="20"><?php echo wp_kses_post( $booking_comment ); ?></textarea>
</p>
