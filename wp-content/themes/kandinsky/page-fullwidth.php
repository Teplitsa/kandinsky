<?php
/**
 * Template Name: Fullwidth
 **/

$qo = get_queried_object(); 

get_header();?>

<article id="single-page" class="main-content tpl-page-fullwidth">
	<?php if(!is_front_page()) { ?>
	<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>
	<?php } ?>
	
	<div class="container">
		<div class="entry-content"><?php echo apply_filters('the_content', $qo->post_content); ?></div>
	</div>
</article>

<?php get_footer();