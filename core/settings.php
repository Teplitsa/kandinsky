<?php

if ( ! defined( 'WPINC' ) )
	die();

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( isset( $_GET['reset_msg'] ) ) {
	update_option( 'knd_admin_notice_welcome', 0 );
}
if ( isset( $_GET['reset_test_content'] ) ) {
	update_option( 'knd_test_content_installed', 0 );
}

function knd_admin_settings_page() {
    // For now, all Knd settings located in theme customizer
}