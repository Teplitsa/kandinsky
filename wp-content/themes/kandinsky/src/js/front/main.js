/* Scripts */
jQuery(document).ready(function($){
	
	
	/** Has js **/
	$('html').removeClass('no-js').addClass('js');
	
    /** Window width **/
	var windowWidth = $('#top').width(),
		$site_header = $('#site_header'),
		breakPointSmall = 480, //small screens break point
		breakPointMedium = 767; //medium screen break point
	
	
	/** Resize event **/
	$(window).resize(function(){
		var winW = $('#top').width();
		
		if (winW < breakPointMedium && $site_header.hasClass('newsletter-open')) {
			$site_header.removeClass('newsletter-open');
		}
	});	
	
	/** == Header states == **/
	
	/** Drawer **/
	$('#trigger_menu').on('click', function(e){
				
		if ($site_header.hasClass('newsletter-open')) { //close newsletter if any
			$site_header.removeClass('newsletter-open');
		}
		
		$site_header.addClass('menu-open');
		
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();
		
	});
	
	$('#trigger_menu_close').on('click', function(e){
		
		$site_header.removeClass('menu-open');
		
		e.stopImmediatePropagation();
		e.stopPropagation();
		e.preventDefault();		
	});
	
	/** Submenu toggle  **/
	$('.submenu-trigger').on('click', function(e){
		
		var li = $(this).parents('.menu-item-has-children');
		if (li.hasClass('open')) {
			li.find('.sub-menu').slideUp(300, function(){				
				li.removeClass('open');
				$(this).removeAttr('style');
			});
		}
		else {		
			
			li.find('.sub-menu').slideDown(300, function(){				
				li.addClass('open');
				$(this).removeAttr('style');
			});
		}
	});
	
	/** Newsletter **/
	$('#trigger_newsletter').on('click', function(e){
				
		if ($('body').hasClass('slug-subscribe')){			
			return false;
		}
				
		var winW = $('#top').width();
				
		if (winW > breakPointMedium && !$site_header.hasClass('newsletter-open')) {
			e.preventDefault();
			e.stopPropagation();
			
			$site_header.find('#newsletter_panel').slideDown(150, function(){				
				$site_header.addClass('newsletter-open').find('.rdc-textfield__input').focus();
				$(this).removeAttr('style');
			});			
		}
		else if($site_header.hasClass('newsletter-open')) {
			e.preventDefault();
			e.stopPropagation();
			
			$site_header.find('#newsletter_panel').slideUp(150, function(){				
				$site_header.removeClass('newsletter-open');
				$(this).removeAttr('style');
			});
		}
	});
	
	//no validate no autocomplete
	$('.novalidate').attr('novalidate', 'novalidate').find('.frm_form_field input').attr('autocomplete', 'off');
	
	//open panel after submit
	if (!$('body').hasClass('slug-subscribe') && $site_header.find('#newsletter_panel').find('.frm_message, .frm_error_style').length > 0) {
		$site_header.addClass('newsletter-open');
	}
	
	
	
	/** Close by key and click **/
	$(document).on('click', function(e){
		
		var $etarget = $(e.target);
		
				
		if ($site_header.hasClass('menu-open')) {
			if(!$etarget.is('#site_nav, #trigger_menu') && !$etarget.closest('#site_nav, #trigger_menu').length)
				$site_header.removeClass('menu-open');
		}
		else if ($site_header.hasClass('newsletter-open')) {
			if(!$etarget.is('#newsletter_panel, #trigger_newsletter') && !$etarget.closest('#newsletter_panel, #trigger_newsletter').length)
				$site_header.removeClass('newsletter-open');
		}
		
	})
	.on('keyup', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($site_header.hasClass('newsletter-open')) {
				$site_header.removeClass('newsletter-open');
			}
		}
	}).on('keydown', function(e){ //close search on by ESC
		if(e.keyCode == 27){
			//hide menu on newsletter
			if ($site_header.hasClass('menu-open')) {
				$site_header.removeClass('menu-open');
			}
			else if ($site_header.hasClass('newsletter-open')) {
				$site_header.removeClass('newsletter-open');
			}
		}
	});
	
	// Search forcus on search page 
	function rdc_search_focus_position(SearchInput) {
		if (SearchInput.length > 0) {
			var strLength= SearchInput.val().length * 2;
		
			SearchInput.focus();
			SearchInput[0].setSelectionRange(strLength, strLength); //this put cursor in last position
		}
	}
	
	rdc_search_focus_position($('#sr_form').find('.search-field'));
	
	
	/** Sticky elements **/
	var position = $(window).scrollTop(), //store intitial scroll position
		scrollTopLimit = ($('body').hasClass('adminbar')) ? 62+32 + 280 : 62 + 280,
		fixedTopPosition = ($('body').hasClass('adminbar')) ? 95 + 32 + 280 : 95 + 280;
		
	
	$(window).scroll(function () {
		var scroll = $(window).scrollTop(),
			winW = $('#top').width();
		
		//no scroll when menu is open
		if ($site_header.hasClass('menu-open')) {
			$(window).scrollTop(position);			
			return;
		}		
		
		//scroll tolerance 3px and ignore out of boundaries scroll
		if((Math.abs(scroll-position) < 3) || rdc_scroll_outOfBounds(scroll))
			return true;
		
		//stick header
		if (scroll < position) { //upword
			$site_header.removeClass('invisible').addClass('fixed-header');
		}
		else if(scroll >= scrollTopLimit) {
			$site_header.removeClass('fixed-header').addClass('invisible');
		}
		else {
			$site_header.removeClass('fixed-header').removeClass('invisible');
		}
		
		//sticky sharing
		if (winW >= breakPointMedium && $('#rdc_sharing').length > 0) {
			stickInParent('#rdc_sharing .social-likes-wrapper', '#rdc_sharing', position, fixedTopPosition);
		}
		
		//sticky sidebar
		if (winW >= breakPointMedium && $('#rdc_sidebar').length > 0) {
			stickInParent('#rdc_sidebar .related-widget', '#rdc_sidebar', position, fixedTopPosition);
		}
		
		position = scroll; //upd scroll position
		return true;
	});
	
	//stick element on scroll
	function stickInParent(el, el_parent, el_position, el_fixedTopPosition) {
		var scroll = $(window).scrollTop(),
			$_el = $(el),
			$_el_parent = $(el_parent),
			topPos = $_el_parent.offset().top,
			height = $_el_parent.outerHeight();	
		
		
		if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //stick on bottom
			if (scroll > el_position) //scroll down
				$_el.addClass('fixed-bottom').removeClass('fixed-top');
		}
		else if (scroll > ((height + topPos) - $_el.outerHeight() - el_fixedTopPosition)) { //unstick on bottom
			if (scroll < el_position)
				$_el.removeClass('fixed-bottom').addClass('fixed-top');						
		}
		else if (scroll > topPos - el_fixedTopPosition) { //stick on top
			$_el.removeClass('fixed-bottom').addClass('fixed-top');						
		}
		else {
			$_el.removeClass('fixed-bottom').removeClass('fixed-top'); //normal position		
		}
	}
	
	
	//determines if the scroll position is outside of document boundaries
	function rdc_scroll_outOfBounds(scroll) { 
		var	documentH = $(document).height(),
			winH = $(window).height();		
		
		if (scroll < 0 || scroll > (documentH+winH)) 
			return true;
		
		return false;
	}
	
	
	/** == Responsive media == **/
    var resize_embed_media = function(){

        $('iframe, embed, object').each(function(){

            var $iframe = $(this),
                $parent = $iframe.parent(),
                do_resize = false;
				
            if($parent.hasClass('embed-content')){
                do_resize = true;            
            }			
            else {                
                
                $parent = $iframe.parents('.entry-content, .player');
                if($parent.length)
                    do_resize = true;				
            }
			
            if(do_resize) {
                var change_ratio = $parent.width()/$iframe.attr('width');
                $iframe.width(change_ratio*$iframe.attr('width'));
                $iframe.height(change_ratio*$iframe.attr('height'));
            }
        });
    };
	
    resize_embed_media(); // Initial page rendering
    $(window).resize(function(){		
		resize_embed_media();	
	});	
	
	
	/** Leyka custom modal **/
	var leykaTopPad = (windowWidth > 940) ? 120 : 66;
	
	$('#leyka-agree-text').easyModal({		
		hasVariableWidth : true,
		top : leykaTopPad,
		//transitionIn: 'animated zoomIn',
		//transitionOut: 'animated zoomOut',
		onClose : function(){  }
	});
	
	$('body').on('click','.leyka-custom-confirmation-trigger', function(e){

		$('#leyka-agree-text').trigger('openModal');			
		e.preventDefault();
	});
	
	$('body').on('click', '.leyka-modal-close', function(e){
		
		$('#leyka-agree-text').trigger('closeModal');
	});
	
	
	
	/* Center logos  */
	function logo_vertical_center() {
				
		$('.logo-gallery').each(function(){
			
			var logoH = $(this).find('.logo').eq(0).parents('.bit').height() - 3;
			console.log(logoH);
			
			$(this).find('.logo-frame').find('span').css({'line-height' : logoH + 'px'})
		});		
	}

	imagesLoaded('#site_content', function(){
		logo_vertical_center();
	});

	$(window).resize(function(){
		logo_vertical_center();
	});
	
	
	/* Scroll */
	$('.local-scroll, .inpage-menu a').on('click', function(e){
		e.preventDefault();
		
		var full_url = $(this).attr('href'),
			trgt = full_url.split("#")[1],
			target = $("#"+trgt).offset();
					
		if (target.top) {			
			$('html, body').animate({scrollTop:target.top - 50}, 900);
		}
		
	});
	
	/** Store **/
	$(document).on('click', '.tpl-storeitem', function(e){
		
		var $_item = $(this),
			$_target_data = $_item.parents('.panel-widget-style').attr('class'),
			$_target_raw = $_target_data.split(' ');
			scroll_target = $('.frm_form_widget').eq(0).offset();
			
		if ($_target_raw[0] && $_target_raw[0].length > 0) {
			//find and check checkbox
			$('#'+ $_target_raw[0]).prop( "checked", true );
			
			//scroll
			$('html, body').animate({scrollTop:scroll_target.top - 50}, 900);
		}
		$('.store_form').delay(500).queue(function (form) {
      $('.store_bubble').hide();
      $(this).closest('.panel-grid').css('position','relative').append('<div class="store_bubble_left animated">Шаг 2. Заполните поля формы</div>');
      $('.store_bubble_left').addClass("fadeIn");
      form();
      $(document).on("click",function(e) {
          $('.store_bubble_left').addClass('fadeOut');
      });
    }).one();
		console.log($_target_raw[0]);
	});
	
  // Showing bubble after page load
  $(document).find(".store_title").delay(1000).queue(function (next) {
    $(this).append('<div class="store_bubble animated">Шаг 1. Выберите Продукт</div>');
    $('.store_bubble').addClass("fadeIn");
    next();
    $(document).on("click",function(e) {
        $('.store_bubble').addClass('fadeOut');
    });
  });
}); //jQuery
