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
function knd_posted_on( WP_Post $cpost ) {
	$meta = array();
	$sep = '';
	
	if ( 'post' == $cpost->post_type ) {
		
		$meta[] = "<span class='date'>" . get_the_date( 'd.m.Y', $cpost ) . "</span>";
		
		$cat = get_the_term_list( $cpost->ID, 'category', '<span class="category">', ', ', '</span>' );
		$meta[] = $cat;
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
		$title = get_theme_mod( 'knd_news_archive_title', esc_html__( 'Blog', 'knd' ) );
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

/** Related project on single page **/
function knd_related_project( WP_Post $cpost ) {
	$pl = get_permalink( $cpost );
	$ex = apply_filters( 'knd_the_title', knd_get_post_excerpt( $cpost, 25, true ) );
	?>
<div class="related-widget widget">
	<h3 class="widget-title"><?php esc_html_e('Related project', 'knd');?></h3>
	<a href="<?php echo $pl;?>" class="entry-link">
		<div class="rw-preview">
			<?php echo knd_post_thumbnail($cpost->ID, 'post-thumbnail');?>
		</div>
		<div class="rw-content">
			<h4 class="entry-title"><?php echo get_the_title($cpost);?></h4>
			<div class="entry-summary"><?php echo $ex;?></div>
		</div>
	</a>
	<div class="help-cta">
		<?php echo knd_get_help_now_cta();?>
	</div>
</div>
<?php
}

function knd_get_help_now_cta( $cpost = null, $label = '' ) {
	$label = ( empty( $label ) ) ? esc_html__( 'Help now', 'knd' ) : $label;
	$cta = '';
	
	if ( ! $cpost ) {
		
		$help_id = knd_get_theme_mod( 'help_campaign_id' );
		if ( ! $help_id )
			return '';
		
		$cta = "<a href='" . get_permalink( $help_id ) . "' class='help-button'>{$label}</a>";
	} else {
		$url = get_post_meta( $cpost->ID, 'cta_link', true );
		$txt = get_post_meta( $cpost->ID, 'cta_text', true );
		
		if ( empty( $url ) )
			return '';
		
		if ( empty( $txt ) )
			$txt = $label;
		
		$css = ( false !== strpos( $url, '#' ) ) ? 'help-button local-scroll' : 'help-button';
		$cta = "<a href='{$url}' class='{$css}'>{$txt}</a>";
	}
	
	return $cta;
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

function knd_get_site_icon_img_url() {
	$logo_id = get_option( 'site_icon' );
	if ( $logo_id ) {
		return wp_get_attachment_image_url( $logo_id, 'full', false );
	} else {
		
		$site_scenario = knd_get_theme_mod( 'knd_site_scenario' );
		return $site_scenario ? get_template_directory_uri() . "/vendor/envato_setup/images/$site_scenario/favicon.png" : '';
	}
}

function knd_get_logo_img_id() {
	$logo_id = knd_get_theme_mod( 'knd_custom_logo' );
	
	return $logo_id ? (int) $logo_id : false;
}

function knd_get_logo_img_url() {
	$logo_id = knd_get_logo_img_id();
	if ( $logo_id ) {
		return wp_get_attachment_image_url( $logo_id, 'full', false );
	} else {
		
		$site_scenario = knd_get_theme_mod( 'knd_site_scenario' );
		return $site_scenario ? get_template_directory_uri() . "/vendor/envato_setup/images/$site_scenario/logo.svg" : '';
	}
}

function knd_get_logo_img() {
	$logo_id = knd_get_logo_img_id();
	return $logo_id ? wp_get_attachment_image( 
		$logo_id, 
		'full', 
		false, 
		array( 'alt' => get_bloginfo( 'name' ), 'class' => 'site-logo-img' ) ) : '<img class="site-logo-img" src="' .
		 get_template_directory_uri() . '/vendor/envato_setup/images/' . knd_get_theme_mod( 'knd_site_scenario' ) .
		 '/logo.svg" width="315" height="66" alt="' . get_bloginfo( 'name' ) . '">';
}

function knd_get_content_image_markup( $attachment_id ) {
	return wp_get_attachment_image( $attachment_id, 'medium', false, array( 'alt' => "" ) );
}

/**
 * Logo Markup
 * 
 * @info Deprecated in version 2
 */
function knd_logo_markup() {

	/** @todo logo sizes may depends on test content */
	$mod = knd_get_theme_mod( 'knd_custom_logo_mod', 'image_only' );
	if ( 'nothing' == $mod ) {
		return;
	}
	?>

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
		<?php if ( 'image_only' == $mod ) { ?>
			<div class="logo-image-only"><?php echo knd_get_logo_img(); ?></div>
		<?php } elseif ( 'text_only' == $mod ) { ?>
		<div class="logo-text-only">
			<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ); ?></span>
			<span class="logo-desc"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ); ?></span>
		</div>
	<?php } else { ?>
		<div class="logo-complex">
			<div class="logo"><?php echo knd_get_logo_img(); ?></div>
			<div class="text">
				<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ); ?></span>
				<span class="logo-desc"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ); ?></span>
			</div>
		</div>
	<?php } ?>
	</a>

	<?php
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
 * Header Logo
 */
