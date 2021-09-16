<?php
/**
 * The template for displaying events page.
 */


if ( defined( 'EM_VERSION' ) ) {
get_header();

?>

<div class="knd-container">

	<?php do_action( 'knd_events_header' ); ?>

	<div class="knd-events__wrapper">
		<h1 class="knd-events__title">
			<?php
				esc_html_e( 'Schedule of events', 'knd' );
				if ( is_paged() ) {
					echo ': ' . esc_html__( 'Page', 'knd' ) . ' ' . get_query_var( 'paged' );
				}
			?>
		</h1>
		<div class="knd-events__toolbar">
			<?php knd_social_share_no_js(); ?>

			<div class="knd-events__layouts">
				<?php foreach( array( 'list', 'grid' ) as $type ) { ?>
					<?php
						$view_class = 'knd-events__layout-' . $type;
					if ( $type == knd_get_archive_type() ) {
						$view_class .= ' active';
					}
					?>
					<a href="#" class="<?php echo esc_attr( $view_class ); ?>" data-type="<?php echo esc_attr( $type ); ?>">
						<?php knd_svg_icon( 'icon-' . $type );?>
					</a>
				<?php } ?>
			</div>
		</div>

		<?php
		$archive_class = '';
		if ( 'grid' ===  knd_get_archive_type() ) {
			$archive_class = ' knd-events__grid';
		}

		$page   = get_query_var( 'page' );
		$pages = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$posts_per_page = (int) get_option( 'dbem_events_default_limit', 10 );

		$events_args = array(
			'post_type'      => 'event',
			'posts_per_page' => $posts_per_page,
			'paged'          => $pages,
			'meta_query' => array(
				array(
					'key' => '_is_sticky',
					'value' => '1',
					'compare' => '!=',
				),
			),
		);
		$events_query = new WP_Query( $events_args );

		$GLOBALS['wp_query'] = $events_query;

		?>

		<?php if ( have_posts() ) : ?>
			<div class="knd-events__main<?php echo esc_attr( $archive_class ); ?>">

				<?php while ( have_posts() ) : ?>
				<?php the_post();

				$this_event = knd_init_event_metas( get_the_ID() );
				?>
					<div class="knd-event__col">
						<article <?php post_class( 'knd-event__item' );?>>
							<div class="knd-event__image">
								<a href="<?php the_permalink(); ?>" class="knd-event__image-inner">
									<?php if ( has_post_thumbnail() ) { ?>
										<?php the_post_thumbnail( get_the_ID(), 'medium_large');?>
									<?php } ?>
								</a>
							</div>
							<div class="knd-event__content">
								<div class="knd-event__content-inner">
									<div class="knd-event__dates">
										<span class="knd-event__date"><?php echo knd_get_event_meta( '#j' ); ?></span>
										<span class="knd-event__month"><?php echo knd_get_event_meta( '#M' ); ?></span>
									</div>
									<div class="knd-event__details">
										<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
										<div class="knd-event__details-list">
											<?php if ( knd_get_event_meta( 'event_rsvp' ) ) { ?>
												<div class="knd-event__details-item knd-event__details-remained">
													<span class="knd-event__details-icon"><?php knd_svg_icon( 'icon-bell' );?></span><?php echo knd_get_event_remained(); ?>
												</div>
											<?php } ?>
											<div class="knd-event__details-item knd-event__details-dates">
												<span class="knd-event__details-icon"><?php knd_svg_icon('icon-calendar');?></span><?php echo knd_get_event_times(); ?>, <?php echo knd_get_event_dates(); ?>
											</div>
											<?php if ( knd_get_event_type() ) { ?>
												<div class="knd-event__details-item knd-event__details-location">
													<span class="knd-event__details-icon"><?php knd_svg_icon('icon-marker-outline'); ?></span><?php echo knd_get_event_type(); ?>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>

								<div class="knd-event__footer">
									<div class="knd-event__speakers">
										<?php knd_event_speakers(); ?>
									</div>
									<div class="knd-event__footer-link">
										<a href="<?php the_permalink(); ?>">
											<?php knd_svg_icon( 'icon-arrow-right' );?>
										</a>
									</div>
								</div>
							</div>

						</article>
					</div>
				<?php endwhile; ?>

			</div>

			<?php
				the_posts_pagination(
					array(
						'aria_label' => esc_html__( 'Events', 'knd' ),
						'class'      => 'knd-pagination',
					)
				);
			?>

		<?php else : ?>
			<p><?php esc_html_e( 'No events', 'knd' ); ?>
		<?php endif;
		wp_reset_postdata();
		wp_reset_query();
		?>

	</div>

</div>

<?php get_footer();

} else {

	get_template_part( 'page' );

}
