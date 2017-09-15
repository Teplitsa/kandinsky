<?php if( !defined('WPINC') ) die;
/**
 * Shortcodes
 **/

add_shortcode('knd_key_phrase', 'knd_key_phrase_shortcode');
function knd_key_phrase_shortcode($atts, $content = null){

    $atts = shortcode_atts(array('subtitle' => ''), $atts);

    if(empty($content)) {
        return '';
    }

    $out = "<div class='knd-key-phrase'>";
    $out .= "<h5>".apply_filters('knd_the_title', $atts['subtitle'])."</h5>";
    $out .= "<h3>".apply_filters('knd_the_content', $content)."</h3>";
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


//knd_cta_section
add_shortcode('knd_cta_section', 'knd_cta_section_shortcode');
function knd_cta_section_shortcode($atts, $content = null){

    $atts = shortcode_atts(
        array(
            'subtitle' => '', 
            'link' =>'', 
            'button' => ''
        ),
        $atts
    );

    if(empty($content) || empty($atts['subtitle'])) {
        return '';
    }

    if(isset($atts['link'])) {
        $atts['link'] = knd_build_imported_url($atts['link']);
    }
    $target = (false === strpos($atts['link'], home_url())) ? 'target="_blank"' : '';

    ob_start();
?>
    <div class="knd-intext-cta"><div class="knd-section-extend">
        <h5><?php echo apply_filters('knd_the_title', $atts['subtitle']);?></h5>
        <h3><?php echo apply_filters('knd_the_title', $content); ?></h3>
        <div class="cta-button"><a href="<?php echo esc_url($atts['link']); ?>" <?php echo $target;?>><?php echo apply_filters('knd_the_title', $atts['button']);?></a></div>
    </div></div>
<?php
    $out = ob_get_contents();
    ob_end_clean();
    
    return $out;
}

add_shortcode('knd_links', 'knd_links_shortcode');
function knd_links_shortcode($atts, $content = null){

    $atts = shortcode_atts(
        array(
            'align' => 'left'
        ),
        $atts
    );

    if(empty($content)) {
        return '';
    }

    $out = '<div class="knd-links '.esc_attr($atts['align']).'">';
    $out .= strip_tags(apply_filters('knd_the_content', $content), "<a>");
    $out .= "</div>";

    return $out;
}

/** Youtube video caption (UI-) **/
add_shortcode('knd_video_caption', 'knd_video_caption_shortcode');
function knd_video_caption_shortcode($atts, $content = null){
    return '<div class="video-caption">'.$content.'</div>';
}

/* fallback for Leyka shortcode */
if( !defined('LEYKA_VERSION') ) {
    add_shortcode('leyka_inline_campaign', 'knd_leyka_inline_campaign_shortcode');
    function knd_leyka_inline_campaign_shortcode($atts, $content = null){
        // don't display anything when we don't have donations

        return '';
    }
}

/** Wrapper to import leyka shortcodes correctly **/
add_shortcode('knd_leyka_inline_campaign', 'knd_leyka_inline_campaign_shortcode');
function knd_leyka_inline_campaign_shortcode($atts, $content = null) {

    $atts = shortcode_atts(
        array(
            'slug' => ''
        ),
        $atts
    );

    if(empty($atts['slug']))
        return '';

    if(!defined('LEYKA_VERSION')) 
        return '';

    $camp = get_page_by_path($atts['slug'], OBJECT, 'leyka_campaign');
    if(!$camp)
        return '';

    return do_shortcode('[leyka_inline_campaign id="'.$camp->ID.'" template="revo"]');
}


add_filter('leyka_revo_template_displayed', 'knd_test_for_revo_template');
function knd_test_for_revo_template($revo_displayed){

    if(!is_singular())
        return $revo_displayed;

    if(is_singular('leyka_campaign'))
        return $revo_displayed;

    if(get_post() && has_shortcode(get_post()->post_content, 'knd_leyka_inline_campaign')){
        $revo_displayed = true;
    }

    return $revo_displayed;
}


/*** IN Dev ***/

/** A button (UI+) **/
//add_shortcode('knd_button', 'knd_button_shortcode');
function knd_button_shortcode($atts){

	$atts = shortcode_atts(array('url'  => '', 'label'  => '', 'in_new_window' => false,), $atts);

	if(empty($atts['url'])) {
		return '';
    }

	ob_start();?>

<section class="knd-button-section page-section">
    <span class="knd-btn">
        <a href="<?php echo esc_url($atts['url']);?>" class="knd-button" <?php echo !!$atts['in_new_window'] ? 'target="_blank"' : '';?>>
            <?php echo apply_filters('knd_the_title', $atts['label']);?>
        </a>
    </span>
</section>

<?php $out = ob_get_contents();
	ob_end_clean();

	return $out;

}

/** A quote (UI+)
 * @todo Quote markup need CSS styles
 **/
//add_shortcode('knd_quote', 'knd_quote_screen');
function knd_quote_screen($atts, $content = null) {

	$atts = shortcode_atts(array('name' => '', 'class' => '',), $atts);

	if(empty($content)) {
		return '';
    }

	$atts['name'] = apply_filters('knd_the_title', $atts['name']);

	ob_start();?>

<div class="knd-quote page-section <?php echo empty($atts['class']) ? '' : esc_attr($atts['class']);?>">
	<div class="knd-quote-content"><?php echo apply_filters('knd_the_content', $content);?></div>
	<?php if($atts['name']) {?>
		<div class="knd-quote-cite"><?php echo $atts['name'];?></div>
	<?php }?>
</div>
<?php $out = ob_get_contents();
	ob_end_clean();

	return $out;

}

/** Social links (UI-) **/
//add_shortcode('knd_social_links', 'knd_social_links');
function knd_social_links($atts = array(), $echo = true) {

    $atts['class'] = empty($atts['class']) ? '' : esc_attr($atts['class']);

    ob_start();

    $social_links = array();
    foreach(knd_get_social_media_supported() as $id => $data) {

        $link = esc_url(get_theme_mod('knd_social_links_'.$id));
        if($link) {
            $social_links[$id] = array('label' => $data['label'], 'link' => $link);
        }

    }

    if($social_links) {?>

    <ul class="knd-social-links <?php echo $atts['class'];?>">
    <?php foreach($social_links as $id => $data) {?>

        <li class="<?php echo esc_attr($id);?>">
            <a href="<?php echo esc_url($data['link']);?>">
                <svg class="svg-icon"><use xlink:href="#<?php echo 'icon-'.$id;?>"></svg>
                <span><?php echo esc_html($data['label']);?></span>
            </a>
        </li>

    <?php }?>
    </ul>

    <?php }

    $out = ob_get_contents();
    ob_end_clean();

    if( !!$echo ) {
        echo $out;
    } else {
        return $out;
    }

}


/** A list of people (UI+) */
add_shortcode('knd_people_list', 'knd_people_list_shortcode');
function knd_people_list_shortcode($atts = array()) {

    $atts = shortcode_atts(array(
        'title'     => '',
        'category'  => '',
        'ids'       => '',
        'num'       => -1,
        'class'     => '',
    ), $atts);

    if(!function_exists('knd_people_gallery'))
        return '';

    ob_start();?>

    <div class="knd-people-shortcode <?php echo $atts['class'] ? esc_attr($atts['class']) : '';?>">

        <?php if($atts['title']) {?>
        <div class="knd-people-title"><?php echo apply_filters('knd_the_title', $atts['title']);?></div>
        <?php }?>

        <div class="knd-section-extend">
        <?php knd_people_gallery($atts['category'], $atts['ids'], $atts['num']); ?>
        </div>

    </div>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;
}


/** A list of organizations (UI+) */
add_shortcode('knd_orgs_list', 'knd_orgs_list_shortcode');
function knd_orgs_list_shortcode($atts = array(), $echo = true) {

    $atts = shortcode_atts(array(
        'title' => '',
        'org-categories' => '',
        'orgs' => '',
        'class' => '',
    ), $atts);

    ob_start();?>

    <section class="knd-orgs-list page-section <?php echo $atts['class'] ? esc_attr($atts['class']) : '';?>">

    <?php if($atts['title']) {?>
        <div class="pb-section-title align-center"><h2><?php echo esc_attr($atts['title']);?></h2></div>
    <?php }

    knd_orgs_gallery($atts['org-categories'], $atts['orgs']);?>

    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    if( !!$echo ) {
        echo $out;
    } else {
        return $out;
    }

}

/** A 3-column markup (UI+) **/
//add_shortcode('knd_columns', 'knd_columns_shortcode');
function knd_columns_shortcode($atts){

    $atts = shortcode_atts(array(
        'title' => '',
        '1-title' => '', '1-text' => '',
        '2-title' => '', '2-text' => '',
        '3-title' => '', '3-text' => '',
    ), $atts);

    if(empty($atts)) {
        return '';
    }

    ob_start();?>

    <section class="knd-columns page-section">

        <?php if($atts['title']) {?>
        <div class="pb-section-title align-center"><h2><?php echo esc_attr($atts['title']);?></h2></div>
        <?php }?>

        <div class="markup-columns">
        <?php for($i=1; $i<=3; $i++) {

        if($atts["$i-title"] || $atts["$i-text"]) { ?>

            <div class="markup-column">

            <?php if($atts["$i-title"]) {?>
                <h3><?php echo esc_attr($atts["$i-title"]); ?></h3>
            <?php }

            if($atts["$i-text"]) {?>
                <div class="markup-column text"><?php echo do_shortcode(urldecode($atts["$i-text"]));?></div>
            <?php }?>

            </div>

        <?php }

        }?>
        </div>

    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    return $out;

}

/** Content manager recommendation (UI-) **/
add_shortcode('knd_r', 'knd_recommendation_shortcode');
function knd_recommendation_shortcode($atts, $content = null){
    return current_user_can('edit_posts') ?
        '<div class="knd-recommend"><span class="recommend">'.__('Recommendations:', 'knd').'</span> '.apply_filters('knd_the_content', $content).'</div>' : '';
}