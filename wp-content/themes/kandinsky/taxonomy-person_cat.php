<?php
/**
 * People category
 */

$qo = get_queried_object(); 
$posts = $wp_query->posts;
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();

if($paged == 1) { //featured post
	$fa_title = get_term_meta($qo->term_id, 'featured_action_title', true);
	$fa_subtitle = get_term_meta($qo->term_id, 'featured_action_subtitle', true);
	$fa_image = (int)get_term_meta($qo->term_id, 'featured_action_image_id', true);
	$fa_link = esc_url(get_term_meta($qo->term_id, 'featured_action_link', true));
	$fa_link_text = get_term_meta($qo->term_id, 'featured_action_link_text', true);
	
	$sharing = (empty($fa_link)) ? true : false;
	$fa_link_text = (empty($fa_link_text)) ? __('More', 'rdc') : $fa_link_text;
	
	if($fa_image) { ?>
	<div class="taxonomy-intro"><div class="container">
	<?php rdc_intro_card_markup_below($fa_title, $fa_subtitle, $fa_image, $fa_link, $fa_link_text); ?>
	</div></div>
 <?php }} ?>

<section class="heading">
	<div class="container"><?php rdc_section_title(); ?></div>
</section>

<section class="main-content cards-holder"><div class="container">
<div class="cards-loop sm-cols-2 md-cols-2 lg-cols-4">
	<?php
		if(have_posts()){
			foreach($posts as $p){
				rdc_person_card($p);
			}
		}
		else {
			echo '<p>Ничего не найдено</p>';
		}
	?>
</div>
</div></section>

<section class="paging">
<?php rdc_paging_nav($wp_query); ?>
</section>

<?php get_footer();
