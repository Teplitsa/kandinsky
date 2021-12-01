<?php
/**
 * Wizard Step Settings
 */

?>

<h1><?php esc_html_e( 'NGO settings', 'knd' ); ?></h1>

<form method="post" class="knd-wizard-step settings-step">
	<p>
		<label for="knd-org-name"><?php esc_html_e('The website title', 'knd'); ?></label><br>
		<input type="text" name="knd_org_name" id="knd-org-name" value="<?php echo get_option('blogname'); ?>" class="knd-setup-wizard-control regular-text">
		
	</p>

	<p>
		<label for="knd-org-description"><?php esc_html_e('The website description', 'knd'); ?></label><br>
		<input type="text" name="knd_org_description" id="knd-org-description" value="<?php echo get_option('blogdescription'); ?>" class="knd-setup-wizard-control regular-text">
		
	</p>

	<p><?php _e( 'Please add your site icon below. The recommended size is <strong>64 x 64 px</strong>. The site icon can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd' ); ?></p>

	<p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new site icon designed.', 'knd'), '<a href="'.TST_PASEKA_OFFICIAL_WEBSITE_URL.'" target="_blank">', '</a>'); ?></p>
	<table>
		<tr>
			<td>
				<div id="current-site-icon">
					<?php knd_site_icon_image(); ?>
				</div>
			</td>
			<td>
				<a href="#" class="button button-small button-upload"><?php esc_html_e('Upload new site icon', 'knd'); ?></a>
			</td>
		</tr>
	</table>

	<input type="hidden" name="new_logo_id" id="new_logo_id" value="">

	<div class="envato-setup-actions knd-wizard-actions step">
		<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
		<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next button-skip">
			<span class="button-text"><?php esc_html_e('Skip this step', 'knd'); ?></span>
			<span class="dashicons dashicons-controls-skipforward"></span>
		</a>
		<?php wp_nonce_field('knd-setup-settings'); ?>
	</div>
</form>