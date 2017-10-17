jQuery( document ).ready( function( $ ) {

// allow remove content checkbox behaviour
$('.knd-may-remove-my-content').prop('checked', false);
$('.knd-may-remove-my-content').change(function(e){
    if($(this).prop('checked')) {
        if(!confirm(kndAdminUpdateTheme.lang_are_you_sure_may_remove_your_content)) {
            $(this).prop('checked', false);
        }
    }
});

if(kndAdminUpdateTheme.update_action_nonce) {
    kndDoUpdateThemeAction('knd_download_theme_update');
}

function kndDoUpdateThemeAction(action) {
    
    var actionIndex = kndAdminUpdateTheme.theme_update_steps.indexOf(action);
    var nextAction = kndAdminUpdateTheme.theme_update_steps[actionIndex + 1];
    
    $.ajax({
        type: "POST",
        url: window.kndAdminUpdateTheme.ajax_url,
        data: {
            'action' : 'knd_download_theme_update',
            'knd-action': action,
            'knd-update-action-nonce': kndAdminUpdateTheme.update_action_nonce
        },
    })
    .done(function(json){
        
        if(json.status === 'ok') {
            $('.knd-updating-theme-steps p:last').append(kndGetUpdateResultIcon('ok', kndAdminUpdateTheme.lang_success));
        }
        else {
            if(json.message) {
                $('.knd-updating-theme-steps p:last').append(kndGetUpdateResultIcon('error', json.message));
            }
            else {
                $('.knd-updating-theme-steps p:last').append(kndGetUpdateResultIcon('error', kndAdminUpdateTheme.lang_failed));
            }
        }
        
        if(nextAction) {
            $('.knd-updating-theme-steps').append('<p>'+kndAdminUpdateTheme['lang_doing_' + nextAction]+'</p>');
            kndDoUpdateThemeAction(nextAction);
        }
        else {
            $('.knd-updating-theme-steps').append('<p class="knd-updated-successfully">'+kndAdminUpdateTheme['lang_doing_knd_update_complete']+'</p>');
            $('.knd-updating-theme-steps').append('<p><a href="'+kndAdminUpdateTheme.home_url+'">' + kndAdminUpdateTheme.lang_visit_site + '</a></p>');
        }
    })
    .fail(function(){
    })
    .always(function(){
    });
}

$('#knd-update-theme').click(function(){
//    $(this).prop('disabled', true);
    $(this).parent().prepend('<button disabled="true" class="button knd-spinner-update-button">' + kndGetAdminSpinner() + '</button>');
    $(this).hide();
    return true;
});

} );

function kndGetAdminSpinner() {
    return '<img src="' + kndAdminUpdateTheme['site_url'] + '/wp-admin/images/spinner-2x.gif" />';
}

function kndGetUpdateResultIcon(status, message) {
    var iconClass;
    if(status == 'error') {
        iconClass = 'dashicons-warning';
    }
    else {
        iconClass = 'dashicons-yes';
    }
        
    return '<span class="dashicons '+iconClass+'" title="'+message+'"></span>';
}