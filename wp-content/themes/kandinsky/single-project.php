<?php
/** Project **/

$cpost = get_queried_object();
$template = get_post_meta($cpost->ID, 'template_format', true);
if(!$template)
	$template= 'general';
	

get_header(); ?>
<section class="main-content single-post-section container-wide format-<?php echo $format;?>">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container">
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo rdc_posted_on($cpost); //for event ?></div>
		<h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>				
		<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
		
		<div class="lead"><?php echo apply_filters('rdc_the_content', $cpost->post_excerpt); ?></div>
	</header>
	
	<?php if($template == 'builder') { ?>
		<main class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></main>
		
	<?php } else { ?>
		<div class="frame">
		<main class="bit md-8">					
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div id="rdc_sidebar" class="bit md-4"><?php dynamic_sidebar( 'right_single-sidebar' ); ?> </div>
	
	</div>
	<?php } ?>	
	
</div>
</section>
<?php
$pquery = new WP_Query(array(
		'post_type'=> 'project',
		'posts_per_page' => 5,
		'post__not_in' => array($cpost->ID),
		'orderby' => 'rand'
	));
	
	if($pquery->have_posts()){
		rdc_more_section($pquery->posts, __('Related projects', 'rdc'), 'projects', 'addon'); 
	}

get_footer();