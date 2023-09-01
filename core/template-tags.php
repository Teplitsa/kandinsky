<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

/** HTML with meta information for the current post-date/time and author **/
function knd_posted_on( WP_Post $cpost, $args = array() ) {
	$meta = array();
	$sep = '';
	
	if ( 'post' == $cpost->post_type ) {

		$meta[] = "<time class='date'>" . get_the_date( 'd.m.Y', $cpost ) . "</time>";

		$cat = strip_tags( get_the_term_list( $cpost->ID, 'category', '<span class="category">', ', ', '</span>' ), '<span>' );

		if ( isset( $args['cat_link'] ) && $args['cat_link'] ) {
			$cat = '<span class="category"><span class="screen-reader-text">' . __( 'Categories', 'knd' ) . ' </span>' . get_the_category_list( ', ', '', $cpost->ID ) . '</span>';
		}

		if ( has_category( '', $cpost ) ) {
			$meta[] = $cat;
		}

		$meta = array_filter( $meta );

		$sep = '<span class="sep"></span>';

	} elseif ( 'project' == $cpost->post_type ) {

		$cat = get_the_term_list( $cpost->ID, 'project_cat', '<span class="category">', ', ', '</span>' );

		if ( has_term( '', 'project_cat', $cpost ) ) {
			$meta[] = $cat;
		}

	} elseif ( 'person' == $cpost->post_type ) {
		
		$cat = get_the_term_list( $cpost->ID, 'person_cat', '<span class="category">', ', ', '</span>' );
		if ( ! empty( $cat ) ) {
			$meta[] = $cat;
		}
	} elseif ( 'page' == $cpost->post_type && is_search() ) {
		
		$meta[] = "<span class='category'>" . esc_html__( 'Page', 'knd' ) . "</span>";
	}
	
	return implode( $sep, $meta );
}

function knd_svg_icon( $id, $echo = true ) {
	ob_start();
	?>
<svg class="svg-icon <?php echo $id;?>">
	<use xlink:href="#<?php echo $id;?>" />
</svg>
<?php
	$out = ob_get_contents();
	ob_end_clean();
	if ( $echo )
		echo $out;
	return $out;
}

/** == Titles == **/
function knd_section_title() {
	global $wp_query;

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$title = '';
	$css   = '';

	if ( is_category() || is_tag() || is_tax() ) {
		$title = single_term_title( '', false );
		$css = 'archive';
	} elseif ( is_home() ) {
		$page_count = '';
		if ( is_paged() ) {
			$page_count = '<span class="screen-reader-text"> ' . esc_html__( 'Page', 'knd' ) . ' ' . $paged . '</span>';
		}
		$title = get_theme_mod( 'knd_news_archive_title', esc_html__( 'Blog', 'knd' ) ) . $page_count;
		$css = 'archive';
	} elseif ( is_post_type_archive( 'leyka_donation' ) ) {
		$title = esc_html__( 'Donations history', 'knd' );
		$css = 'archive';
	} elseif ( is_post_type_archive( 'project' ) ) {
		$title = knd_get_theme_mod( 'knd_projects_archive_title' );
		if($title === false) {
			$title = esc_html__( 'Our projects', 'knd' );
		}
		$css = 'archive';
	} elseif ( is_post_type_archive( 'leyka_campaign' ) ) {
		if ( isset( $wp_query->query_vars['completed'] ) && $wp_query->query_vars['completed'] == 'true' ) {
			$title = knd_get_theme_mod( 'knd_completed_campaigns_archive_title' );
			if($title === false) {
				$title = esc_html__( 'They alredy got help', 'knd' );
			}
		} else {
			$title = knd_get_theme_mod( 'knd_active_campaigns_archive_title' );
			if($title === false) {
				$title = esc_html__( 'They need help', 'knd' );
			}
		}
		$css = 'archive';
	} elseif ( is_search() ) {
		$title = esc_html__( 'Search results', 'knd' );
		$css = 'archive search';
	} elseif ( is_404() ) {
		$title = esc_html__( '404: Page not found', 'knd' );
		$css = 'archive e404';
	}
	
	echo '<h1 class="section-title ' . esc_attr( $css ) . '">' . wp_kses_post( $title ) . '</h1>';
}

