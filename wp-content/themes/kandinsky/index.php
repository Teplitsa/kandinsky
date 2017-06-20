<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

if(is_home() && $paged == 1) { //featured posts
	//2 for featured 
	$featured = array_slice($posts, 0, 2); 
	array_splice($posts, 0, 2);
?>
<section class="featured-post"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2">
	<?php
		foreach($featured as $f){
			rdc_related_post_card($f);
		}
	?>
</div>
</div></section>
<?php } ?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">
	<?php
		if(!empty($posts)){
			foreach($posts as $p){
				rdc_post_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging"><?php rdc_paging_nav($wp_query); ?></section>

<?php get_footer();