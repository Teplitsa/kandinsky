<?php
/**
 * The template for displaying all pages.
 *
 * @package Kandinsky
 */

get_header();
?>
<header class="page-header">
	<div class="container">
		<div class="text-column">

			<?php if ( wp_get_post_parent_id( $post ) ) { ?>
				<div class="page-crumb">
					<a href="<?php the_permalink( wp_get_post_parent_id( $post ) ); ?>">
						<?php echo esc_html( get_the_title( wp_get_post_parent_id( $post ) ) ); ?>
					</a>
				</div>
			<?php } ?>

			<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>

			<?php if ( has_excerpt() ) { ?>
				<div class="page-intro">
					<?php the_excerpt(); ?>
				</div>
			<?php } ?>

		</div>
	</div>
</header>

<div class="page-content container">
	<div class="the-content text-column">
		<?php the_content(); ?>
	</div>
</div>

<?php
get_footer();
