<?php
/**
 * The template for displaying search forms 
 */
$knd_unique_id = wp_unique_id( 'search-form-' );

?>

<form role="search" method="get" class="knd-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

	<label for="<?php echo esc_attr( $knd_unique_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search', 'knd' ); ?></label>

	<input type="search" class="knd-search__input" id="<?php echo esc_attr( $knd_unique_id ); ?>" value="<?php the_search_query(); ?>" name="s" placeholder="<?php esc_attr_e( 'Enter text to search ...', 'knd' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'knd' ); ?>" autocomplete="off" >

	<button class="knd-search__submit" type="submit" aria-label="<?php esc_attr_e( 'Start search', 'knd' ); ?>">
		<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M2 8.5C2 5.191 4.691 2.5 8 2.5C11.309 2.5 14 5.191 14 8.5C14 11.809 11.309 14.5 8 14.5C4.691 14.5 2 11.809 2 8.5ZM17.707 16.793L14.312 13.397C15.365 12.043 16 10.346 16 8.5C16 4.089 12.411 0.5 8 0.5C3.589 0.5 0 4.089 0 8.5C0 12.911 3.589 16.5 8 16.5C9.846 16.5 11.543 15.865 12.897 14.812L16.293 18.207C16.488 18.402 16.744 18.5 17 18.5C17.256 18.5 17.512 18.402 17.707 18.207C18.098 17.816 18.098 17.184 17.707 16.793Z" fill="currentColor"/>
		</svg>
	</button>

</form>