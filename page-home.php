<?php
/**
 * Template Name: Homepage
 *
 * @package Kandinsky
 **/

get_header();
?>

<div id="single-page" class="homepage-start">

<?php echo knd_hero_image_markup(); ?>

</div>

<?php if ( is_active_sidebar( 'knd-homepage-sidebar' ) ) : ?>

	<div class="knd-homepage-sidebar">
		<?php dynamic_sidebar( 'knd-homepage-sidebar' ); ?>
	</div>

<?php endif; ?>

<?php
get_footer();