if ( ! function_exists( 'knd_archive_description' ) ) {
	/**
	 * Archive Description
	 */
	function knd_archive_description() {
		if ( get_the_archive_description() ) {
			?>
			<div class="knd-archive-description">
				<?php echo wp_kses_post( get_the_archive_description() ); ?>
			</div>
			<?php
		}
	}
}

/**
 * The Posts Pagination
 */
function knd_posts_pagination( $args = array() ){

	$args = wp_parse_args(
		$args,
		array(
			'screen_reader_text' => esc_html__( 'Posts navigation', 'knd' ),
			'before_page_number' => '<span class="screen-reader-text"> ' . esc_html__( 'Page', 'knd' ) . ' </span>',
			'prev_text'          => esc_html__( 'Previous', 'knd' ) . '<span class="screen-reader-text"> ' . esc_html__( 'Page', 'knd' ) . '</span>',
			'next_text'          => esc_html__( 'Next', 'knd' ) . '<span class="screen-reader-text"> ' . esc_html__( 'Page', 'knd' ) . '</span>',
			'class'              => 'knd-pagination',
		)
	);

	the_posts_pagination( $args );
}

/** More section **/
function knd_more_section( $posts, $title = '', $type = 'news', $css = '' ) {
	if ( empty( $posts ) )
		return;
	
	if ( $type == 'projects' ) {
		$all_link = "<a href='" . home_url( 'activity' ) . "'>" . esc_html__( 'More projects', 'knd' ) . "&nbsp;&rarr;</a>";
		$title = ( empty( $title ) ) ? esc_html__( 'Our projects', 'knd' ) : $title;
	} else {
		$all_link = "<a href='" . home_url( 'news' ) . "'>" . esc_html__( 'More news', 'knd' ) . "&nbsp;&rarr;</a>";
		$title = ( empty( $title ) ) ? esc_html__( 'Latest news', 'knd' ) : $title;
	}
	
	$css .= ' related-card-holder';
	?>
<section class="<?php echo esc_attr($css);?>">

	<h3 class="related-title"><?php echo $title; ?></h3>

	<div class="related-cards-loop">
	<?php
	foreach ( $posts as $p ) {
		knd_related_post_link( $p );
	}
	?>
</div>

</section>
<?php
}

/**
 * Get Home Url
 */
function knd_get_home_url() {
	return apply_filters( 'knd_get_home_url', home_url( '/' ) );
}

/**
 * Get Default site icon url
 */
function knd_get_default_site_icon_url() {
	return get_template_directory_uri() . '/assets/images/favicon.png';
}

/**
 * Get site icon id
 */
function knd_get_site_icon_id() {
	$icon_id = get_option( 'site_icon' );
	return $icon_id;
}

/**
 * Get site icon url
 */
function knd_get_site_icon_url() {
	$logo_id = knd_get_site_icon_id();
	if ( $logo_id ) {
		$icon_url = wp_get_attachment_image_url( $logo_id, 'full', false );
	} else {
		$icon_url = knd_get_default_site_icon_url();
	}
	return $icon_url;
}

/**
 * Get site icon image
 */
function knd_get_site_icon_image() {
	return knd_get_image_markup( knd_get_site_icon_url(),
		array(
			'class' => 'site-logo-img site-favicon-img',
			'alt' => get_bloginfo( 'name' ),
		)
	);
}

/**
 * Site icon image
 */
function knd_site_icon_image() {
	echo knd_get_site_icon_image();
}

/**
 * Get Default logo url
 */
function knd_get_default_logo_url() {
	return get_template_directory_uri() . '/assets/images/logo.svg';
}

/**
 * Get logo id
 */
function knd_get_logo_id() {
	$logo_id = false;
	if ( get_theme_mod( 'custom_logo' ) ) {
		$logo_id = get_theme_mod( 'custom_logo' );
	/** Deprecated, remove in version 3.0, use only 'custom_logo' */
	} elseif ( get_theme_mod( 'header_logo_image' ) ) {
		$logo_id = get_theme_mod( 'header_logo_image' );
	} elseif ( get_theme_mod( 'knd_custom_logo' ) ) {
		$logo_id = get_theme_mod( 'knd_custom_logo' );
	}
	return $logo_id;
}

/**
 * Get footer logo id
 */
function knd_get_footer_logo_id() {
	$logo_id = knd_get_logo_id();
	if ( class_exists( 'Kirki' ) ) {
		$logo_id = get_theme_mod( 'footer_logo_image', $logo_id );
	}
	return $logo_id;
}

