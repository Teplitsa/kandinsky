<?php
/**
 * Search tempalte
 **/

$s_query = get_search_query();
$num = 0;

if(!empty($s_query) && $wp_query->found_posts > 0){
	$num = (int)$wp_query->found_posts;
}


//build correct label for results
function rdc_build_results_label($number){
	
	$label = "Найдено %d страниц";	
	$test = $number % 10;
	
	if($test == 1)
		$label = "Найдена %d страница";
	elseif($test > 1 && $test < 5)
		$label = "Найдено %d страницы";
	
	//11 case		
	if($number % 100 >= 11 &&  $number % 100 <= 19){
		$label = "Найдено %d страниц";
	}
	
	return sprintf($label, $number);

}
 
get_header();
?>
<section class="heading">
	<div class="container">
		<?php rdc_section_title(); ?>
		<div id="sr_form" class="sr-form"><?php get_search_form();?></div>
		<div class="sr-num"><?php echo rdc_build_results_label($num);?></div>
	</div>
</section>

<section class="main-content search-results"><div class="container">
	<?php
		if(empty($s_query)){
			$l = __('Enter terms for search in the form and hit Enter', 'rdc');
			echo "<article class='tpl-search'><div class='entry-summary'><p>{$l }</p></div></article>";							
		}
		elseif($num == 0){
			$l = __('Nothing found under your request', 'rdc');
			echo "<article class='tpl-search'><div class='entry-summary'><p>{$l}</p></div></article>";				
		}
		else {
			foreach($wp_query->posts as $sp){
				rdc_search_card($sp);
			}
		}
	?>
</section>
<section class="paging"><?php rdc_paging_nav($wp_query); ?></section>

<?php get_footer();