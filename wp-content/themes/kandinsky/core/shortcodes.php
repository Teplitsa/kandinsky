<?php
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
add_shortcode('knd_btn', 'knd_btn_shortcode');
function knd_btn_shortcode($atts){

	$atts = shortcode_atts(array(
		'url'  => '',
		'txt'  => ''
	), $atts);

	if(empty($atts['url'])) {
		return '';
    }

	ob_start();?>

<span class="rdc-btn">
    <a href="<?php echo esc_url($atts['url']);?>" class="rdc-button">
        <?php echo apply_filters('rdc_the_title', $atts['txt']);?>
    </a>
</span>

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
	$name = apply_filters('rdc_the_title', $name);

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

/** A text with dackground (image or color). */
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

/** Shortcake (Shortcode UI) plugin needed */
add_action('register_shortcode_ui', 'knd_add_shortcodes_ui');
function knd_add_shortcodes_ui() {

//    $fields = array(
//        array(
//            'label'       => esc_html__( 'Attachment', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'        => 'attachment',
//            'type'        => 'attachment',
//            /*
//             * These arguments are passed to the instantiation of the media library:
//             * 'libraryType' - Type of media to make available.
//             * 'addButton'   - Text for the button to open media library.
//             * 'frameTitle'  - Title for the modal UI once the library is open.
//             */
//            'libraryType' => array( 'image' ),
//            'addButton'   => esc_html__( 'Select Image', 'shortcode-ui-example', 'shortcode-ui' ),
//            'frameTitle'  => esc_html__( 'Select Image', 'shortcode-ui-example', 'shortcode-ui' ),
//        ),
//        array(
//            'label'       => 'Gallery',
//            'attr'        => 'gallery',
//            'description' => esc_html__( 'You can select multiple images.', 'shortcode-ui' ),
//            'type'        => 'attachment',
//            'libraryType' => array( 'image' ),
//            'multiple'    => true,
//            'addButton'   => 'Select Images',
//            'frameTitle'  => 'Select Images',
//        ),
//        array(
//            'label'  => __('Quote origin', 'knd'),
//            'attr'   => 'name',
//            'type'   => 'text',
//            'encode' => false,
//            'meta'   => array('placeholder' => __('A quote origin (or author)', 'knd'),),
//        ),
//        array(
//            'label'    => esc_html__( 'Select Page', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'     => 'page',
//            'type'     => 'post_select',
//            'query'    => array( 'post_type' => 'page' ),
//            'multiple' => true,
//        ),
//        array(
//            'label'    => __( 'Select Tag', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'     => 'tag',
//            'type'     => 'tag_select',
//            'taxonomy' => 'post_tag',
//            'multiple' => true,
//        ),
//        array(
//            'label'    => __( 'User Select', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'     => 'users',
//            'type'     => 'user_select',
//            'multiple' => true,
//        ),
//        array(
//            'label'  => esc_html__( 'Color', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'   => 'color',
//            'type'   => 'color',
//            'encode' => false,
//            'meta'   => array(
//                'placeholder' => esc_html__( 'Hex color code', 'shortcode-ui-example', 'shortcode-ui' ),
//            ),
//        ),
//        array(
//            'label'       => esc_html__( 'Alignment', 'shortcode-ui-example', 'shortcode-ui' ),
//            'description' => esc_html__( 'Whether the quotation should be displayed as pull-left, pull-right, or neither.', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'        => 'alignment',
//            'type'        => 'select',
//            'options'     => array(
//                array( 'value' => '', 'label' => esc_html__( 'None', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                array( 'value' => 'left', 'label' => esc_html__( 'Pull Left', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                array( 'value' => 'right', 'label' => esc_html__( 'Pull Right', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                array(
//                    'label' => 'Test Optgroup',
//                    'options' => array(
//                        array( 'value' => 'left-2', 'label' => esc_html__( 'Pull Left', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                        array( 'value' => 'right-2', 'label' => esc_html__( 'Pull Right', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                    )
//                ),
//            ),
//        ),
//        array(
//            'label'       => esc_html__( 'CSS Classes', 'shortcode-ui-example', 'shortcode-ui' ),
//            'description' => esc_html__( 'Which classes the shortcode should get.', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'        => 'classes',
//            'type'        => 'select',
//            /**
//             * Use this to allow for multiple selections – similar to `'multiple' => true'`.
//             */
//            'meta' => array( 'multiple' => true ),
//            'options'     => array(
//                array( 'value' => '', 'label' => esc_html__( 'Default', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                array( 'value' => 'bold', 'label' => esc_html__( 'Bold', 'shortcode-ui-example', 'shortcode-ui' ) ),
//                array( 'value' => 'italic', 'label' => esc_html__( 'Italic', 'shortcode-ui-example', 'shortcode-ui' ) ),
//            ),
//        ),
//        array(
//            'label'       => esc_html__( 'Year', 'shortcode-ui-example', 'shortcode-ui' ),
//            'description' => esc_html__( 'Optional. The year the quotation is from.', 'shortcode-ui-example', 'shortcode-ui' ),
//            'attr'        => 'year',
//            'type'        => 'number',
//            'meta'        => array(
//                'placeholder' => 'YYYY',
//                'min'         => '1990',
//                'max'         => date_i18n( 'Y' ),
//                'step'        => '1',
//            ),
//        ),
//    );

    shortcode_ui_register_for_shortcode('rdc_quote', array(
        'label' => __('A quote', 'knd'), // How the shortcode should be labeled in the UI. Required argument
        'listItemImage' => 'dashicons-editor-quote', // A dashicon class or full HTML (e.g. <img src="/path/to/your/icon">)
//        'post_type' => array('post'), // Limit this shortcode UI to specific posts. Optional

        'inner_content' => array( // A UI for the "inner content" of the shortcode. Optional
            'label'        => __('A quote text', 'knd'),
//            'description'  => __('A text of the quote.', 'knd'),
        ),

        'attrs' => array( // Define & bind the UI for shortcode attributes. Optional
            array(
                'label'  => __('Quote origin', 'knd'),
                'attr'   => 'name',
                'type'   => 'text',
                'encode' => false,
                'meta'   => array('placeholder' => __('A quote origin (or author)', 'knd'),),
            ),
        ),
    ));
}