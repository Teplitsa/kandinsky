<?php
/**
 * Gutenberg Block Events
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Events
 */
register_block_type( 'knd/events', array(

	'render_callback' => 'knd_block_events_render_callback',

	'attributes'      => array(
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Schedule of events', 'knd' ),
		),
		'postsToShow'     => array(
			'type'    => 'integer',
			'default' => 2,
		),
		'align'           => array(
			'type'    => 'string',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'backgroundColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'layout'  => array(
			'type'    => 'string',
			'default' => 'list',
		),
	),
) );

/**
 * Render Block Events
 *
 * @param array $attr Block Attributes.
 */
function knd_block_events_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'wp-block-knd-events',
	);

	$layout = 'list';

	// Block Class Name
	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
		$class_names = explode( ' ', $attr['className'] );
		if ( in_array( 'is-style-grid', $class_names ) ) {
			$layout = 'grid';
		}
	}

	// Align
	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	// Posts To Show
	$posts_to_show = 2;
	if ( isset( $attr['postsToShow'] ) && $attr['postsToShow'] ) {
		$posts_to_show = $attr['postsToShow'];
	}

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color  = $attr['backgroundColor'];
		$style .= '--knd-block-events-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-color-headings:' . $attr['headingColor'] . ';';
	}

	$html  = '<div class="' . knd_block_class( $classes ) . '" style="' . $style . '">';
	$html .= '<div class="knd-container">';

	// Heading
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
		$html .= '<div class="section-heading">
			<h2 class="section-title">' . esc_html( $heading ) . '</h2>
		</div>';
	}

	global $this_event;

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => $posts_to_show,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		$html .= '<div class="knd-events__main knd-events__' . $layout . '">';

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_event = knd_init_event_metas( get_the_ID() );

			$thumbnail_img = '';
			if ( has_post_thumbnail() ) {
				$thumbnail_img = get_the_post_thumbnail( get_the_ID(), 'medium_large');
			}

			$event_rsvp = '';
			if ( knd_get_event_meta( 'event_rsvp' ) ) {
				$event_rsvp = '<div class="knd-event__details-item knd-event__details-remained">
					<span class="knd-event__details-icon">' . knd_svg_icon( 'icon-bell', false ) . '</span>' . knd_get_event_remained() . '
				</div>';
			}

			$event_type = '';
			if ( knd_get_event_type() ) {
				$event_type = '<div class="knd-event__details-item knd-event__details-location">
					<span class="knd-event__details-icon">' . knd_svg_icon( 'icon-marker-outline', false ) . '</span>' . knd_get_event_type() . '
				</div>';
			}

			$html .= '
				<div class="knd-event__col">
					<article class="knd-event__item">
						<div class="knd-event__image">
							<a href="' . get_the_permalink() . '" class="knd-event__image-inner">
								' . $thumbnail_img . '
							</a>
						</div>
						<div class="knd-event__content">
							<div class="knd-event__content-inner">
								<div class="knd-event__dates">
									<span class="knd-event__date">' . knd_get_event_meta( '#j' ) . '</span>
									<span class="knd-event__month">' . knd_get_event_meta( '#M' ) . '</span>
								</div>
								<div class="knd-event__details">
									<h5><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h5>
									<div class="knd-event__details-list">
										' . $event_rsvp . '
										<div class="knd-event__details-item knd-event__details-dates">
											<span class="knd-event__details-icon">' . knd_svg_icon( 'icon-calendar', false ) . '</span>' . knd_get_event_times() . ', ' . knd_get_event_dates() . '
										</div>
										' . $event_type . '
									</div>
								</div>
							</div>

							<div class="knd-event__footer">
								<div class="knd-event__speakers">
									' . knd_get_event_speakers() . '
								</div>
								<div class="knd-event__footer-link">
									<a href="' . get_the_permalink() . '">
										' . knd_svg_icon( 'icon-arrow-right', false ) . '
									</a>
								</div>
							</div>
						</div>

					</article>
				</div>';

			endwhile;

		$html .= '</div>';

		else :

			$html .= '<p>' . esc_html__( 'No events', 'knd' ) . '</p>';

		endif;
		wp_reset_postdata();

		$html .= '</div>';
		$html .= '</div>';

	return $html;
}
