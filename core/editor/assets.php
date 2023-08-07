<?php
/**
 * Gutenberg Assets
 *
 * @package Kandinsky
 */

/**
 * Enqueue scripts for editor
 */
function knd_enqueue_block_editor_assets() {

	$dependencies = array(
		'wp-blocks',
		'wp-plugins',
		'wp-element',
		'wp-components',
		'wp-editor',
		'wp-block-editor',
		'wp-edit-post',
		'wp-data',
		'wp-core-data',
		'wp-compose',
		'wp-hooks',
		'wp-server-side-render',
		'wp-i18n',
		'lodash',
	);

	wp_enqueue_script( 'knd-gutenberg', get_template_directory_uri() . '/assets/js/gutenberg.js', $dependencies, filemtime( get_template_directory() . '/assets/js/gutenberg.js' )  );
	wp_enqueue_script( 'knd-blocks', get_template_directory_uri() . '/assets/js/blocks.js', array( 'knd-gutenberg' ), filemtime( get_template_directory() . '/assets/js/blocks.js' ) );

	wp_enqueue_style( 'knd-gutenberg', get_template_directory_uri() . '/assets/css/gutenberg.css', filemtime( get_template_directory() .'/assets/css/gutenberg.css' ) );

	$kndBlock = array();

	if ( defined( 'LEYKA_VERSION' ) ) {
		wp_enqueue_style(
			'leyka-revo-plugin-styles',
			LEYKA_PLUGIN_BASE_URL . 'assets/css/public.css',
			array(),
			LEYKA_VERSION
		);

		$campaign_args = array(
			'post_type'      => 'leyka_campaign',
			'posts_per_page' => -1,
		);
		$campaigns = get_posts( $campaign_args );

		if ( $campaigns ) {
			$kndBlock['campaigns'][] = array(
				'value' => '',
				'label' => esc_html__( 'Select campaign', 'knd' ),
			);
			foreach( $campaigns as $campaign ) {
				$kndBlock['campaigns'][] = array(
					'label' => $campaign->post_title,
					'value' => $campaign->ID,
				);
			}
		}

	}

	$kndBlock['getAdminUrl'] = array(
		'projects' => admin_url( 'edit.php?post_type=project' ),
		'news'     => admin_url( 'edit.php?post_type=post' ),
		'partners' => admin_url( 'edit.php?post_type=org' ),
		'people'   => admin_url( 'edit.php?post_type=person' ),
		'event'   => admin_url( 'edit.php?post_type=event' ),
	);

	$kndBlock['getImageUrl'] = array(
		'heroBackground'  => esc_url( get_theme_file_uri( 'assets/images/hero.jpg' ) ),
		'heroFeatured'    => esc_url( get_theme_file_uri( 'assets/images/cta-image.png' ) ),
		'ctaFeatured'     => esc_url( get_theme_file_uri( 'assets/images/cta-image.png' ) ),
		'campaignPreview' => esc_url( get_theme_file_uri( 'assets/images/campaign.jpg' ) ),
	);

	$kndBlock['postTypes'] = get_post_types( array( 'public' => true ) );

	$kndBlock['getEvents'] = get_posts( array( 'post_type' => 'event', 'numberposts' => -1 ) );

	// Get partner count
	$kndBlock['partnerCount'] = 0;
	$count_partners = wp_count_posts('org');
	if ( $count_partners ) {
		$kndBlock['partnerCount'] = $count_partners->publish;
	}

	// Get Leyka version
	$kndBlock['leykaVersion'] = '';
	if ( defined( 'LEYKA_VERSION' ) ) {
		$kndBlock['leykaVersion'] = LEYKA_VERSION;
	}

	foreach( knd_get_image_sizes() as $name => $size ) {
		$kndBlock['imageSizes'][ $name ] = $name . ' [' . $size['width'] . 'px, ' . $size['height'] . 'px]';
	}

	wp_localize_script(
		'knd-gutenberg',
		'kndBlock',
		$kndBlock
	);

	wp_set_script_translations( 'knd-gutenberg', 'knd', get_template_directory() . '/lang' );

}
add_action( 'enqueue_block_editor_assets', 'knd_enqueue_block_editor_assets' );
add_action( 'enqueue_block_editor_assets', 'knd_inline_style' );


