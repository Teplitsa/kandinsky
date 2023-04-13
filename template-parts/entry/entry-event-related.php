<?php
/**
 * The template for displaying entry related
 *
 * @package Kandinsky
 */

$related_args = array(
	'post_type'      => 'event',
	'posts_per_page' => 2,
	'post__not_in'   => array( get_the_ID() ),
);
$related_query = new WP_Query( $related_args );

if ( $related_query->have_posts() ) :
?>

<div class="knd-event__related">
	<?php if ( get_option( 'dbem_event_related_heading', true ) ) { ?>
		<h5 class="knd-event__related-heading"><?php echo get_option( 'dbem_event_related_heading', esc_html__( 'More events', 'knd' ) ); ?></h5>
	<?php } ?>

	<?php
	$events_class = '';
	if ( $related_query->post_count > 1 ) {
		$events_class = ' knd-events__grid';
	}
	?>
	<div class="knd-events__main<?php echo esc_attr( $events_class ); ?>">

		<?php while ( $related_query->have_posts() ) : ?>
		<?php $related_query->the_post();

		global $this_event;

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

</div>
<?php
endif;
wp_reset_postdata();
