<?php
/**
 * The template for displaying all single posts.
 *
 * @package bb
 */

$event = new TST_Event(get_queried_object());
$format = rdc_get_post_format($event->post_object);
$video = $thumbnail = '';

if($format == 'introvid'){
	$video = get_post_meta($event->ID, 'post_video', true);
	if(empty($video))
		$format = 'standard';
}
elseif($format == 'introimg') {
	$thumbnail = rdc_post_thumbnail_src($event->ID, 'full');
}

get_header(); ?>
<section class="main-content single-post-section container-wide format-<?php echo $format;?>">
<div id="rdc_sharing" class="regular-sharing hide-upto-medium"><?php echo rdc_social_share_no_js();?></div>

<div class="container " <?php echo $event->get_event_schema_prop();?>>
	<header class="entry-header-full">
		<div class="entry-meta"><?php echo $event->posted_on_single(); //for event ?></div>
		<h1 class="entry-title" <?php echo $event->get_event_name_prop();?>><?php echo get_the_title($event->post_object);?></h1>				
		<div class="mobile-sharing hide-on-medium"><?php echo rdc_social_share_no_js();?></div>
		
		<div class="full-event-metas">
		<?php
			$date = $event->date_mark_for_context('single_details');
			$location = $event->get_full_address_mark();
			$parts = $event->get_participants_mark();
			
			if($parts){							
				echo "<p>{$parts}</p>";
			}
			
			if($date){							
				echo "<p>{$date}</p>";							
			}
			
			if($location){						
				echo "<p class='full-address'>{$location}</p>";
			}
		?>
			<div class="mf hidden">
				<?php echo "<a class='hidden' href='".get_permalink($qo)."' ".$event->get_event_url_prop().">Постоянная ссылка</a>"; ?>
				<?php echo $event->get_event_offer_field();?>
			</div>
		</div>			
	</header>
	
	<?php if($format == 'introimg'){ ?>
	<section class="entry-preview introimg">		
		<div class="tpl-pictured-bg" style="background-image: url(<?php echo $thumbnail;?>);" ></div>		
	</section>
	<?php } ?>
	
	<div class="frame">
		<main class="bit md-8">		
			
		<?php if($format == 'standard') {
			$thumb = rdc_post_thumbnail($event->ID, 'medium-thumbnail', false);
			if($thumb) {
		?>
			<div class="entry-preview"><?php echo $thumb;?></div>
		<?php } ?>
		<?php } elseif($format == 'introvid') { ?>
			<div class="entry-preview introvid player">
				<?php echo apply_filters('the_content', $video);?>
			</div>
		<?php } ?>				
						
			<div class="entry-content">
				
				<?php if(!empty($event->post_object->post_excerpt)) { ?>
				<div class="lead"><?php echo apply_filters('rdc_the_content', $event->post_object->post_excerpt); ?></div>
				<?php } ?>
				
				<?php echo apply_filters('the_content', $event->post_object->post_content); ?>
			</div>
		</main>
		
		<div id="rdc_sidebar" class="bit md-4">
		<?php
			if(!$event->is_expired()){
				echo "<p class='add-to-cal-wrap'>";
				rdc_add_to_calendar_link($event, true, 'tst-add-calendar', "", true);							
				echo "</p>";
			}
			
			dynamic_sidebar( 'right_event-sidebar' );
		?>			
		</div>
	
	</div>
</div>
</section>

<?php
	
	$pquery = new WP_Query(array(
		'post_type'=> 'event',
		'posts_per_page' => 5,
		'post__not_in' => array($event->ID),
		'orderby'  => array('menu_order' => 'DESC', 'meta_value' => 'DESC'),					
		'meta_key' => 'event_date_start'
	));
		
	rdc_more_section($pquery->posts, __('Visit or events', 'rdc'), 'events', 'addon'); 
		
	

get_footer();