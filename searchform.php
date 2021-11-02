<?php
/**
 * The template for displaying search forms 
 */
$knd_unique_id = wp_unique_id( 'search-form-' );

?>
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="<?php echo esc_attr( $knd_unique_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search', 'knd' ); ?></label>
		<?php knd_svg_icon('icon-search');?>

		<input type="search" class="search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="<?php echo esc_attr( $knd_unique_id ); ?>"  placeholder="<?php esc_attr_e('Find', 'knd');?>" autocomplete="off">
	</form>
