<?php /**
 * Shortcodes
 **/

if ( ! defined( 'WPINC' ) )
	die();

add_shortcode( 'knd_key_phrase', 'knd_key_phrase_shortcode' );

function knd_key_phrase_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array( 'subtitle' => '' ), $atts );
	
	if ( empty( $content ) ) {
		return '';
	}
	
	$out = "<div class='knd-key-phrase'>";
	$out .= "<h5>" . apply_filters( 'knd_the_title', $atts['subtitle'] ) . "</h5>";
	$out .= "<h3>" . apply_filters( 'knd_the_content', $content ) . "</h3>";
	$out .= "</div>";
	
	return $out;
}

add_shortcode('knd_image_section', 'knd_image_section_shortcode');
function knd_image_section_shortcode($atts, $content = null){

    $atts = shortcode_atts(
        array(
            'title' => '', 
            'text_place' => 'ontop', 
            'img' => 0
        ),
        $atts
    );

    if($atts['img'] == 0) {
        return '';
    }

    $src = wp_get_attachment_url($atts['img']); //make it responsive
    $css = '';

    switch ($atts['text_place']) {
        case 'ontop':
            $css = 'mark-over';
            break;
        
        case 'under':
            $css = 'mark-under';
            break;

        case 'color':
            $css = 'mark-over colored';
            break;
    }


    $out = '';

    $id = uniqid('knd-img-');
    ob_start();
?>
    <div class="knd-image-section <?php echo $css;?>">
        <style>#<?php echo esc_attr($id);?>{ background-image: url(<?php echo $src;?>);}</style>
        <div class="knd-section-extend"><div id="<?php echo esc_attr($id);?>" class="knd-img-bg"></div></div>
        <div class="kng-img-mark">
            <h4 class="mark-title"><?php echo apply_filters('knd_the_title', $atts['title']);?></h4>
            <div class="mark-text"><?php echo apply_filters('knd_the_content', $content);?></div>
        </div>
    </div>
<?php
    $out = ob_get_contents();
    ob_end_clean();
    
    return $out;
}
	
	// knd_cta_section
add_shortcode( 'knd_cta_section', 'knd_cta_section_shortcode' );

function knd_cta_section_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( 
		array( 'subtitle' => '', 'link' => '', 'is_external' => '', 'button' => '' ),
		$atts );
	
	if ( empty( $content ) || empty( $atts['subtitle'] ) ) {
		return '';
	}

	if ( isset( $atts['link'] ) ) {
		$atts['link'] = !!$atts['is_external'] ? trim($atts['link']) : knd_build_imported_url( $atts['link'] );
	}
	$target = ( false === strpos( $atts['link'], home_url() ) ) ? 'target="_blank"' : '';

	ob_start();
	?>
<div class="knd-intext-cta">
    <div class="knd-section-extend">
        <h5><?php echo apply_filters('knd_the_title', $atts['subtitle']);?></h5>
        <h3><?php echo apply_filters('knd_the_title', $content); ?></h3>
        <div class="cta-button">
            <a href="<?php echo esc_url($atts['link']); ?>"
                <?php echo $target;?>><?php echo apply_filters('knd_the_title', $atts['button']);?></a>
        </div>
    </div>
</div>
<?php
	$out = ob_get_contents();
	ob_end_clean();

	return $out;
}

add_shortcode( 'knd_links', 'knd_links_shortcode' );

function knd_links_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array( 'align' => 'left' ), $atts );
	
	if ( empty( $content ) ) {
		return '';
	}
	
	$out = '<div class="knd-links ' . esc_attr( $atts['align'] ) . '">';
	$out .= strip_tags( apply_filters( 'knd_the_content', $content ), "<a>" );
	$out .= "</div>";
	
	return $out;
}

/** Youtube video caption (UI-) **/
add_shortcode( 'knd_video_caption', 'knd_video_caption_shortcode' );

