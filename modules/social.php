<?php if( !defined('WPINC') ) die;
/**
 * Socila links and sharing
 */
function knd_social_links( $atts = array(), $echo = true ) {

	$classes = isset( $atts['class'] ) ? $atts['class'] : '';

	$social_links = array();

	foreach( knd_get_social_media_supported() as $id => $label ) {

		$link = esc_url( knd_get_theme_mod( 'knd_social_' . $id ) );
		if( $link ) {
			$social_links[ $id ] = array( 'label' => $label, 'link' => $link );
		}

	}

	$default_socials = array();

	if( $social_links ) {
		foreach( $social_links as $id => $data ) {
			$default_socials[] = array(
				'network' => $id,
				'label'   => $data['label'],
				'url'     => $data['link'],
			);
		}
	}

	$knd_social = get_theme_mod( 'knd_social', $default_socials );

	ob_start();

	if ( $knd_social ) {
		?>
			<ul class="knd-social-links <?php echo esc_attr( $classes ); ?>">
				<?php
				foreach( $knd_social as $setting ) {

					$icon = '<svg class="svg-icon">
						<title>' . esc_html( $setting['label'] ) . '</title>
						<use xlink:href="#icon-' . esc_attr( $setting['network'] ) . '" />
					</svg>';

					if ( ! $setting['network'] && $setting['image'] ) {
						$icon = '<div class="image-icon-mask"><div class="image-icon" style="--hms-social-icon:url(' . wp_get_attachment_image_url( $setting['image'] ) . ')"></div></div>';
					} else if ( ! $setting['network'] ) {
						$icon = '';
					}

					if ( $icon ) {
					?>
					<li class="<?php echo esc_attr( $setting['network'] );?>">
						<a href="<?php echo esc_url( $setting['url'] );?>" target="_blank" aria-label="<?php echo esc_attr( $setting['label'] );?>">
							<?php echo $icon; ?>
							<span><?php echo esc_html( $setting['label'] ); ?></span>
						</a>
					</li>
					<?php
					}
				}
				?>
			</ul>

		<?php 
	}

	$out = ob_get_contents();
	ob_end_clean();

	if( $echo ) {
		echo $out;
	} else {
		return $out;
	}

}

/** Available social shares */
function knd_social_shares(){
	global $post;
	$title = get_the_title( $post );
	$link = knd_current_url();
	$text = $title . ' ' . $link;

	$shares = array(
		'vk' => array(
			'label'       => esc_html__( 'Share on VK', 'knd' ),
			'url'         => 'https://vk.com/share.php?url=' . esc_url( $link ).'&title=' . esc_html( $title ),
			'txt'         => esc_html__( 'VKontakte', 'knd' ),
			'icon'        => 'icon-vk',
			'only_mobile' => false,
		),
		'twitter' => array(
			'label'       => esc_html__( 'Share on Twitter', 'knd' ),
			'url'         => 'https://twitter.com/intent/tweet?url=' . esc_url( $link ).'&text=' . esc_html( $title ),
			'txt'         => esc_html__( 'Twitter', 'knd' ),
			'icon'        => 'icon-twitter',
			'only_mobile' => false,
		),
		'ok' => array(
			'label' => esc_html__( 'Share on OK', 'knd' ),
			'url' => 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl=' . esc_url( $link ),
			'txt' => esc_html__( 'Odnoklassniki', 'knd' ),
			'icon' => 'icon-ok',
			'only_mobile'  => false,
		),
		'facebook' => array(
			'label'       => esc_html__( 'Share on Facebook', 'knd' ),
			'url'         => 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $link ),
			'txt'         => esc_html__( 'Facebook', 'knd' ),
			'icon'        => 'icon-facebook',
			'only_mobile' => false,
		),
		'whatsapp' => array(
			'label'       => esc_html__( 'Share on WhatsApp', 'knd' ),
			'url'         => 'whatsapp://send?text=' . esc_html( $text ),
			'txt'         => 'WhatsApp',
			'icon'        => 'icon-whatsup',
			'only_mobile' => true,
		),
		'telegram' => array(
			'label'       => esc_html__( 'Share on Telegram', 'knd' ),
			'url'         => 'tg://msg?text=' . esc_html( $text ),
			'txt'         => 'Telegram',
			'icon'        => 'icon-telegram',
			'only_mobile' => true,
		),
		'viber' => array(
			'label'       => esc_html__( 'Share on Viber', 'knd' ),
			'url'         => 'viber://forward?text=' . esc_html( $text ),
			'txt'         => 'Viber',
			'icon'        => 'icon-viber',
			'only_mobile' => true,
		),
	);

	return apply_filters( 'knd_social_shares', $shares );
}

/** Social buttons **/
function knd_social_share_no_js() {
	$shares = knd_social_shares();

	if ( ! knd_is_mobile_user_agent() ) {
		foreach( $shares as $slug => $item ){
			if ( $item['only_mobile'] ){
				unset( $shares[ $slug ] );
			}
		}
	}

	foreach( $shares as $slug => $item ){
		if ( ! get_theme_mod('social_share_' . $slug, true ) ) {
			unset( $shares[ $slug ] );
		}
	}

	?>
	<div class="social-likes-wrapper">
		<div class="social-likes social-likes_visible social-likes_ready">
		<?php
		foreach( $shares as $slug => $item ){
			$on_click = '';
			if ( ! $item['only_mobile'] ) {
				$on_click = 'onClick="window.open(\'' . $item['url'] . '\',\'' . $item['label'] . '\',\'top=320,left=325,width=650,height=430,status=no,scrollbars=no,menubar=no,tollbars=no\');return false;"';
			}
			?>
			<div title="<?php echo esc_attr( $item['label']);?>" class="social-likes__widget social-likes__widget_<?php echo esc_attr( $slug ); ?>">
				<a href="<?php echo $item['url'];?>" class="social-likes__button social-likes__button_<?php echo esc_attr( $slug ); ?>" target="_blank" <?php echo $on_click; ?>>
					<svg class="svg-icon"><use xlink:href="#<?php echo esc_attr( $item['icon'] ); ?>" /></svg>
					<span class="sh-text"><?php echo esc_html( $item['txt'] ); ?></span>
				</a>
			</div>
			<?php
		}
		?>
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
