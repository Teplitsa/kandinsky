<?php
/**
 * Request corrections
 * 
 **/

/* CPT Filters */
add_action('parse_query', 'rdc_request_corrected');
function rdc_request_corrected(WP_Query $query) {

	if(is_admin()) {
		return;
	}

	if(!$query->is_main_query()) {
		return;
	}

	if(is_search()){
		
		$per = get_option('posts_per_page');
		if($per < 25)
			$query->set('posts_per_page', 25);		
	}
	
	//if(is_tax() || is_category()) {
	//
	//	$qo = $query->get_queried_object();
	//	$f_ids = get_field('featured_posts', $qo->taxonomy.'_'.$qo->term_id);
	//
	//	if($f_ids) {
	//		$query->set('post__not_in', $f_ids);
	//	}
	//
	//} elseif(is_post_type_archive('event')) {
	//
	//	$query->set('orderby', 'meta_value');
	//	$query->set('meta_key', 'event_date');
	//}
}






