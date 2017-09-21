
var EnvatoWizard = (function($){

    var t,
        callbacks = {
            install_plugins: function(btn){
                var plugins = new PluginManager();
                plugins.init(btn);
            },
            install_content: function(btn){
                var content = new ContentManager();
                content.init(btn);
            }
        };

    function window_loaded(){

        $('.wizard-error-support-text a').on('click', function(e){ // Follow the support link manually
            window.location = $(this).attr('href');
        });

        // init button clicks:
        $('.button-next').on( 'click', function(e) {

            var loading_button = dtbaker_loading_button(this);
            if( !loading_button ) {
                return false;
            }
            if($(this).data('callback') && typeof callbacks[$(this).data('callback')] != 'undefined') {

                callbacks[$(this).data('callback')](this);
                return false;

            } else {

                loading_content();
                return true;

            }
        });
        $('.button-upload').on('click', function(e){

            e.preventDefault();
            renderMediaUploader();

        });
        $('.theme-presets a').on('click', function(e){

            e.preventDefault();

            $(this).parents('ul').first().find('.current').removeClass('current');
            $(this).parents('li').first().addClass('current');

            $('#new_scenario_id').val($(this).data('scenario-id'));

            return false;

        });
    }

    function loading_content() {
        $('.envato-setup-content').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    }

    function PluginManager() {

        var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';

        function ajax_callback(response) {
            if(typeof response == 'object' && typeof response.message != 'undefined'){

                $current_node.find('span').text(response.message);
                if(typeof response.url != 'undefined'){
                    // we have an ajax url action to perform.

                    if(response.hash == current_item_hash){
                        $current_node.find('span').text("failed");
                        find_next();
                    } else {
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, function(response2) {
                            process_current();
                            $current_node.find('span').text(response.message + envato_setup_params.verify_text);
                        }).fail(ajax_callback);
                    }

                } else if(typeof response.done != 'undefined') { // finished processing this plugin, move onto next
                    find_next();
                } else { // error processing this plugin
                    find_next();
                }

            } else { // error - try again with next plugin
                $current_node.find('span').text('ajax error');
                find_next();
            }
        }
        function process_current(){
            if(current_item){
                // query our ajax handler to get the ajax to send to TGM
                // if we don't get a reply we can assume everything worked and continue onto the next one.
                jQuery.post(envato_setup_params.ajaxurl, {
                    action: 'knd_wizard_setup_plugins',
                    wpnonce: envato_setup_params.wpnonce,
                    slug: current_item
                }, ajax_callback).fail(ajax_callback);
            }
        }
        function find_next(){
            var do_next = false;
            if($current_node) {
                if( !$current_node.data('done_item') ) {
                    items_completed++;
                    $current_node.data('done_item', 1);
                }
                $current_node.find('.spinner').css('visibility','hidden');
            }
            var $li = $('.envato-wizard-plugins li,.envato-wizard-plugins-recommended li:has(.plugin-accepted:checked)');
            $li.each(function(){
                if(current_item == '' || do_next){
                    current_item = $(this).data('slug');
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                }else if($(this).data('slug') == current_item){
                    do_next = true;
                }
            });
            if(items_completed >= $li.length){
                complete();
            }
        }
        
        return {
            init: function(btn){
                $('.envato-wizard-plugins').addClass('installing');
                complete = function(){
                    loading_content();
                    window.location.href = btn.href;
                };
                find_next();
            }
        }
    }

    function ContentManager(){

        var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';

        function ajax_callback(response) {
            if(typeof response == 'object' && typeof response.message != 'undefined') {

                $current_node.find('span').text(response.message);
                if(typeof response.url != 'undefined') {
                    if(response.hash == current_item_hash) {

                        $current_node.find('span').text("failed");
                        find_next();

                    } else {

                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback);

                    }
                } else if(typeof response.done != 'undefined') {
                    find_next();
                } else { // error processing
                    find_next();
                }

            } else {

                $current_node.find('span').text("ajax error");
                find_next();

            }
        }

        function process_current() {
            if(current_item) {

                var $check = $current_node.find('input:checkbox');
                if($check.is(':checked')) {
                    jQuery.post(envato_setup_params.ajaxurl, {
                        action: 'knd_wizard_setup_content',
                        wpnonce: envato_setup_params.wpnonce,
                        content: current_item
                    }, ajax_callback).fail(ajax_callback);
                } else {

                    $current_node.find('span').text(envato_setup_params.text_processing);
                    setTimeout(find_next,300);

                }

            }
        }

        function find_next() {
            var do_next = false;
            if($current_node){
                if(!$current_node.data('done_item')){
                    items_completed++;
                    $current_node.data('done_item',1);
                }
                $current_node.find('.spinner').css('visibility','hidden');
            }
            var $items = $('tr.envato_default_content');
            // var $enabled_items = $('tr.envato_default_content input:checked');
            $items.each(function(){
                if(current_item == '' || do_next) {
                    current_item = $(this).data('content');
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data('content') == current_item) {
                    do_next = true;
                }
            });
            if(items_completed >= $items.length){
                complete();
            }
        }

        return {
            init: function(btn){
                $('.envato-setup-pages').addClass('installing').find('input').prop('disabled', true);
                complete = function(){
                    loading_content();
                    window.location.href=btn.href;
                };
                find_next();
            }
        }
    }

    /**
     * Callback function for the 'click' event of the 'Set Footer Image'
     * anchor in its meta box.
     *
     * Displays the media uploader for selecting an image.
     *
     * @since 0.1.0
     */
    function renderMediaUploader() {
        'use strict';

        var file_frame, attachment;

        if ( undefined !== file_frame ) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Upload Logo',//jQuery( this ).data( 'uploader_title' ),
            button: {
                text: 'Select Logo' //jQuery( this ).data( 'uploader_button_text' )
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            jQuery('.site-logo-img').attr('src', attachment.url);
            jQuery('#new_logo_id').val(attachment.id);
            // Do something with attachment.id and/or attachment.url here
        });
        // Now display the actual file_frame
        file_frame.open();

    }

    function dtbaker_loading_button(btn){

        var $button = jQuery(btn);
        if($button.data('done-loading') == 'yes') {
            return false;
        }

        var existing_text = $button.text(),
            existing_width = $button.outerWidth(),
            loading_text = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄',
            completed = false;

        $button.css('width', existing_width).addClass('dtbaker_loading_button_current');

        var _modifier = $button.is('input') || $button.is('button') ? 'val' : 'text';
        $button[_modifier](loading_text);
        //$button.attr('disabled',true);
        $button.data('done-loading','yes');

        var anim_index = [0,1,2];

        // animate the text indent
        function moo() {
            if (completed)return;
            var current_text = '';
            // increase each index up to the loading length
            for(var i = 0; i < anim_index.length; i++){
                anim_index[i] = anim_index[i]+1;
                if(anim_index[i] >= loading_text.length)anim_index[i] = 0;
                current_text += loading_text.charAt(anim_index[i]);
            }
            $button[_modifier](current_text);
            setTimeout(function(){ moo();},60);
        }

        moo();

        return {
            done: function(){
                completed = true;
                $button[_modifier](existing_text);
                $button.removeClass('dtbaker_loading_button_current');
                $button.attr('disabled',false);
            }
        }

    }

    return {
        init: function(){
            t = this;
            $(window_loaded);
        },
        callback: function(func){
            console.log(func);
            console.log(this);
        }
    }

})(jQuery);

EnvatoWizard.init();