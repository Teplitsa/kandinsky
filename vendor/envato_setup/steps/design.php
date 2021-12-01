<?php
/**
 * Wizard Step Design
 */

?>

<h1><?php esc_html_e( 'Logo', 'knd' ); ?></h1>
<form method="post">

	<p><?php _e( 'Please add your organization main logo below. The recommended size is <strong>315 x 66 px</strong> if only an image is used as a logo and <strong>66 x 66 px</strong> for use along with the text. The logo can be changed at any time from the Appearance > Customize > Header area in your website dashboard.', 'knd' ); ?></p>

	<p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new logo designed.', 'knd'), '<a href="'.TST_PASEKA_OFFICIAL_WEBSITE_URL.'" target="_blank">', '</a>'); ?></p>
	<table>
		<tr>
			<td>
				<div id="current-logo">
					<?php echo knd_get_logo_image(); ?>
				</div>
			</td>
			<td>
				<a href="#" class="button button-small button-upload"><?php esc_html_e('Upload new logo', 'knd'); ?></a>
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
		<?php wp_nonce_field('knd-setup-design'); ?>
	</div>
</form>