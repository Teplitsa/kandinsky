<?php
/**
 * Gutenberg Block People
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Partners
 */
register_block_type( 'knd/people', array(

	'render_callback' => 'knd_block_people_render_callback',

	'attributes'      => array(
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Our Team', 'knd' ),
		),
		'postsToShow'     => array(
			'type'    => 'integer',
			'default' => 4,
		),
		'columns'     => array(
			'type'    => 'integer',
			'default' => 4,
		),
		'align'           => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'nameColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'metaColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'category'   => array(
			'type'    => 'string',
			'default' => '',
		)
	),
) );

/**
 * Render Block People
 *
 * @param array $attr Block Attributes.
 */
function knd_block_people_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'wp-block-knd-people',
	);

	// Block Class Name
	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	// Align
	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	// Posts To Show
	$posts_to_show = 4;
	if ( isset( $attr['postsToShow'] ) ) {
		$posts_to_show = $attr['postsToShow'];
	}

	// Columns
	$columns = 4;
	if ( isset( $attr['columns'] ) && $attr['columns'] ) {
		$columns = $attr['columns'];
	}
	$classes['cols'] = ' knd-block-col-' . $columns;

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color  = $attr['backgroundColor'];
		$style .= '--knd-block-people-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-block-people-heading-color:' . $attr['headingColor'] . ';';
	}

	// Name Color
	if ( isset( $attr['nameColor'] ) && $attr['nameColor'] ) {
		$style .= '--knd-block-people-name-color:' . $attr['nameColor'] . ';';
	}

	// Meta Color
	if ( isset( $attr['metaColor'] ) && $attr['metaColor'] ) {
		$style .= '--knd-block-people-meta-color:' . $attr['metaColor'] . ';';
	}

	// Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
	}

	$html  = '<div class="' . knd_block_class( $classes ) . '" style="' . $style . '">';
	$html .= '<div class="knd-container">';

	if ( $heading ) {
		$html .= '<div class="section-heading"><h2 class="section-title">' . esc_html( $heading ) . '</h2></div>';
	}

	$html .= '<div class="knd-block-items">';

	$args = array(
		'post_type'      => 'person',
		'posts_per_page' => $posts_to_show,
	);

	if ( isset( $attr['category'] ) && $attr['category'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'person_cat',
				'field'    => 'id',
				'terms'    => array( $attr['category'] )
			),
		);
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_post = get_post( get_the_ID() );
	
			$excerpt = $this_post->post_excerpt;

			$html .= '<article class="knd-block-item">
				<div class="entry-preview">' . get_the_post_thumbnail( null, 'square' ) . '</div>
				<div class="entry-data">
					<h4 class="entry-title">' . get_the_title() . '</h4>
					<div class="entry-meta">' . esc_html( $excerpt ) . '</div>
				</div>
			</article>';

		endwhile;

	endif;

	wp_reset_postdata();

	$html .= '</div> </div>';

	$html .= '</div>';

	return $html;
}
