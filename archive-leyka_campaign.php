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

<?php knd_posts_pagination( array( 'screen_reader_text' => esc_html__( 'Campaigns navigation', 'knd' ) ) ); ?>

<?php get_footer();