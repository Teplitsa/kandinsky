<?php
/**
 * Gutenberg Block Testimonials
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Testimonials
 */
register_block_type( 'knd/testimonials', array(

	'render_callback' => 'knd_block_testimonials_render_callback',

	'attributes'      => array(
		'heading'         => array(
			'type'    => 'string',
			'default' => esc_html__( 'Testimonials', 'knd' ),
		),
		'align'           => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'textColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'cartBackgroundColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'layout'     => array(
			'type'    => 'string',
			'default' => 'grid',
		),
		'autoplay'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'preview'    => array(
			'type' => 'boolean',
			'default' => false,
		),
		'className'       => array(
			'type'    => 'string',
			'default' => '',
		),
		'anchor'       => array(
			'type'    => 'string',
			'default' => '',
		),
	),
) );

/**
 * Render Block Testimonials
 *
 * @param array $attr Block Attributes.
 */
function knd_block_testimonials_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'wp-block-knd-testimonials',
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

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color  = $attr['backgroundColor'];
		$style .= '--knd-block-testimonial-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-block-testimonial-heading-color:' . $attr['headingColor'] . ';';
	}

	// Text Color
	if ( isset( $attr['textColor'] ) && $attr['textColor'] ) {
		$style .= '--knd-block-testimonial-text-color:' . $attr['textColor'] . ';';
	}

	// Meta Color
	if ( isset( $attr['cartBackgroundColor'] ) && $attr['cartBackgroundColor'] ) {
		$style .= '--knd-block-testimonial-cart-background:' . $attr['cartBackgroundColor'] . ';';
	}

	// Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$html  = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">';
	$html .= '<div class="knd-container">';

	if ( $heading ) {
		$html .= '<div class="section-heading"><h2 class="section-title">' . esc_html( $heading ) . '</h2></div>';
	}

	$data_flickity = array(
		'cellAlign' => 'left',
		'contain'   => true,
		'pageDots'  => false,
		'groupCells' => '1',
		'selectedAttraction' =>  0.08,
		'friction' => 1,
		'imagesLoaded' => true,
		'arrowShape' => 'm 56.0012 76.6652 c 1.3652 0 2.7308 -0.5224 3.7708 -1.5624 c 2.0852 -2.0856 2.0852 -5.456 0 -7.5416 l -17.6268 -17.6264 l 16.96 -17.5628 c 2.0428 -2.1228 1.984 -5.4984 -0.1332 -7.5412 c -2.1228 -2.0428 -5.4988 -1.984 -7.5416 0.128 l -20.5972 21.3332 c -2.0212 2.096 -1.9944 5.4188 0.064 7.4776 l 21.3332 21.3332 c 1.0404 1.04 2.4056 1.5624 3.7708 1.5624 z',
	);
	// Svg path editor online //https://yqnn.github.io/svg-path-editor/

	if ( isset( $attr['autoplay'] ) && $attr['autoplay'] ) {
		$data_flickity['autoPlay'] = 5000;
	}

	$items_class = 'knd-block-items';

	$data_attr = '';
	if ( isset( $attr['layout'] ) && $attr['layout'] === 'carousel' ) {
		if ( true !== $attr['preview'] ) {
			$items_class  .= ' knd-block-carousel flickity-buttons-top';
			$data_flickity = wp_json_encode( $data_flickity );
			$data_attr     = ' data-flickity="' . esc_attr( $data_flickity ) . '"';
		}
	}

	$html .= '<div class="' . esc_attr( $items_class ) . '"' . $data_attr . '>';

	$args = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => -1,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_post = get_post( get_the_ID() );

			$excerpt = $this_post->post_excerpt;

			$item_class = 'knd-block-item knd-entry';

			$author_avatar = '';
			if ( get_the_post_thumbnail() ) {
				$author_avatar = '<div class="author-avatar">' . get_the_post_thumbnail( null, 'square-small' ) . '</div>';
			}

			$html .= '<article class="' . esc_attr( join( ' ', get_post_class( $item_class ) ) ) . '">
				<div class="knd-block-item__inner">
					<div class="entry-data">
						<h4 class="entry-title">' . get_the_title() . '</h4>
						<div class="entry-meta">' . wp_kses_post( wpautop( $excerpt ) ) . '</div>
					</div>
					' . wp_kses_post( $author_avatar ) . '
				</div>
			</article>';

		endwhile;

	endif;

	wp_reset_postdata();

	$html .= '</div> </div>';

	$html .= '</div>';

	return $html;
}
