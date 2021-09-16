<?php
/**
 * Wizard Step Design
 */
?>

<h1><?php esc_html_e( 'Logo', 'knd' ); ?></h1>
<form method="post">

	<p><?php _e( 'Please add your organization main logo below. The recommended size is <strong>315 x 66 px</strong> (for "Image only" mode) and <strong>66 x 66 px</strong> (for "Image with site name" mode). The logo can be changed at any time from the Appearance > Customize area in your website dashboard.', 'knd' ); ?></p>

	<p><?php printf(esc_html__('Try our %sPaseka program%s if you need a new logo designed.', 'knd'), '<a href="'.TST_PASEKA_OFFICIAL_WEBSITE_URL.'" target="_blank">', '</a>'); ?></p>
	<table>
		<tr>
			<td>
				<div id="current-logo"><?php echo knd_get_logo_img(); ?></div>
			</td>
			<td>
				<a href="#" class="button button-upload"><?php esc_html_e('Upload new logo', 'knd'); ?></a>
			</td>
		</tr>
	</table>

	<input type="hidden" name="new_logo_id" id="new_logo_id" value="">

	<p class="envato-setup-actions step">
		<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
		<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e('Continue', 'knd'); ?>" name="save_step">
		<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
			<?php esc_html_e('Skip this step', 'knd'); ?>
		</a>
		<?php wp_nonce_field('knd-setup-design'); ?>
	</p>
</form>