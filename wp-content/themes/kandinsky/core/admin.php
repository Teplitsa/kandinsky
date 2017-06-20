<?php
/**
 * Admin customization
 **/

add_filter('manage_posts_columns', 'rdc_common_columns_names', 50, 2);
function rdc_common_columns_names($columns, $post_type) {
		
	if(in_array($post_type, array('post', 'project', 'org', 'person', 'event'))){
		
		if(in_array($post_type, array('event', 'programm')))
			$columns['menu_order'] = 'Порядок';
		
		if(in_array($post_type, array('event')))
			$columns['event_start'] = 'Начало';
		
		if(!in_array($post_type, array('attachment')))
			$columns['thumbnail'] = 'Миниат.';
		
		if(isset($columns['author'])){
			$columns['author'] = 'Создал';
		}
		
		$columns['id'] = 'ID';
	}
	
	return $columns;
}

add_action('manage_pages_custom_column', 'rdc_common_columns_content', 2, 2);
add_action('manage_posts_custom_column', 'rdc_common_columns_content', 2, 2);
function rdc_common_columns_content($column_name, $post_id) {
	
	$cpost = get_post($post_id);
	if($column_name == 'id'){
		echo intval($cpost->ID);
		
	}
	elseif($column_name == 'thumbnail') {
		$img = get_the_post_thumbnail($post_id, 'thumbnail');
		if(empty($img))
			echo "&ndash;";
		else
			echo "<div class='admin-tmb'>{$img}</div>";			
		
	}
	elseif($column_name == 'event_start') {
		$event = new TST_Event($post_id);
		echo $event->get_date_mark('formal');
	}
	elseif($column_name == 'menu_order') {
			
		echo intval($cpost->menu_order);
	}
}


add_filter('manage_pages_columns', 'rdc_pages_columns_names', 50);
function rdc_pages_columns_names($columns) {		
		
	if(isset($columns['author'])){
		$columns['author'] = 'Создал';
	}
	
	//$columns['menu_order'] = 'Порядок';	
	$columns['id'] = 'ID';
		
	return $columns;
}




//manage_edit-topics_columns
add_filter( "manage_edit-category_columns", 'rdc_common_tax_columns_names', 10);
add_filter( "manage_edit-post_tag_columns", 'rdc_common_tax_columns_names', 10);
function rdc_common_tax_columns_names($columns){
	
	$columns['id'] = 'ID';
	
	return $columns;	
}

add_filter( "manage_category_custom_column", 'rdc_common_tax_columns_content', 10, 3);
add_filter( "manage_post_tag_custom_column", 'rdc_common_tax_columns_content', 10, 3);
function rdc_common_tax_columns_content($content, $column_name, $term_id){
	
	if($column_name == 'id')
		return intval($term_id);
}


/* admin tax columns */
/*add_filter('manage_taxonomies_for_material_columns', function($taxonomies){
	$taxonomies[] = 'pr_type';
	$taxonomies[] = 'audience';
	
    return $taxonomies;
});*/



/**
* SEO UI cleaning
**/
add_action('admin_init', function(){
	foreach(get_post_types(array('public' => true), 'names') as $pt) {
		add_filter('manage_' . $pt . '_posts_columns', 'rdc_clear_seo_columns', 100);
	}	
}, 100);

function rdc_clear_seo_columns($columns){

	if(isset($columns['wpseo-score']))
		unset($columns['wpseo-score']);
	
	if(isset($columns['wpseo-title']))
		unset($columns['wpseo-title']);
	
	if(isset($columns['wpseo-metadesc']))
		unset($columns['wpseo-metadesc']);
	
	if(isset($columns['wpseo-focuskw']))
		unset($columns['wpseo-focuskw']);
	
	return $columns;
}

add_filter('wpseo_use_page_analysis', '__return_false');


/* Excerpt metabox */
add_action('add_meta_boxes', 'rdc_correct_metaboxes', 2, 2);
function rdc_correct_metaboxes($post_type, $post ){
	
	if(post_type_supports($post_type, 'excerpt')){
		remove_meta_box('postexcerpt', null, 'normal');
		
		$label = ($post_type == 'org') ? __('Website', 'kds') : __('Excerpt', 'kds');
		add_meta_box('rdc_postexcerpt', $label, 'rdc_excerpt_meta_box', null, 'normal', 'core');
	}
	
}

