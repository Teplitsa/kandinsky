<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Kandinsky
 */

if ( ! defined( 'WPINC' ) )
	die();

function knd_has_authors() {
	if ( defined( 'TST_HAS_AUTHORS' ) && TST_HAS_AUTHORS && function_exists( 'get_term_meta' ) )
		return true;

	return false;
}

/* Custom conditions */
function is_about() {

	if ( is_page_branch( 2 ) )
		return true;

	if ( is_post_type_archive( 'org' ) )
		return true;

	if ( is_post_type_archive( 'org' ) )
		return true;

	return false;
}

function is_page_branch( $pageID ) {
	global $post;
	
	if ( empty( $pageID ) )
		return false;
	
	if ( ! is_page() || is_front_page() )
		return false;
	
	if ( is_page( $pageID ) )
		return true;
	
	if ( $post->post_parent == 0 )
		return false;
	
	$parents = get_post_ancestors( $post );
	
	if ( is_string( $pageID ) ) {
		$test_id = get_page_by_path( $pageID )->ID;
	} else {
		$test_id = (int) $pageID;
	}

	if ( in_array( $test_id, $parents ) )
		return true;

	return false;
}

function is_tax_branch( $slug, $tax ) {
	
	$test = get_term_by( 'slug', $slug, $tax );
	if ( empty( $test ) )
		return false;
	
	if ( is_tax( $tax ) ) {
		$qobj = get_queried_object();
		if ( $qobj->term_id == $test->term_id || $qobj->parent == $test->term_id )
			return true;
	}
	
	return false;
}

function is_posts() {
	if ( is_home() || is_category() )
		return true;
	
	if ( is_tax( 'auctor' ) )
		return true;
	
	if ( is_singular( 'post' ) )
		return true;
	
	return false;
}

function is_projects() {
	if ( is_page( 'programms' ) )
		return true;
	
	if ( is_singular( 'programm' ) )
		return true;
	
	return false;
}

/*function is_expired_event() {
	if ( ! is_single() )
		return false;
	
	$event = new TST_Event( get_queried_object() );
	return $event->is_expired();
}*/

