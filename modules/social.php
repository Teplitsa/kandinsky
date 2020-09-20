<?php if( !defined('WPINC') ) die;
/**
 * Socila links and sharing
 **/

function knd_social_links($atts = array(), $echo = true) {

	$atts['class'] = empty($atts['class']) ? '' : esc_attr($atts['class']);

	ob_start();

	$social_links = array();
	foreach(knd_get_social_media_supported() as $id => $data) {

		$link = esc_url(knd_get_theme_mod('knd_social_links_'.$id));
		if($link) {
			$social_links[$id] = array('label' => $data['label'], 'link' => $link);
		}

	}

	if($social_links) {?>

	<ul class="knd-social-links <?php echo $atts['class'];?>">
	<?php foreach($social_links as $id => $data) {?>

		<li class="<?php echo esc_attr($id);?>">
			<a href="<?php echo esc_url($data['link']);?>">
				<svg class="svg-icon"><use xlink:href="#<?php echo 'icon-'.$id;?>"/></svg>
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

/**
 * Social Header links
 */
function knd_header_social($atts = array(), $echo = true) {

	$atts['class'] = empty($atts['class']) ? '' : esc_attr($atts['class']);

	ob_start();

	$social_links = array();
	foreach(knd_get_social_media_supported() as $id => $data) {

		$link = esc_url(knd_get_theme_mod('knd_header_social_'.$id));
		if($link) {
			$social_links[$id] = array('label' => $data['label'], 'link' => $link);
		}

	}

	if($social_links) {?>

	<ul class="knd-social-links <?php echo $atts['class'];?>">
	<?php foreach($social_links as $id => $data) {?>

		<li class="<?php echo esc_attr($id);?>">
			<a href="<?php echo esc_url($data['link']);?>">
				<svg class="svg-icon"><use xlink:href="#<?php echo 'icon-'.$id;?>"/></svg>
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

/** Social buttons **/
function knd_social_share_no_js() {

	global $post;
	$title = esc_html( get_the_title( $post ) );
	$link = knd_current_url();
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
			'url' => 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
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
	if((knd_is_mobile_user_agent() && $obj['show_mobile']) || !knd_is_mobile_user_agent()){
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
		
		if((!knd_is_mobile_user_agent() && $obj['show_desktop']) || knd_is_mobile_user_agent()) {
?>
	<div title="<?php echo esc_attr($obj['label']);?>" class="social-likes__widget social-likes__widget_<?php echo $key;?>">
	<a href="<?php echo $obj['url'];?>" target="_blank" class="social-likes__button social-likes__button_<?php echo $key;?>"><svg class="svg-icon"><use xlink:href="#<?php echo $obj['icon'];?>" /></svg><span class="sh-text"><?php echo $obj['txt'];?></span></a>
	</div>	
<?php } } //endforeach ?>

</div>
</div>
<?php
}

function knd_is_mobile_user_agent(){
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