/**
 * Get logo url
 */
function knd_get_logo_url() {
	$logo_id = knd_get_logo_id();
	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full', false );
	} else {
		$logo_url = knd_get_default_logo_url();
	}
	return $logo_url;
}

/**
 * Get logo image
 */
function knd_get_logo_image() {
	$logo_id = knd_get_logo_id();

	$attr = array( 'class' => 'site-logo-img', 'alt' => get_bloginfo( 'name' ) );

	$logo_image = '';
	if ( $logo_id ) {
		$logo_image = wp_get_attachment_image( $logo_id, 'full', false, $attr );
	}
	if ( ! $logo_image ) {
		$logo_image = knd_get_image_markup( knd_get_logo_url(), $attr );
	}

	return $logo_image;
}

/**
 * Get image markup.
 */
function knd_get_image_markup( $url, $attr = array() ) {

	$html = '';
	$html_attr = '';
	if ( $url ) {
		$html_attr .= ' src="' . esc_url( $url ) . '"';

		foreach ( $attr as $name => $value ) {
			$html_attr .= " $name=" . '"' . $value . '"';
		}

		$html = '<img ' . $html_attr . '>';
	}

	return $html;
}

/**
 * Header Logo
 */
if ( ! function_exists( 'knd_header_logo' ) ) {
	function knd_header_logo() {

		$logo_title = get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) );
		$logo_desc  = get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) );

		?>
		<a href="<?php echo esc_url( knd_get_home_url() ); ?>" rel="home" class="knd-header-logo">
			<div class="knd-header-logo__inner">
				<?php
				$logo_id = knd_get_logo_id();

				$logo_url = wp_get_attachment_image_url( $logo_id, 'full', false );

				if ( $logo_url ) {
					$aria_hidden = '';
					if ( $logo_title || $logo_desc ) {
						$aria_hidden = ' aria-hidden="true"';
					}
					?>
					<div class="logo"<?php echo $aria_hidden; ?>>
						<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
					</div>
				<?php } ?>

				<?php if ( $logo_title || $logo_desc ) { ?>
					<div class="text">
						<?php if ( $logo_title ) { ?>
							<span class="logo-name"><?php echo wp_kses( nl2br( $logo_title ), array( 'br' => array() ) ); ?></span>
						<?php } ?>
						<?php if ( $logo_desc ) { ?>
							<span class="logo-desc"><?php echo wp_kses( nl2br( $logo_desc ), array( 'br' => array() ) ); ?></span>
						<?php } ?>
					</div>
				<?php } ?>

			</div>
		</a>
		<?php
	}
}

/**
 * Header Mobile Toggle
 */
if ( ! function_exists( 'knd_header_mobile_logo' ) ) {
	function knd_header_mobile_logo() {
		?>
		<a href="<?php echo esc_url( knd_get_home_url() ); ?>" rel="home" class="knd-header-mobile-logo">
		<?php
		$logo_id  = knd_get_logo_id();
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full', false );

		if ( $logo_url ) {
			echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) );
		} elseif ( get_theme_mod( 'header_logo_title' ) ) {
			echo wp_kses_post( get_theme_mod( 'header_logo_title' ) );
		} else {
			bloginfo();
		}
		?>
		</a>
		<?php
	}
}

/**
 * Off-Canvas Logo
 */
if ( ! function_exists( 'knd_offcanvas_logo' ) ) {
	function knd_offcanvas_logo() {

		$logo_title = '<span>' . nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) . '</span>';
		$logo_id    = knd_get_logo_id();
		$logo_img   = wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) );

		$logo = $logo_title;
		if ( $logo_id ) {
			$logo = $logo_img;
		}

		if ( ! $logo ) {
			return;
		}

		?>

		<a href="<?php echo esc_url( knd_get_home_url() ); ?>" rel="home" class="snt-cell" aria-hidden="true" tabindex="-1">
			<span class="logo-name"><?php echo wp_kses_post( $logo ); ?></span>
		</a>

		<?php
	}
}

/**
 * Footer Logo
 */
