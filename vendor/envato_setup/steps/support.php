<?php
/**
 * Wizard Step Support
 */

if ( defined('LEYKA_VERSION') && get_option('knd_setup_install_leyka') ) {

	if ( is_plugin_active('leyka/leyka.php') ) {
		knd_activate_leyka();
		update_option('knd_setup_install_leyka', false);
		// Add current Leyka version.
		update_option( 'leyka_last_ver', LEYKA_VERSION );
	}

}

if ( defined( 'EM_VERSION' ) ) {
	knd_events_update_default_options();
}

?>

<h1><?php esc_html_e('Help and support', 'knd'); ?></h1>

<p>
	<?php esc_html_e('Thank you for using “Kandinsky” theme on your website!','knd'); ?>
</p>
<p>
	<?php printf(__('“Kandinsky” — is a free and open-source project supported by <a href="%s" target="_blank">Teplitsa. Technologies for Social Good</a> together with the community of independent developers.', 'knd'), TST_OFFICIAL_WEBSITE_URL); ?>
</p>
<p><?php esc_html_e('In case you encounter any questions or issues, we recommend you the following links:', 'knd'); ?></p>
<ul class="knd-wizard-support-variants">
	<li><?php echo sprintf( __('Documentation and FAQ — <a href="%s" target="_blank">GitHub Wiki</a>', 'knd'), esc_url( KND_DOC_URL ) ); ?></li>
	<li><?php echo sprintf( __('Source code — <a href="%s" target="_blank">GitHub</a>', 'knd'), esc_url( KND_SOURCES_PAGE_URL ) ); ?></li>
	<li><?php echo sprintf( __('Developers’ <a href="%s">Telegram-channel</a> (in Russian)', 'knd'), esc_url( KND_SUPPORT_TELEGRAM ) ); ?></li>
</ul>
<p><?php echo sprintf( __('If you need personalized (free during the testing period) consultations from the theme developers, please feel free to write at <a href="mailto:%s" target="_blank">%s</a> or <a href="%s" target="_blank">leave a ticket at GitHub</a>.', 'knd'), KND_SUPPORT_EMAIL, KND_SUPPORT_EMAIL, KND_SOURCES_ISSUES_PAGE_URL ); ?></p>

<p class="envato-setup-actions step">
	<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
	<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next"><?php esc_html_e("OK, I've got it!", 'knd'); ?></a>
</p>
