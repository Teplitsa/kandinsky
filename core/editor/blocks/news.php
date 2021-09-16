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
		'heading'          => array(
			'type'    => 'string',
			'default' => esc_html__( 'News', 'knd' ),
		),
		'postsToShow'      => array(
			'type'    => 'integer',
			'default' => 3,
		),
		'align'            => array(
			'type'    => 'string',
			'default' => 'full',
		),
		'className'        => array(
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
		'titleColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'linkColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'metaColor'  => array(
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
	),
) );

/**
 * Render Block News
 *
 * @param array $attr Block Attributes.
 */
function knd_block_news_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'wp-block-knd-news',
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
	$posts_to_show = 3;
	if ( isset( $attr['postsToShow'] ) && $attr['postsToShow'] ) {
		$posts_to_show = $attr['postsToShow'];
	}

	// Background Color
	$style = '';
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$color  = $attr['backgroundColor'];
		$style .= '--knd-block-news-background:' . $color . ';';
		$classes[] = 'has-background';
	}

	// Heading Color
	if ( isset( $attr['headingColor'] ) && $attr['headingColor'] ) {
		$style .= '--knd-color-headings:' . $attr['headingColor'] . ';';
	}

	// Title Color
	if ( isset( $attr['titleColor'] ) && $attr['titleColor'] ) {
		$style .= '--knd-block-news-title-color:' . $attr['titleColor'] . ';';
	}

	// Links Color
	if ( isset( $attr['linkColor'] ) && $attr['linkColor'] ) {
		$style .= '--knd-block-news-link-color:' . $attr['linkColor'] . ';';
	}

	// Meta Color
	if ( isset( $attr['metaColor'] ) && $attr['metaColor'] ) {
		$style .= '--knd-block-news-meta-color:' . $attr['metaColor'] . ';';
	}

	// Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
	}

	$html  = '<div class="' . knd_block_class( $classes ) . '" style="' . $style . '">';
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

	if ( $heading ) {
		$html .= '<div class="section-heading">
			<h2 class="section-title">' . esc_html( $heading ) . '</h2>
			' . $heading_links . '
		</div>';
	}

	$html .= '<div class="knd-row start cards-row">';

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $posts_to_show,
		'ignore_sticky_posts' => true,
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		while ( $query->have_posts() ) :
			$query->the_post();

			$this_post = get_post( get_the_ID() );

			$html .= '<article class="' . esc_attr( join( ' ', get_post_class( 'knd-col' ) ) ) . '">
			<a href="' . get_the_permalink() . '" class="thumbnail-link">
				<div class="entry-preview">
					' . get_the_post_thumbnail( null, 'post-thumbnail' ) . '
				</div>
				' . the_title( '<h3 class="entry-title">', '</h3>', false ) . '
				<div class="entry-meta">' . strip_tags( knd_posted_on( $this_post ), '<span>') . '</div>
			</a>
		</article>';

		endwhile;

	endif;

	wp_reset_postdata();

	$html .= '</div> </div>';

	$html .= '</div>';

	return $html;
}

