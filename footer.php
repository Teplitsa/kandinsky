<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Kandinsky
 */

$cc_link = '<a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">' . esc_html__( 'Creative Commons СС-BY-SA 3.0', 'knd' ) . '</a>';

$footer_text = knd_get_theme_mod( 'footer_text' );

?>
</div><!--  #site_content -->

<div id="bottom_bar" class="bottom-bar">
	<div class="container">
		<div class="flex-row align-bottom bottom-branding">
			<div class="flex-cell flex-md-5">
				<span class="logo-name"><?php bloginfo( 'name' ); ?></span>
				<span class="logo-desc"><?php bloginfo( 'description' ); ?></span>
			</div>

			<div class="flex-cell flex-md-7 links-right">
				<?php
				$social_icons = knd_social_links( array(), false );
				if ( $social_icons ) {
					echo $social_icons;
				}
				?>
			</div>

		</div>
	</div>
</div>

<footer class="site-footer">
	<div class="container">

		<div class="widget-area"><?php dynamic_sidebar( 'knd-footer-sidebar' ); ?></div>

		<div class="hr"></div>

		<div class="flex-row footer-credits align-center">

			<div class="flex-cell flex-mf-8 flex-md-6">

				<div class="copy">
					<?php echo apply_filters( 'knd_the_content', $footer_text ); ?>
					<p><?php printf( __( 'All materials of the site are avaliabe under license %s', 'knd' ), $cc_link ); ?></p>
				</div>

			</div>

			<div class="flex-cell flex-mf-4 flex-md-6">
				<div class="knd-brand">
					<a title="<?php esc_attr_e( 'Project Kandinsky', 'knd');?>" href="<?php echo esc_attr( KND_OFFICIAL_WEBSITE_URL ); ?>" target="_blank">
						<div class="support"><?php esc_html_e( 'Powered by Kandinsky', 'knd' ); ?></div>
						<div class="knd-banner"><svg class="knd-icon pic-knd"><use xlink:href="#pic-knd" /></svg></div>
					</a>
				</div>
			</div>

		</div>

	</div>

</footer>

<?php wp_footer(); ?>

</body>
</html>
