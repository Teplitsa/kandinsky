<?php
/**
 * Search tempalte
 **/

get_header();
?>
<div class="page-header">
	<div class="container">
		<div class="text-column">
			<h1 class="page-title"><?php esc_html_e( 'Search', 'knd' );?></h1>
		</div>
	</div>
	<div class="widget-full widget_search search-holder">
		<?php get_search_form();?>
		<div class="sr-num"><?php printf( _n( '%s result', '%s results', (int) $wp_query->found_posts, 'knd' ), (int) $wp_query->found_posts );?></div>
	</div>
</div>

<div class="main-content container search-loop">

	<?php if ( have_posts() ) { ?>

	<div class="text-column">

		<?php
		while ( have_posts() ) {
			the_post();

			$post_type_object = get_post_type_object(get_post_type());
			$post_meta = $post_type_object->labels->singular_name;
			$excerpt = apply_filters( 'knd_the_title', knd_get_post_excerpt( $post, 40, true ) );
			?>
			<article class="tpl-search">
				
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>" class="entry-link"><?php the_title();?></a>
				</h2>
				<div class="entry-meta"><?php echo esc_html( $post_meta ); ?></div>
				<div class="entry-summary"><?php echo esc_html( $excerpt );?></div>
			</article>
			<?php
		}
		?>
	</div>

	<?php knd_posts_pagination(); ?>

	<?php } else { ?>

		<div class="text-column">

			<article class="tpl-search">
				<div class="entry-summary">
					<p><?php esc_html_e( 'Nothing found under your request', 'knd' ); ?></p>
				</div>
			</article>

		</div>

	<?php } ?>

</div>

<?php get_footer();