function knd_header_logo() {
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="knd-header-logo">
		<div class="knd-header-logo__inner">
			<?php
			$logo_id = get_theme_mod( 'header_logo_image', get_theme_mod( 'knd_custom_logo' ) );

			$logo_url = wp_get_attachment_image_url( $logo_id, 'full', false );

			if ( $logo_url ) {
				?>

				<div class="logo">
					<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
				</div>
			<?php } ?>
			<div class="text">
				<?php if ( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) { ?>
					<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ); ?></span>
				<?php } ?>
				<?php if ( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) { ?>
					<span class="logo-desc"><?php echo wp_kses_post( nl2br( get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ); ?></span>
				<?php } ?>
			</div>
		</div>
	</a>
	<?php
}

/**
 * Bottom Bar Logo
 */
function knd_footer_logo() {

	if ( get_theme_mod( 'footer_logo', true ) ) {

		$logo_id = get_theme_mod( 'footer_logo_image', get_theme_mod( 'header_logo_image', get_theme_mod( 'knd_custom_logo' ) ) );

		$logo_url = wp_get_attachment_image_url( $logo_id, 'full', false );
		?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="knd-footer-logo">
			<span class="knd-footer-logo__inner">
				<?php if ( $logo_url ) { ?>
					<span class="knd-footer-logo__image">
						<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) ); ?>
					</span>
				<?php } ?>

				<span class="knd-footer-logo__text">
					<span class="logo-name"><?php echo wp_kses_post( nl2br( get_theme_mod( 'footer_logo_title', get_theme_mod( 'header_logo_title', get_bloginfo( 'name' ) ) ) ) ); ?></span>
					<span class="logo-desc"><?php echo wp_kses_post( nl2br( get_theme_mod( 'footer_logo_text', get_theme_mod( 'header_logo_text', get_bloginfo( 'description' ) ) ) ) ); ?></span>
				</span>
			</span>
		</a>

		<?php
	}
}

/**
 * Site Search Toggle
 */
function knd_search_toggle() {
	if ( get_theme_mod( 'header_search', true ) ) {
	?>
		<span class="knd-search-toggle">
			<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M2 8.5C2 5.191 4.691 2.5 8 2.5C11.309 2.5 14 5.191 14 8.5C14 11.809 11.309 14.5 8 14.5C4.691 14.5 2 11.809 2 8.5ZM17.707 16.793L14.312 13.397C15.365 12.043 16 10.346 16 8.5C16 4.089 12.411 0.5 8 0.5C3.589 0.5 0 4.089 0 8.5C0 12.911 3.589 16.5 8 16.5C9.846 16.5 11.543 15.865 12.897 14.812L16.293 18.207C16.488 18.402 16.744 18.5 17 18.5C17.256 18.5 17.512 18.402 17.707 18.207C18.098 17.816 18.098 17.184 17.707 16.793Z" fill="currentColor"/>
			</svg>
		</span>
	<?php
	}
}

/**
 * Site Search
 */
