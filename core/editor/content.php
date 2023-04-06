<?php
/**
 * Content
 *
 * @package Kandinsky
 */

/**
 * Return block content.
 */
function knd_get_block_content( $block_name ){
	$content = null;
	if ( 'info' === $block_name ) {
		$content = '';
	} elseif ( 'cta' === $block_name ) {
		$content = '';
	}

	elseif ( 'media' === $block_name ) {
		$content = '<!-- wp:knd/news {"layout":"type-3","heading":"","postsToShow":1,"columns":1,"overlayColor":"#00000082","overlayHoverColor":"#00000096","titleColor":"#ffffff","titleHoverColor":"#d30a6a","linkColor":"#f99fcb","excerptColor":"#ffffff","dateColor":"#b9b9b9","imageOrientation":"landscape-16-9","imageSize":"1536x1536","author":true,"avatar":true,"excerpt":true,"titleFontSize":"38px","excerptFontSize":"18px","titleFontWeight":"medium","dateFormat":"j F Y","alignment":"bottom left","paddingTop":false,"paddingBottom":false} /-->';
	}

	elseif ( 'media-2' === $block_name ) {
		$content = '<!-- wp:knd/news {"layout":"type-3","postsToShow":2,"columns":2,"overlayColor":"#00000082","overlayHoverColor":"#000000a8","titleColor":"#ffffff","titleHoverColor":"#d30a6a","linkColor":"#f99fcb","excerptColor":"#ffffff","dateColor":"#b9b9b9","imageOrientation":"landscape-16-9","author":true,"avatar":true,"titleFontSize":"24px","excerptFontSize":"18px","titleFontWeight":"medium","dateFormat":"j F Y","alignment":"bottom left","paddingTop":false,"paddingBottom":false} /-->';
	}

	elseif ( 'media-3' === $block_name ) {
		$content = '<!-- wp:knd/news {"layout":"type-2","postsToShow":1,"columns":1,"overlayHoverColor":"#00000030","imageOrientation":"landscape-16-9","imageWidth":"two-thirds","author":true,"avatar":true,"excerpt":true,"titleFontSize":"24px","excerptFontSize":"16px","dateFormat":"j F Y","paddingTop":false,"paddingBottom":false} /-->';
	}

	elseif ( 'media-4' === $block_name ) {
		$content = '<!-- wp:knd/news {"layout":"type-2","postsToShow":4,"columns":2,"imageOrientation":"landscape-16-9","imageSize":"medium","imagePosition":"right","author":true,"avatar":true,"excerptFontSize":"16px","dateFormat":"j F Y","paddingTop":false,"paddingBottom":false} /-->';
	}

	elseif ( 'media-5' === $block_name ) {
		$content = '<!-- wp:knd/news {"heading":"Новости","titleAlign":true,"postsToShow":6,"linkHoverBackground":"#caffc2","thumbnail":false,"author":true,"avatar":true,"excerpt":true,"excerptLength":39,"titleFontWeight":"semibold","dateFormat":"j F Y"} /-->';
	}

	elseif ( 'media-6' === $block_name ) {
		$content = '<!-- wp:separator {"align":"wide","style":{"color":{"background":"#bbbbbb"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-text-color has-alpha-channel-opacity has-background is-style-wide" style="background-color:#bbbbbb;color:#bbbbbb"/>
<!-- /wp:separator -->

<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:knd/news {"postsToShow":1,"columns":1,"align":"","titleColor":"#000000","titleHoverColor":"#000000","linkHoverBackground":"#d40b6c0d","thumbnail":false,"category":false,"titleFontSize":"28px","titleFontWeight":"regular","paddingTop":false,"paddingBottom":false} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:knd/news {"postsToShow":4,"columns":2,"align":"","titleColor":"#000000","titleHoverColor":"#000000","linkHoverBackground":"#d40b6c0d","thumbnail":false,"category":false,"titleFontWeight":"regular","paddingTop":false,"paddingBottom":false} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:separator {"align":"wide","style":{"color":{"background":"#bbbbbb"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator alignwide has-text-color has-alpha-channel-opacity has-background is-style-wide" style="background-color:#bbbbbb;color:#bbbbbb"/>
<!-- /wp:separator -->';
	}

	elseif ( 'media-7' === $block_name ) {
		$content = '<!-- wp:knd/news {"layout":"type-3","heading":"","postsToShow":4,"columns":4,"radius":11,"overlayColor":"#d40b6c12","overlayHoverColor":"#d40b6c24","titleColor":"#000000","titleHoverColor":"#d30a6a","linkHoverColor":"#000000","headingLinks":[],"hiddenReload":"Ссылка в шапке","thumbnail":false,"imageOrientation":"square","author":true,"avatar":true,"titleFontWeight":"medium","alignment":"bottom left"} /-->';
	}

	

	return $content;
}