function rdc_excerpt_meta_box($post){
	if($post->post_type == 'org'){
?>
<label class="screen-reader-text" for="excerpt"><?php _e('Website', 'kds'); ?></label>
<input type="text" name="excerpt" id="url-excerpt" value="<?php echo $post->post_excerpt; // textarea_escaped ?>" class="widefat">

<?php }	else { ?>

<label class="screen-reader-text" for="excerpt"><?php _e('Excerpt', 'kds'); ?></label>
<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; // textarea_escaped ?></textarea>
<p><?php _e('Annotation for items lists (will be printed at the beginning of the single page)', 'kds'); ?></p>

<?php	
}
	
}


/**  Home page settings in admin menu */
add_action('admin_menu', 'rdc_add_menu_items', 25);
function rdc_add_menu_items(){
    
	$id = (int)get_option('page_on_front', 0);
	
    add_submenu_page('index.php',
                    'Настройки главной',
                    'Настройки главной',
                    'edit_pages',
                    'post.php?post='.$id.'&action=edit'                    
    );   
}


/** Visual editor **/
add_filter('tiny_mce_before_init', 'rdc_format_TinyMCE');
function rdc_format_TinyMCE($in){

    $in['block_formats'] = "Абзац=p; Выделенный=pre; Заголовок 3=h3; Заголовок 4=h4; Заголовок 5=h5; Заголовок 6=h6";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';

	return $in;
}

/* Menu Labels */
add_action('admin_menu', 'rdc_admin_menu_labels');
function rdc_admin_menu_labels(){ /* change adming menu labels */
    global $menu, $submenu;
	
    //lightbox   
    foreach($submenu['options-general.php'] as $order => $item){
		
        if(isset($item[2]) && $item[2] == 'responsive-lightbox'){
			$submenu['options-general.php'][$order][0] = 'Lightbox';			
		}        
    }
	
	//forms
	foreach($menu as $order => $item){
        
        if($item[2] == 'ninja-forms'){ 
            $menu[$order][0] = __('Forms', 'tst');            
            break;
        }
    }   
}

/** Remove leyka metabox for embedable iframe */
add_action( 'add_meta_boxes' , 'rdc_remove_leyka_wrong_metaboxes', 20 );
function rdc_remove_leyka_wrong_metaboxes() {
	
	remove_meta_box('leyka_campaign_embed', 'leyka_campaign', 'normal');
}


/** Dashboards widgets **/
add_action('wp_dashboard_setup', 'rdc_remove_dashboard_widgets' );
function rdc_remove_dashboard_widgets() {
	
	//remove defaults 	
	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );	
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		
	
	//add ours
	$locale = get_locale();
    
    if($locale == 'ru_RU') {
        add_meta_box('custom_links', 'Полезные ссылки', 'rdc_custom_links_dashboard_screen', 'dashboard', 'side', 'core');
    }	
} 



function rdc_custom_links_dashboard_screen(){
	
	rdc_itv_info_widget();
	rdc_support_widget();


}

function rdc_itv_info_widget(){
	    
    $src = get_template_directory_uri().'/assets/img/logo-itv.png';
    $domain = parse_url(home_url()); 
    $itv_url = "https://itv.te-st.ru/?giger=".$domain['host'];
?>
<div id="itv-dashboard-card" class="rdc-dashboard">
	<div class="cols">
		<div class="col-logo"><div class="itv-logo col-logo">
			<a href="<?php echo esc_url($itv_url);?>" target="_blank"><img src="<?php echo esc_url($src);?>"></a>
		</div></div>
		<div class="col-btn"><a href="<?php echo esc_url($itv_url);?>" target="_blank" class="button">Опубликовать задачу</a></div>
	</div>
	
	
	<p>Вам нужна помощь в настройке или доработке сайта?<br>Опубликуйте задачу на платформе <a href="<?php echo esc_url($itv_url);?>" target="_blank">it-волонтер</a></p>                
	
</div>
<?php
}

