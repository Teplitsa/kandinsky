<?php
/**
 * The template for displaying the header layout type 0
 *
 * @package Kandinsky
 */

?>

<header id="site_header" class="site-header">

	<div class="site-header-panel">

		<div class="site-panel-row">

			<div class="site-branding site-panel-cell"><?php knd_logo_markup(); ?></div>

			<?php $header_text = knd_get_theme_mod( 'text_in_header' ); ?>
			<?php if ( $header_text ) { ?>
				<div class="site-details site-panel-cell">
					<div class="site-details-cell"><?php echo wp_kses_post( wpautop( $header_text ) ); ?></div>
				</div>
			<?php } ?>

			<?php if ( get_theme_mod( 'header_button', true ) ) { ?>
				<div class="trigger-button donate site-panel-cell hide-upto-medium">
					<a id="trigger_donate" href="<?php echo esc_url( get_theme_mod( 'header_button_link', get_theme_mod( 'knd_hero_image_support_url' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'header_button_text', get_theme_mod( 'knd_hero_image_support_button_caption', esc_html__( 'Help now', 'knd' ) ) ) ); ?></a>
				</div>
			<?php } ?>

			<div class="trigger-button menu site-panel-cell">
				<a id="trigger_menu" href="<?php echo esc_url( home_url( 'sitemap' ) ); ?>">
					<?php knd_svg_icon( 'icon-menu' ); ?>
				</a>
			</div>

		</div>

	</div>

	<div class="nav-overlay"></div>

	<nav id="site_nav" class="site-nav">
		<div class="site-nav-title">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="snt-cell">
				<span class="logo-name"><?php bloginfo('name'); ?></span>
				<span class="logo-desc hide-upto-medium"><?php bloginfo('description'); ?></span>
			</a>

			<div id="trigger_menu_close" class="trigger-button close"><?php knd_svg_icon( 'icon-close' ); ?></div>
		</div>
		<?php
		$after = '<span class="submenu-trigger">' . knd_svg_icon( 'icon-up', false ) . knd_svg_icon( 'icon-down', false ) . '</span>';
		wp_nav_menu(
			array(
				'menu'           => esc_html__('Main menu', 'knd'),
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'main-menu',
				'after'          => $after,
			)
		);
		?>
		<div class="search-holder"><?php get_search_form(); ?></div>
		<?php knd_social_links(); ?>
	</nav>
</header>
