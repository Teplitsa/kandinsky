<?php
/**
 * The template for displaying the header layout type 1
 *
 * @package Kandinsky
 */

?>

<header class="knd-header">
	<div class="knd-container-fluid">
		<div class="knd-header__inner knd-header__inner-desktop">
			<div class="knd-header__col knd-col-left">
				<?php
				knd_header_logo();
				if ( get_theme_mod( 'header_custom_text' ) ) {
					?>
						<div class="knd-header-text"><?php echo wp_kses_post( get_theme_mod( 'header_custom_text' ) ); ?></div>
					<?php
				}
				?>
			</div>
			<div class="knd-header__col knd-col-right">
				<div class="knd-header-contacts">
					<?php if ( get_theme_mod( 'header_address' ) ) { ?>
						<div class="knd-header-address"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_address' ) ) ); ?></div>
					<?php } ?>
					<?php if ( get_theme_mod( 'header_email' ) ) { ?>
						<a href="mailto:<?php echo esc_attr( get_theme_mod( 'header_email' ) ); ?>" class="knd-header-email"><?php echo esc_html( get_theme_mod( 'header_email' ) ); ?></a>
					<?php } ?>
					<?php if ( get_theme_mod( 'header_phone' ) ) { ?>
						<div class="knd-header-phone"><?php echo esc_html( get_theme_mod( 'header_phone' ) ); ?></div>
					<?php } ?>
				</div>
				<?php
					if ( get_theme_mod( 'header_social' ) ) {
						knd_social_links();
					}
					knd_header_button();
					knd_offcanvas_toggle( get_theme_mod( 'header_offcanvas', true ) );
				?>
			</div>
		</div>
		<?php get_template_part( 'template-parts/navbar-mobile' ); ?>
	</div>
	<?php
		knd_header_search();
		get_template_part( 'template-parts/offcanvas' );
	?>
</header>
