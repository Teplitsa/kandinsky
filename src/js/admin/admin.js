jQuery( document ).ready( function( $ ) {

	const { __, _x, _n, _nx } = wp.i18n;

	// allow remove content checkbox behaviour
	$('.knd-may-remove-my-content').prop('checked', false);
	$('.knd-may-remove-my-content').change(function(e){
		if($(this).prop('checked')) {
			if(!confirm(kndAdminUpdateTheme.lang_are_you_sure_may_remove_your_content)) {
				$(this).prop('checked', false);
			}
		}
	});

	// if(kndAdminUpdateTheme.update_action_nonce) {
	// 	kndDoUpdateThemeAction('knd_download_theme_update');
	// }

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

	/**
	 * Disable kirki notice on click dismiss
	 */
	$('.knd-kirki-notice-dismiss').on('click', function(e){
		e.preventDefault();
		var $el = $( this ).parents('.knd-kirki-notice');
		$el.fadeTo( 100, 0, function() {
			$el.slideUp( 100, function() {
				$el.remove();
			});
		});

		var data = {
			action: 'knd_dismiss_notice',
			nonce: _knd.nonce,
		};

		$.post( _knd.ajaxurl, data, function( response ) {
			// do nothing.
		});

	});

	$( document ).on( 'wp-theme-update-success', function( event, response ) {
		$('.knd-update-notice').fadeOut();
	} );

	/** Events */
	$(document).on('click', '.knd-event-remove-item', function(e){
		e.preventDefault();
		$(this).parents('.knd-event-admin-schedule-item').remove();
	});

	$(document).on('click','.knd-event-remove-group', function(e){
		e.preventDefault();
		$(this).parents('.knd-event-admin-schedule-group').remove();
	});

	$(document).on('click','.knd-event-add-time', function(e){
		e.preventDefault();

		var itemKey = 0;
		var itemId = 0;

		var scheduleGroup = $(this).parents('.knd-event-admin-schedule-group');

		var scheduleList = scheduleGroup.find( '.knd-event-admin-schedule-list' );

		var itemKey = scheduleGroup.prevAll().length;
		var itemId = scheduleList.find( '.knd-event-admin-schedule-item').length;

		var scheduleItemHtml = '<div class="knd-event-admin-schedule-item">' +
		'<div class="knd-event-admin-schedule-times">' +
		'<input type="text" name="_schedule[' + itemKey + '][list][' + itemId + '][hour_start]" class="em-time-input ui-em_timepicker-input" maxlength="8" size="8" value="0:00" autocomplete="off">' +
		'<input type="text" name="_schedule[' + itemKey + '][list][' + itemId + '][hour_end]" class="em-time-input ui-em_timepicker-input" maxlength="8" size="8" value="0:00" autocomplete="off">' +
		'<a href="#" class="button button-link button-link-delete button-small knd-event-remove-item">' + __( 'Remove time', 'knd' )  + '</a>' +
				'</div>' +
			'<textarea class="large-text" name="_schedule[' + itemKey + '][list][' + itemId + '][desc]" cols="2"></textarea>' +
		'</div>';

		scheduleList.append( scheduleItemHtml );
		em_setup_timepicker('body');

	});

	$(document).on('click','.knd-event-add-schedule', function(e){
		e.preventDefault();

		var itemKey = $('.knd-event-admin-schedule-group').length;

		var scheduleItemHtml = '<div class="knd-event-admin-schedule-group">' +
			'<div class="knd-event-admin-schedule-name"><input type="text" name="_schedule[' + itemKey + '][title]" value="" class="regular-text"><a href="#" class="button button-link button-link-delete knd-event-remove-group">' + __( 'Delete schedule', 'knd' ) + '</a></div>' +
				'<div class="knd-event-admin-schedule-list">' + 
				'</div>' +
				'<a href="#" class="button button-secondary knd-event-add-time">' + __( 'Add time', 'knd' )  + '</a>' +
			'</div>';

		$('.knd-event-admin-schedule').append( scheduleItemHtml );
	});

	$(document).on('click', '.knd-booking-fields-remove', function(e){
		e.preventDefault();
		$(this).parents('.dbem-kookings-fields-group').remove();
		var fieldsGroup = $('.dbem-kookings-fields .dbem-kookings-fields-group');
		fieldsGroup.each(function( index ) {
			$(this).find('.bookings-custom-field-order').val( index ).attr('name','dbem_bookings_custom_fields[' + index + '][order]');
			$(this).find('.bookings-custom-field-label').attr('name','dbem_bookings_custom_fields[' + index + '][label]');
			$(this).find('.bookings-custom-field-slug').attr('name','dbem_bookings_custom_fields[' + index + '][slug]');
		});
	});

	$(document).on('click','.knd-booking-add-field', function(e){
		e.preventDefault();

		var itemKey = $('.dbem-kookings-fields .dbem-kookings-fields-group').length;

		var fieldHtml = '<div class="dbem-kookings-fields-group">' +
			'<div class="drag-icons-group"><i class="dashicons dashicons-ellipsis"></i><i class="dashicons dashicons-ellipsis"></i></div>' +
			'<input name="dbem_bookings_custom_fields[' + itemKey + '][order]" class="bookings-custom-field-order" type="hidden" value="' + itemKey + '">' +
			'<input name="dbem_bookings_custom_fields[' + itemKey + '][label]" class="bookings-custom-field-label"  type="text" value="" placeholder="' + __( 'Label', 'knd' ) + '">' +
			'<input name="dbem_bookings_custom_fields[' + itemKey + '][slug]" class="bookings-custom-field-slug" type="text" value="" placeholder="' + __( 'slug', 'knd' ) + '">' +
			'<a href="#" class="button button-link button-link-delete knd-booking-fields-remove">' + __( 'Delete', 'knd' ) + '</a>' + 
		'</div>';

		$('.dbem-kookings-fields').append( fieldHtml );
	});

	/**
	 * Sortable
	 */
	 $('.dbem-kookings-fields').sortable({
		axis: 'y',
		cursor: 'move',
		placeholder: 'ui-state-highlight',
		update: function( event, ui ) {
			var bookingFields = $('.dbem-kookings-fields .dbem-kookings-fields-group');
			bookingFields.each(function( index ) {
				$(this).find('.bookings-custom-field-order').val( index ).attr('name','dbem_bookings_custom_fields[' + index + '][order]');
				$(this).find('.bookings-custom-field-label').attr('name','dbem_bookings_custom_fields[' + index + '][label]');
				$(this).find('.bookings-custom-field-slug').attr('name','dbem_bookings_custom_fields[' + index + '][slug]');
			});
		}
	});


	//dbem-kookings-fields-group


} );

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
