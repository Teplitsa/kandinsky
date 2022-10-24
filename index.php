<?php
/**
 * The main template file.
 * 
 * @package Kandinsky
 */

get_header(); ?>

<div class="container heading">
	<?php knd_section_title(); ?>
	<?php knd_archive_description(); ?>
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

		<?php if( is_post_type_archive( 'project' ) && get_theme_mod( 'projects_completed' ) && get_theme_mod( 'projects_completed_cat' ) ) {

			$args = array(
				'post_type'      => 'project',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'project_cat',
						'field'    => 'slug',
						'terms'    => array( get_theme_mod( 'projects_completed_cat' ) ),
					),
				),
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) :
				$term = get_term_by('slug', get_theme_mod( 'projects_completed_cat' ) , 'project_cat' ); ?>
				<div class="container projects-completed-container">
					<div class="section-heading">
						<h2 class="section-title"><?php echo esc_html( $term->name ); ?></h2>
					</div>
					<div class="flex-row start cards-loop">
					<?php
					while ( $query->have_posts() ) : $query->the_post();
					$post_class = 'flex-cell flex-md-6 knd-post-item flex-lg-4 tpl-post';
					if ( get_theme_mod('projects_completed_style') ) {
						$post_class .= ' knd-project-completed';
					}
					?>

					<article <?php post_class( $post_class );?>>
						<div class="knd-post-item__inner">
							<a href="<?php the_permalink(); ?>" class="thumbnail-link">
								<div class="entry-preview"><?php echo knd_post_thumbnail( get_the_ID(), 'post-thumbnail' );?></div>
								<div class="entry-data">
									<?php the_title('<h2 class="entry-title">','</h2>'); ?>
								</div>
							</a>
						</div>
					</article>
					<?php
					endwhile;
					?>
					</div>
				</div>
				<?php
				endif;
			wp_reset_postdata();
		}
	?>

	</div>

<?php } else { ?>
	<div class="main-content listing-bg">
		<div class="container">
			<div class="empty-message"><?php esc_html_e( 'Unfortunately, nothing found', 'knd' );?></div>
			<?php get_search_form(); ?>
		</div>
	</div>
<?php }

knd_bottom_blocks();

get_footer();
