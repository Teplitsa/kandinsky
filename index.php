<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts; 
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();
?>

<div class="container heading">
	<?php knd_section_title(); ?>
</div>

<?php if(empty($posts)) { ?>
	<div class="main-content listing-bg"><div class="container">
		<div class="empty-message"><?php esc_html_e('Unfortunately, nothing found', 'knd');?></div>
	</div></div>

<?php  } else { ?>
	<?php 
	if($paged == 1) { //featured posts
		//2 for featured 
		$featured = array_slice($posts, 0, 2); 
		array_splice($posts, 0, 2);
	?>

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

	<?php } ?>

	<?php if(!empty($posts)):?>
	<div class="main-content cards-holder listing-bg archive-post-list <?php if($paged > 1):?>next-page<?php endif?>">
		<div class="container">
			<div class="flex-row start cards-loop">
			<?php
				foreach($posts as $p){
					knd_post_card($p);
				}
			?>
			</div>
		</div>

		<div class="paging"><?php knd_paging_nav( $wp_query ); ?></div>

	</div>
	<?php endif;?>

<?php } ?>

<?php knd_bottom_blocks(); ?>

<?php get_footer();