/**
 * Enqueue scripts for admin and front
 */
function knd_enqueue_block_assets() {

	$css_dependencies = array(
		'wp-block-library',
		'flickity',
	);

	$js_dependencies = array(
		'jquery',
		'flickity',
		'knd-fancybox',
	);

	if ( is_admin() ) {
		$css_dependencies[] = 'wp-edit-blocks';
	} else {
		if ( function_exists( 'wp_enqueue_classic_theme_styles' ) ) {
			$css_dependencies[] = 'classic-theme-styles';
		}
	}

	wp_enqueue_script( 'flickity', get_template_directory_uri() . '/assets/js/flickity.pkgd.min.js', array( 'jquery' ), '2.2.2' );

	// Scripts.
	wp_enqueue_script( 'knd', get_template_directory_uri() . '/assets/js/scripts.js', $js_dependencies, knd_get_theme_version(), true );

	// Register Flickity style.
	wp_register_style( 'flickity', get_template_directory_uri() . '/assets/css/flickity.min.css', array(), '2.2.2' );

	wp_enqueue_style( 'knd-blocks', get_template_directory_uri() . '/assets/css/blocks.css', $css_dependencies, filemtime( get_template_directory() . '/assets/css/blocks.css' ) );
	
	if ( defined( 'LEYKA_VERSION' ) ) {
		wp_enqueue_style( 'leyka-revo-plugin-styles', LEYKA_PLUGIN_BASE_URL . 'assets/css/public.css', array(), LEYKA_VERSION );
	}

	knd_inline_style();

}
add_action( 'enqueue_block_assets', 'knd_enqueue_block_assets' );


/**
 * Render blocks CSS.
 */
function knd_blocks_inline_style( $style ) {
	if ( ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks' ) || ! has_blocks( get_the_ID() ) ) {
		return;
	}

	global $post;

	if ( ! is_object( $post ) ) {
		return;
	}

	$blocks = parse_blocks( $post->post_content );

	if ( ! is_array( $blocks ) || empty( $blocks ) ) {
		return;
	}

	$style .= knd_parse_blocks_css( $blocks );

	return $style; // XSS.
}
//add_filter( 'knd_inline_style', 'knd_blocks_inline_style' );


/**
 * Parse blocks and prepare styles
 *
 * @param array $blocks Blocks array with attributes.
 * @return string
 */
function knd_parse_blocks_css( $blocks ) {

	$styles = '';

	// Loop blocks.
	foreach ( $blocks as $block ) {
		if ( isset( $block['attrs'] ) ) {
			if ( isset( $block['attrs']['blockId'] ) && $block['attrs']['blockId'] ) {
				$css_selector = '.knd-block-' . $block['attrs']['blockId'];
				
				if ( isset( $block['attrs']['backgroundColor'] ) && $block['attrs']['backgroundColor'] ) {
					$background = '--knd-block-hero-background:' . $block['attrs']['backgroundColor'] . ';';
				}
				$styles .= $css_selector . '{' . $background . '}';
			}
		}
	}
	
	return $styles;
}

/**
 * Change default file path for loading script translations.
 *
 * @param string|false $file   Path to the translation file to load. False if there isn't one.
 * @param string       $handle Name of the script to register a translation domain to.
 * @param string       $domain The text domain.
 */
function knd_load_script_translation_file( $file, $handle, $domain ){

	$locale = determine_locale();

	if ( 'knd' === $domain && ( 'knd-gutenberg' === $handle || 'knd-admin' === $handle ) ) {
		$new_file = get_parent_theme_file_path( '/lang/' . $locale . '.json' );
		// Add translate file if exists
		if ( is_readable( $new_file ) ) {
			$file = $new_file;
		}
	}

	return $file;
}
add_filter( 'load_script_translation_file', 'knd_load_script_translation_file', 10, 3 );
