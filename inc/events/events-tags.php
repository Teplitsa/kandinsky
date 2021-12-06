<?php
/**
 * Kandinsky Events Functions
 *
 * @package Kandinsky
 */

/**
 * If Post has Speakers
 */
function knd_has_post_speakers() {
	if ( get_post_meta( get_the_ID(), '_speakers', true ) ) {
		return true;
	}
	return false;
}

/**
 * Event Speakers
 */
function knd_get_event_speakers() {
	if ( ! knd_has_post_speakers() ) {
		return;
	}

	$speakers_ids = get_post_meta( get_the_ID(), '_speakers', true );

	$speakers_args = array(
		'post_type'      => 'person',
		'posts_per_page' => -1,
		'post__in'       => $speakers_ids,
	);

	ob_start();
	$speakers_query = new WP_Query( $speakers_args );
	if ( $speakers_query->have_posts() ) :
		
		?>
		<div class="knd-event__speakers-icon">
			<?php knd_svg_icon('icon-mic'); ?>
		</div>
		
		<div class="knd-event__speakers-group">
		<?php while ( $speakers_query->have_posts() ) : $speakers_query->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="knd-event__speaker-item">
				<div class="knd-event__speaker-image">
					<?php if ( has_post_thumbnail() ) { ?>
						<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'square' ) ;?>" alt="<?php echo get_the_title(); ?>">
					<?php } ?>
				</div>
				<div class="knd-event__speaker-name">
					<span><?php the_title(); ?></span>
				</div>
			</a>
		<?php endwhile; ?>
		</div>
		<?php
		
	endif;
	wp_reset_postdata();
	$output = ob_get_clean();

	return $output;
}

/**
 * Event Speakers
 */
function knd_event_speakers() {
	echo knd_get_event_speakers();
}

/**
 * Entry Event Speakers
 */
function knd_entry_event_speakers() {

	$speakers_ids = get_post_meta( get_the_ID(), '_speakers', true );

	if ( $speakers_ids ) {
		$speakers_args = array(
			'post_type'      => 'person',
			'posts_per_page' => -1,
			'post__in'       => $speakers_ids,
		);
		
		$speakers_query = new WP_Query( $speakers_args );
		if ( $speakers_query->have_posts() ) :
			?>

			<div class="knd-entry-event__speakers">
			<?php while ( $speakers_query->have_posts() ) : $speakers_query->the_post(); ?>
				<div class="knd-entry-event__speaker-col">
					<div class="knd-entry-event__speaker">
						<a href="<?php the_permalink(); ?>" target="_blank" class="knd-entry-event__speaker-image">
							<?php if ( has_post_thumbnail() ) { ?>
								<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'square' ) ;?>" alt="<?php echo get_the_title(); ?>">
							<?php } ?>
						</a>
						<div class="knd-entry-event__speaker-name"><?php the_title(); ?></div>
						<div class="knd-entry-event__speaker-desc"><?php the_excerpt(); ?></div>
					</div>
				</div>
			<?php endwhile; ?>
			</div>
			<?php
		endif;
		wp_reset_postdata();
	}
}

/**
 * Entry Event Times
 */
function knd_get_event_times() {

	$event_timezone = knd_get_event_meta( '#_EVENTTIMEZONE' );

	$zone = explode( '/', $event_timezone );

	if ( isset( $zone[1] ) ) {
		$city = $zone[1];
		$event_timezone = esc_html__( $city, 'continents-cities' );
		if ( 'Moscow' === $city ) {
			$event_timezone = esc_html__( 'Msk', 'knd' );
		}
	}

	$times = knd_get_event_meta( '#_EVENTTIMES' );
	if ( ! knd_get_event_meta( 'event_all_day' ) ) {
		$times .= ' (' . $event_timezone . ')';
	}

	return $times;
}

/**
 * Entry Event Dates
 */
function knd_get_event_dates() {
	$dates = wp_date( 'D d F Y', strtotime( knd_get_event_meta( '#_{Y-m-d}' ) ) );
	if ( knd_get_event_meta( '#@_{Y}' ) ) {
		$dates .= ' - ' . wp_date( 'D d F Y', strtotime( knd_get_event_meta( '#@_{Y-m-d}' ) ) );
	}
	return $dates;
}

