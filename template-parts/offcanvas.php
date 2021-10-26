<?php
/**
 * The template for displaying offcanvas
 *
 * @package Kandinsky
 */

?>

<div class="nav-overlay"></div>

<div id="site_nav" class="site-nav" tabindex="-1" aria-hidden="true" aria-label="<?php esc_attr_e( 'Off-Canvas is open', 'knd' ); ?>">

	<span class="screen-reader-text" tabindex="0" aria-hidden="true" aria-label="<?php esc_attr_e( 'Off-Canvas is open', 'knd' ); ?>"></span>

	<div class="site-nav-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="snt-cell" aria-hidden="true">
			<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ); ?></span>
			<span class="logo-desc hide-upto-medium"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ); ?></span>
		</a>

		<?php knd_offcanvas_close(); ?>
	</div>

	<?php
	if ( get_theme_mod( 'offcanvas_menu', true ) ) {
		$after = '<span class="submenu-trigger">' . knd_svg_icon( 'icon-up', false ) . knd_svg_icon( 'icon-down', false ) . '</span>';
		wp_nav_menu(
			array(
				'menu'           => esc_html__('Main menu', 'knd'),
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'main-menu',
				'items_wrap'     => '<ul id="%1$s" tabindex="-1" class="%2$s" aria-label="' . esc_attr__( 'Primary menu', 'knd' ) . '">%3$s</ul>',
				'after'          => $after,
			)
		);
	}
	?>

	<?php
	if ( get_theme_mod( 'offcanvas_search', true ) ) {
		?>
		<div class="search-holder"><?php get_search_form(); ?></div>
		<?php
	}

	knd_offcanvas_button();

	if ( get_theme_mod( 'offcanvas_social', true ) ) {
		knd_social_links();
	}
	?>

</div>
