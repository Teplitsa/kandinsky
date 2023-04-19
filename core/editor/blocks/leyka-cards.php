<?php
/**
 * Gutenberg Block Leyka Campaign
 *
 * @package Kandinsky
 */

/**
 * Register Block Type Leyka Cards
 */
register_block_type( 'knd/leyka-cards', array(

	'render_callback' => 'knd_block_leyka_cards_render_callback',

	'attributes'      => array(
		'heading'          => array(
			'type'    => 'string',
			'default' => esc_html__( 'Donations', 'knd' ),
		),
		'align'           => array(
			'type'    => 'string',
			'default' => '',
		),
		'postsToShow'     => array(
			'type'    => 'integer',
			'default' => 2,
		),
		'columns'     => array(
			'type'    => 'integer',
			'default' => 2,
		),
		'backgroundColor' => array(
			'type'    => 'string',
			'default' => '',
		),
		'headingColor'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorBackground'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorTitle'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorExcerpt'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorButton'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorFulfilled'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorUnfulfilled'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorTargetAmount'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'colorCollectedAmount'  => array(
			'type'    => 'string',
			'default' => '',
		),
		'preview'     => array(
			'type'    => 'boolean',
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
		'buttonText' => array(
			'type' => 'string',
			'default' => esc_html__('Donate', 'knd'),
		),
		'showImage' => array(
			'type' => 'boolean',
			'default' => true,
		),
		// 'showTags' => array(
		// 	'type' => 'boolean',
		// 	'default' => false,
		// ),
		'showTitle' => array(
			'type' => 'boolean',
			'default' => true,
		),
		
		'showExcerpt' => array(
			'type' => 'boolean',
			'default' => false,
		),
		'showButton' => array(
			'type' => 'boolean',
			'default' => true,
		),
		'showProgressbar' => array(
			'type' => 'boolean',
			'default' => true,
		),
		'showTargetAmount' => array(
			'type' => 'boolean',
			'default' => true,
		),
		'showCollectedAmount' => array(
			'type' => 'boolean',
			'default' => true,
		),
		'titleFontSize' => array(
			'type' => 'string',
			'default' => '',
		),
		'queryInclude' => array(
			'type' => 'array',
			'default' => array(),
		),
		'queryExclude' => array(
			'type' => 'array',
			'default' => array(),
		),
		'queryOffset' => array(
			'type' => 'string',
			'default' => '',
		),
		'queryIsFinished' => array(
			'type' => 'boolean',
			'default' => false,
		),
		'queryOrderBy' => array(
			'type' => 'string',
			'default' => 'date',
		),
		'queryCampaignType' => array(
			'type'    => 'string',
			'default' => 'all',
		),
	),
) );

/**
 * Render Block Leyka Cards
 *
 * @param array $attr Block Attributes.
 */
