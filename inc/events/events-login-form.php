<?php
/**
 * Events Login Form Template
 */

?>
<div class="knd-event__login">
	<div class="knd-event__login-toggle">
		<?php echo sprintf( esc_html__('%sLog in%s if you already have an account with us.','knd'), '<a href="#">', '</a>' ); ?>
	</div>
	<div class="knd-event__login-form">
		<?php wp_login_form( array(
			'redirect'       => site_url( $_SERVER['REQUEST_URI'] ),
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
		) ); ?>
	</div>
</div>


