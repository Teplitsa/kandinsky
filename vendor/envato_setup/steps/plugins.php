<?php
/**
 * Wizard Step Plugins
 */

tgmpa_load_bulk_installer();
if( !class_exists('TGM_Plugin_Activation') || !isset($GLOBALS['tgmpa'])) {
	die(__('Failed to find TGM plugin', 'knd'));
}

// Prevent start Leyka wizard if is activated via Theme wizard.
if ( ! get_option( 'leyka_last_ver' ) ) {
	update_option( 'leyka_last_ver', '3.10');
}

if ( ! get_option( 'wpseo' ) ) {
	$wpseo_default = array(
		'should_redirect_after_install_free' => 1,
		'activation_redirect_timestamp_free' => time(),
	);
	update_option( 'wpseo', $wpseo_default );
}

?>

<h1><?php esc_html_e('Default Plugins', 'knd'); ?></h1>
<form method="post">

	<?php $plugins = $this->_get_plugins();
	if($plugins['all']) {

		$plugins_required = $plugins_recommended = array();

		foreach($plugins['all'] as $slug => $plugin) {
			if(empty($plugin['required'])) {
				$plugins_recommended[ $slug ] = $plugin;
			} else {
				$plugins_required[ $slug ] = $plugin;
			}
		}

		if($plugins_required) { ?>

			<p><?php esc_html_e('Your website needs a few essential plugins. The following plugins will be installed or updated:', 'knd'); ?></p>
			<p><?php esc_html_e('You can add and remove plugins later on, in the Plugins admin folder.', 'knd'); ?></p>

			<ul class="envato-wizard-plugins">
				<?php foreach($plugins_required as $slug => $plugin) { ?>
					<li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?>
						<span>
				<?php $plugin_status = '';

				if(isset($plugins['install'][ $slug ])) {
					$plugin_status = __('Installation required', 'knd');
				} else if(isset($plugins['update'][ $slug ])) {
					$plugin_status = isset($plugins['activate'][ $slug ]) ?
						__('Update and activation required', 'knd') : __('Update required', 'knd');
				} else if(isset($plugins['activate'][ $slug ])) {
					$plugin_status = __('Activation required', 'knd');
				}

				echo $plugin_status; ?>
						</span>
						<div class="spinner"></div>

						<div class="knd-plugin-description"><?php echo $plugin['description'] ?></div>
					</li>
				<?php } ?>
			</ul>
		<?php }

		if($plugins_recommended) {

			if($plugins_required) { ?>
				<p><?php esc_html_e('We also recommend to add several more:', 'knd'); ?></p>
			<?php } else { ?>
				<p><?php esc_html_e('We recommend to add or update the following plugins:', 'knd'); ?></p>
			<?php } ?>

			<ul class="envato-wizard-plugins-recommended">
				<?php foreach($plugins_recommended as $slug => $plugin) { ?>
					<li data-slug="<?php echo esc_attr($slug); ?>"><?php echo esc_html($plugin['name']); ?>
						<span>

							<?php $plugin_status = '';

							if(isset($plugins['install'][ $slug ])) {
								$plugin_status = __('Install', 'knd');
							} else if(isset($plugins['update'][ $slug ])) {
								$plugin_status = isset($plugins['activate'][ $slug ]) ?
									__('Update and activate', 'knd') : __('Update', 'knd');
							} else if(isset($plugins['activate'][ $slug ])) {
								$plugin_status = __('Activate', 'knd');
							} ?>

							<label>
								<input type="checkbox" class="plugin-accepted" name="knd-recommended-plugin-<?php echo $slug; ?>">
								<?php echo $plugin_status; ?>
							</label>

						</span>
						<div class="spinner"></div>

						<div class="knd-plugin-description"><?php echo $plugin['description'] ?></div>

					</li>
				<?php } ?>
			</ul>

		<?php }

	} else {
		echo '<p><strong>'.esc_html_e("Good news! All plugins are already installed and up to date. Let's proceed further.", 'knd').'</strong></p>';
	} ?>

	<div class="envato-setup-actions knd-wizard-actions step">
		<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next" data-callback="installPlugins">
			<?php esc_html_e('Continue', 'knd'); ?>
		</a>
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next button-skip">
			<span class="button-text"><?php esc_html_e('Skip this step', 'knd'); ?></span>
			<span class="dashicons dashicons-controls-skipforward"></span>
		</a>
		<?php wp_nonce_field('envato-setup'); ?>
	</div>
</form>