function knd_block_leyka_cards_render_callback( $attr ) {

	// Classes
	$classes = array(
		'block_class' => 'knd-block-leyka-cards',
	);

	if ( isset( $attr['align'] ) && $attr['align'] ) {
		$classes['align'] = 'align' . $attr['align'];
	} else {
		$classes['align'] = 'alignnone';
	}

	if ( isset( $attr['className'] ) && $attr['className'] ) {
		$classes['class_name'] = $attr['className'];
	}

	// Posts To Show
	$posts_to_show = 2;
	if ( isset( $attr['postsToShow'] ) ) {
		$posts_to_show = $attr['postsToShow'];
	}

	// Columns
	$columns = 4;
	if ( isset( $attr['columns'] ) && $attr['columns'] ) {
		$columns = $attr['columns'];
	}
	$classes['cols'] = ' knd-block-col-' . $columns;

	// Heading
	$heading = '';
	if ( isset( $attr['heading'] ) && $attr['heading'] ) {
		$heading = $attr['heading'];
	}

	// Background Color
	if ( isset( $attr['backgroundColor'] ) && $attr['backgroundColor'] ) {
		$classes[] = 'has-background';
	}

	// Id
	$attr_id = '';
	if ( isset( $attr['anchor'] ) && $attr['anchor'] ) {
		$attr_id = esc_attr( $attr['anchor'] ) . '"';
	}

	// Query Args
	$args = array(
		'post_type'      => 'leyka_campaign',
		'posts_per_page' => $posts_to_show,
	);

	if ( $attr['queryInclude'] ) {
		$post__in = array();
		foreach ( $attr['queryInclude'] as $page_title ) {
			$page_obj = knd_get_post_by_title( $page_title, 'leyka_campaign' );
			$post__in[] = $page_obj->ID;
		}
		$args['post__in'] = $post__in;

		// Order by
		if ( $attr['queryOrderBy'] && 'date' !== $attr['queryOrderBy'] ) {
			$args['orderby'] = $attr['queryOrderBy'];
		}
	}

	if ( $attr['queryExclude'] ) {
		$post__not_in = array();
		foreach ( $attr['queryExclude'] as $page_title ) {
			$page_obj = knd_get_post_by_title( $page_title, 'leyka_campaign' );
			$post__not_in[] = $page_obj->ID;
		}

		if ( isset( $args['post__in'] ) && $post__not_in ) {

			foreach ( $post__not_in as $id ) {
				$key = array_search( $id, $args['post__in'] );
				if ( isset( $args['post__in'][ $key ] ) ) {
					unset( $args['post__in'][ $key ] );
				}
			}
			
		} else {
			$args['post__not_in'] = $post__not_in;
		}
	}

	if ( $attr['queryOffset'] ) {
		$args['offset'] = $attr['queryOffset'];
	}

	if ( $attr['queryIsFinished'] ) {
		$args['meta_query'][] = array(
			'key'     => 'is_finished',
			'value'   => 1,
			'compare' => '!=',
			'type' => 'NUMERIC',
		);
	}

	if ( 'all' !== $attr['queryCampaignType'] ) {
		$args['meta_query'][] = array(
			'key'     => 'campaign_type',
			'value'   => $attr['queryCampaignType'],
		);
	}

	$block_attr = knd_block_attr(
		array(
			'class' => knd_block_class( $classes ),
			'id'    => $attr_id,
			'style' => array(
				'--knd-block-cards-background'      => $attr['backgroundColor'],
				'--knd-block-people-heading-color'  => $attr['headingColor'],
				'--knd-block-card-background'       => $attr['colorBackground'],
				'--knd-block-card-title'            => $attr['colorTitle'],
				'--knd-block-card-excerpt'          => $attr['colorExcerpt'],
				'--knd-block-card-collected-amount' => $attr['colorCollectedAmount'],
				'--knd-block-card-target-amount'    => $attr['colorTargetAmount'],
				'--knd-block-card-button'           => $attr['colorButton'],
				'--knd-block-card-progressbar'      => $attr['colorFulfilled'],
				'--knd-block-card-title-size'       => $attr['titleFontSize'],
				'--leyka-color-main-second'         => $attr['colorUnfulfilled'],
			),
		)
	);

	$html = '';

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :

		$html = '<div ' . $block_attr . '>';

		$html .= '<div class="knd-container">';

		if ( $heading ) {
			$html .= '<div class="section-heading">
				<h2 class="section-title">' . esc_html( $heading ) . '</h2>
			</div>';
		}

		$html .='<div class="knd-block-items">';

		while ( $query->have_posts() ) : $query->the_post();

			$campaign = new Leyka_Campaign( get_the_ID() );

			$is_finished = get_post_meta( get_the_ID(), 'is_finished', true);

			$html_image = '';
			if ( isset( $attr['showImage'] ) && $attr['showImage'] && has_post_thumbnail() ) {
				$html_image = '<a href="' . get_the_permalink() . '" class="campaign-thumb sub-block" style="background-image:url(' . get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) . ')" title="' . get_the_title() . '"></a>';
			}

			$html_tags = '';
			/**
			 * Add in future display tags
			if ( isset( $attr['showTags'] ) && $attr['showTags'] ) {
				$tags = get_the_term_list( $campaign->ID, 'post_tags' );
				if ( !empty( $tags ) && !is_wp_error( $tags ) ) {
					$html_tags = $tags;
				}
			}
			*/

			$html_title = '';
			if ( isset( $attr['showTitle'] ) && $attr['showTitle'] ) {
				$html_title = '<h3 class="campaign-title sub-block">' . get_the_title() . '</h3>';
			};

			$html_excerpt = '';

			if ( isset( $attr['showExcerpt'] ) && $attr['showExcerpt'] ) {
				if ( $is_finished ){
					$excerpt = esc_html__( 'Thank you for you support. This campaign is finished and help is going to be provided. Please follow the updates.', 'knd' );
				} else {
					$excerpt = knd_get_post_excerpt( get_the_ID(), 28, false );
				}
				$html_excerpt = '<div class="campaign-excerpt">' . esc_html( $excerpt ) . '</div>';
			}

			$percent = $campaign->target ? round(100.0 * $campaign->total_funded / $campaign->target, 1) : 0;
			$percent = $percent > 100.0 ? 100.0 : $percent;

			$html_progress = '';
			if ( isset( $attr['showProgressbar'] ) && $attr['showProgressbar'] ) {
				$html_progress = '<div class="progressbar-unfulfilled sub-block">
				<div class="progressbar-fulfilled" style="width: ' . $percent . '%;"></div>
			</div>';
			};

			$html_button = '';
			if ( isset( $attr['showButton'] ) && $attr['showButton'] ) {
				$html_button = '<a class="bottom-line-item leyka-button-wrapper" href="' . get_the_permalink() . '">' . $attr['buttonText'] . '</a>';
			};

			$html_founded = '';
			if ( isset( $attr['showCollectedAmount'] ) && $attr['showCollectedAmount'] ) {
				$html_founded = '<div class="funded"> ' . leyka_format_amount( $campaign->total_funded ) . ' ' . leyka_get_currency_label() . '</div>';
			};

			$html_collected = '';
			if ( isset( $attr['showTargetAmount'] ) && $attr['showTargetAmount'] ) {
				$html_collected = '<div class="target">' . sprintf(__('We need to raise: %s %s', 'leyka'), leyka_format_amount($campaign->target), leyka_get_currency_label() ) . '</div>';
			};

			$html_info = '';
			if ( $html_founded || $html_collected ) {
				$html_info = '<div class="bottom-line-item target-info">' . $html_founded . $html_collected . '</div>';
			}

			$html_content = '';
			if ( $html_info || $html_button ) {
				$html_content = '<div class="bottom-line sub-block">' . $html_info . $html_button . '</div>';
			}

			$block_item_attr = knd_block_attr(
				array(
					'class' => 'leyka-shortcode campaign-card wp-block-leyka-card',
				)
			);

			//var_dump( get_post_meta( get_the_ID(), 'campaign_type', true ) );

			$html .= '<div class="knd-block-item">
				<div ' . $block_item_attr . '>
					' . $html_image . '
					' . $html_tags . '
					' . $html_title . '
					' . $html_excerpt . '
					' . $html_progress . '
					' . $html_content . '
				</div>
			</div>';

		endwhile;

		$html .= '</div>';
		$html .= '</div>';

		$html .= '</div>';

	else:

	endif;

	wp_reset_postdata();

	return $html;
}
