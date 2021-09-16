<?php
/**
 * Gutenberg Block Partners
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Partners
 */
register_block_type( 'knd/partners', array(

	'render_callback' => 'knd_block_partners_render_callback',

	'attributes'      => array(
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Our Partners', 'knd' ),
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
	),
) );

/**
 * Render Block Partners
 *
 * @param array $attr Block Attributes.
 */
function knd_block_partners_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'wp-block-knd-partners',
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
	if ( isset( $attr['postsToShow'] ) && $attr['postsToShow'] ) {
		$posts_to_show = $attr['postsToShow'];
	}

	$columns = 4;
	if ( isset( $attr['columns'] ) && $attr['columns'] ) {
		$columns = $attr['columns'];
	}
	$classes[] = 'knd-block-col-' . $columns;

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color = $attr['backgroundColor'];
		$style .= '--knd-block-partners-background:' . $color . ';';
		$classes[] = ' has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-color-headings:' . $attr['headingColor'] . ';';
	}

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
		'post_type'      => 'org',
		'posts_per_page' => $posts_to_show,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_post = get_post( get_the_ID() );

			$url = $this_post->post_excerpt;

			$html .= '<article class="knd-block-item">
				<a href="' . esc_url( $url ) . '" class="partner-link" target="_blank" title="' . get_the_title() . '">' . get_the_post_thumbnail( null, 'medium_large' ) . '</a>
		</article>
		';

		endwhile;

	endif;

	wp_reset_postdata();

	$html .= '</div> </div>';

	$html .= '</div>';

	return $html;
}
