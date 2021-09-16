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
		$content = '<!-- wp:knd/info {"heading":"' . esc_html__( 'Charitable organization &#34;Line of Color&#34;', 'knd' ) . '","text":"' . esc_html__( 'For more than 10 years we have been helping people with alcoholism, organizing rehabilitation programs and groups', 'knd' ) . '","heading1":"' . esc_html__( 'Who we are?', 'knd' ) . '","heading2":"' . esc_html__( 'What we do?', 'knd' ) . '","heading3":"' . esc_html__( 'Stop drinking?', 'knd' ) . '","text1":"' . esc_html__( 'The charitable organization &#34;Line of Color&#34; helps to overcome alcohol addiction and return to a fulfilling life.', 'knd' ) . '","text2":"' . esc_html__( 'We organize rehabilitation programs, inform and help those who are ready to give up their addiction and return their lives.', 'knd' ) . '","text3":"' . esc_html__( 'Fill out the anonymous form on the website, choose a convenient time for an individual consultation, or sign up for a support group.', 'knd' ) . '","linkText1":"' . esc_html__( 'Learn about our work', 'knd' ) . '","linkText2":"' . esc_html__( 'View projects', 'knd' ) . '","linkText3":"' . esc_html__( 'Get help', 'knd' ) . '","linkUrl1":"' . home_url( '/about/' ) . '","linkUrl2":"' . home_url( '/projects/' ) . '","linkUrl3":"' . home_url( '/gethelp/' ) . '"} /-->
';
	} elseif ( 'cta' === $block_name ) {
		$content = '<!-- wp:knd/cta {"featuredImage":{"title":"cta-image","url":"' . esc_url( get_theme_file_uri( 'assets/images/call-to-action.png' ) ) . '"},"heading":"' . esc_html__( '112 volunteers are helping Line of Color at the moment', 'knd' ) . '","text":"' . esc_html__( 'Join a team of volunteers and consultants in our projects', 'knd' ) . '","buttonUrl":"' . home_url( '/volunteers/' ) . '"} /-->';
	}

	return $content;
}
