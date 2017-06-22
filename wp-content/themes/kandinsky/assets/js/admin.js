jQuery(document).ready(function($){

    var $test_content_settings = $('.install-test-content');

    $test_content_settings.on('click', 'a', function(e){

        e.preventDefault();

        var $this = $(this),
            $ajax_loader = $test_content_settings.find('.ajax-loader');

        $this.hide();
        $ajax_loader.show();

        /** @todo Uncomment lines below after starter procedure debugging */
        // $.post(ajaxurl, {
        //     nonce: $test_content_settings.data('nonce'),
        //     action: $test_content_settings.data('action')
        // }, function(response){
        //
        //     $ajax_loader.hide();
        //     $this.removeAttr('disabled').hide();
        //
        //     response = $.parseJSON(response);
        //     if(typeof response.status != 'undefined' && response.status == 'ok') {
        //         $test_content_settings.find('.success').show();
        //     }
        //
        // });

        /** @todo Remove lines below after starter procedure debugging */
        $ajax_loader.hide();
        $test_content_settings.find('.success').show();

    });

});