<?php /**
 * Update Kandisky Theme Module
 **/

if ( ! defined( 'WPINC' ) )
	die();

/*
 * Update theme main page layout
 */
function knd_update_theme() {
	$is_theme_updating = false;

	if(isset($_POST['knd-update-theme'])) {
		$nonce = $_REQUEST['_wpnonce'];
		if(wp_verify_nonce( $nonce, 'knd-update-theme' )) {
			
			$knd_update_may_remove_my_content = isset($_POST['knd-may-remove-my-content']) && $_POST['knd-may-remove-my-content'] === "remove";
			if($knd_update_may_remove_my_content) {
				update_option( 'knd_update_may_remove_my_content', $knd_update_may_remove_my_content );
			}
			else {
				delete_option( 'knd_update_may_remove_my_content' );
			}
				
			$is_theme_updating = true;
			
			$url = admin_url('admin.php?page=update-kandinsky-theme');
			$method = '';
			$fields = array_keys($_POST);
			
			if(false === ($creds = request_filesystem_credentials(esc_url_raw($url), $method, false, false, $fields))) {
				return true;
			}
			
			if( !WP_Filesystem($creds)) {
				request_filesystem_credentials(esc_url_raw($url), $method, true, false, $fields);
			}

			knd_download_theme_archive();
		}
	}

	?>
<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>

<?php if($is_theme_updating): ?>
        <div class="notice notice-success is-dismissible">
          <p><?php esc_html_e( 'Updating Kandisky theme is in progress...', 'knd' ); ?></p>
        </div>
        <div class="knd-updating-theme-steps">
          <p><?php esc_html_e( 'Downloading latest version of Kandisky theme...', 'knd' ); ?></p>
        </div>
<?php else:?>

	<form action="" method="POST" class="knd-confirm-remove-theme">
		<?php
			wp_nonce_field('knd-update-theme');
			?>
            <h2><?php esc_html_e("You are going to update Kandinsky theme. Continue?", 'knd'); ?></h2>
            <p class="knd-remove-my-content-control">
            <input type="checkbox" name="knd-may-remove-my-content" class="knd-may-remove-my-content" value="remove" /> <label><?php esc_html_e("Remove all my content and install Kandinsky demo content instead.", 'knd'); ?></label>
            </p>
            <p>
            <?php
			submit_button(__("Yes, update theme to the latest version", 'knd'), 'primary', 'knd-update-theme', false);
			submit_button(__("Cancel", 'knd'), 'secondary', 'knd-cancel-update-theme', false, array('class' => 'knd-cancel-updated-theme-button'));
		?>
            </p>
	</form>
    
</div>
<?php
	endif;
}

function knd_cancel_update_theme() {
	if(isset($_POST['knd-cancel-update-theme'])) {
		wp_redirect( admin_url( '/index.php' ) );
	}
}
add_action( 'admin_init', 'knd_cancel_update_theme' );

/*
 * Create JS data for update process
 */
add_action( 'admin_enqueue_scripts', 'knd_update_theme_ajax_data', 99 );
function knd_update_theme_ajax_data(){
	
	if(is_admin() || current_user_can('manage_options')) {
		$knd_admin_js_vars = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'home_url' => home_url('/'),
			'theme_update_steps' => knd_theme_update_get_steps(),
			'lang_are_you_sure_may_remove_your_content' => esc_html__('Are you sure you want to delete your content?', 'knd'),
			'lang_doing_knd_unzip_theme_update' => esc_html__('Unzipping theme archive...', 'knd'),
			'lang_doing_knd_update_import_content' => esc_html__('Downloading Kandinsky content...', 'knd'),
			'lang_doing_knd_update_title_and_description' => esc_html__('Upating title and description...', 'knd'),
			'lang_doing_knd_update_theme_settings' => esc_html__('Updating theme settings...', 'knd'),
			'lang_doing_knd_update_theme_content' => esc_html__('Updating theme content...', 'knd'),
			'lang_doing_knd_update_theme_donations' => esc_html__('Updating donations...', 'knd'),
			'lang_doing_knd_update_complete' => esc_html__('Kandinsky theme updated successfully.', 'knd'),
			'lang_success' => esc_html__('Success!', 'knd'),
			'lang_failed' => esc_html__('Failed!', 'knd'),
			'lang_visit_site' => esc_html__('Visit site', 'knd'),
		);
	}

	if(isset($_POST['knd-update-theme'])) {
		$nonce = $_REQUEST['_wpnonce'];
		if(wp_verify_nonce( $nonce, 'knd-update-theme' )) {
			
			$knd_admin_js_vars['update_action_nonce'] = wp_create_nonce('knd-update-action-nonce');
			
		}
	}

	wp_localize_script( 'knd-admin', 'kndAdminUpdateTheme', $knd_admin_js_vars );
}


if( wp_doing_ajax() ){
	add_action( 'wp_ajax_knd_download_theme_update', 'knd_do_update_theme_action' );
}
/*
 * Main Kandisnky update ajax handler
 */
