<?php
/**
 * The template for displaying all single posts.
 *
 * @package Kandinsky
 */

$cpost = get_queried_object();

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

			<?php knd_entry_tags(); ?>

			<?php knd_entry_related(); ?>
		</main>

		<div class="flex-cell flex-md-2 hide-upto-medium"></div>

	</div>

	</div><!-- .container -->
</div><!-- .main-content -->

<?php knd_bottom_blocks(); ?>

<?php
get_footer();