function rdc_support_widget(){
	
	$src = get_template_directory_uri().'/assets/img/tst-logo';
	
	$doc = (defined('TST_DOC_URL') && !empty(TST_DOC_URL)) ? TST_DOC_URL : '';
	if(!empty($doc))
		$doc = str_replace('<a', '<a target="_blank" ', make_clickable($doc));
	
?>
<div id="rdc-support-card" class="rdc-dashboard">
	<div class="cols">
		
	<div class="col-logo"><div class="tree-logo">
		<img src="<?php echo $src;?>.svg" onerror="this.onerror=null;this.src=<?php echo $src;?>.png">
	</div></div>
	<div class="col-btn"><a href="mailto:support@te-st.ru" target="_blank" class="button">Написать в поддержку</a></div>
	</div>
	
	<p>Возникли проблемы с использованием сайта, нашли ошибку?<br>Обратитесь в поддержку Теплицы социальных технологий <a href="mailto:support@te-st.ru" target="_blank">support@te-st.ru</a></p>
	<?php if(!empty($doc)) { ?>
		<p>Справочная информация по работе с сайтом находится по ссылке <?php echo $doc; ?></p>
	<?php } ?>
</div>
<?php
}

/** Doc link in footer text **/
add_filter( 'admin_footer_text', 'rdc_admin_fotter_text' );
function rdc_admin_fotter_text($text) {
		
	$doc = (defined('TST_DOC_URL') && !empty(TST_DOC_URL)) ? TST_DOC_URL : '';
	
	if(empty($doc))
		return $text;
	
	if(!empty($doc))
		$doc = str_replace('<a', '<a target="_blank" ', make_clickable($doc));
	
	$text = '<span id="footer-thankyou">Краткое руководство по работе с сайтом - ' . $doc . '</span>';	
	return $text;
}



/** Notification about wront thumbnail size **/
add_filter('admin_post_thumbnail_html', 'rdc_thumbnail_dimensions_check', 10, 2);
function rdc_thumbnail_dimensions_check($thumbnail_html, $post_id) {
	global $_wp_additional_image_sizes;
	
	if('org' == get_post_type($post_id))
		return $thumbnail_html;
	
    $meta = wp_get_attachment_metadata(get_post_thumbnail_id($post_id));
    $needed_sizes = (isset($_wp_additional_image_sizes['post-thumbnail'])) ? $_wp_additional_image_sizes['post-thumbnail'] : false;
	
    if(
        $meta && $needed_sizes &&
        ($meta['width'] < $needed_sizes['width'] || $meta['height'] < $needed_sizes['height'])
    ) {
	
	$size = "<b>".$needed_sizes['width'].'x'.$needed_sizes['height']."</b>";
	$txt = sprintf(__('ATTENTION! You thumbnail image is too small. It should be at least %s px', 'kds'), $size);
	
    echo "<p class='rdc-error'>{$txt}<p>";
    }

    return $thumbnail_html;
}


/** == Revome unused metabox == **/
//add_action( 'add_meta_boxes' , 'rdc_remove_wrong_metaboxes', 20 );
function rdc_remove_wrong_metaboxes() {
	
	//hide section default metabox
	remove_meta_box('tagsdiv-auctor', 'post', 'side');
	
}


/** ==  Auctor Meta UI - for WP 4.4 + only == **/
add_action( 'create_auctor', 'rdc_save_auctor_meta');
add_action( 'edited_auctor', 'rdc_save_auctor_meta');
function rdc_save_auctor_meta($term_id){
		
	
	if (
		// nonce was submitted and is verified
		isset( $_POST['taxonomy-term-image-save-form-nonce'] ) &&
		wp_verify_nonce( $_POST['taxonomy-term-image-save-form-nonce'], 'taxonomy-term-image-form-save' ) &&

		// taxonomy corect
		isset( $_POST['taxonomy'] ) && $_POST['taxonomy'] == 'auctor'
	)
	{
		$img_id = (!empty($_POST['auctor_photo'])) ? intval($_POST['auctor_photo']) : null;
		update_term_meta($term_id, 'auctor_photo', $img_id);
		
		$fb = (!empty($_POST['auctor_facebook'])) ? esc_url_raw($_POST['auctor_facebook']) : '';
		update_term_meta($term_id, 'auctor_facebook', $fb);
	}
}