if ( ! function_exists( 'knd_footer_logo' ) ) {
	function knd_footer_logo() {

		if ( get_theme_mod( 'footer_logo', true ) ) {

			$logo_title = get_theme_mod( 'footer_logo_title', get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) );
			$logo_desc  = get_theme_mod( 'footer_logo_text', get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) );
			$logo_id    = knd_get_footer_logo_id();
			$logo_url   = wp_get_attachment_image_url( $logo_id, 'full', false );
			?>

			<a href="<?php echo esc_url( knd_get_home_url() ); ?>" class="knd-footer-logo">
				<span class="knd-footer-logo__inner">
					<?php if ( $logo_url ) {

						$aria_hidden = '';
						if ( $logo_title || $logo_desc ) {
							$aria_hidden = ' aria-hidden="true"';
						}
						?>
						<span class="knd-footer-logo__image"<?php echo $aria_hidden; ?>>
							<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
						</span>
					<?php } ?>

					<?php if ( $logo_title || $logo_desc ) { ?>
						<span class="knd-footer-logo__text">
							<?php if ( $logo_title ) { ?>
								<span class="logo-name"><?php echo wp_kses( nl2br( $logo_title ), array( 'br' => array() ) ); ?></span>
							<?php } ?>
							<?php if ( $logo_desc ) { ?>
								<span class="logo-desc"><?php echo wp_kses( nl2br( $logo_desc ), array( 'br' => array() ) ); ?></span>
							<?php } ?>
						</span>
					<?php } ?>

				</span>
			</a>

			<?php
		}
	}
}

/**
 * Site Search Toggle
 */
function knd_search_toggle() {
	if ( get_theme_mod( 'header_search', true ) ) {
	?>
		<button class="knd-search-toggle" type="button" aria-label="<?php esc_attr_e( 'Open search', 'knd' ); ?>">
			<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M2 8.5C2 5.191 4.691 2.5 8 2.5C11.309 2.5 14 5.191 14 8.5C14 11.809 11.309 14.5 8 14.5C4.691 14.5 2 11.809 2 8.5ZM17.707 16.793L14.312 13.397C15.365 12.043 16 10.346 16 8.5C16 4.089 12.411 0.5 8 0.5C3.589 0.5 0 4.089 0 8.5C0 12.911 3.589 16.5 8 16.5C9.846 16.5 11.543 15.865 12.897 14.812L16.293 18.207C16.488 18.402 16.744 18.5 17 18.5C17.256 18.5 17.512 18.402 17.707 18.207C18.098 17.816 18.098 17.184 17.707 16.793Z" fill="currentColor"/>
			</svg>
		</button>
	<?php
	}
}

/**
 * Site Search
 */
function knd_header_search(){
	if ( get_theme_mod( 'header_search', true ) ) {
	?>
	<div class="knd-search" tabindex="-1">
		<div class="knd-search__inner">
			<div class="knd-container">
				<?php get_search_form();?>
			</div>
		</div>
		<button class="knd-search-close" aria-label="<?php esc_attr_e( 'Close search', 'knd' ); ?>"></button>
	</div>
	<?php
	}
}

/**
 * Off-Canvas Toggle
 */
function knd_offcanvas_toggle( $is_active = true ){
	if ( ! $is_active ) {
		return;
	}

	?>
	<button class="knd-offcanvas-toggle" aria-label="<?php esc_attr_e( 'Open Off-Canvas', 'knd' ); ?>">
		<span></span>
		<span></span>
		<span></span>
	</button>
	<?php
}

/**
 * Off-Canvas Close
 */
function knd_offcanvas_close(){
	?>
	<button class="trigger-button close knd-offcanvas-close" aria-label="<?php esc_attr_e( 'Close Off-Canvas', 'knd' ); ?>">
		<?php knd_svg_icon( 'icon-close' ); ?>
	</button>
	<?php
}

/**
 * Header Button
 */
function knd_header_button(){
	if ( get_theme_mod( 'header_button', true ) ) {
		$link = get_theme_mod( 'header_button_link' );
		$text = get_theme_mod( 'header_button_text', esc_html__( 'Button text', 'knd' ) );
		if ( $text ) {
			?>
			<a href="<?php echo esc_url( $link ); ?>" role="button" class="knd-button knd-button-sm">
				<?php echo esc_html( $text ); ?>
			</a>
			<?php
		}
	}
}

/**
 * Off-Canvas Button
 */
