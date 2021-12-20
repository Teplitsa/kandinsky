<?php
/**
 * Wizard Step Content
 */

$demo_dir_exists = false;
$git_imp = new KND_Import_Git_Content('color-line');
if ( Knd_Filesystem::get_instance()->is_dir( $git_imp->is_dir() ) ) {
	$demo_dir_exists = true;
}

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
				<?php
					$checked = true;
					$disabled = false;
					if ( ( isset($default['checked'] ) || $default['checked'] ) ) {
						$checked = true;
					}
					if ( 'content' === $slug && ! $demo_dir_exists ) {
						$checked = false;
						$disabled = true;
					}
				?>
				<tr class="envato_default_content" data-content="<?php echo esc_attr($slug); ?>">
					<td>
						<input type="checkbox" name="default_content[<?php echo esc_attr($slug); ?>]" class="envato_default_content" id="default_content_<?php echo esc_attr($slug); ?>" value="1" <?php checked( $checked, true ); ?> <?php if ( 'content' === $slug ) disabled( $disabled, true ); ?>>
					</td>
					<td>
						<label for="default_content_<?php echo esc_attr($slug); ?>">
							<?php echo esc_html($default['title']); ?>
						</label>
					</td>
					<td class="description">
						<?php echo esc_html($default['description']); ?>

						<?php if ( 'content' === $slug && ! $demo_dir_exists ) { ?>
							<span class="description-warning"><br><?php esc_html_e( 'This option is disabled because is missing demo content import folder.', 'knd' ); ?><br>
							<?php esc_html_e( 'Most likely you missed the first step.', 'knd' ); ?><br>
							<?php echo sprintf( esc_html__( 'To import all content, please go back %sprev step%s and click the "Let\'s go!" button.', 'knd' ), '<a href="' . esc_url( $this->get_prev_step_link() ) . '">', '</a>' ); ?></span>
						<?php } ?>

						<?php if ( 'donations' === $slug && get_option('leyka_org_full_name') ) { ?>
							<br>
							<span class="description-warning">
								<strong><?php esc_html_e( 'Warning!', 'knd' ); ?></strong><br>
								<?php esc_html_e( 'Donation content was detected on the site.', 'knd' ); ?>
								<br>
								<?php esc_html_e( 'Please uncheck this option if you do not need to overwrite content to demo data.', 'knd' ); ?>
							</span>
						<?php } ?>

						</td>
					<td class="status"><span><?php echo esc_html($default['pending']); ?></span>
						<div class="spinner"></div>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

		<div class="envato-setup-actions knd-wizard-actions step">
			<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button-wizard-back button button-large"><?php esc_html_e( 'Back', 'knd' ); ?></a>
			<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button-primary button button-large button-next" data-callback="installContent">
				<?php esc_html_e('Set up', 'knd'); ?>
			</a>
			<a href="<?php echo esc_url($this->get_next_step_link()); ?>" class="button button-large button-next button-skip">
				<span class="button-text"><?php esc_html_e('Skip this step', 'knd'); ?></span>
				<span class="dashicons dashicons-controls-skipforward"></span>
			</a>
			<?php wp_nonce_field('knd-setup-content'); ?>
		</div>
	</form>