function knd_do_update_theme_action() {
	$possible_functions = knd_theme_update_get_steps();
	$action = isset($_POST['knd-action']) && $_POST['knd-action'] ? $_POST['knd-action'] : 'knd_update_complete';
	
	$json_response = array( 'status' => 'error', 'message' => esc_html__('Unknown update action', 'knd') );
	
	if(in_array($action, $possible_functions)) {
		$nonce = $_REQUEST['knd-update-action-nonce'];
		
		if(wp_verify_nonce( $nonce, 'knd-update-action-nonce' )) {
			
			try {
				$json_response = array( 'status' => 'ok', 'message' => esc_html__('Success', 'knd') );
// 				$json_response = call_user_func($action);
			}
			catch(Exception $ex) {
				error_log("KND_ERROR: $ex");
				$json_response = array( 'status' => 'error', 'message' => esc_html__('Unknown error', 'knd') );
			}
			
		}
		else {
			$json_response = array( 'status' => 'error', 'message' => esc_html__('Security check failed while updating theme', 'knd') );
		}
		
	}
	
	wp_send_json( $json_response );
}

function knd_theme_update_get_steps() {
	return array('knd_download_theme_update', 'knd_unzip_theme_update', 'knd_update_import_content', 'knd_update_title_and_description', 'knd_update_theme_settings', 'knd_update_theme_content', 'knd_update_theme_donations');
}

function knd_get_current_plot_pbd() {
	$plot = knd_get_theme_mod('knd_site_scenario');
	
	$imp = new KND_Import_Remote_Content($plot);
	$imp->import_downloaded_content();
	
	$pdb = KND_Plot_Data_Builder::produce_builder($imp);
	
	return $pdb;
}

function knd_download_theme_archive() {
	global $wp_filesystem;
	$knd_theme_archive_url = KND_DISTR_ARCHIVE_URL;
	
	$upload_dir = wp_upload_dir();
	$filename = trailingslashit($upload_dir['path']).'master.zip';
	
	$downloaded_theme_fpath = download_url($knd_theme_archive_url);
	
	$json_response = array( 'status' => 'error', 'message' => esc_html__('Unknown error', 'knd') );
	
	if(is_wp_error($downloaded_theme_fpath)) {
		error_log($downloaded_theme_fpath->get_error_message());
		$json_response = array( 'status' => 'error', 'message' => esc_html__('Downloading theme file failed!', 'knd') );
	}
	else {
		try {
			unzip_file($downloaded_theme_fpath, get_theme_root());
			$template_dir = get_template_directory();
			
			if(preg_match('/\/master\.zip$/', $knd_theme_archive_url)) {
				if(!preg_match('/\/kandinsky-master$/', $template_dir)) {
					copy_dir(get_theme_root() . '/kandinsky-master', get_template_directory());
					$wp_filesystem->rmdir(get_theme_root() . '/kandinsky-master', true);
				}
			}
			elseif(preg_match('/\/dev\.zip$/', $knd_theme_archive_url)) {
				if(!preg_match('/\/kandinsky-dev$/', $template_dir)) {
					copy_dir(get_theme_root() . '/kandinsky-dev', get_template_directory());
					$wp_filesystem->rmdir(get_theme_root() . '/kandinsky-dev', true);
				}
			}
				
			$json_response = array( 'status' => 'ok' );
		}
		catch(Exception $ex) {
			error_log("KND_ERROR: $ex");
			$json_response = array( 'status' => 'error', 'message' => esc_html__('Saving theme file failed!', 'knd') );
		}
	}
	
	return $json_response;
}

function knd_download_theme_update() {
	$plot = knd_get_theme_mod('knd_site_scenario');
	
	$json_response = array( 'status' => 'ok' );
	
	return $json_response;
}

function knd_unzip_theme_update() {
	$plot = knd_get_theme_mod('knd_site_scenario');
	
	$json_response = array( 'status' => 'ok' );
	return $json_response;
}

function knd_update_import_content() {
	$plot = knd_get_theme_mod('knd_site_scenario');
	$imp = new KND_Import_Remote_Content($plot);
	$imp->import_content();

	$is_may_remove_my_content = get_option( 'knd_update_may_remove_my_content', false );

	$json_response = array( 'status' => 'ok' );
	return $json_response;
}

function knd_update_title_and_description() {
	$pdb = knd_get_current_plot_pbd();
	$is_may_remove_my_content = get_option( 'knd_update_may_remove_my_content', false );
	
	if($is_may_remove_my_content) {
		$pdb->build_title_and_description();
	}
	
	$json_response = array( 'status' => 'ok' );
	return $json_response;
}

function knd_update_theme_settings() {
	$pdb = knd_get_current_plot_pbd();
	$is_may_remove_my_content = get_option( 'knd_update_may_remove_my_content', false );
	
	if($is_may_remove_my_content) {
		$pdb->build_theme_options();
		$pdb->build_general_options();
	}
	
	$json_response = array( 'status' => 'ok' );
	return $json_response;
}

function knd_update_theme_content() {
	$pdb = knd_get_current_plot_pbd();
	$is_may_remove_my_content = get_option( 'knd_update_may_remove_my_content', false );
	
	if($is_may_remove_my_content) {
		$pdb->build_posts();
		$pdb->build_pages();
		$pdb->build_theme_options();
		$pdb->build_menus();
		//$pdb->build_sidebars();
	}
	
	$json_response = array( 'status' => 'ok' );
	return $json_response;
}

function knd_update_theme_donations() {
	knd_get_current_plot_pbd();
	$is_may_remove_my_content = get_option( 'knd_update_may_remove_my_content', false );
	
	if($is_may_remove_my_content) {
		if(is_plugin_active('leyka/leyka.php')) {
			knd_activate_leyka();
		}
	}
	
	$json_response = array( 'status' => 'ok' );
	return $json_response;
}