function knd_offcanvas_button(){
	if ( get_theme_mod( 'offcanvas_button', true ) ) {
		?>
		<div class="knd-offcanvas-section knd-offcanvas-button">
			<?php
			$link = get_theme_mod( 'offcanvas_button_link' );
			$text = get_theme_mod( 'offcanvas_button_text', esc_html__( 'Button text', 'knd' ) );
			if ( $text ) {
				?>
				<a href="<?php echo esc_url( $link ); ?>" role="button" class="knd-button knd-button-sm">
					<?php echo esc_html( $text ); ?>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

/**
 * Header Additional Button Markup
 */
function knd_header_additional_button_markup(){
	$link = get_theme_mod( 'header_additional_button_link' );
	$text = get_theme_mod( 'header_additional_button_text' );
	if ( $text ) {
		?>
		<a href="<?php echo esc_url( $link ); ?>" role="button" class="knd-button knd-button-outline knd-button-sm">
			<?php echo esc_html( $text ); ?>
		</a>
		<?php
	}
}

/**
 * Header Additional Button
 */
function knd_header_additional_button(){
	if ( get_theme_mod( 'header_additional_button', true ) ) {
		knd_header_additional_button_markup();
	}
}

/**
 * Off-Canvas Additional Button
 */
function knd_offcanvas_additional_button(){
	if ( get_theme_mod( 'offcanvas_additional_button', true ) ) {
		?>
		<div class="knd-offcanvas-section knd-offcanvas-button">
			<?php knd_header_additional_button_markup(); ?>
		</div>
		<?php
	}
}

/**
 * Header Mobile Button
 */
function knd_header_mobile_button(){
	if ( get_theme_mod( 'header_button', true ) ) {
		$link = get_theme_mod( 'header_button_link' );
		$text = get_theme_mod( 'header_button_text', esc_html__( 'Button text', 'knd' ) );
		if ( $text ) {
			?>
			<a href="<?php echo esc_url( $link ); ?>" role="button" class="knd-button knd-button-xs">
				<?php echo esc_html( $text ); ?>
			</a>
			<?php
		}
	}
}

/**
 * Header Nav Menu
 */
function knd_header_nav_menu() {
	if ( get_theme_mod( 'header_menu', true ) ) {
		if ( has_nav_menu( 'primary' ) ) {
			$menu_class = 'menu knd-nav-menu'; // knd-nav-menu-flex
			wp_nav_menu(
				array(
					'theme_location'       => 'primary',
					'container'            => 'nav',
					'container_class'      => 'knd-header-nav',
					'container_aria_label' => esc_attr__( 'Primary menu', 'knd' ),
					'depth'                => 7,
					'menu_class'           => $menu_class,
				)
			);
		}
	}
}

if ( ! function_exists( 'knd_breadcrumbs' ) ) {
	/**
	 * Breadcrumbs
	 */
	function knd_breadcrumbs() {
		if ( function_exists( 'yoast_breadcrumb' ) ) {

			yoast_breadcrumb( '<div class="knd-breadcrumbs">', '</div>' );

		} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {

			$rank_math_args = array(
				'wrap_before' => '<div class="knd-breadcrumbs">',
				'wrap_after'  => '</div>',
			);
			rank_math_the_breadcrumbs( $rank_math_args );

		}
	}
}
add_action( 'knd_entry_header', 'knd_breadcrumbs' );
add_action( 'knd_events_header', 'knd_breadcrumbs' );

if ( ! function_exists( 'knd_entry_tags' ) ) {
	/**
	 * Entry Tags
	 */
	function knd_entry_tags() {

		if ( ! is_singular( array( 'post', 'project' ) ) ) {
			return;
		}

		$post_type = get_post_type( get_the_ID() );

		if ( ! get_theme_mod( $post_type . '_tags', true ) ) {
			return;
		}

		echo get_the_term_list(
			get_the_ID(),
			$post_type . '_tag',
			'<div class="single-post-terms tags-line">',
			', ',
			'</div>'
		);
	}
}


if ( ! function_exists( 'knd_entry_shares' ) ) {
	/**
	 * Entry Shares
	 */
	function knd_entry_shares() {
		if ( get_theme_mod( 'social_share_location' ) === 'bottom' ) {
			?>
			<div class="knd-entry-shares">
				<?php echo knd_social_share_no_js(); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'knd_entry_related' ) ) {
	/**
	 * Entry Related
	 */
	function knd_entry_related() {

		$post_type = get_post_type( get_the_ID() );

		if ( ! get_theme_mod( $post_type . '_related', true ) ) {
			return;
		}

		if ( 'post' === $post_type ) {
			$cat    = get_the_terms( get_the_ID(), 'category' );
			$pquery = new WP_Query(
				array(
					'post_type'      => 'post',
					'posts_per_page' => 5,
					'post__not_in'   => array( get_the_ID() ),
					'tax_query'      => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'id',
							'terms'    => ( isset( $cat[0] ) ) ? $cat[0]->term_id : array(),
						),
					),
				)
			);

			if ( ! $pquery->have_posts() ) {
				$pquery = new WP_Query(array(
					'post_type'      => 'post',
					'posts_per_page' => 5,
					'post__not_in'   => array( get_the_ID() ),
				));
			}

			knd_more_section( $pquery->posts, get_theme_mod( 'post_related_title', __( 'Related posts', 'knd') ), 'news', 'addon' );

		} elseif ( 'project' === $post_type ) {
			$pquery = new WP_Query(
				array(
					'post_type'      => 'project',
					'posts_per_page' => 5,
					'post__not_in'   => array( get_the_ID() ),
					'orderby'        => 'rand',
				)
			);

			if ( $pquery->have_posts() ) {
				knd_more_section( $pquery->posts, get_theme_mod( 'project_related_title', __( 'Related projects', 'knd') ), 'projects', 'addon' );
			}
		}

	}
}

if ( ! function_exists( 'knd_bottom_blocks' ) ) {
	/**
	 * Bottom Blocks
	 */
	function knd_bottom_blocks() {
		if ( is_singular( 'post' ) && get_theme_mod( 'post_bottom_block') ) {
			$block_name = get_theme_mod( 'post_bottom_block');
			$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
			if ( $block ) {
				$content = $block->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				?>
				<div class="knd-signle-after-content">
					<div class="container entry-content the-content">
						<?php echo $content; ?>
					</div>
				</div>
				<?php
			}
		}

		if ( is_singular( 'project' ) && get_theme_mod( 'project_bottom_block') ) {
			$block_name = get_theme_mod( 'project_bottom_block');
			$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
			if ( $block ) {
				$content = $block->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				?>
				<div class="knd-signle-after-content">
					<div class="container entry-content the-content">
						<?php echo $content; ?>
					</div>
				</div>
				<?php
			}
		}

		if ( ( is_home() || is_category() || is_tag() ) && get_theme_mod( 'archive_bottom_block') ) {
			$block_name = get_theme_mod( 'archive_bottom_block' );
			$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
			if ( $block ) {
				$content = $block->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				?>
				<div class="knd-archive-sidebar">
					<div class="container entry-content the-content">
						<?php echo $content; ?>
					</div>
				</div>
				<?php
			}
		}

		if ( ( is_post_type_archive( 'project' ) || is_tax( 'project_tag' ) ) && get_theme_mod( 'projects_bottom_block') ) {
			$block_name = get_theme_mod( 'projects_bottom_block' );
			$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
			if ( $block ) {
				$content = $block->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				?>
				<div class="knd-archive-sidebar">
					<div class="container entry-content the-content">
						<?php echo $content; ?>
					</div>
				</div>
				<?php
			}
		}

	}
}

if ( ! function_exists( 'knd_screen_reader_alert' ) ) {
	/**
	 * Screen Reader Alert
	 */
	function knd_screen_reader_alert() { ?>
		<span class="screen-reader-text knd-screen-reader-alert" role="alert"></span>
	<?php }
}

if ( ! function_exists( 'knd_button_totop' ) ) {
	/**
	 * Scroll To Top Button
	 */
	function knd_button_totop() {
		if ( get_theme_mod( 'button_totop', true ) ) { ?>
		<a href="#" aria-hidden="true" tabindex="-1" class="knd-to-top">
			<?php knd_svg_icon( 'icon-up' ); ?>
		</a>
		<?php }
	}
}

if ( ! function_exists( 'knd_block_post_title' ) ) {
	/**
	 * Block post title
	 */
	function knd_block_post_title( $options = array() ) {
		if ( $options['title'] === false ) {
			return;
		}

		$title_class = 'knd-block-post-title';

		if ( isset( $options['titleFontWeight'] ) && $options['titleFontWeight'] != 'bold' ) {
			$title_class .= ' knd-font-weight-' . $options['titleFontWeight'];
		}

		$title = the_title( '<h3 class="' . esc_attr( $title_class ) . '"><a href="' . get_the_permalink() . '">', '</a></h3>', false );

		return $title;
	}
}

if ( ! function_exists( 'knd_block_post_author' ) ) {
	/**
	 * Block post author
	 */
	function knd_block_post_author( $options = array() ) {
		if ( $options['author'] === false ) {
			return;
		}

		$avatar = '';
		if ( $options['avatar'] !== false ) {
			$avatar = get_avatar( get_the_author_meta( 'ID' ), '28' );
		}

		$author = '<div class="knd-block-post-author">
			<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">
				' . $avatar . '
				' . get_the_author() . '
			</a>
		</div>';

		return $author;
	}
}

if ( ! function_exists( 'knd_block_post_date' ) ) {
	/**
	 * Block post date
	 */
	function knd_block_post_date( $options = array() ) {
		if ( $options['date'] === false ) {
			return;
		}

		$date_format = 'd.m.Y';
		if ( isset( $options['dateFormat'] ) ) {
			$date_format = $options['dateFormat'];
		}

		$date = get_the_time( $date_format );
		if ( $date_format === 'relative' ) {
			$date = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . esc_html__( 'назад', 'knd' ); // ago
		}

		$post_date = '<time datetime="' . get_post_time( DATE_W3C ) . '" class="knd-block-post-date">' . esc_html( $date ) . '</time>';

		return $post_date;
	}
}

if ( ! function_exists( 'knd_block_post_excerpt' ) ) {
	/**
	 * Block post date
	 */
	function knd_block_post_excerpt( $options = array() ) {
		if ( $options['excerpt'] === false ) {
			return;
		}

		$length  = $options['excerptLength'];
		$excerpt = wp_strip_all_tags( get_the_excerpt() );

		if ( ! has_excerpt() ) {
			$excerpt = wp_trim_words( $excerpt, $length );
		}

		$excerpt = '<div class="knd-block-post-excerpt">' . esc_html( $excerpt ) . '</div>';

		return $excerpt;
	}
}

if ( ! function_exists( 'knd_block_post_thumbnail' ) ) {
	/**
	 * Block post thumbnail
	 */
	function knd_block_post_thumbnail( $options = array() ) {
		if ( $options['thumbnail'] === false && $options['thumbnail_link'] === true ) {
			return;
		}

		$image_size = 'post-thumbnail';
		if ( isset( $options['imageSize'] ) && $options['imageSize'] ) {
			$image_size = $options['imageSize'];
		}

		$thumbnail = '';
		if ( $options['thumbnail'] !== false ) {
			$thumbnail = get_the_post_thumbnail( null, $image_size, array( 'alt' => wp_trim_words( get_the_title(), 5 ), 'aria-hidden' => 'true' ) );
		}

		$link_start = '<span>';
		$link_end   = '</span>';
		if ( isset( $options['thumbnail_link'] ) && $options['thumbnail_link'] === true ) {
			if ( ! has_post_thumbnail() ) {
				return;
			}

			$link_start = '<a href="' . get_the_permalink() . '">';
			$link_end   = '</a>';
		}

		$orientation_class = '';
		if ( $options['layout'] !== 'type-3' ) {
			$orientation_class = ' knd-ratio-' . $options['imageOrientation'];
		}

		$thumbnail = '<div class="knd-block-featured-image' . $orientation_class . '">
			' . $link_start . '
				' . $thumbnail . '
			' . $link_end . '
		</div>';

		return $thumbnail;
	}
}

if ( ! function_exists( 'knd_block_post_category' ) ) {
	/**
	 * Block post category
	 */
	function knd_block_post_category( $options = array() ) {
		if ( $options['category'] === false ) {
			return;
		}

		$category = '<div class="knd-block-post-category">' . get_the_category_list( '&nbsp;&nbsp; ' ) . '</div>';

		return $category;
	}
}

if ( ! function_exists( 'knd_block_post_meta' ) ) {
	/**
	 * Block post meta
	 */
	function knd_block_post_meta( $options = array() ) {
		if ( $options['author'] === false && $options['date'] === false ) {
			return;
		}

		$post_meta = '<div class="knd-block-post-meta">
			' . knd_block_post_author( $options ) . '
			' . knd_block_post_date( $options ) . '
		</div>';


		return $post_meta;
	}
}


