<?php
/**
 * The main template file.
 * 
 * @package Kandinsky
 */

get_header(); ?>

<div class="container heading">
	<?php knd_section_title(); ?>
</div>

<?php if ( have_posts() ) { ?>
	<div class="main-content cards-holder listing-bg archive-post-list knd-overflow-visible">
		<div class="container">
			<div class="flex-row start cards-loop">
			<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', 'archive' );
				}
			?>
			</div>
		</div>

		<?php knd_posts_pagination(); ?>

	</div>

<?php } else { ?>
	<div class="main-content listing-bg">
		<div class="container">
			<div class="empty-message"><?php esc_html_e( 'Unfortunately, nothing found', 'knd' );?></div>
			<?php get_search_form(); ?>
		</div>
	</div>
<?php } ?>

<?php

knd_bottom_blocks();

get_footer();