/**
 * Entry Event Schedule
 */
function knd_entry_event_schedule() {

	$schedules = get_post_meta( get_the_ID(), '_schedule', true );

	if ( $schedules ) {
	?>

	<div class="knd-event__schedule">

		<?php if ( get_option( 'dbem_event_schedule_heading', true ) ) { ?>
			<div class="knd-event__schedule-header">
				<h4><?php echo get_option( 'dbem_event_schedule_heading', esc_html__( 'Schedule', 'knd' ) ); ?></h4>
			</div>
		<?php } ?>

		<?php foreach( $schedules as $schedule ) { ?>

			<div class="knd-event__schedule-group">
				<?php if( isset( $schedule['title'] ) ) { ?>
					<div class="knd-event__schedule-title">
						<h5><?php echo esc_html( $schedule['title'] ); ?></h5>
					</div>
				<?php } ?>

				<?php if ( isset( $schedule['list'] ) && $schedule['list'] ) { ?>
					<div class="knd-event__schedule-list">
						<?php foreach ( $schedule['list'] as $item ) { ?>
						<div class="knd-event__schedule-item">
							<?php if ( isset( $item['hour_start'] ) && $item['hour_start'] ) {
								$schedule_dates = $item['hour_start'];
								if ( isset( $item['hour_end'] ) && $item['hour_end'] ) {
									$schedule_dates .= ' - ' . $item['hour_end'];
								}
								?>
								<div class="knd-event__schedule-hours"><?php echo esc_html( $schedule_dates ); ?></div>
							<?php } ?>
							<?php if ( isset( $item['desc'] ) && $item['desc'] ) { ?>
								<div class="knd-event__schedule-text">
									<div class="knd-event__schedule-text-inner"><?php echo wp_kses_post( $item['desc'] ); ?></div>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				<?php } ?>

			</div>

		<?php } ?>

	</div>
	<?php
	}
}

/**
 * Entry Event Question
 */
function knd_entry_event_question(){
	?>
	<?php if ( get_option( 'dbem_event_question_heading', true ) ) { ?>
	<div class="knd-event__question-header">
		<h4><?php echo get_option( 'dbem_event_question_heading', esc_html__( 'Still have questions?', 'knd' ) ); ?></h4>
	</div>
	<?php } ?>
	<?php if ( get_option( 'dbem_event_question_content' ) ) { ?>
		<div class="knd-event__question-content">
			<?php echo wpautop( get_option( 'dbem_event_question_content' ) ); ?>
		</div>
	<?php } ?>

	<?php
}

/**
 * Entry Events Header
 */
function knd_events_header(){
	$sticky_args = array(
		'post_type'      => 'event',
		'posts_per_page' => -1,
		'meta_key'       => '_is_sticky',
		'meta_value'     => '1',
	);
	$sticky_query = new WP_Query( $sticky_args );

	global $this_event;

	if ( $sticky_query->have_posts() ) : ?>
		<div class="knd-events__header">
			<?php
			while ( $sticky_query->have_posts() ) : $sticky_query->the_post();

				$this_event = knd_init_event_metas( get_the_ID() );

				?>
				<div class="knd-event__sticky">
					<?php if ( has_post_thumbnail( get_the_ID() ) ) { ?>
						<div class="knd-event__sticky-image">
							<?php echo get_the_post_thumbnail( get_the_ID(), 'full');?>
						</div>
					<?php } ?>
					<div class="knd-event__sticky-inner">
						<div class="knd-event__sticky-content">
							<div class="knd-event__sticky-dates">
								<span class="knd-event__sticky-date"><?php echo knd_get_event_meta( '#j' ); ?></span>
								<span class="knd-event__sticky-month"><?php echo knd_get_event_meta( '#M' ); ?></span>
							</div>
							<h2>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?>
									<span class="knd-event__sticky-icon">
										<?php knd_svg_icon( 'icon-arrow-right' );?>
									</span>
								</a>
							</h2>
						</div>
					</div>
				</div>
				<?php
			endwhile;
		?>
		</div>
		<?php
	endif;
	wp_reset_postdata();
}
add_action( 'knd_events_header', 'knd_events_header', 20 );
