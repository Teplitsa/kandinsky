jQuery( document ).ready( function( $ ) {

// allow remove content checkbox behaviour
$('.knd-may-remove-my-content').prop('checked', false);
$('.knd-may-remove-my-content').change(function(e){
    if($(this).prop('checked')) {
        if(!confirm(knd_admin_update_theme.lang_are_you_sure_may_remove_your_content)) {
            $(this).prop('checked', false);
        }
    }
});

if(knd_admin_update_theme.update_action_nonce) {
    knd_do_update_theme_action('knd_download_theme_update');
}

function knd_do_update_theme_action(action) {
    
    var action_index = knd_admin_update_theme.theme_update_steps.indexOf(action);
    var next_action = knd_admin_update_theme.theme_update_steps[action_index + 1];
    
    $.ajax({
        type: "POST",
        url: window.knd_admin_update_theme.ajax_url,
        data: {
            'action' : 'knd_download_theme_update',
            'knd-action': action,
            'knd-update-action-nonce': knd_admin_update_theme.update_action_nonce
        },
    })
    .done(function(json){
        
        if(json.status == 'ok') {
            $('.knd-updating-theme-steps p:last').append('<p>' + knd_admin_update_theme.lang_success + '</p>');
        }
        else {
            if(json.message) {
                $('.knd-updating-theme-steps').append('<p>' + json.message + '</p>');
            }
            else {
                $('.knd-updating-theme-steps p:last').append('<p>' + knd_admin_update_theme.lang_failed + '</p>');
            }
        }
        
        if(next_action) {
            $('.knd-updating-theme-steps').append('<p>'+knd_admin_update_theme['lang_doing_' + next_action]+'</p>');
            knd_do_update_theme_action(next_action);
        }
        else {
            $('.knd-updating-theme-steps').append('<p class="knd-updated-successfully">'+knd_admin_update_theme['lang_doing_knd_update_complete']+'</p>');
            $('.knd-updating-theme-steps').append('<p><a href="'+knd_admin_update_theme.home_url+'">' + knd_admin_update_theme.lang_visit_site + '</a></p>');
        }
    })
    .fail(function(){
    })
    .always(function(){
    });
}

} );
