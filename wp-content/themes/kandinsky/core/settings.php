<?php
add_action('admin_menu', 'knd_add_settings_page');

function knd_add_settings_page() {
    add_submenu_page('themes.php', __('Kandinsky settings', 'knd'), __('Kandinsky', 'knd'), 'manage_options', 'knd_admin_settings_page', 'knd_admin_settings_page');
}

function knd_admin_settings_page() {?>

    <h2><?php _e('Test content', 'knd');?></h2>
    <div class="install-test-content" data-nonce="<?php echo wp_create_nonce('install-test-content');?>" data-action="setup_starter_data">
        <a href="#">
            <?php _e('Install test content', 'knd');?>
        </a>
        <img src="<?php echo admin_url().'/images/spinner.gif';?>" style="display: none;"  class="ajax-loader">
        <div class="success" style="display: none;"><?php _e('Test content successfully imported!', 'knd');?></div>
        <div class="failure" style="display: none;"><?php _e('Test content import failed', 'knd');?></div>
    </div>

    <?php /*?>
    <h2><?php _e('Features', 'knd');?></h2>
    <div class="install-test-content">
        <form id="knd-features-form">
            <div>
                <input type="checkbox" id="knd-feature-events" name="features[]" value="events">
                <label for="knd-feature-events"><?php _e('Events', 'knd');?></label>
            </div>
            <div>
                <input type="checkbox" id="knd-feature-donations" name="features[]" value="donations">
                <label for="knd-feature-donations"><?php _e('Donations', 'knd');?></label>
            </div>
            <div>
                <input type="submit" name="knd-features-submit" value="<?php _e('Save', 'knd');?>">
            </div>
        </form>
    </div>

<?php */

}

add_action('tgmpa_register', 'knd_register_required_plugins');

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function knd_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'        => __('Yoast SEO', 'knd'),
            'slug'        => 'wordpress-seo',
            'is_callable' => 'wpseo_init',
//            'required'    => false,
        ),
        array(
            'name'        => __('Leyka', 'knd'),
            'slug'        => 'leyka',
            'is_callable' => 'leyka',
//            'required'    => false,
        ),
        array(
            'name'        => __('Cyr to Lat enhanced', 'knd'),
            'slug'        => 'cyr3lat',
            'is_callable' => 'ctl_sanitize_title',
            'required'    => true,
        ),
        array(
            'name'        => __('Disable Comments', 'knd'),
            'slug'        => 'disable-comments',
            'is_callable' => array('Disable_Comments', 'get_instance'),
            'required'    => true,
        ),
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'knd',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'knd-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                     // Message to output right before the plugins table.

        /*
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'knd' ),
            'menu_title'                      => __( 'Install Plugins', 'knd' ),
            /* translators: %s: plugin name. * /
            'installing'                      => __( 'Installing Plugin: %s', 'knd' ),
            /* translators: %s: plugin name. * /
            'updating'                        => __( 'Updating Plugin: %s', 'knd' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'knd' ),
            'notice_can_install_required'     => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'knd'
            ),
            'notice_can_install_recommended'  => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'knd'
            ),
            'notice_ask_to_update'            => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'knd'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                /* translators: 1: plugin name(s). * /
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'knd'
            ),
            'notice_can_activate_required'    => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'knd'
            ),
            'notice_can_activate_recommended' => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'knd'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'knd'
            ),
            'update_link' 					  => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'knd'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'knd'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'knd' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'knd' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'knd' ),
            /* translators: 1: plugin name. * /
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'knd' ),
            /* translators: 1: plugin name. * /
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'knd' ),
            /* translators: 1: dashboard link. * /
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'knd' ),
            'dismiss'                         => __( 'Dismiss this notice', 'knd' ),
            'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'knd' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'knd' ),

            'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
        */
    );

    tgmpa( $plugins, $config );
}