function knd_video_caption_shortcode( $atts, $content = null ) {
	return '<div class="video-caption">' . $content . '</div>';
}

/** Support for leyka shortcode for import **/
add_shortcode( 'knd_leyka_inline_campaign', 'knd_leyka_inline_campaign_shortcode' );
if ( defined( 'LEYKA_VERSION' ) ) {

	/** Wrapper to import leyka shortcodes correctly **/
	function knd_leyka_inline_campaign_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array( 'slug' => '' ), $atts );
		
		if ( empty( $atts['slug'] ) )
			return '';
		
		if ( ! defined( 'LEYKA_VERSION' ) )
			return '';
		
		$camp = get_page_by_path( $atts['slug'], OBJECT, 'leyka_campaign' );
		if ( ! $camp )
			return '';
		
		return do_shortcode( '[leyka_inline_campaign id="' . $camp->ID . '" template="star"]' );
	}
} else {
 // fallback for Leyka shortcode
	function knd_leyka_inline_campaign_shortcode( $atts, $content = null ) {
		// don't display anything when we don't have donations
		return '';
	}
}

add_filter( 'leyka_revo_template_displayed', 'knd_test_for_revo_template' );

function knd_test_for_revo_template( $revo_displayed ) {
	if ( ! is_singular() )
		return $revo_displayed;
	
	if ( is_singular( 'leyka_campaign' ) )
		return $revo_displayed;
	
	if ( get_post() && has_shortcode( get_post()->post_content, 'knd_leyka_inline_campaign' ) ) {
		$revo_displayed = true;
	}
	
	return $revo_displayed;
}

/** A list of people (UI+) */
add_shortcode( 'knd_people_list', 'knd_people_list_shortcode' );

function knd_people_list_shortcode( $atts = array() ) {
	$atts = shortcode_atts( 
		array( 'title' => '', 'category' => '', 'ids' => '', 'num' => - 1, 'class' => '' ), 
		$atts );
	
	if ( ! function_exists( 'knd_people_gallery' ) )
		return '';
	
	ob_start();
	?>

<div
    class="knd-people-shortcode <?php echo $atts['class'] ? esc_attr($atts['class']) : '';?>">

        <?php if($atts['title']) {?>
        <div class="knd-people-title"><?php echo apply_filters('knd_the_title', $atts['title']);?></div>
        <?php }?>

        <div class="knd-section-extend-on-large">
        <?php knd_people_gallery($atts['category'], $atts['ids'], $atts['num']); ?>
        </div>

</div>

<?php
	
$out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** A list of organizations (UI+) */
add_shortcode( 'knd_orgs_list', 'knd_orgs_list_shortcode' );

function knd_orgs_list_shortcode( $atts = array(), $echo = true ) {
	$atts = shortcode_atts( 
		array( 'title' => '', 'org-categories' => '', 'orgs' => '', 'class' => '' ), 
		$atts );
	
	ob_start();
	?>

<section
    class="knd-orgs-list <?php echo $atts['class'] ? esc_attr($atts['class']) : '';?>">

    <?php if($atts['title']) {?>
        <div class="pb-section-title align-center">
        <h2><?php echo esc_attr($atts['title']);?></h2>
    </div>
    <?php
	
}
	
	knd_orgs_gallery( $atts['org-categories'], $atts['orgs'] );
	?>

    </section>

<?php
	
$out = ob_get_contents();
	ob_end_clean();
	
	if ( ! ! $echo ) {
		echo $out;
	} else {
		return $out;
	}
}

/** Content manager recommendation (UI-) **/
add_shortcode( 'knd_r', 'knd_recommendation_shortcode' );

function knd_recommendation_shortcode( $atts, $content = null ) {
	return current_user_can( 'edit_posts' ) ? '<div class="knd-recommend"><span class="recommend">' .
        esc_html__( 'Recommendations:', 'knd' ) . '</span> ' . apply_filters( 'knd_the_content', $content ) . '</div>' : '';
}