/** Menu filter sceleton **/
// add_filter('wp_nav_menu_objects', 'knd_custom_menu_items', 2, 2);
function knd_custom_menu_items( $items, $args ) {
	if ( empty( $items ) )
		return;
	
	if ( $args->theme_location == 'primary' ) {
		
		foreach ( $items as $index => $menu_item ) {
			if ( in_array( 'current-menu-item', $menu_item->classes ) )
				$items[$index]->classes[] = 'active';
		}
	}
	
	return $items;
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
	//} elseif ( 'event' == $cpost->post_type ) {
		
		//$event = new TST_Event( $cpost );
		//return $event->posted_on_card();
	} elseif ( 'project' == $cpost->post_type ) {
		
		$p = get_page_by_path( 'activity' );
		if ( $p ) {
			$meta[] = "<span class='category'><a href='" . get_permalink( $p ) . "'>" . get_the_title( $p ) .
				 "</a></span>";
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

/** Logo **/
function knd_site_logo( $size = 'regular' ) {
	switch ( $size ) {
		case 'regular' :
			$file = 'pic-logo';
			break;
		case 'small' :
			$file = 'pic-logo-small';
			break;
		default :
			$file = 'icon-logo';
			break;
	}
	
	$file = esc_attr( $file );
	?>
<svg class="logo <?php echo $file;?>">
	<use xlink:href="#<?php echo $file;?>" />
</svg>
<?php
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

/** Separator **/
function knd_get_sep( $mark = '//' ) {
	return "<span class='sep'>" . $mark . "</span>";
}

/** == Titles == **/
/** CPT archive title **/
function knd_get_post_type_archive_title( $post_type ) {
	$pt_obj = get_post_type_object( $post_type );
	$name = $pt_obj->labels->menu_name;
	
	return $name;
}

function knd_section_title() {
	global $wp_query;

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$title = '';
	$css = '';
	
	if ( is_category() ) {
		$p = get_post( get_option( 'page_for_posts' ) );
		$title = get_the_title( $p );
		$title .= knd_get_sep( '&mdash;' );
		$title .= single_term_title( '', false );
		$css = 'archive';
	} elseif ( is_tag() || is_tax() ) {
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
	
	echo "<h1 class='section-title {$css}'>{$title}</h1>";
}

/** == NAVs == **/
/** Deprecated, remove in version 3.0 */
function knd_paging_nav( WP_Query $query = null ) {
	if ( ! $query ) {
		
		global $wp_query;
		$query = $wp_query;
	}
	
	if ( $query->max_num_pages < 2 ) { // Don't print empty markup if there's only one page
		return;
	}
	
	$p = knd_paginate_links( $query, false );
	if ( $p ) {
		?>
<nav class="paging-navigation" role="navigation">
	<div class="container"><?php echo $p; ?></div>
</nav>
<?php
	}
}

/** Deprecated, remove in version 3.0 */
function knd_paginate_links( WP_Query $query = null, $echo = true ) {
	global $wp_query;
	
	if ( ! $query ) {
		$query = $wp_query;
	}
	
	$current = ( $query->query_vars['paged'] > 1 ) ? $query->query_vars['paged'] : 1;
	
	$parts = parse_url( get_pagenum_link( 1 ) );
	
	$pagination = array( 
		'base' => trailingslashit( esc_url( $parts['host'] . $parts['path'] ) ) . '%_%', 
		'format' => 'page/%#%/', 
		'total' => $query->max_num_pages, 
		'current' => $current, 
		'prev_next' => true, 
		'prev_text' => '&lt;', 
		'next_text' => '&gt;', 
		'end_size' => 4, 
		'mid_size' => 4, 
		'show_all' => false, 
		'type' => 'plain',  // list
		'add_args' => array() );
	
	if ( ! empty( $query->query_vars['s'] ) ) {
		$pagination['add_args'] = array( 's' => str_replace( ' ', '+', get_search_query() ) );
	}
	
	foreach ( array( 's' ) as $param ) { // Params to remove
		
		if ( $param == 's' ) {
			continue;
		}
		
		if ( isset( $_GET[$param] ) && ! empty( $_GET[$param] ) ) {
			$pagination['add_args'] = array_merge( 
				$pagination['add_args'], 
				array( $param => esc_attr( trim( $_GET[$param] ) ) ) );
		}
	}
	
	if ( $echo ) {
		
		echo paginate_links( $pagination );
		return '';
	} else {
		return paginate_links( $pagination );
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

/** == Orgs functions == **/
function knd_orgs_gallery( $category_ids = '', $org_ids = '' ) {
	$args = array( 'post_type' => 'org', 'posts_per_page' => - 1 );
	
	if ( $category_ids ) {
		$args['tax_query'] = array( array( 'taxonomy' => 'org_cat', 'field' => 'id', 'terms' => $category_ids ) );
	}
	if ( $org_ids ) {
		$args['post__in'] = explode( ',', $org_ids );
	}
	
	$query = new WP_Query( $args );
	if ( ! $query->have_posts() ) {
		return '';
	}
	?>

<div class="orgs-gallery  frame">
	<?php foreach($query->posts as $org) {?>
		<div class="bit mf-6 sm-4 md-3 "><?php knd_org_card($org);?></div>
	<?php }?>
	</div>
<?php
}

/** Single template helpers **/
function knd_related_reports( TST_Event $event, $css = '' ) {
	$related = $event->get_related_post_id();
	if ( ! empty( $related ) ) {
?>
<div class="expired-notice <?php echo esc_attr($css);?>">
	<h6>Читать отчет</h6>
	<?php foreach ( $related as $r ): ?>
	<p>
		<a href="<?php echo get_permalink($r);?>"><?php echo get_the_title($r);?></a>
	</p>
	<?php endforeach; ?>
	</div>
<?php
	}
}

function knd_show_post_terms( $post_id ) {
	?>
<div class="tags-line">
	<?php $terms_list = wp_get_object_terms( $post_id, 'post_tag'); ?>
		<?php foreach($terms_list as $term):?>
		<a href="<?php get_term_link( $term->term_id, 'post_tag' ) ?>">#<?php echo $term->name?></a>
	<?php endforeach;?>
	</div>

<?php
}


/**
 * Get Home Url
 */
function knd_get_home_url() {
	return apply_filters( 'knd_get_home_url', home_url( '/' ) );
}

/**
 * Get content image markup
 */
function knd_get_content_image_markup( $attachment_id ) {
	return wp_get_attachment_image( $attachment_id, 'medium', false, array( 'alt' => "" ) );
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
 * Header Button Markup
 */
function knd_header_button_markup() {
	$link = get_theme_mod( 'header_button_link' );
	$text = get_theme_mod( 'header_button_text', esc_html__( 'Help now', 'knd' ) );
	if ( $text ) {
		?>
		<a href="<?php echo esc_url( $link ); ?>" role="button" class="knd-button knd-button-sm">
			<?php echo esc_html( $text ); ?>
		</a>
		<?php
	}
}

/**
 * Header Button
 */
function knd_header_button(){
	if ( get_theme_mod( 'header_button', true ) ) {
		knd_header_button_markup();
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
			$text = get_theme_mod( 'offcanvas_button_text', esc_html__( 'Help now', 'knd' ) );
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
		$text = get_theme_mod( 'header_button_text', esc_html__( 'Help now', 'knd' ) );
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
			wp_nav_menu(
				array(
					'theme_location'       => 'primary',
					'container'            => 'nav',
					'container_class'      => 'knd-header-nav',
					'container_aria_label' => esc_attr__( 'Primary menu', 'knd' ),
					'depth'                => 7,
					'menu_class'           => 'menu knd-nav-menu',
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
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb( '<div class="knd-breadcrumbs">', '</div>' );
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

		if ( ! get_theme_mod( $post_type . '_tags', false ) ) {
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
