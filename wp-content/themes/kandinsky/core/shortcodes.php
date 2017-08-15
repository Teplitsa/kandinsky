<?php if( !defined('WPINC') ) die;
/**
 * Shortcodes
 **/

add_filter('widget_text', 'do_shortcode');

/** sitemap **/
add_shortcode('rdc_sitemap', 'rdc_sitemap_screen');
function rdc_sitemap_screen($atts){
	
	$out =  wp_nav_menu(array('theme_location' => 'sitemap', 'container' => false, 'menu_class' => 'sitemap', 'echo'=> false));
	
	return $out;
}


/** Youtube video caption **/
add_shortcode('yt_caption', 'rdc_yt_caption_screen');
function rdc_yt_caption_screen($atts, $content = null){
	return '<div class="yt-caption">'.apply_filters('rdc_the_content', $content).'</div>';
}


/** A button (UI+) **/
add_shortcode('knd_button', 'knd_button_shortcode');
function knd_button_shortcode($atts){

	$atts = shortcode_atts(array('url'  => '', 'txt'  => '', 'in_new_window' => false,), $atts);

	if(empty($atts['url'])) {
		return '';
    }

	ob_start();?>

<section class="knd-button-section">
    <span class="knd-btn">
        <a href="<?php echo esc_url($atts['url']);?>" class="knd-button" <?php echo !!$atts['in_new_window'] ? 'target="_blank"' : '';?>>
            <?php echo apply_filters('knd_the_title', $atts['txt']);?>
        </a>
    </span>
</section>

<?php $out = ob_get_contents();
	ob_end_clean();

	return $out;

}

/** A quote (UI+) **/
add_shortcode('knd_quote', 'knd_quote_screen');
function knd_quote_screen($atts, $content = null) {

	extract(shortcode_atts(array('name' => '', 'class' => '',), $atts));

	if(empty($content)) {
		return '';
    }

    /** @var string $name */
	$name = apply_filters('knd_the_title', $name);

	ob_start();?>

<div class="knd-quote <?php echo empty($class) ? '' : esc_attr($class);?>">
	<div class="knd-quote-content"><?php echo apply_filters('knd_the_content', $content);?></div>
	<?php if($name) {?>
		<div class="knd-quote-cite"><?php echo $name;?></div>
	<?php }?>
</div>
<?php $out = ob_get_contents();
	ob_end_clean();
	
	return $out;
}

/** Social links **/
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

/** A text with dackground (image or color).
 * @todo Make it UI+
 */
add_shortcode('knd_background_text', 'knd_background_text_shortcode');
function knd_background_text_shortcode($atts = array(), $echo = true) {

    $atts = shortcode_atts(array(
        'bg-image' => '',
        'bg-color' => '',
        'title' => '',
        'subtitle' => '',
        'cta-label' => '',
        'cta-color' => '',
        'cta-link' => '',
        'class' => '',
    ), $atts);

    array_map(function($value){ return esc_attr($value); }, $atts);

    ob_start();?>

    <section class="knd-background-text background text-over-image">
        <?php if($atts['bg-image'] && (int)$atts['bg-image'] > 0) {?>
        <div class="tpl-pictured-bg" style="background-image: url(<?php echo wp_get_attachment_url((int)$atts['bg-image']);?>)"></div>
        <?php }?>
    </section>
    <section class="knd-background-text text text-over-image <?php echo $atts['cta-link'] && $atts['cta-label'] ? 'has-button' : '';?>">
        <div class="ihc-content">
            <?php if($atts['cta-link']) {?>
            <a href="<?php echo $atts['cta-link'];?>">
            <?php }?>

            <?php if($atts['title']) {?>
            <h2 class="ihc-title"><span><?php echo $atts['title'];?></span></h2>
            <?php }

            if($atts['subtitle']) {?>
            <div class="ihc-desc">
                <p><?php echo $atts['subtitle'];?></p>
            </div>
            <?php }

            if($atts['cta-link'] && $atts['cta-label']) {?>
            <div class="cta"><?php echo $atts['cta-label'];?></div>
            <?php }?>

            <?php if($atts['cta-link']) {?>
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