<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
$format = rdc_get_post_format($cpost);
$video = '';


if($format == 'introvid'){
	$video = get_post_meta($cpost->ID, 'post_video', true);
	if(empty($video))
		$format = 'standard';
}
elseif($format == 'introimg') {
	$thumbnail = rdc_post_thumbnail_src($cpost->ID, 'full');
}

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
	
	<?php if($format == 'introimg'){ ?>
		<section class="entry-preview"><?php rdc_single_post_thumbnail($cpost->ID, 'full', 'introimg'); ?></section>
	<?php } ?>
		
	<div class="frame">
		<main class="bit md-8">		
			
		<?php if($format == 'standard') { ?>
			<div class="entry-preview"><?php rdc_single_post_thumbnail($cpost->ID, 'medium-thumbnail'); ?></div>
			
		<?php } elseif($format == 'introvid') { ?>
			<div class="entry-preview introvid player"><?php echo apply_filters('the_content', $video);?></div>
			
		<?php } ?>				
			
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
		</main>
		
		<div id="rdc_sidebar" class="bit md-4"><?php dynamic_sidebar( 'right_single-sidebar' ); ?> </div>
	
	</div>
</div>
</section>

<?php
	if($cpost->post_type == 'post') {
		$cat = get_the_terms($post->ID, 'category');
		$pquery = new WP_Query(array(
			'post_type'=> 'post',
			'posts_per_page' => 5,
			'post__not_in' => array($cpost->ID),
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => (isset($cat[0])) ? $cat[0]->term_id : array()
				)
			)
		));
		
		if(!$pquery->have_posts()) {
			$pquery = new WP_Query(array(
				'post_type'=> 'post',
				'posts_per_page' => 5,
				'post__not_in' => array($cpost->ID),			
			));
		}
		
		rdc_more_section($pquery->posts, __('Related news', 'rdc'), 'news', 'addon'); 
		
	}
	elseif($cpost->post_type == 'project') {
		$pquery = new WP_Query(array(
			'post_type'=> 'project',
			'posts_per_page' => 5,
			'post__not_in' => array($cpost->ID),
			'orderby' => 'rand'
		));
		
		if($pquery->have_posts()){
			rdc_more_section($pquery->posts, __('Related projects', 'rdc'), 'projects', 'addon'); 
		}
	}
	elseif($cpost->post_type == 'person') {
		$cat = get_the_terms ($cpost, 'person_cat');
		
		$pquery = new WP_Query(array(
			'post_type'=> 'person',
			'posts_per_page' => 4,
			'post__not_in' => array($cpost->ID),
			'orderby' => 'rand',
			'tax_query' => array(
				array(
					'taxonomy' => 'person_cat',
					'field' => 'id',
					'terms' => (!empty($cat)) ? rdc_get_term_id_from_terms($cat): array()
				)
			)
		));
		
		if($pquery->have_posts()){
			rdc_more_section($pquery->posts, __('Our volunteers', 'rdc'), 'people', 'addon'); 
		}
	}
	

get_footer();