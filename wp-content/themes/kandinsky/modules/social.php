<?php
/** Social menu and sharing **/
function rdc_get_social_menu(){
	return wp_nav_menu(array(
			'theme_location' => 'social',
			'container' => false,
			'menu_class' => 'social-menu',
			'fallback_cb' => '',
			'echo' => false
		)); 
}


/** Support for social icons in menu **/
add_filter( 'pre_wp_nav_menu', 'rdc_pre_wp_nav_menu_social', 10, 2 );
function rdc_pre_wp_nav_menu_social( $output, $args ) {
	if ( ! $args->theme_location || 'social' !== $args->theme_location ) {
		return $output;
	}

	// Get the menu object
	$locations = get_nav_menu_locations(); 
	$menu      = (isset($locations[ $args->theme_location ])) ? wp_get_nav_menu_object( $locations[ $args->theme_location ] ) : false;

	if ( ! $menu || is_wp_error( $menu ) ) {
		return $output;
	}

	$output = '';

	// Get the menu items
	$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

	// Set up the $menu_item variables
	_wp_menu_item_classes_by_context( $menu_items );

	// Sort the menu items
	$sorted_menu_items = array();
	foreach ( (array) $menu_items as $menu_item ) {
		$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
	}

	unset( $menu_items, $menu_item );

	// Supported social icons (filterable); [url pattern] => [css class]
	$supported_icons = apply_filters( 'rdc_supported_social_icons', array(
		'instagram.com'      => 'icon-instagram',	
		'facebook.com'       => 'icon-facebook',		
		'twitter.com'        => 'icon-twitter',
		'vk.com'             => 'icon-vk',
		'youtube.com'        => 'icon-youtube',		
		'odnoklassniki.ru'   => 'icon-ok',
		'ok.ru'              => 'icon-ok'
	));

	// Process each menu item
	foreach ( $sorted_menu_items as $item ) {
		$item_output = '';

		// Look for matching icons
		foreach ( $supported_icons as $pattern => $class ) {
			if ( false !== strpos( $item->url, $pattern ) ) {
				
				$icon = '<svg class="svg-icon"><use xlink:href="#'.$class.'" /></svg>';
				
				$item_output .= '<li class="' . esc_attr( str_replace( array('fa-', 'icon-'), '', $class ) ) . '">';
				$item_output .= '<a href="' . esc_url( $item->url ) . '">';				
				$item_output .= $icon;
				$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
				$item_output .= '</a></li>';
				break;
			}
		}

		// No matching icons
		if ( '' === $item_output ) {
			//$item_output .= '<li class="external-link-square">';
			//$item_output .= '<a href="' . esc_url( $item->url ) . '">';
			//$item_output .= '<i class="fa fa-fw fa-external-link-square">';
			//$item_output .= '<span>' . esc_html( $item->title ) . '</span>';
			//$item_output .= '</i></a></li>';
		}

		// Add item to list
		$output .= $item_output;
		unset( $item_output );
	}

	// If there are menu items, add a wrapper
	if ( '' !== $output ) {
		$output = '<ul class="' . esc_attr( $args->menu_class ) . '">' . $output . '</ul>';
	}

	return $output;
}


/** == Social buttons == **/
function rdc_social_share_no_js() {
	
	$title = (class_exists('WPSEO_Frontend')) ? WPSEO_Frontend::get_instance()->title( '' ) : '';
	$link = rdc_current_url();
	$text = $title.' '.$link;

	$data = array(
		'vk' => array(
			'label' => 'Поделиться во Вконтакте',
			'url' => 'https://vk.com/share.php?url='.$link.'&title='.$title,
			'txt' => 'Вконтакте',
			'icon' => 'icon-vk',
			'show_mobile' => true
		),			
		'twitter' => array(
			'label' => 'Поделиться ссылкой в Твиттере',
			'url' => 'https://twitter.com/intent/tweet?url='.$link.'&text='.$title,
			'txt' => 'Twitter',
			'icon' => 'icon-twitter',
			'show_mobile' => false		
		),
		'ok' => array(
			'label' => 'Поделиться ссылкой в Одноклассниках',
			'url' => 'http://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
			'txt' => 'Одноклассники',
			'icon' => 'icon-ok',
			'show_mobile' => false
			
		),
		'facebook' => array(
			'label' => 'Поделиться на Фейсбуке',
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$link,
			'txt' => 'Facebook',
			'icon' => 'icon-facebook',
			'show_mobile' => true
		),	
	);
	
?>
<div class="social-likes-wrapper">
<div class="social-likes social-likes_visible social-likes_ready">

<?php
foreach($data as $key => $obj){		
	if((rdc_is_mobile_user_agent() && $obj['show_mobile']) || !rdc_is_mobile_user_agent()){
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
		<a href="<?php echo $obj['url'];?>" class="social-likes__button social-likes__button_<?php echo $key;?>" target="_blank" onClick="window.open('<?php echo $obj['url'];?>','<?php echo $obj['label'];?>','top=320,left=325,width=650,height=430,status=no,scrollbars=no,menubar=no,tollbars=no');return false;">
			<svg class="svg-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span>
		</a>
	</div>
<?php 
	}
	
} //foreach

	$text = $title.' '.$link;
	
	$mobile = array(
		//'twitter' => array(
		//	'label' => 'Поделиться ссылкой в Твиттере',
		//	'url' => 'twitter://post?message='.$text,
		//	'txt' => 'Twitter',
		//	'icon' => 'icon-twitter',
		//	'show_desktop' => false		
		//),
		'whatsapp' => array(
			'label' => 'Поделиться ссылкой в WhatsApp',
			'url' => 'whatsapp://send?text='.$text,
			'txt' => 'WhatsApp',
			'icon' => 'icon-whatsup',
			'show_desktop' => false
		),
		'telegram' => array(
			'label' => 'Поделиться ссылкой в Telegram',
			'url' => 'tg://msg?text='.$text,
			'txt' => 'Telegram',
			'icon' => 'icon-telegram',
			'show_desktop' => false
		),
		'viber' => array(
			'label' => 'Поделиться ссылкой в Viber',
			'url' => 'viber://forward?text='.$text,
			'txt' => 'Viber',
			'icon' => 'icon-viber',
			'show_desktop' => false
		),
	);
		
	foreach($mobile as $key => $obj) {
		
		if((!rdc_is_mobile_user_agent() && $obj['show_desktop']) || rdc_is_mobile_user_agent()) {
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
	<a href="<?php echo $obj['url'];?>" target="_blank" class="social-likes__button social-likes__button_<?php echo $key;?>"><svg class="svg-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span></a>
	</div>	
<?php } } //endforeach ?>

</div>
</div>
<?php
}

function rdc_is_mobile_user_agent(){
	//may be need some more sophisticated testing
	$test = false;
	
	if(!isset($_SERVER['HTTP_USER_AGENT']))
		return $test;
	
	if(stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
		$test = true;
	}
	
	return $test;
}


/** == Newsletter == */
function rdc_get_newsletter_form($type = ''){
	
	$key = ($type == 'bottom') ? 'newsletter_bottom_form_id' : 'newsletter_form_id';
	$form_id = get_theme_mod($key, 0);
	if(empty($form_id))
		return '';
	
	$code = "[formidable title=false description=false id='{$form_id}']";
	
	return do_shortcode($code);
}

add_shortcode('newsletter_form', 'rdc_newsletter_form_screen');
function rdc_newsletter_form_screen($atts){
	
	return "<div class='newsletter-form'>".rdc_get_newsletter_form()."</div>";
}
