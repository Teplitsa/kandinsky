<?php
/**
 * Wizard Step Scenario
 */

/*
?>

<h1><?php esc_html_e('Choose website template', 'knd'); ?></h1>


<form method="post">
	
	<div class="wizard-error" id="knd-download-plot-error" style="display: none;">
		<span class="error-begin"><?php esc_html_e('Error:', 'knd')?></span>
		<span class="error-text"><?php esc_html_e('Downloading theme file failed!', 'knd')?></span>
		<div class="wizard-error-support-text"></div>
		<p class="envato-setup-actions error step">
			<a href="<?php echo admin_url()?>" class="button button-large button-error"><?php esc_html_e('Back to the Dashboard', 'knd')?></a>
			<a href="mailto:<?php echo KND_SUPPORT_EMAIL?>" class="button button-error button-large button-primary"><?php esc_html_e('Email to the theme support', 'knd')?></a>
		</p>
	</div>

	<p><?php esc_html_e('For your convenience, we’ve created several templates for NGOs. Select the one that you fits you best. You will be able to change colours, content (text and images).', 'knd'); ?></p>
	
	<div class="theme-presets">
		<ul>
			<?php $current_scenario_id = knd_get_theme_mod('knd_site_scenario', $this->get_default_site_scenario_id());

			if(empty($this->site_scenarios)) {
				throw new Exception(__('No scenarios detected', 'knd'), 1);
			}

			$locale = get_locale();

			unset( $this->site_scenarios['fundraising-org'] );
			unset( $this->site_scenarios['public-campaign'] );

			foreach( $this->site_scenarios as $scenario_id => $data) {
				?>
				<li <?php echo $scenario_id == $current_scenario_id ? 'class="current" ' : ''; ?>>
					<a href="#" data-scenario-id="<?php echo esc_attr($scenario_id); ?>">
						<img src="<?php echo esc_url(get_template_directory_uri().'/vendor/envato_setup/images/'.$scenario_id.'/style.png'); ?>">
						<span class="plot-data">
						<h3 class="plot-title"><?php echo $data['name']; ?></h3>
						<div class="plot-info">
							<?php echo empty($data['description']) ? '' : $data['description']; ?>
						</div>
					</span>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>

	<input type="hidden" name="new_scenario_id" id="new_scenario_id" value="<?php echo $current_scenario_id ? $current_scenario_id : ''; ?>">

	<div class="envato-setup-actions knd-wizard-actions step">
		<a class="button button-large button-wizard-back" href="<?php echo esc_url( admin_url() ); ?>">
			<?php esc_html_e( 'Exit', 'knd'); ?>
		</a>
		<input type="submit" class="button-primary button button-large button-next" id="knd-install-scenario" data-callback="kndDownloadPlotStep" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next knd-download-plot-skip">
			<?php esc_html_e('Skip this step', 'knd'); ?>
		</a>
		<span id="knd-download-status-explain" style="display: none;"><?php esc_html_e('Downloading template archive...', 'knd')?></span>
		<?php wp_nonce_field('knd-setup'); ?>
	</div>
</form>

*/