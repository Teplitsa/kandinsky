<?php
/**
 * The template for displaying all single posts.
 *
 * @package Kandinsky
 */

$cpost = get_queried_object();

$cpost_type = $cpost->post_type;
$tags_term  = null;
if ( 'post' === $cpost_type ) {
	$tags_term = 'post_tag';
} elseif ( 'project' === $cpost_type ) {
	$tags_term = 'project_tag';
} else {
	$tags_term = null;
}

get_header();
?>

<div class="main-content single-post-section">

<div class="container">

	<header class="flex-row entry-header-single centered">

		<div class="flex-cell flex-md-10">
			<?php do_action( 'knd_entry_header' ); ?>
			<div class="entry-meta"><?php echo knd_posted_on( $cpost ); ?></div>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php if ( get_theme_mod( 'post_social_shares', true ) ) { ?>
				<div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js(); ?></div>
			<?php } ?>
		</div>

	</header>

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="flex-row entry-preview-single centered">
			<div class="flex-cell flex-md-10">
				<?php knd_single_post_thumbnail( $cpost->ID, 'full', 'standard' ); ?>
			</div>
		</div>
	<?php } ?>

	<div class="flex-row entry-content-single">

		<div class="flex-cell flex-md-1 hide-upto-medium"></div>

		<div class="flex-cell flex-md-1 single-sharing-col hide-upto-medium">
			<?php if ( get_theme_mod( 'post_social_shares', true ) ) { ?>
				<div id="knd_sharing" class="regular-sharing">
					<?php echo knd_social_share_no_js();?>
				</div>
			<?php } ?>
		</div>

		<main class="flex-cell flex-md-8">

			<?php if ( has_excerpt() ) { ?>
				<div class="entry-lead">
					<?php the_excerpt(); ?>
				</div>
			<?php } ?>

			<div class="entry-content the-content">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>

			<?php
			echo get_the_term_list(
				$cpost->ID,
				$tags_term,
				'<div class="single-post-terms tags-line">',
				', ',
				'</div>'
			);
			?>

			<?php
			if ( 'post' === $cpost->post_type ) {
				$cat    = get_the_terms( $post->ID, 'category' );
				$pquery = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 5,
						'post__not_in'   => array( $cpost->ID ),
						'tax_query'      => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'id',
								'terms'    => ( isset( $cat[0] ) ) ? $cat[0]->term_id : array(),
							),
						),
					)
				);

				if ( ! $pquery->have_posts() ) {
					$pquery = new WP_Query(array(
						'post_type'      => 'post',
						'posts_per_page' => 5,
						'post__not_in'   => array( $cpost->ID ),
					));
				}

				knd_more_section( $pquery->posts, __('Related items', 'knd'), 'news', 'addon' );

			} elseif ( 'project' === $cpost->post_type ) {
				$pquery = new WP_Query(
					array(
						'post_type'      => 'project',
						'posts_per_page' => 5,
						'post__not_in'   => array( $cpost->ID ),
						'orderby'        => 'rand',
					)
				);

				if ( $pquery->have_posts() ) {
					knd_more_section( $pquery->posts, __('Related projects', 'knd'), 'projects', 'addon' );
				}
			}
			?>
		</main>

		<div class="flex-cell flex-md-2 hide-upto-medium"></div>

	</div>

	</div><!-- .container -->
</div><!-- .main-content -->

<div class="knd-signle-after-content">

	<?php
	if ( 'post' === $cpost->post_type ) {
		dynamic_sidebar( 'knd-news-archive-sidebar' );
	} else {
		dynamic_sidebar( 'knd-projects-archive-sidebar' );
	}
	?>

</div>

<?php
get_footer();
