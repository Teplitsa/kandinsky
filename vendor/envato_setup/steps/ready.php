<?php
/**
 * Wizard Step Support
 */

update_option('knd_setup_complete', time());

?>

<h1><?php esc_html_e('Yay! Your website is ready!', 'knd'); ?></h1>

<p><?php esc_html_e('Congratulations! You’re doing really good #success! You’ve successfully installed and set up your Kandinsky theme. You need, however, to do a little bit more.', 'knd'); ?></p>
<p><?php esc_html_e('As a part of the installation process, we’ve added some test content of an imaginary organization that you will need to edit (we’ve provided the recommendations on how to make great content).', 'knd'); ?></p>
<p><?php esc_html_e('Moreover, you need to set up few additional plug-ins for the optimal work of your site (don’t worry, our recommendations will help you).', 'knd'); ?></p>

<div class="envato-setup-next-steps">
	<div class="envato-setup-next-steps-first">
		<ul>
			<li class="setup-product">
				<a class="button button-primary button-large" href="<?php echo admin_url(); ?>">
					<?php esc_html_e('Continue the set-up', 'knd'); ?>
				</a>
			</li>
			<li class="setup-product">
				<a class="button button-next button-large" href="<?php echo home_url(); ?>">
					<?php esc_html_e('View your new website!', 'knd'); ?>
				</a>
			</li>
			<li class="setup-product">
				<a href="<?php echo esc_url($this->get_prev_step_link()); ?>" class="button button-link"><?php esc_html_e( 'Back', 'knd' ); ?></a>
			</li>
		</ul>
	</div>
	<div class="envato-setup-next-steps-last">
		<?php $funny_gif_url = 'https://media.giphy.com/media/XreQmk7ETCak0/giphy.gif';?>
		<img src="<?php echo $funny_gif_url;?>" alt="<?php esc_attr_e('#success', 'knd'); ?>">
	</div>
</div>
