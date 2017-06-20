<?php
/**
 * The template for displaying all pages.
 */


$cpost = get_queried_object();

$leyka_succ = get_option('leyka_success_page');
$leyka_fail = get_option('leyka_failure_page');
$leyka_quit = get_option('leyka_quittance_redirect_page');

$css = (is_page($leyka_succ) || is_page($leyka_fail) || is_page($leyka_quit)) ? ' thank-you-leyka' : '';
get_header(); 
?>
<section class="page-header-simple<?php echo $css;?>"><div class="container-narrow">
	<h1 class="page-title"><?php echo get_the_title($cpost);?></h1>
	<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
</div></section>

<section class="main-content page-content-simple<?php echo $css;?>">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container-narrow">	
	<div class="entry-content"><?php echo apply_filters('rdc_entry_the_content', $cpost->post_content); ?></div>
	<?php if(is_page($leyka_succ) || is_page($leyka_fail) || is_page($leyka_quit)) { ?>
		<div class="logo-inpage"><?php rdc_site_logo('regular');?></div>
	<?php } ?>
</div>
</section>


<?php get_footer(); ?>
