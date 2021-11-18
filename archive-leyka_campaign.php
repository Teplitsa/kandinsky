<?php
/**
 * The main template file.
 */

$posts = $wp_query->posts;

$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();
?>

<section class="container heading">
	<?php knd_section_title(); ?>
</section>


<div class="main-loop container">
	<?php if(!empty($posts)) { ?>
		<div class="knd-block-leyka-cards alignfull  knd-block-col-2">
			<div class="knd-block-items">
				<?php
					foreach($posts as $p){
						knd_donation_card($p);
					}
				?>
			</div>
		</div>
	<?php } else { ?>
		<div class="empty-message"><?php esc_html_e('Unfortunately, nothing found', 'knd');?></div>
	<?php } ?>
</div>

<?php if(!empty($posts)) { ?>
<div class="paging"><?php knd_paging_nav($wp_query); ?></div>
<?php } ?>

<?php get_footer();