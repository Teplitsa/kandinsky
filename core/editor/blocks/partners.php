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
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
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
		'isLink'     => array(
			'type'    => 'boolean',
			'default' => true,
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
		'queryOrder' => array(
			'type'    => 'string',
			'default' => 'date/desc',
		),
		'queryOffset' => array(
			'type' => 'string',
			'default' => '',
		),
		'queryCategory' => array(
			'type' => 'string',
			'default' => 0,
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
		//'wrapAround' => true, // infinite scroll
		'groupCells' => true,
		'selectedAttraction' => 0.08,
		'friction' => 1,
		'imagesLoaded' => true,
		'arrowShape' => 'm 56.0012 76.6652 c 1.3652 0 2.7308 -0.5224 3.7708 -1.5624 c 2.0852 -2.0856 2.0852 -5.456 0 -7.5416 l -17.6268 -17.6264 l 16.96 -17.5628 c 2.0428 -2.1228 1.984 -5.4984 -0.1332 -7.5412 c -2.1228 -2.0428 -5.4988 -1.984 -7.5416 0.128 l -20.5972 21.3332 c -2.0212 2.096 -1.9944 5.4188 0.064 7.4776 l 21.3332 21.3332 c 1.0404 1.04 2.4056 1.5624 3.7708 1.5624 z',
	);
	// Svg path editor online //https://yqnn.github.io/svg-path-editor/

	if ( isset( $attr['autoplay'] ) && $attr['autoplay'] ) {
		$data_flickity['autoPlay'] = true;
	}

	$items_class = 'knd-block-items';

	$data_attr = '';
	if ( isset( $attr['layout'] ) && $attr['layout'] === 'carousel' ) {
		if ( true !== $attr['preview'] ) {
			$items_class  .= ' knd-block-carousel';
			$data_flickity = wp_json_encode( $data_flickity );
			$data_attr     = ' data-flickity="' . esc_attr( $data_flickity ) . '"';
		}
	}

	$html .= '<div class="' . esc_attr( $items_class ) . '"' . $data_attr . '>';

	$args = array(
		'post_type'      => 'org',
		'posts_per_page' => $posts_to_show,
		//'meta_key'       => '_thumbnail_id',
	);

	// Orderby
	if ( isset( $attr['queryOrder'] ) && $attr['queryOrder'] ) {
		$queryOrder = $attr['queryOrder'];
		$order = explode( '/', $queryOrder );
		if ( $order ) {
			$args['orderby'] = array(
				$order[0] => $order[1],
			);
			if ( 'date' === $order[0] ) {
				$args['orderby']['ID'] = $order[1];
			}
		}
	}

	// Offset
	if ( isset( $attr['queryOffset'] ) && $attr['queryOffset'] ) {
		$args['offset'] = $attr['queryOffset'];
	}

	// Category
	if ( isset( $attr['queryCategory'] ) && $attr['queryCategory'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'org_cat',
				'field'    => 'id',
				'terms'    => $attr['queryCategory'],
			),
		);
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_post = get_post( get_the_ID() );

			$url = $this_post->post_excerpt;

			if ( ! $url ) {
				$url = get_post_meta( get_the_ID(), '_knd_org_url', true );
			}

			$alt_text = get_the_title();

			$html .= '<div class="knd-block-item">';

				if ( has_post_thumbnail() ) {
					$partner_logo = get_the_post_thumbnail( null, 'medium_large', array( 'alt' => get_the_title() ) );
				} else {
					$partner_logo = '<div class="knd-partner-placeholder"></div>';
				}

				if ( isset( $attr['isLink'] ) && $attr['isLink'] ) {
					$html .= '<a href="' . esc_url( $url ) . '" class="partner-link" target="_blank">' . $partner_logo . '</a>';
				} else {
					$html .= '<div class="partner-link">' . $partner_logo . '</div>';
				}

			$html .= '</div>';

		endwhile;

	endif;

	wp_reset_postdata();

	$html .= '</div> </div>';

	$html .= '</div>';

	return $html;
}
