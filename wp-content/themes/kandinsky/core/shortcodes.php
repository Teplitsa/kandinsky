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
    $out .= "<h3>".apply_filters('knd_the_title', $content)."</h3>";
    $out .= "</div>";

    return $out;
}

add_shortcode('knd_image_section', 'knd_image_section_shortcode');
function knd_image_section_shortcode($atts, $content = null){

    $atts = shortcode_atts(
        array(
            'title' => '', 
            'text_on_top' => 0, 
            'img' => 0
        ),
        $atts
    );

    if(empty($content) || $atts['img'] == 0) {
        return '';
    }

    $src = wp_get_attachment_url($atts['img']); //make it responsive
    $css = ($atts['text_on_top']) ? 'mark-over' : 'mark-under';
    $oust = '';

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


/*** IN Dev ***/
/** Sitemap (UI-) **/
//add_shortcode('knd_sitemap', 'knd_sitemap_shortcode');
function knd_sitemap_shortcode($atts) {
	return wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));
}

/** Youtube video caption (UI-) **/
add_shortcode('knd_youtube_caption', 'knd_youtube_caption_shortcode');
function knd_youtube_caption_shortcode($atts, $content = null){
	return '<div class="yt-caption">'.apply_filters('knd_the_content', $content).'</div>';
}

/** A button (UI+) **/
add_shortcode('knd_button', 'knd_button_shortcode');
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
add_shortcode('knd_quote', 'knd_quote_screen');
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
add_shortcode('knd_social_links', 'knd_social_links');
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

/** A text with background (image or color) (UI+) */
add_shortcode('knd_background_text', 'knd_background_text_shortcode');
function knd_background_text_shortcode($atts = array(), $echo = true) {

    $atts = shortcode_atts(array(
        'bg-image' => '',
        'bg-color' => '',
        'title' => '',
        'subtitle' => '',
        'cta-label' => '',
//        'cta-color' => '',
        'cta-url' => '',
        'class' => '',
    ), $atts);

    array_map(function($value){ return esc_attr($value); }, $atts);

    ob_start();?>

    <?php if($atts['bg-image'] && (int)$atts['bg-image'] > 0) {?>
    <section class="knd-background-text background text-over-image <?php echo $atts['class'] ? $atts['class'] : '';?>">
        <div class="tpl-pictured-bg" style="background-image: url(<?php echo wp_get_attachment_url((int)$atts['bg-image']);?>)"></div>
    </section>
    <?php }?>
    <section class="knd-background-text page-section text <?php echo $atts['bg-image'] && (int)$atts['bg-image'] > 0 ? 'text-over-image' : 'text-over-color';?> <?php echo $atts['cta-url'] && $atts['cta-label'] ? 'has-button' : '';?> <?php echo $atts['class'] ? $atts['class'] : '';?>">
        <div class="ihc-content" <?php echo $atts['bg-color'] ? 'style="background-color: '.$atts['bg-color'].';"' : '';?>>
            <?php if($atts['cta-url']) {?>
            <a href="<?php echo $atts['cta-url'];?>">
            <?php }?>

            <?php if($atts['title']) {?>
            <h2 class="ihc-title"><span><?php echo $atts['title'];?></span></h2>
            <?php }

            if($atts['subtitle']) {?>
            <div class="ihc-desc">
                <p><?php echo urldecode($atts['subtitle']);?></p>
            </div>
            <?php }

            if($atts['cta-url'] && $atts['cta-label']) {?>
            <div class="cta"><?php echo $atts['cta-label'];?></div>
            <?php }?>

            <?php if($atts['cta-url']) {?>
            </a>
            <?php }?>

        </div>
    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    if( !!$echo ) {
        echo $out;
    } else {
        return $out;
    }

}

/** A list of persons (UI+) */
add_shortcode('knd_persons_list', 'knd_persons_list_shortcode');
function knd_persons_list_shortcode($atts = array(), $echo = true) {

    $atts = shortcode_atts(array(
        'title' => '',
        'person-categories' => '',
        'persons' => '',
        'class' => '',
    ), $atts);

    ob_start();?>

    <section class="knd-persons-list page-section <?php echo $atts['class'] ? esc_attr($atts['class']) : '';?>">

        <?php if($atts['title']) {?>
        <div class="pb-section-title align-center"><h2><?php echo esc_attr($atts['title']);?></h2></div>
        <?php }

        knd_people_gallery($atts['person-categories'], $atts['persons']);?>

    </section>

    <?php $out = ob_get_contents();
    ob_end_clean();

    if( !!$echo ) {
        echo $out;
    } else {
        return $out;
    }

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
add_shortcode('knd_columns', 'knd_columns_shortcode');
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