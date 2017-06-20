<?php
/**
 * Template Name: Events
 * 
 **/

$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

//future
$future = $featured = array();
$today_stamp = strtotime('today midnight');

if($paged < 2) {
$future_events = new WP_Query(array(
	'post_type' => 'event',
	'posts_per_page' => -1,
	'orderby'  => array('menu_order' => 'DESC', 'meta_value' => 'ASC'),					
	'meta_key' => 'event_date_start',
	'meta_query' => array(					
		array(
			'key' => 'event_date_end',
			'value' => $today_stamp,
			'compare' => '>=',
			'type' => 'numeric'
		)
	)
));

$future = $future_events->posts;
$featured = array_slice($future, 0, 2); 
array_splice($future, 0, 2);
}

//past
$past_agrs = array(
	'post_type' => 'event',
	'posts_per_page' => get_option('posts_per_page'),
	'orderby'  => array('meta_value' => 'DESC'),					
	'meta_key' => 'event_date_start',
	'meta_query' => array(					
		array(
			'key' => 'event_date_end',
			'value' => $today_stamp,
			'compare' => '<',
			'type' => 'numeric'
		)
	)
);

if($paged > 1){
	$past_agrs['paged'] = $paged; 
}

$past_events = new WP_Query($past_agrs);

get_header();
?>
<?php if(!empty($featured)) { //featured post ?>
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

<?php if(!empty($future)) { ?>
<section class="heading">
	<div class="container"><h1 class="section-title archive"><?php _e('Future events', 'rdc'); ?></h1></div>
</section>

<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">
	<?php
		foreach($future as $p){
			rdc_event_card($p);
		}		
	?>
</div>
</div></section>
<?php } ?>

<section class="heading">
	<div class="container"><h1 class="section-title archive"><?php _e('Past events', 'rdc'); ?></h1></div>
</section>

<section class="main-content cards-holder"><div class="container-wide">
<div class="cards-loop sm-cols-1 md-cols-2 lg-cols-4">
	<?php
		if($past_events->have_posts()){
			foreach($past_events->posts as $p){
				rdc_event_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging"><?php rdc_paging_nav($past_events); ?></section>

<?php get_footer();