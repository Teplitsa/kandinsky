<?php
/**
 * Search tempalte
 **/

$posts = $wp_query->posts;
$s_query = get_search_query();
$num = 0;

if(!empty($s_query) && $posts > 0){
	$num = (int)$wp_query->found_posts;
}

get_header();
?>
<header class="page-header">
	<div class="container">
		<div class="text-column">
			<h1 class="page-title"><?php esc_html_e( 'Search', 'knd' );?></h1>
		</div>
	</div>
	<div class="widget-full widget_search search-holder">
		<?php get_search_form();?>
		<div class="sr-num"><?php printf( _n( '%s result', '%s results', $num, 'knd' ), $num );?></div>
	</div>
</header>

<div class="main-content container search-loop">
	<div class="text-column">
		<?php
			if(empty($s_query)){
				$l = __('Enter terms for search in the form and hit Enter', 'knd');
				echo "<article class='tpl-search'><div class='entry-summary'><p>{$l }</p></div></article>";	
			}
			elseif($num == 0){
				$l = __('Nothing found under your request', 'knd');
				echo "<article class='tpl-search'><div class='entry-summary'><p>{$l}</p></div></article>";
			}
			else {
				foreach($posts as $p){
					knd_search_card($p);
				}
			}
		?>
	</div>
</div>

<?php if($num > 0) { ?>
<div class="paging"><?php knd_paging_nav(); ?></div>
<?php } ?>

<?php get_footer();
