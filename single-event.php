<?php
/**
 * The template for displaying single event.
 *
 * @package Kandinsky
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	$this_event = knd_init_event_metas( get_the_ID() );

	?>

	<div class="main-content knd-entry-event">

		<div class="container">

			<div class="knd-entry-event__header">
				<?php do_action( 'knd_entry_header' ); ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<?php if ( get_theme_mod( 'post_social_shares', true ) ) { ?>
					<div class="mobile-sharing hide-on-medium"><?php echo knd_social_share_no_js(); ?></div>
				<?php } ?>

				<?php if ( has_post_thumbnail() ) { ?>
					<div class="knd-entry-event__image">
						<?php the_post_thumbnail( 'full' ); ?>
					</div>
				<?php } ?>

			</div>

			<div class="knd-entry-event__info">
				<div class="knd-event__content-inner">
					<div class="knd-event__dates">
						<span class="knd-event__date"><?php echo knd_get_event_meta( '#j' ); ?></span>
						<span class="knd-event__month"><?php echo knd_get_event_meta( '#M' ); ?></span>
					</div>
					<div class="knd-event__details">
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
					<?php if ( knd_get_event_meta( 'event_rsvp' ) && knd_is_booking_open() ) { ?>
						<div class="knd-event__cta">
							<a href="#" class="knd-button knd-event__cta-button">
								<?php echo get_option( 'dbem_bookings_cta_field', esc_html__( 'Book Now', 'knd' ) ); ?>
							</a>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="flex-row entry-content-single knd-entry-event__main">

				<div class="flex-cell flex-md-1 single-sharing-col hide-upto-medium">
					<?php if ( get_theme_mod( 'post_social_shares', true ) && get_theme_mod( 'social_share_location', 'left' ) === 'left' ) { ?>
						<div id="knd_sharing" class="regular-sharing knd-entry-event__sharing">
							<?php echo knd_social_share_no_js();?>
						</div>
					<?php } ?>
				</div>

				<main class="flex-cell flex-md-11">

					<div class="entry-content the-content knd-entry-event__content">
						<?php the_content(); ?>

						<?php knd_entry_event_speakers(); ?>

						<?php knd_entry_event_schedule(); ?>

						<?php knd_entry_event_question(); ?>

					</div>

					<?php knd_entry_shares(); ?>

				</main>

			</div>

			<?php if ( knd_get_event_meta( 'event_rsvp' ) ) { ?>
				<div class="knd-event__booking">
					<?php echo knd_get_event_meta( '#_BOOKINGFORM' );?>
				</div>
			<?php } ?>

			<?php get_template_part( 'template-parts/entry/entry-event-related' ); ?>

		</div><!-- .container -->

	</div><!-- .main-content -->

	<?php knd_bottom_blocks(); ?>

<?php
endwhile;

get_footer();
