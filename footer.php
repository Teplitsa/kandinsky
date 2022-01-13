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
</main><!--  .knd-main -->

<footer class="knd-footer">

	<?php if ( get_theme_mod( 'footer_social', true ) || get_theme_mod( 'footer_logo', true ) ) { ?>
		<div id="bottom_bar" class="bottom-bar">
			<div class="knd-container">
				<div class="flex-row align-bottom">
					<div class="flex-cell flex-md-6">
						<?php knd_footer_logo(); ?>
					</div>

					<div class="flex-cell flex-md-6 links-right">
						<?php
						if ( get_theme_mod( 'footer_social', true ) ) {
							knd_social_links();
						}
						?>
					</div>

				</div>
			</div>
		</div>
	<?php } ?>

	<div class="knd-container">

		<?php if ( get_theme_mod( 'footer_about' ) || get_theme_mod( 'footer_menu_ourwork' ) || get_theme_mod( 'footer_menu_news' ) || get_theme_mod( 'footer_policy' ) ) { ?>
		<div class="widget-area">

			<?php if ( get_theme_mod( 'footer_about' ) ) { ?>
				<div class="widget-bottom widget-bottom-about">
					<?php if ( get_theme_mod( 'footer_about_title' ) ) { ?>
						<h2 class="widget-title"><?php echo get_theme_mod( 'footer_about_title', esc_html__( 'About Us', 'knd' ) ); ?></h2>
					<?php } ?>
					<div class="textwidget">
						<?php echo do_shortcode( wpautop( get_theme_mod( 'footer_about' ) ) ); ?>
					</div>
				</div>
			<?php } ?>

			<?php
			if ( get_theme_mod( 'footer_menu_ourwork' ) && is_nav_menu( get_theme_mod( 'footer_menu_ourwork' ) ) ) {
				$before = '';
				if ( get_theme_mod( 'footer_menu_ourwork_title' ) ) {
					$before = '<h2 class="widget-title">' . get_theme_mod( 'footer_menu_ourwork_title', esc_html__( 'Our Work', 'knd' ) ) . '</h2>';
				}
				wp_nav_menu(
					array(
						'menu'            => get_theme_mod( 'footer_menu_ourwork' ),
						'container'       => 'div',
						'container_class' => 'widget-bottom widget-bottom-menu',
						'depth'           => 1,
						'items_wrap'      => $before . '<ul class="%2$s">%3$s</ul>',
					)
				);
			}
			?>

			<?php
			if ( get_theme_mod( 'footer_menu_news' ) && is_nav_menu( get_theme_mod( 'footer_menu_news' ) ) ) {
				$before = '';
				if ( get_theme_mod( 'footer_menu_news_title' ) && '&nbsp;' !== get_theme_mod( 'footer_menu_news_title' ) ) {
					$before = '<h2 class="widget-title">' . get_theme_mod( 'footer_menu_news_title', esc_html__( 'News', 'knd' ) ) . '</h2>';
				} else if ( '&nbsp;' === get_theme_mod( 'footer_menu_news_title' ) ) {
					$before = '<div class="widget-title">&nbsp;</div>';
				}
				wp_nav_menu(
					array(
						'menu'            => get_theme_mod( 'footer_menu_news' ),
						'container'       => 'div',
						'container_class' => 'widget-bottom widget-bottom-menu',
						'depth'           => 1,
						'items_wrap'      => $before . '<ul class="%2$s">%3$s</ul>',
					)
				);
			}
			?>

			<?php if ( get_theme_mod( 'footer_policy' ) ) { ?>
				<div class="widget-bottom widget-bottom-policy">
					<?php if ( get_theme_mod( 'footer_policy_title' ) ) { ?>
						<h2 class="widget-title"><?php echo get_theme_mod( 'footer_policy_title', esc_html__( 'Security policy', 'knd' ) ); ?></h2>
					<?php } ?>
					<div class="textwidget">
						<?php echo do_shortcode( wpautop( get_theme_mod( 'footer_policy' ) ) ); ?>
					</div>
				</div>
			<?php } ?>

		</div>
		<?php } ?>

		<?php if ( get_theme_mod( 'footer_copyright', true ) && get_theme_mod( 'footer_copyright', true ) ) { ?>
			<div class="hr"></div>

			<div class="flex-row footer-credits align-center">

				<div class="flex-cell flex-sm-8 flex-md-6">
					<?php if ( get_theme_mod( 'footer_copyright', true ) ) { ?>
						<div class="copy">
							<?php echo get_theme_mod( 'footer_copyright_text', $copyright ); ?>
						</div>
					<?php } ?>
				</div>

				<?php if ( get_theme_mod( 'footer_creator', true ) ) { ?>
					<div class="flex-cell flex-sm-4 flex-md-6">
						<div class="knd-brand">
							<a href="<?php echo esc_attr( KND_OFFICIAL_WEBSITE_URL ); ?>" target="_blank">
								<div class="support"><?php esc_html_e( 'Powered by Kandinsky', 'knd' ); ?></div>
								<div class="knd-banner">
									<svg class="knd-icon pic-knd">
										<title><?php esc_html_e( 'Kandinsky logo', 'knd' ); ?></title>
										<use xlink:href="#pic-knd" />
									</svg>
								</div>
							</a>
						</div>
					</div>
				<?php } ?>

			</div>
		<?php } ?>

	</div>

</footer>

<?php do_action( 'knd_before_wp_footer' ); ?>

<?php wp_footer(); ?>

</body>
</html>
