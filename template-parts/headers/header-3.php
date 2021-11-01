<?php
/**
 * The template for displaying the header layout type 3
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
					knd_header_nav_menu();
					knd_search_toggle();
				?>
			</div>
			<div class="knd-header__col knd-col-right">
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
