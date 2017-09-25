<?php
/**
 * Template Name: Homepage
 **/

get_header();
?>

<div id="single-page" class="homepage-start">

<?php echo knd_hero_image_markup(); ?>

</div>

<div class="knd-homepage-sidebar">

<?php dynamic_sidebar( 'knd-homepage-sidebar' );?>
    
</div>

<?php get_footer();
