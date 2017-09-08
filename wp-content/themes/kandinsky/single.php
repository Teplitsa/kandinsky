<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$cpost = get_queried_object(); 
$format = rdc_get_post_format($cpost);
$thumbnail_id = get_post_thumbnail_id($cpost->ID);
$src = $thumbnail_id ? wp_get_attachment_image_src( $thumbnail_id, 'full' ) : null;

if($src && isset($src[1])) {
    $thumb_width = (int)$src[1];
}
else {
    $thumb_width = 0;
}

$video = '';


if($format == 'introvid'){
	$video = get_post_meta($cpost->ID, 'post_video', true);
	if(empty($video))
		$format = 'standard';
}
elseif($format == 'introimg') {
	$thumbnail = knd_post_thumbnail_src($cpost->ID, 'full');
}

get_header(); ?>
<section class="main-content single-post-section container-wide format-<?php echo $format;?>">

<?php
/*
if($thumb_width > 1104) {
?>
    <div class="container-wide full-width-image">
        <section class="entry-preview"><?php knd_single_post_thumbnail($cpost->ID, 'full', 'introimg'); ?></section>
      
        <header class="entry-header-full"><div class="container">
        
            <div class="flex-row">
        
                <div class="flex-md-1"></div>
                
                <div class="flex-mf-12 flex-md-10">
                    <div class="entry-meta"><?php echo knd_posted_on($cpost); //for event ?></div>
                    <h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
                    <div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js();?></div>
                </div>
                
                <div class="flex-md-1"></div>
            
            </div>
            
        </div></header>
      
    </div>
    
<?php } else { */?>

    <div class="container">
        <header class="flex-row entry-header-full">
        
            <div class="flex-md-2"></div>
            
            <div class="flex-md-8">
                <div class="entry-meta"><?php echo knd_posted_on($cpost); //for event ?></div>
                <h1 class="entry-title"><?php echo get_the_title($cpost);?></h1>
                <div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js();?></div>
            </div>
            
            <div class="flex-md-2"></div>
            
        </header>
        
        <div class="flex-row">
          <div class="flex-md-1"></div>
          <div class="flex-mf-12 flex-md-10">
            <section class="entry-preview"><?php knd_single_post_thumbnail($cpost->ID, 'full', 'introimg'); ?></section>
          </div>
          <div class="flex-md-1"></div>
        </div>
        
    </div>

<?php //}?>
    
<div class="container">

	<div class="flex-row">
    
        <div class="flex-md-1"></div>
        
        <div class="flex-md-1 single-sharing-col">
            <div id="knd_sharing" class="regular-sharing hide-upto-medium"><?php echo knd_social_share_no_js();?></div>
        </div>
    
		<main class="flex-md-8">		
			
		<?php if($format == 'standard') { ?>
        
		<?php } elseif($format == 'introvid') { ?>
			<div class="entry-preview introvid player"><?php echo apply_filters('the_content', $video);?></div>
			
		<?php } ?>				
			
            <div class="entry-lead"><?php echo apply_filters('rdc_the_content', $cpost->post_excerpt); ?></div>
			<div class="entry-content"><?php echo apply_filters('the_content', $cpost->post_content); ?></div>
            
		</main>
		
        <div class="flex-md-2"></div>
        
	</div>
</div>
</section>

<div class="container flex-row">
    <div class="flex-md-1"></div>
    <div class="flex-md-1"></div>
    <div class="flex-md-8">
    
        <div class="single-post-terms">
            <?php knd_show_post_terms($cpost->ID) ?>
        </div>
    
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
		
		knd_more_section($pquery->posts, __('Related items', 'knd'), 'news', 'addon');
		
	}
	elseif($cpost->post_type == 'project') {
		$pquery = new WP_Query(array(
			'post_type'=> 'project',
			'posts_per_page' => 5,
			'post__not_in' => array($cpost->ID),
			'orderby' => 'rand'
		));
		
		if($pquery->have_posts()){
			knd_more_section($pquery->posts, __('Related projects', 'knd'), 'projects', 'addon');
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
					'terms' => (!empty($cat)) ? knd_get_term_id_from_terms($cat): array()
				)
			)
		));
		
		if($pquery->have_posts()){
			//knd_more_section($pquery->posts, __('Our volunteers', 'knd'), 'people', 'addon');
		}
	}
?>
    </div>
    <div class="flex-md-2"></div>
</div>

<div class="knd-signle-after-content">

    <?php if($cpost->post_type == 'post'):?>
        <?php dynamic_sidebar( 'knd-news-archive-sidebar' );?>
    <?php else: ?>
        <?php dynamic_sidebar( 'knd-projects-archive-sidebar' );?>    
    <?php endif ?>

</div>

<?php

get_footer();