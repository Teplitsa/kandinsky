<?php
/**
 * Campaign page
 *
 * @package Kandinsky
 */

$cpost = get_queried_object();
$age   = get_post_meta( $cpost->ID, 'campaign_age', true );
if ( ! empty( $age ) ) {
	$age = ', ' . $age;
}

$is_finished = get_post_meta( $cpost->ID, 'is_finished', true );

get_header();
?>
<article class="main-content leyka-campaign">
	<div class="container">

		<div class="entry-header-single container-text">

			<?php if ( get_theme_mod( 'campaign_link_above_title', true ) ) { ?>
				<div class="entry-meta">
				<?php if ( $is_finished ) { ?>
					<a href="<?php echo site_url( '/campaign/completed/' ); ?>" class="entry-link">
						<?php echo esc_html( get_theme_mod( 'knd_completed_campaigns_archive_title', __( 'They alredy got help', 'knd' ) ) ); ?>
					</a> 
				<?php } else { ?>
					<a href="<?php echo site_url( '/campaign/active/' ); ?>" class="entry-link">
						<?php echo esc_html( get_theme_mod( 'knd_active_campaigns_archive_title', __( 'They need help', 'knd' ) ) ); ?>
					</a> 
				<?php } ?>
				</div>
			<?php } ?>

			<?php the_title( '<h1 class="entry-title">', $age . '</h1>' ); ?>

		</div>

		<main class="container-text">

			<div class="campaign-form entry-content the-content">
				<?php the_content(); ?>
			</div>

			<?php if ( get_theme_mod( 'campaign_links_after_content', true ) ) { ?>
				<div class="related-campaigns">
					<a href="<?php echo site_url( '/campaign/active/' ); ?>" class="entry-link">
						<?php echo esc_html( get_theme_mod( 'knd_active_campaigns_archive_title', __( 'They need help', 'knd' ) ) ); ?>
					</a>
					<a href="<?php echo site_url( '/campaign/completed/' ); ?>" class="entry-link">
						<?php echo esc_html( get_theme_mod( 'knd_completed_campaigns_archive_title', __( 'They alredy got help', 'knd' ) ) ); ?>
					</a>
				</div>
			<?php } ?>

		</main>

	</div>
</article>

<?php
get_footer();