function knd_header_search(){
	if ( get_theme_mod( 'header_search', true ) ) {
	?>
	<div class="knd-search">
		<div class="knd-search__inner">
			<div class="knd-container">
				<form role="search" method="get" class="knd-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input class="knd-search__input" type="search" value="<?php the_search_query(); ?>" name="s" placeholder="<?php esc_attr_e( 'Search ...', 'knd' ); ?>">
					<button class="knd-search__submit" type="submit">
						<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M2 8.5C2 5.191 4.691 2.5 8 2.5C11.309 2.5 14 5.191 14 8.5C14 11.809 11.309 14.5 8 14.5C4.691 14.5 2 11.809 2 8.5ZM17.707 16.793L14.312 13.397C15.365 12.043 16 10.346 16 8.5C16 4.089 12.411 0.5 8 0.5C3.589 0.5 0 4.089 0 8.5C0 12.911 3.589 16.5 8 16.5C9.846 16.5 11.543 15.865 12.897 14.812L16.293 18.207C16.488 18.402 16.744 18.5 17 18.5C17.256 18.5 17.512 18.402 17.707 18.207C18.098 17.816 18.098 17.184 17.707 16.793Z" fill="currentColor"/>
						</svg>
					</button>
				</form>   
			</div>
		</div>
		<span class="knd-search-close"></span>
	</div>
	<?php
	}
}

/**
 * Header Off-Canvas Toggle
 */
function knd_offcanvas_toggle(){
	if ( get_theme_mod( 'header_offcanvas', true ) ) {
	?>
	 <span class="knd-offcanvas-toggle" id="trigger_menu">
		<span></span>
		<span></span>
		<span></span>
	</span>
	<?php
	}
}

/**
 * Header Button Markup
 */
function knd_header_button_markup() {
	$link = get_theme_mod( 'header_button_link' );
	$text = get_theme_mod( 'header_button_text', esc_html__( 'Help now', 'knd' ) );
	if ( $text ) {
		?>
		<a href="<?php echo esc_url( $link ); ?>" class="knd-button knd-button-sm">
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
				<a href="<?php echo esc_url( $link ); ?>" class="knd-button knd-button-sm">
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
		<a href="<?php echo esc_url( $link ); ?>" class="knd-button knd-button-outline knd-button-sm">
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
 * Header Off-Canvas Toggle
 */
function knd_offcanvas_mobile_toggle() {
	?>
	<span class="knd-offcanvas-toggle">
		<span></span>
		<span></span>
		<span></span>
	</span>
	<?php
}

/**
 * Header Off-Canvas Toggle
 */
function knd_header_mobile_logo() {
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="knd-header-mobile-logo">
	<?php
	$logo_id  = get_theme_mod( 'header_logo_image', get_theme_mod( 'knd_custom_logo' ) );
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

/**
 * Header Mobile Button
 */
function knd_header_mobile_button(){
	$link = get_theme_mod( 'header_button_link' );
	$text = get_theme_mod( 'header_button_text', esc_html__( 'Help now', 'knd' ) );
	if ( $text ) {
		?>
		<a href="<?php echo esc_url( $link ); ?>" class="knd-button knd-button-xs">
			<?php echo esc_html( $text ); ?>
		</a>
		<?php
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
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_class' => 'knd-header-nav',
					'depth'           => 7,
					'menu_class'      => 'menu knd-nav-menu',
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
			?>
			<div class="knd-signle-after-content">
				<div class="container entry-content the-content">
					<?php
					$block_name = get_theme_mod( 'post_bottom_block');
					$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
					$content    = $block->post_content;
					$content    = apply_filters('the_content', $content);
					$content    = str_replace(']]>', ']]&gt;', $content);
					echo $content;
					?>
				</div>

			</div>
			<?php
		}

		if ( is_singular( 'project' ) && get_theme_mod( 'project_bottom_block') ) {
			?>
			<div class="knd-signle-after-content">
				<div class="container entry-content the-content">
					<?php
					$block_name = get_theme_mod( 'project_bottom_block');
					$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
					$content    = $block->post_content;
					$content    = apply_filters('the_content', $content);
					$content    = str_replace(']]>', ']]&gt;', $content);
					echo $content;
					?>
				</div>

			</div>
			<?php
		}

		if ( ( is_home() || is_category() || is_tag() ) && get_theme_mod( 'archive_bottom_block') ) {
			$block_name = get_theme_mod( 'archive_bottom_block' );
			$block      = get_page_by_path( $block_name, OBJECT, 'wp_block' );
			if ( $block ) {
				$content    = $block->post_content;
				$content    = apply_filters('the_content', $content);
				$content    = str_replace(']]>', ']]&gt;', $content);
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
			$content    = $block->post_content;
			$content    = apply_filters('the_content', $content);
			$content    = str_replace(']]>', ']]&gt;', $content);
			if ( $block ) {
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
