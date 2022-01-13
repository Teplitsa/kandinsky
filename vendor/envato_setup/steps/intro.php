<?php
/**
 * Wizard Step Intro
 */

// Remove the old scenario import data:
$is_show_hello = true;
$current_site_scenario = knd_get_theme_mod('knd_site_scenario');
if($current_site_scenario) {
	$is_show_hello = false;
	
	$destination = wp_upload_dir();
	$unzipped_dir = "{$destination['path']}/kandinsky-text-"
		.knd_get_wizard_plot_names($current_site_scenario).'-master';

	$knd_fs = Knd_Filesystem::get_instance();
	if($knd_fs) {
		$is_show_hello = true;
	}
	if($knd_fs && $knd_fs->is_dir($unzipped_dir)) {
		$knd_fs->rmdir($unzipped_dir, true);
	}
	
}

$locale     = get_locale();
$thumb_slug = 'demo-thumb';

if ( 'ru_RU' === $locale ) {
	$thumb_slug .= '-ru';
}

$demo_thumb_url = get_template_directory_uri() . '/assets/images/' . $thumb_slug . '.jpg';

if ( $is_show_hello) : ?>

<h1><?php esc_html_e( 'Kandinsky Configuration Wizard', 'knd' ); ?></h1>
<form method="post">

	<input type="hidden" name="new_scenario_id" id="new_scenario_id" value="problem-org">

	<p><?php esc_html_e( 'You have installed the Kandinsky theme. This is how your default website design will look like:', 'knd' ); ?></p>

	<p class="knd-wizard-preview">
		<img src="<?php echo esc_url( $demo_thumb_url ); ?>" alt="">
	</p>

	<p><?php esc_html_e( 'Now you can make basic settings - upload a logo, set the site structure, install the necessary add-ons. This will take a couple of minutes. And you can change the text, pictures, colors and fonts later.', 'knd' ); ?></p>

	<p><?php esc_html_e( 'If you want to skip the setup, click «Not right now». You can customize the theme yourself at any time from the admin panel.', 'knd' ); ?></p>

	<div class="wizard-error" id="knd-download-plot-error" style="display: none;">
		<span class="error-begin"><?php esc_html_e('Error:', 'knd')?></span>
		<span class="error-text"><?php esc_html_e('Downloading theme file failed!', 'knd')?></span>
		<div class="wizard-error-support-text"></div>
		<p class="envato-setup-actions error step">
			<a href="<?php echo admin_url()?>" class="button button-large button-error"><?php esc_html_e('Back to the Dashboard', 'knd')?></a>
			<a href="mailto:<?php echo KND_SUPPORT_EMAIL?>" class="button button-error button-large button-primary"><?php esc_html_e('Email to the theme support', 'knd')?></a>
		</p>
	</div>

	<div class="envato-setup-actions knd-wizard-actions step">
		<a class="button button-large button-wizard-back" href="<?php echo esc_url( admin_url() ); ?>">
			<?php esc_html_e('Not right now', 'knd'); ?>
		</a>
		<input type="submit" class="button-primary button button-large button-next" id="knd-install-scenario" data-callback="kndDownloadPlotStep" value="<?php esc_attr_e("Let's go!", 'knd'); ?>" name="save_step">
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next button-skip knd-download-plot-skip">
			<span class="button-text"><?php esc_html_e('Skip this step', 'knd'); ?></span>
			<span class="dashicons dashicons-controls-skipforward"></span>
		</a>
		<span id="knd-download-status-explain" style="display: none;"><?php esc_html_e('Downloading template archive...', 'knd')?></span>
		<?php wp_nonce_field('knd-setup'); ?>
	</div>
</form>

<?php
endif;
