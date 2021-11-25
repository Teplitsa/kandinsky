<?php
/**
 * The template for displaying offcanvas
 *
 * @package Kandinsky
 */

?>

<div class="nav-overlay"></div>

<div id="site_nav" class="site-nav" tabindex="-1">

	<div class="site-nav-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="snt-cell" aria-hidden="true" tabindex="-1">
			<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ); ?></span>
			<span class="logo-desc hide-upto-medium"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ); ?></span>
		</a>

		<?php knd_offcanvas_close(); ?>
	</div>

	<?php
	if ( get_theme_mod( 'offcanvas_menu', true ) ) {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'menu'                 => esc_html__('Main menu', 'knd'),
					'theme_location'       => 'primary',
					'container'            => 'nav',
					'container_class'      => 'nav-main-menu',
					'container_aria_label' => esc_attr__('Primary menu', 'knd'),
					'menu_class'           => 'main-menu',
				)
			);
		}
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
