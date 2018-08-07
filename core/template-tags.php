<?php /**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package bb
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

function is_expired_event() {
	if ( ! is_single() )
		return false;
	
	$event = new TST_Event( get_queried_object() );
	return $event->is_expired();
}

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
	} elseif ( 'event' == $cpost->post_type ) {
		
		$event = new TST_Event( $cpost );
		return $event->posted_on_card();
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
		$title = knd_get_theme_mod( 'knd_news_archive_title' );
		if($title === false) {
			$p = get_post( get_option( 'page_for_posts' ) );
			$title = get_the_title( $p );
		}
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

/** == Events functions == **/

/** always populate end-date **/
add_action( 'wp_insert_post', 'knd_save_post_event_actions', 50, 2 );

function knd_save_post_event_actions( $post_ID, $post ) {
	
	// populate end date
	if ( $post->post_type == 'event' ) {
		$event = new TST_Event( $post_ID );
		$event->populate_end_date();
	}
}

/* remove forms from expired events */
function knd_remove_unused_form( $the_content ) {
	$msg = "<div class='tst-notice'>Регистрация закрыта</div>";
	$the_content = preg_replace( '/\[formidable(.+)\]/', $msg, $the_content );
	
	return $the_content;
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

function knd_logo_markup() {
	
	/** @todo logo sizes may depends on test content */
	$mod = knd_get_theme_mod( 'knd_custom_logo_mod', 'image_only' );
	if ( $mod == 'nothing' ) {
		return;
	}
	?>

<a href="<?php echo esc_url(home_url('/'));?>" rel="home"
    class="site-logo">
<?php if($mod == 'image_only') {?>
    <div class="logo-image-only"><?php echo knd_get_logo_img();?></div>
<?php } elseif($mod == 'text_only') {?>
    <div class="logo-text-only">
        <h1 class="logo-name"><?php bloginfo('name');?></h1>
        <h2 class="logo-name"><?php bloginfo('description');?></h2>
    </div>
<?php } else {?>
    <div class="logo-complex">
        <div class="logo"><?php echo knd_get_logo_img();?></div>
        <div class="text">
            <h1 class="logo-name"><?php bloginfo('name');?></h1>
            <h2 class="logo-name"><?php bloginfo('description');?></h2>
        </div>
    </div>
<?php }?>
</a>
<?php
}

function knd_hero_image_markup() {

	$hero = knd_get_theme_mod( 'knd_hero_image' );
	$hero_img = '';
	
	if ( $hero ) {
		$hero_img = wp_get_attachment_image_src( (int) $hero, 'full' );
		if ( ! empty( $hero_img ) ) {
			$hero_img = $hero_img[0];
		}
	}
	
	if ( $hero_img ) {
		$knd_hero_image_support_title = knd_get_theme_mod( 'knd_hero_image_support_title' );
		$knd_hero_image_support_url = knd_get_theme_mod( 'knd_hero_image_support_url' );
		$knd_hero_image_support_text = knd_get_theme_mod( 'knd_hero_image_support_text' );
		$knd_hero_image_support_button_caption = knd_get_theme_mod( 'knd_hero_image_support_button_caption' );
?>
<div class="hero-section" style="background-image: url(<?php echo $hero_img;?>)">
	<div class="container">
		<div class="hero-content">

		<?php if($knd_hero_image_support_title) { ?>
			<div class="hero-title"><?php echo $knd_hero_image_support_title ?></div>
		<?php } ?>

		<?php if($knd_hero_image_support_text) { ?>
			<div class="hero-text"><?php echo $knd_hero_image_support_text ?></div>
		<?php } ?>

		<?php if( $knd_hero_image_support_url && $knd_hero_image_support_button_caption ) { ?>
		<a href="<?php echo esc_url($knd_hero_image_support_url); ?>" class="hero-button">
			<?php echo $knd_hero_image_support_button_caption; ?>
		</a>
		<?php } ?>
		</div>
	</div>
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

function knd_show_cta_block() {
	?>
<div class="knd-joinus-widget">

    <div class="container widget">

        <h2><?php echo knd_get_theme_mod('cta-title') ?></h2>

        <div class="flex-row knd-whoweare-headlike-text-wrapper">

            <p class="knd-whoweare-headlike-text flex-mf-12 flex-sm-10">
                <?php echo knd_get_theme_mod('cta-description') ?>
                </p>

        </div>

        <div class="knd-cta-wrapper-wide">
            <a class="cta" href="<?php echo knd_get_theme_mod('cta-url') ?>"><?php echo knd_get_theme_mod('cta-button-caption') ?></a>
        </div>

    </div>

</div>
<?php
}

function knd_show_posts_shortlist( $posts, $title, $links ) {
?>
<div class="knd-shortlist-widget"><div class="container">

	<div class="knd-widget-head">
        <h2 class="section-title"><?php echo $title; ?></h2>

        <div class="section-links">
	    	<?php foreach( $links as $link ){ ?>
	    		<a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>
	    	<?php } ?>
		</div>
	</div>

    <div class="flex-row start cards-row">
	<?php
		if ( ! empty( $posts ) ) {
			foreach ( $posts as $p ) {
				knd_project_card( $p );
			}
		}
	?>
	</div>

</div></div>
<?php
}