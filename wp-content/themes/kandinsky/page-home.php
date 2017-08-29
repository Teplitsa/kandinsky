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
    <div class="container-wide knd-joinus-widget">
    
        <div class="container widget">
        
            <h2>112 волонтеров помогают «Линии цвета» в настоящий момент</h2>
            
            <div class="flex-row knd-whoweare-headlike-text-wrapper">
            
                <p class="knd-whoweare-headlike-text flex-mf-12 flex-sm-10">
                Присоединяйтесь к команде волонтеров <br />и консультантов в наших проектах
                </p>
                
            </div>
            
            <div class="knd-cta-wrapper-wide">
                <a class="cta" href="#">Стать волонтером</a>
            </div>
        
        </div>
    
    </div>

<!-- purple bar -->

<?php
$projects = KND_Project::get_short_list(3);
KND_Project::print_short_list($projects);
?>

<!-- partners -->
<?php
$partners_widget = new KND_Org_Widget();
$partners_widget->widget(array('before_widget' => '', 'after_widget' => ''), array('title' => 'Наши партнеры', 'num' => 4));
?>

    
</div>

<?php get_footer();
