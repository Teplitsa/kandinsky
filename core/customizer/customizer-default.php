<?php
/**
 * Customizer Deprecated
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Add admin notice if Kirki plugin not activated.
 */
function knd_kirki_admin_notices() {

	if ( get_option( 'knd_disable_kirki_notice' ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( 'appearance_page_knd-install-plugins' !== $screen->id ) {

		?>
		<div class="notice notice-info knd-kirki-notice">
			<p><?php esc_html_e('In order to make Kandinsky work full speed, you will need few plugins. WordPress will install or update the following plugins:', 'knd');?></p>
			<ul>
				<li><strong>Kirki Customizer Framework</strong><br>
					<i><?php esc_html_e('Plugin that allows web-font customization.', 'knd');?></i>
				</li>
			</ul>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=knd-install-plugins' ) ); ?>">
					<?php esc_html_e( 'Install', 'knd' ); ?>
				</a>
				<a class="button-secondary button delete-theme knd-kirki-notice-dismiss" href="#">
					<?php esc_html_e( 'No, I’ll do it some other time', 'knd' );?>
				</a>
			</p>
			<button type="button" class="knd-kirki-notice-dismiss notice-dismiss"></button>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'knd_kirki_admin_notices' );

/**
 * Save dismiss notice status
 */
function knd_save_dismiss_notice() {

	/* Check nonce */
	check_ajax_referer( 'knd-nonce', 'nonce' );

	/* Stop if the current user is not an admin or do not have administrative access */
	if ( ! current_user_can( 'manage_options' ) ) {
		die();
	}

	update_option( 'knd_disable_kirki_notice', true );

	$result = get_option( 'knd_disable_kirki_notice' );

	wp_send_json( $result );

}
add_action( 'wp_ajax_knd_dismiss_notice', 'knd_save_dismiss_notice' );
