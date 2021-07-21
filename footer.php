<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Kandinsky
 */

$copyright = sprintf( __( 'All materials of the site are avaliabe under license %s', 'knd' ), '<a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">' . esc_html__( 'Creative Commons СС-BY-SA 3.0', 'knd' ) . '</a>' );

?>
</div><!--  #site_content -->

<div id="bottom_bar" class="bottom-bar">
	<div class="container">
		<div class="flex-row align-bottom bottom-branding">
			<div class="flex-cell flex-md-6">
				<?php knd_footer_logo(); ?>
			</div>

			<div class="flex-cell flex-md-6 links-right">
				<?php
				if ( get_theme_mod( 'footer_social', true ) ) {
					$social_icons = knd_social_links( array(), false );
					if ( $social_icons ) {
						echo $social_icons;
					}
				}
				?>
			</div>

		</div>
	</div>
</div>

<footer class="site-footer">
	<div class="container">

		<?php if ( is_active_sidebar( 'knd-footer-sidebar' ) ) { ?>
			<div class="widget-area">
				<?php dynamic_sidebar( 'knd-footer-sidebar' ); ?>
			</div>
		<?php } ?>

		<?php if ( get_theme_mod( 'footer_copyright', true ) && get_theme_mod( 'footer_copyright', true ) ) { ?>
			<div class="hr"></div>

			<div class="flex-row footer-credits align-center">

				<div class="flex-cell flex-mf-8 flex-md-6">
					<?php if ( get_theme_mod( 'footer_copyright', true ) ) { ?>
						<div class="copy">
							<?php echo get_theme_mod( 'footer_copyright_text', $copyright ); ?>
						</div>
					<?php } ?>
				</div>

				<?php if ( get_theme_mod( 'footer_creator', true ) ) { ?>
					<div class="flex-cell flex-mf-4 flex-md-6">
						<div class="knd-brand">
							<a title="<?php esc_attr_e( 'Project Kandinsky', 'knd' ) ;?>" href="<?php echo esc_attr( KND_OFFICIAL_WEBSITE_URL ); ?>" target="_blank">
								<div class="support"><?php esc_html_e( 'Powered by Kandinsky', 'knd' ); ?></div>
								<div class="knd-banner"><svg class="knd-icon pic-knd"><use xlink:href="#pic-knd" /></svg></div>
							</a>
						</div>
					</div>
				<?php } ?>

			</div>
		<?php } ?>

	</div>

</footer>

<?php wp_footer(); ?>

</body>
</html>