add_action( "auctor_edit_form_fields", 'rdc_auctor_edit_term_fields');
function rdc_auctor_edit_term_fields($term){
		
	$fb = get_term_meta($term->term_id, 'auctor_facebook', true);		
?>
<tr class="form-field term-auctor_facebook-wrap">
	<th scope="row"><label for="auctor_facebook"><?php _e( 'Facebook Profile', 'tst' ); ?></label></th>
	<td><input name="auctor_facebook" id="auctor_facebook" type="text" value="<?php echo esc_attr($fb);?>">
	<p class="description"><?php _e('Enter URL of author\'s Facebook profile'); ?></p></td>
</tr>
<tr class="form-field term-auctor_photo-wrap">
	<th scope="row"><label for="auctor_photo"><?php _e( 'Avatar', 'tst' ); ?></label></th>
	<td><?php rdc_auctor_photo_field($term);?></td>
</tr>
<?php
}

add_action( "auctor_add_form_fields", 'rdc_auctor_add_term_fields');
function rdc_auctor_add_term_fields($term){
	
?>
<div class="form-field term-auctor_facebook-wrap">
	<label for="auctor_facebook"><?php _e( 'Facebook Profile', 'tst' ); ?></label>
	<input name="auctor_facebook" id="auctor_facebook" type="text" value="">
	<p class="description"><?php _e('Enter URL of author\'s Facebook profile'); ?></p>
</div>
<div class="form-field term-auctor_photo-wrap">
	<label for="auctor_photo"><?php _e( 'Avatar', 'tst' ); ?></label>
	<td><?php rdc_auctor_photo_field(null);?>
</div>
<?php
}


function rdc_auctor_photo_field($term){
	
	rdc_auctor_enqueue_scripts();
	
	$image_ID = ($term) ? get_term_meta($term->term_id, 'auctor_photo', true) : '';
	$image_src = ($image_ID) ? wp_get_attachment_image_src($image_ID, 'thumbnail') : array();
	$labels = rdc_get_image_field_labels();

	wp_nonce_field('taxonomy-term-image-form-save', 'taxonomy-term-image-save-form-nonce');
?>
<input type="button" class="taxonomy-term-image-attach button" value="<?php echo esc_attr( $labels['imageButton'] ); ?>" />
<input type="button" class="taxonomy-term-image-remove button" value="<?php echo esc_attr( $labels['removeButton'] ); ?>" />
<input type="hidden" id="taxonomy-term-image-id" name="auctor_photo" value="<?php echo esc_attr( $image_ID ); ?>" />
<p id="taxonomy-term-image-container">
	<?php if ( isset( $image_src[0] ) ) : ?>
		<img class="taxonomy-term-image-attach" src="<?php print esc_attr( $image_src[0] ); ?>" />
	<?php endif; ?>
</p>
<?php
	
}

function rdc_get_image_field_labels() {
	
	return array(
		'fieldTitle'       => __( 'Taxonomy Term Image' ),
		'fieldDescription' => __( 'Select which image should represent this term.' ),
		'imageButton'      => __( 'Select Image' ),
		'removeButton'     => __( 'Remove' ),
		'modalTitle'       => __( 'Select or upload an image for this term' ),
		'modalButton'      => __( 'Attach' ),
	);
}

function rdc_auctor_enqueue_scripts(){
	
	$screen = get_current_screen();
	$labels = rdc_get_image_field_labels();
		
	if ( $screen->id == 'edit-auctor' ){
		// WP core stuff we need
		wp_enqueue_media();
		wp_enqueue_style( 'thickbox' );
		$dependencies = array( 'jquery', 'thickbox', 'media-upload' );

		// register our custom script
		$url = get_template_directory_uri().'/assets/js';
		wp_register_script( 'taxonomy-term-image-js', $url.'/taxonomy-term-image.js', $dependencies, '1.5.1', true );

		// Localize the modal window text so that we can translate it
		wp_localize_script( 'taxonomy-term-image-js', 'TaxonomyTermImageText', $labels );

		// enqueue the registered and localized script
		wp_enqueue_script( 'taxonomy-term-image-js' );
	}
}

