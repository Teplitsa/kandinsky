<?php /**
 * Common media settings and utilities
 *
 **/

if ( ! defined( 'WPINC' ) )
	die();
	
/* Register thumbnails */
add_action( 'after_setup_theme', 'knd_thumbnails_sizes', 15 );

function knd_thumbnails_sizes() {

	// Thumbnails:
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 640, 395, true ); // regular thumbnails,
	add_image_size( 'square', 450, 450, true ); // square thumbnail
	add_image_size( 'medium-thumbnail', 790, 488, true ); // poster in widget
	add_image_size( 'landscape-mini', 300, 185, true ); // fixed size for embedding
}

/** Custom image size for medialib **/
add_filter( 'image_size_names_choose', 'knd_medialib_custom_image_sizes' );

function knd_medialib_custom_image_sizes( $sizes ) {
	$addsizes = apply_filters( 
		'knd_medialib_custom_image_sizes', 
		array( 
			'landscape-mini' => esc_html__( 'Landscape thumbnail', 'knd' ), 
			'post-thumbnail' => esc_html__( 'Post thumbnail', 'knd' ), 
			'medium-thumbnail' => esc_html__( 'Fixed for embed', 'knd' ) ) );
	
	return array_merge( $sizes, $addsizes );
}

/* Remove support for attachment pages from drop-downs */
add_action( 'admin_head', 'knd_fix_media_templates' );

function knd_fix_media_templates() {
	?>
<style>
.attachment-display-settings .link-to option[value="post"] {
    display: none;
}
</style>
<?php
}

add_action( 'wp', 'knd_redirect_attachment_page' );

function knd_redirect_attachment_page() {
	if ( is_attachment() ) {
		$p = get_post();
		if ( $p->post_parent > 0 ) {
			$redirect = get_permalink( $p->post_parent );
		} else {
			$redirect = home_url();
		}
		
		wp_redirect( $redirect );
		die();
	}
}