<?php
/**
 * Gutenberg Block News
 *
 * @package Kandinsky
 */

/**
 * Register Block Type News
 */
register_block_type( 'knd/news', array(

	'render_callback' => 'knd_block_news_render_callback',

	'attributes'      => array(
		'layout'     => array(
			'type'    => 'string',
			'default' => 'type-1',
		),
		'heading'          => array(
			'type'    => 'string',
			//'default' => esc_html__( 'News', 'knd' ),
		),
		'headingLink'          => array(
			'type'    => 'string',
		),
		'titleAlign'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'postsToShow'      => array(
			'type'    => 'integer',
			'default' => 3,
		),
		'columns'     => array(
			'type'    => 'integer',
			'default' => 3,
		),
		'radius'     => array(
			'type'    => 'integer',
			'default' => 5,
		),
		'align'            => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'backgroundColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'titleColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'titleHoverColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'cardBackroundColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'overlayColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'overlayHoverColor'  => array(
			'type'    => 'string',
			'default' => '',
		),

		'linkColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'linkHoverColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'linkHoverBackground'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'metaColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'excerptColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'dateColor'  => array(
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
		'className'        => array(
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

		'thumbnail'     => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'imageOrientation'     => array(
			'type'    => 'string',
			'default' => 'landscape',
		),
		'imageSize'     => array(
			'type'    => 'string',
			'default' => 'post-thumbnail',
		),
		'imageWidth'     => array(
			'type'    => 'string',
			'default' => 'half',
		),
		'imagePosition'     => array(
			'type'    => 'string',
			'default' => 'left',
		),
		
		'date'     => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'author'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'avatar'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'category'     => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'title'     => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'excerpt'     => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'excerptLength'     => array(
			'type'    => 'integer',
			'default' => 30,
		),
		'titleFontSize'  => array(
			'type'    => 'string',
			'default' => '18px',
		),
		'titleFontSizeMobile'  => array(
			'type'    => 'string',
			'default' => '18px',
		),
		'excerptFontSize'  => array(
			'type'    => 'string',
			'default' => '14px',
		),
		'excerptFontSizeMobile'  => array(
			'type'    => 'string',
			'default' => '14px',
		),
		'titleFontWeight'  => array(
			'type'    => 'string',
			'default' => 'bold',
		),
		'dateFormat'  => array(
			'type'    => 'string',
			'default' => 'd.m.Y',
		),
		'alignment'  => array(
			'type'    => 'string',
			'default' => 'bottom left',
		),
		'paddingTop'  => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'paddingBottom'  => array(
			'type'    => 'boolean',
			'default' => true,
		),
	),
) );

/**
 * Render Block News
 *
 * @param array $attr Block Attributes.
 */
function knd_block_news_render_callback( $attr ) {

	//print_r($attr);

	// Classes
	$classes = array(
		'block_class' => 'knd-block-news',
	);

	$layout = 'type-1';
	if ( isset( $attr['layout'] ) && $attr['layout'] ) {
		$classes[] = 'knd-block-news-' . $attr['layout'];
		$layout = $attr['layout'];
	}

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

	// Block Content Alignment
	if ( isset( $attr['alignment'] ) && $attr['alignment'] && $attr['layout'] === 'type-3' ) {
		$alignment = str_replace( ' ', '-', $attr['alignment'] );
		$classes['alignment'] = 'is-position-' . $alignment;
	}

	// Block image width
	if ( isset( $attr['imageWidth'] ) && $attr['imageWidth'] && $attr['layout'] === 'type-2' ) {
		$classes['imagewidth'] = 'is-image-width-' . $attr['imageWidth'];
	}

	// Block image position
	if ( isset( $attr['imagePosition'] ) && $attr['imagePosition'] && $attr['layout'] === 'type-2' ) {
		$alignment = str_replace( ' ', '-', $attr['alignment'] );
		$classes['imageposition'] = 'is-image-position-' . $attr['imagePosition'];
	}

	// Block Padding
	if ( $attr['paddingTop'] === false ||  $attr['paddingBottom'] === false ) {
		$padding_class = 'is-no-padding';
		if ( $attr['paddingTop'] === false ) {
			$padding_class .= '-top';
		}
		if ( $attr['paddingBottom'] === false ) {
			$padding_class .= '-bottom';
		}
		$classes['padding'] = $padding_class;
	}

	// Posts To Show
	$posts_to_show = 3;
	if ( isset( $attr['postsToShow'] ) && $attr['postsToShow'] ) {
		$posts_to_show = $attr['postsToShow'];
	}

	// Columns
	$columns = 3;
	if ( isset( $attr['columns'] ) && $attr['columns'] ) {
		$columns = $attr['columns'];
	}
	$classes['cols'] = 'knd-block-col-' . $columns;

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color  = $attr['backgroundColor'];
		$style .= '--knd-block-news-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-block-heading-color:' . $attr['headingColor'] . ';';
	}

	// Title Color
	if ( isset( $attr['titleColor'] ) && $attr['titleColor'] ) {
		$style .= '--knd-block-news-title-color:' . $attr['titleColor'] . ';';
		$style .= '--knd-block-post-title-color:' . $attr['titleColor'] . ';';
	}

	// Title Hover Color
	if ( isset( $attr['titleHoverColor'] ) && $attr['titleHoverColor'] ) {
		$style .= '--knd-block-post-title-color-hover:' . $attr['titleHoverColor'] . ';';
	}

	// Card background Color
	if ( isset( $attr['cardBackroundColor'] ) && $attr['cardBackroundColor'] ) {
		$style .= '--knd-card-background:' . $attr['cardBackroundColor'] . ';';
	}

	// Card overlay Color
	if ( isset( $attr['overlayColor'] ) && $attr['overlayColor'] ) {
		$style .= '--knd-block-post-overlay:' . $attr['overlayColor'] . ';';
	}

	// Card overlay Hover Color
	if ( isset( $attr['overlayHoverColor'] ) && $attr['overlayHoverColor'] ) {
		$style .= '--knd-block-post-overlay-hover:' . $attr['overlayHoverColor'] . ';';
	}

	// Links Color
	if ( isset( $attr['linkColor'] ) && $attr['linkColor'] ) {
		$style .= '--knd-block-link-color:' . $attr['linkColor'] . ';';
	}

	// Links Hover Color
	if ( isset( $attr['linkHoverColor'] ) && $attr['linkHoverColor'] ) {
		$style .= '--knd-block-link-color-hover:' . $attr['linkHoverColor'] . ';';
	}

	// Links Hover Background Color
	if ( isset( $attr['linkHoverBackground'] ) && $attr['linkHoverBackground'] ) {
		$style .= '--knd-block-link-background:' . $attr['linkHoverBackground'] . ';';
	}

	// Meta Color
	if ( isset( $attr['metaColor'] ) && $attr['metaColor'] ) {
		$style .= '--knd-block-news-meta-color:' . $attr['metaColor'] . ';';
	}

	// Date Color
	if ( isset( $attr['excerptColor'] ) && $attr['excerptColor'] ) {
		$style .= '--knd-block-post-excerpt-color:' . $attr['excerptColor'] . ';';
	}

	// Date Color
	if ( isset( $attr['dateColor'] ) && $attr['dateColor'] ) {
		$style .= '--knd-block-post-date-color:' . $attr['dateColor'] . ';';
	}

	// Border Radius
	if ( isset( $attr['radius'] ) ) {
		$style .= '--knd-image-border-radius:' . $attr['radius'] . 'px;';
	}

	// Title font size mobile
	if ( isset( $attr['titleFontSize'] ) && $attr['titleFontSize'] != '18px' ) {
		$style .= '--knd-block-post-title-fontsize:' . $attr['titleFontSize'] . ';';
	}

	// Title font size mobile
	if ( isset( $attr['titleFontSizeMobile'] ) && $attr['titleFontSizeMobile'] != '14px' ) {
		$style .= '--knd-block-post-title-fontsize-mobile:' . $attr['titleFontSizeMobile'] . ';';
	}

	// Excerpt font size
	if ( isset( $attr['excerptFontSize'] ) && $attr['excerptFontSize'] != '14px' ) {
		$style .= '--knd-block-post-excerpt-fontsize:' . $attr['excerptFontSize'] . ';';
	}

	// Excerpt font size mobile
	if ( isset( $attr['excerptFontSizeMobile'] ) && $attr['excerptFontSizeMobile'] != '14px' ) {
		$style .= '--knd-block-post-excerpt-fontsize-mobile:' . $attr['excerptFontSizeMobile'] . ';';
	}

	$image_size = 'post-thumbnail';
	if ( isset( $attr['imageSize'] ) && $attr['imageSize'] ) {
		$image_size = $attr['imageSize'];
	}

	$link_icon = '<svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M12.0303 6.53033C12.3232 6.23744 12.3232 5.76256 12.0303 5.46967L7.25736 0.696699C6.96447 0.403806 6.48959 0.403806 6.1967 0.696699C5.90381 0.989593 5.90381 1.46447 6.1967 1.75736L10.4393 6L6.1967 10.2426C5.90381 10.5355 5.90381 11.0104 6.1967 11.3033C6.48959 11.5962 6.96447 11.5962 7.25736 11.3033L12.0303 6.53033ZM0 6.75H11.5V5.25H0V6.75Z" fill="currentColor"/>
	</svg>';

	// Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
	}

	// Heading link
	if ( isset( $attr['headingLink'] ) && $attr['headingLink'] ) {
		$heading = '<a href="' . $attr['headingLink'] . '">' . $heading . $link_icon . '</a>';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = ' id="' . esc_attr( $attr['anchor'] ) . '"';
	}

	$start_block = '<div class="' . knd_block_class( $classes ) . '"' . $attr_id . ' style="' . $style . '">';
	$end_block   = '</div>';

	$start_container = '<div class="knd-container">';
	$end_container   = '</div>';

	$start_row = '<div class="knd-row">';
	$end_row   = '</div>';

	$before_row = '';
	$after_row  = '';

	// Heading Links.
	$heading_links = '';
	if ( isset( $attr['headingLinks'] ) && $attr['headingLinks'] ) {

		foreach( $attr['headingLinks'] as $index => $link ) {
			if ( ! isset( $link['linkTitle'] ) || ! $link['linkTitle'] ) {
				unset( $attr['headingLinks'][ $index ] );
			}
		}

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

	$block_heading = '';

	if ( $heading ) {
		$block_title_class = 'knd-block-title';
		if ( isset( $attr['titleAlign'] ) && $attr['titleAlign'] ) {
			$block_title_class .= ' is-align-center';
		}
		$block_heading = '<div class="knd-block-heading">
			<h2 class="' . esc_attr( $block_title_class ) . '">' . $heading . '</h2>
			' . $heading_links . '
		</div>';
	}

	$articles = '';

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $posts_to_show,
		'ignore_sticky_posts' => true,
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
				'taxonomy' => 'category',
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

			if ( $attr['layout'] === 'type-0' ) {

					$articles .= '<article class="' . esc_attr( join( ' ', get_post_class( 'knd-col knd-entry' ) ) ) . '">
					<a href="' . get_the_permalink() . '" class="thumbnail-link">
						<div class="entry-preview">
							' . get_the_post_thumbnail( null, $image_size, array( 'alt' => wp_trim_words( get_the_title(), 5 ), 'aria-hidden' => 'true' ) ) . '
						</div>
						' . the_title( '<h3 class="entry-title">', '</h3>', false ) . '
						<div class="entry-meta">' . strip_tags( knd_posted_on( $this_post ), '<span><time>') . '</div>
					</a>
				</article>';

			} else if ( $attr['layout'] === 'type-1' ) {
				$attr['thumbnail_link'] = true;
				$articles .= '<article class="' . esc_attr( join( ' ', get_post_class( 'knd-col knd-entry' ) ) ) . '">
					' . knd_block_post_thumbnail( $attr ) . '
					' . knd_block_post_category( $attr ) . '
					' . knd_block_post_title( $attr ) . '
					' . knd_block_post_excerpt( $attr ) . '
					' . knd_block_post_meta( $attr ) . '
				</article>';
			} else if ( $attr['layout'] === 'type-2' ) {
				$attr['thumbnail_link'] = true;
				$articles .= '<article class="' . esc_attr( join( ' ', get_post_class( 'knd-col knd-entry' ) ) ) . '">
					<div class="knd-post-entry-inner">
						' . knd_block_post_thumbnail( $attr ) . '
						<div class="knd-post-entry-content">
						' . knd_block_post_category( $attr ) . '
						' . knd_block_post_title( $attr ) . '
						' . knd_block_post_excerpt( $attr ) . '
						' . knd_block_post_meta( $attr ) . '
					</div>
				</article>';
			} else if ( $attr['layout'] === 'type-3' ) {

				$orientation_class = 'knd-ratio-' . $attr['imageOrientation'];
				$attr['thumbnail_link'] = false;
				$articles .= '<article class="' . esc_attr( join( ' ', get_post_class( 'knd-col knd-entry' ) ) ) . '">
					<div class="knd-entry-overlay ' . $orientation_class . '">
						' . knd_block_post_thumbnail( $attr ) . '
						<a href="' . get_the_permalink() . '" class="knd-block-post-overlay-link"></a>
						<div class="knd-block-post-content">
							' . knd_block_post_category( $attr ) . '
							' . knd_block_post_title( $attr ) . '
							' . knd_block_post_excerpt( $attr ) . '
							' . knd_block_post_meta( $attr ) . '
						</div>
					</div>
				</article>';
			}

		endwhile;

	else:

	endif;

	wp_reset_postdata();

	if ( $attr['layout'] === 'type-1' ) {
		$before_row = $block_heading;
	} else if ( $attr['layout'] === 'type-2' ) {
		$before_row = $block_heading;
	} else if ( $attr['layout'] === 'type-3' ) {
		$before_row = $block_heading;
	} else if ( $attr['layout'] === 'type-4' ) {
		//$html = $attr['layout'];
	}

	$html = $start_block . $start_container . $before_row . $start_row . $articles . $end_row . $after_row . $end_container . $end_block;

	return $html;
}

