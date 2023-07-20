<?php if( !defined('WPINC') ) die;

function knd_get_social_media_supported() {
	return array( 
		'vk'        => esc_html__( 'VKontakte', 'knd' ),
		'ok'        => esc_html__( 'Odnoklassniki', 'knd' ),
		'facebook'  => esc_html__( 'Facebook', 'knd' ),
		'instagram' => esc_html__( 'Instagram', 'knd' ),
		'twitter'   => esc_html__( 'Twitter', 'knd' ),
		'telegram'  => esc_html__( 'Telegram', 'knd' ),
		'youtube'   => esc_html__( 'YouTube', 'knd' ),
		'tiktok'    => esc_html__( 'TikTok', 'knd' ),
	);
}

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
	$title = rawurlencode_deep( get_the_title( $post ) );
	$url   = knd_current_url();
	$text  = rawurlencode_deep( $title . ' ' . $url );

	$shares = array(
		'vk' => array(
			'label'       => esc_html__( 'Share on VK', 'knd' ),
			'url'         => 'https://vk.com/share.php?url=' . esc_url( $url ).'&title=' . esc_html( $title ),
			'txt'         => esc_html__( 'VKontakte', 'knd' ),
			'icon'        => 'icon-vk',
			'only_mobile' => false,
			'target'      => 'popup',
		),
		'twitter' => array(
			'label'       => esc_html__( 'Share on Twitter', 'knd' ),
			'url'         => 'https://twitter.com/intent/tweet?url=' . esc_url( $url ).'&text=' . esc_html( $title ),
			'txt'         => esc_html__( 'Twitter', 'knd' ),
			'icon'        => 'icon-twitter',
			'only_mobile' => false,
			'target'      => 'popup'
		),
		'ok' => array(
			'label'       => esc_html__( 'Share on OK', 'knd' ),
			'url'         => 'https://connect.ok.ru/offer?url=' . esc_url( $url ) . '&title=' . esc_html( $title ),
			'txt'         => esc_html__( 'Odnoklassniki', 'knd' ),
			'icon'        => 'icon-ok',
			'only_mobile' => false,
			'target'      => 'popup'
		),
		'facebook' => array(
			'label'       => esc_html__( 'Share on Facebook', 'knd' ),
			'url'         => 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $url ),
			'txt'         => esc_html__( 'Facebook', 'knd' ),
			'icon'        => 'icon-facebook',
			'only_mobile' => false,
			'target'      => 'popup'
		),
		'telegram' => array(
			'label'       => esc_html__( 'Share on Telegram', 'knd' ),
			'url'         => 'https://t.me/share/url?url=' . esc_url( $url ) . '&text=' . esc_html( $title ),
			'txt'         => 'Telegram',
			'icon'        => 'icon-telegram',
			'only_mobile' => false,
			'target'      => 'blank',
		),
		'viber' => array(
			'label'       => esc_html__( 'Share on Viber', 'knd' ),
			'url'         => 'viber://forward?text=' . esc_html( $text ),
			'txt'         => 'Viber',
			'icon'        => 'icon-viber',
			'only_mobile' => false,
			'target'      => 'blank'
		),
		'whatsapp' => array(
			'label'       => esc_html__( 'Share on WhatsApp', 'knd' ),
			'url'         => 'whatsapp://send?text=' . esc_html( $text ),
			'txt'         => 'WhatsApp',
			'icon'        => 'icon-whatsup',
			'only_mobile' => true,
			'target'      => 'blank'
		),
	);

	return apply_filters( 'knd_social_shares', $shares );
}

/** Social buttons **/
function knd_social_share_no_js() {
	$shares = knd_social_shares();

	if ( ! wp_is_mobile() ) {
		foreach( $shares as $slug => $item ){
			if ( $item['only_mobile'] ){
				unset( $shares[ $slug ] );
			}
		}
	}

	foreach( $shares as $slug => $item ){
		$default = true;
		if ( 'telegram' === $slug || 'viber' === $slug ) {
			$default = false;
		}
		if ( wp_is_mobile() ) {
			$default = true;
		}
		if ( ! get_theme_mod('social_share_' . $slug, $default ) ) {
			unset( $shares[ $slug ] );
		}
	}

	?>
	<div class="social-likes-wrapper">
		<div class="social-likes social-likes_visible social-likes_ready">
		<?php
		foreach( $shares as $slug => $item ){
			$on_click = '';
			if ( 'popup' === $item['target'] ) {
				$on_click = 'onClick="window.open(\'' . $item['url'] . '\',\'' . $item['label'] . '\',\'top=320,left=325,width=650,height=430,status=no,scrollbars=no,menubar=no,tollbars=no\');return false;"';
			}
			?>
			<div class="social-likes__widget social-likes__widget_<?php echo esc_attr( $slug ); ?>">
				<a href="<?php echo esc_attr( $item['url'] );?>" title="<?php echo esc_attr( $item['label']);?>" class="social-likes__button social-likes__button_<?php echo esc_attr( $slug ); ?>" target="_blank" <?php echo $on_click; ?>>
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
