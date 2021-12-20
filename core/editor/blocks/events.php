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
		'layout'  => array(
			'type'    => 'string',
			'default' => 'list',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'anchor'       => array(
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
		'linkColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingLinks'  => array(
			'type'    => 'array',
			'default' => array(),
		),
		'hiddenReload'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'queryOffset' => array(
			'type' => 'string',
			'default' => '',
		),
		'queryOrderBy' => array(
			'type' => 'string',
			'default' => '_event_start_date',
		),
		'queryInclude' => array(
			'type' => 'array',
			'default' => array(),
		),
		'queryWhat' => array(
			'type' => 'string',
			'default' => 'future',
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

	// Links Color
	if ( isset( $attr['linkColor'] ) && $attr['linkColor'] ) {
		$style .= '--knd-block-link-color:' . $attr['linkColor'] . ';';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html  = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">';
	$html .= '<div class="knd-container">';

	// Heading Links.
	$heading_links = '';
	if ( isset( $attr['headingLinks'] ) && $attr['headingLinks'] ) {

		foreach( $attr['headingLinks'] as $index => $link ) {
			if ( ! isset( $link['linkTitle'] ) || ! $link['linkTitle'] ) {
				unset( $attr['headingLinks'][ $index ] );
			}
		}

		$link_icon = '<svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12.0303 6.53033C12.3232 6.23744 12.3232 5.76256 12.0303 5.46967L7.25736 0.696699C6.96447 0.403806 6.48959 0.403806 6.1967 0.696699C5.90381 0.989593 5.90381 1.46447 6.1967 1.75736L10.4393 6L6.1967 10.2426C5.90381 10.5355 5.90381 11.0104 6.1967 11.3033C6.48959 11.5962 6.96447 11.5962 7.25736 11.3033L12.0303 6.53033ZM0 6.75H11.5V5.25H0V6.75Z" fill="currentColor"/>
		</svg>';

		$heading_links = '<div class="section-links">';

		$i     = 0;
		$items = count( $attr['headingLinks'] );

		foreach( $attr['headingLinks'] as $link ) {
			$icon = '';
			$i++;
			if ( $i == $items ) {
				$icon = $link_icon;
			}
			$heading_links .= '<a href="' . $link['linkUrl'] . '">' . $link['linkTitle'] . $icon .  '</a>';
		}

		$heading_links .= '</div>';

	}

	// Heading
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
		$html .= '<div class="section-heading">
			<h2 class="section-title">' . esc_html( $heading ) . '</h2>
			' . $heading_links . '
		</div>';
	}

	global $this_event;

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => $posts_to_show,
		'meta_key'       => '_event_start_date',
	);

	if ( $attr['queryOffset'] ) {
		$args['offset'] = $attr['queryOffset'];
	}

	// Order by
	if ( $attr['queryOrderBy'] ) {
		$args['orderby'] = $attr['queryOrderBy'];
		if ( '_event_start_date' === $attr['queryOrderBy'] ) {
			$args['orderby'] = 'meta_value';
			$attr['queryOrderBy'] = 'meta_value';
		}
		if ( 'meta_value' === $attr['queryOrderBy'] ) {
			$args['order'] = 'asc';
		}
	}

	// What events to show
	if ( $attr['queryWhat'] && 'future' === $attr['queryWhat'] ) {
		$args['meta_query'] = array(
			array(
				'key' => '_event_start_date',
				'value' => date( 'Y-m-d'),
				'compare' => '>='
			),
		);
	}

	// Include campaigns.
	if ( $attr['queryInclude'] ) {
		$post__in = array();
		foreach ( $attr['queryInclude'] as $page_title ) {
			$page_obj = get_page_by_title( $page_title, OBJECT, 'event' );
			$post__in[] = $page_obj->ID;
		}
		$args['post__in'] = $post__in;

		// Order by
		if ( $attr['queryOrderBy'] && 'date' !== $attr['queryOrderBy'] ) {
			$args['orderby'] = $attr['queryOrderBy'];
		}
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		$html .= '<div class="knd-events__main knd-events__' . $layout . '">';

		while ( $query->have_posts() ) :
			$query->the_post();

			global $post;

			$post_id = $post->ID;

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
					<article class="' . esc_attr( implode( ' ', get_post_class( 'knd-event__item', $post_id ) ) ) . '">
						<div class="knd-event__image">
							<a href="' . get_the_permalink( $post_id ) . '" class="knd-event__image-inner">
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
									<h5><a href="' . get_the_permalink( $post_id ) . '">' . get_the_title() . '</a></h5>
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
									<a href="' . get_the_permalink( $post_id ) . '">
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
