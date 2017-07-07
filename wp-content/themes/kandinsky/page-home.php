<?php
/**
 * Template Name: Homepage
 **/

$qo = get_queried_object(); 

get_header();

$about_post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_name IN (%s) LIMIT 1", 'new-site-created'));

if($about_post):
?>

<article id="single-page" class="main-content tpl-page-fullwidth">

<div class="container">
<div class="entry-content">

<div id="pl-72">
<div id="pg-72-0" class="panel-grid">
<div class="panel-row-style-homepage-intro homepage-intro panel-row-style">
<div id="pgc-72-0-0" class="panel-grid-cell">
<div class="no-bottom-margin panel-cell-style">

<?php echo knd_hero_image_markup(); ?>

<div id="panel-72-0-0-1" class="so-panel widget widget_sow-editor panel-last-child" data-index="1">
<div class="content-center container-extended-colored panel-widget-style">
<div class="so-widget-sow-editor so-widget-sow-editor-base">
<div class="siteorigin-widget-tinymce textwidget">
<h2>Наша организация «Линия Цвета»</h2>
<p>
Более 10 лет мы помогаем людям, страдающим алкоголизмом в нашем городе
<br>
организуя реабилитационные программы.
</p>
</div>
</div>
</div>
</div>

</div></div></div></div></div></div></div>
</article>

<?php
endif;
?>

<div class="knd-homepage-widgets">
    <?php dynamic_sidebar( 'knd-homepage-sidebar' );?>
</div>

<?php get_footer();
