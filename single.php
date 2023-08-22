<?php
/**
 * The template for displaying all single posts.
 *
 * @package Kandinsky
 */

$cpost = get_queried_object();

$featured_image = true;

if ( is_singular( 'post' ) ) {
	$featured_image = get_theme_mod( 'post_featured_image', true );
} else if ( is_singular( 'project') ) {
	$featured_image = get_theme_mod( 'project_featured_image', true );
}

get_header();
?>

<div class="main-content single-post-section">

<div class="container">

	<div class="flex-row entry-header-single centered">

		<div class="flex-cell flex-md-10">
			<?php do_action( 'knd_entry_header' ); ?>
			<div class="entry-meta"><?php echo knd_posted_on( $cpost ); ?></div>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php if ( get_theme_mod( 'post_social_shares', true ) ) { ?>
				<div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js(); ?></div>
			<?php } ?>
		</div>

	</div>

	<?php if ( has_post_thumbnail() && $featured_image ) { ?>
		<div class="flex-row entry-preview-single centered">
			<div class="flex-cell flex-md-10">
				<?php knd_single_post_thumbnail( $cpost->ID, 'full', 'standard' ); ?>
			</div>
		</div>
	<?php } ?>

	<div class="flex-row entry-content-single">

		<div class="flex-cell flex-md-1 hide-upto-medium"></div>

		<div class="flex-cell flex-md-1 single-sharing-col hide-upto-medium">
			<?php if ( get_theme_mod( 'social_share_location', 'left' ) === 'left' ) { ?>
				<?php if ( get_post_type() === 'project' ) { ?>
					<?php if ( get_theme_mod( 'project_social_shares', true ) ) { ?>
						<div id="knd_sharing" class="regular-sharing">
							<?php echo knd_social_share_no_js();?>
						</div>
					<?php } ?>
				<?php } else { ?>
					<?php if ( get_theme_mod( 'post_social_shares', true ) ) { ?>
						<div id="knd_sharing" class="regular-sharing">
							<?php echo knd_social_share_no_js();?>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>

		<main class="flex-cell flex-md-8">

			<?php if ( has_excerpt() ) { ?>
				<div class="entry-lead">
					<?php echo wpautop( $post->post_excerpt ); ?>
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

			<?php knd_entry_tags(); ?>

			<?php knd_entry_shares(); ?>

			<?php knd_entry_related(); ?>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</main>

		<div class="flex-cell flex-md-2 hide-upto-medium"></div>

	</div>

	</div><!-- .container -->
</div><!-- .main-content -->

<?php knd_bottom_blocks(); ?>

<?php
get_footer();
