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
    
    <!-- yellow bar -->
    <?php knd_show_cta_block() ?>

    <!-- purple bar -->
<?php
    $projects = KND_Project::get_short_list(3);
    
    $menu_items = wp_get_nav_menu_items(__( 'Kandinsky projects block menu', 'knd' ));
    $project_menu_items = [];
    foreach($menu_items as $k => $v) {
        $project_menu_items[] = array(
            'title' => $v->title,
            'url' => $v->url,
        );
    }
    
    knd_show_posts_shortlist($projects, "ПРОЕКТЫ «ЛИНИИ ЦВЕТА»", $project_menu_items);
?>

<!-- partners -->
<?php
$partners_widget = new KND_Org_Widget();
$partners_widget->widget(array('before_widget' => '', 'after_widget' => ''), array('title' => 'Наши партнеры', 'num' => 4));
?>

    
</div>

<?php get_footer();
