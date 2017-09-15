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
if($paged == 1) { //featured posts
	//2 for featured 
	$featured = array_slice($posts, 0, 2);
	array_splice($posts, 0, 2);
?>
<section class="container-wide">
    <div class="featured-post listing-bg">
        <div class="container">
            <div class="flex-row cards-loop leyka-loop">
            <?php
                foreach($featured as $f){
                    knd_donation_card($f);
                }
            ?>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<section class="main-content cards-holder listing-bg archive-post-list <?php if($paged > 1):?>next-page<?php endif?>"><div class="container">
<div class="flex-row start cards-loop leyka-loop">
<?php if(!empty($posts)){
    foreach($posts as $p){
        knd_donation_card($p);
    }
}?>
</div>
</div></section>

<section class="paging listing-bg"><?php knd_paging_nav($wp_query); ?></section>

<div class="knd-archive-sidebar">

    <?php if(is_home()):?>
        <?php dynamic_sidebar( 'knd-news-archive-sidebar' );?>
    <?php else: ?>
        <?php dynamic_sidebar( 'knd-projects-archive-sidebar' );?>    
    <?php endif ?>
    
</div>

<?php get_footer();