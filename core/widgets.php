<?php /**
 * Widgets
 **/

if ( ! defined( 'WPINC' ) )
	die();

function knd_custom_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	
	// Most of widgets do not perform well with MDL as for now
	unregister_widget( 'Leyka_Campaign_Card_Widget' );
	unregister_widget( 'Leyka_Campaigns_List_Widget' );
}
//add_action( 'widgets_init', 'knd_custom_widgets', 20 );
