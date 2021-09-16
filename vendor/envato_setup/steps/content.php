<?php
/**
 * Wizard Step Content
 */
?>

<h1><?php esc_html_e('Theme default content', 'knd'); ?></h1>
	<form method="post">
		<?php if($this->is_default_content_installed()) { ?>
			<p><?php esc_html_e('It looks like you already have content installed on this website. If you would like to install the default demo content as well you can select it below. Otherwise just choose the upgrade option to ensure everything is up to date.', 'knd'); ?></p>
		<?php } else { ?>
			<p><?php esc_html_e("It's time to insert some default content for your new WordPress website. Choose what you would like inserted below and click Install. We recommend to select everything. Once inserted, this content can be managed from the WordPress admin dashboard.", 'knd'); ?></p>
		<?php } ?>
		<table class="envato-setup-pages" cellspacing="0">
			<thead>
			<tr>
				<td class="check"></td>
				<th class="item"><?php esc_html_e('Item', 'knd'); ?></th>
				<th class="description"><?php esc_html_e('Description', 'knd'); ?></th>
				<th class="status"><?php esc_html_e('Status', 'knd'); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($this->_content_default_get() as $slug => $default) { ?>
				<tr class="envato_default_content" data-content="<?php echo esc_attr($slug); ?>">
					<td>
						<input type="checkbox" name="default_content[<?php echo esc_attr($slug); ?>]" class="envato_default_content" id="default_content_<?php echo esc_attr($slug); ?>" value="1" <?php echo !isset($default['checked']) || $default['checked'] ? 'checked="checked"' : ''; ?>>
					</td>
					<td>
						<label for="default_content_<?php echo esc_attr($slug); ?>">
							<?php echo esc_html($default['title']); ?>
						</label>
					</td>
					<td class="description"><?php echo esc_html($default['description']); ?></td>
					<td class="status"><span><?php echo esc_html($default['pending']); ?></span>
						<div class="spinner"></div>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

		<p class="envato-setup-actions step">
			<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
			<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next" data-callback="installContent">
				<?php esc_html_e('Set up', 'knd'); ?>
			</a>
			<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next">
				<?php esc_html_e('Skip this step', 'knd'); ?>
			</a>
			<?php wp_nonce_field('knd-setup-content'); ?>
		</p>
	</form>