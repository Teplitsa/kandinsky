<?php
/**
 * Template Name: Homepage
 **/

$qo = get_queried_object(); 

get_header();

$about_post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page' AND post_name IN (%s) LIMIT 1", 'about'));

if($about_post):
?>

<article id="single-page" class="main-content tpl-page-fullwidth">

<?php echo knd_hero_image_markup(); ?>

</article>

<?php
endif;
?>

<div class="knd-homepage-widgets">
    <?php dynamic_sidebar( 'knd-homepage-sidebar' );?>
</div>

<?php get_footer();
