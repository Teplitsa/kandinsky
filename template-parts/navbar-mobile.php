<?php
/**
 * The template for displaying the navbar mobile
 *
 * @package Kandinsky
 */

?>

<?php if ( get_theme_mod( 'header_button', true ) ) { ?>

	<div class="knd-header__inner knd-header__inner-mobile">
		<div class="knd-header__col knd-col-left">
			<?php knd_header_mobile_button(); ?>
		</div>
		<div class="knd-header__col knd-col-center">
			<?php knd_header_mobile_logo(); ?>
		</div>
		<div class="knd-header__col knd-col-right">
			<?php knd_offcanvas_toggle(); ?>
		</div>
	</div>

<?php } else { ?>

	<div class="knd-header__inner knd-header__inner-mobile">
		<div class="knd-header__col knd-col-left">
			<?php knd_header_mobile_logo(); ?>
		</div>
		<div class="knd-header__col knd-col-right">
			<?php knd_offcanvas_toggle(); ?>
		</div>
	</div>

<?php }
