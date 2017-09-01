<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();
?>

<section class="container heading">
    <?php knd_section_title(); ?>
</section>

<?php 
if(is_home() && $paged == 1) { //featured posts
	//2 for featured 
	$featured = array_slice($posts, 0, 2); 
	array_splice($posts, 0, 2);
?>
<section class="container-wide">
    <div class="featured-post listing-bg">
        <div class="container">
            <div class="flex-row cards-loop">
            <?php
                foreach($featured as $f){
                    knd_related_post_card($f);
                }
            ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<section class="main-content cards-holder listing-bg archive-post-list <?php if($paged > 1):?>next-page<?php endif?>"><div class="container">
<div class="flex-row cards-loop">
<?php if(!empty($posts)){
    foreach($posts as $p){
        knd_post_card($p);
    }
}?>
</div>
</div></section>

<section class="paging listing-bg"><?php knd_paging_nav($wp_query); ?></section>

<!-- yellow bar -->
<?php knd_show_cta_block() ?>

<!-- purple bar -->
<?php

$projects = KND_Project::get_short_list(3);
knd_show_posts_shortlist($projects, "ПРОЕКТЫ «ЛИНИИ ЦВЕТА»", array(
    array('title' => 'Все проекты', 'url' => '#'),
    array('title' => 'Пресса о нас', 'url' => '#'),
    array('title' => 'Отчеты', 'url' => '#'),
));

get_footer();