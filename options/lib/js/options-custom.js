/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */

jQuery(document).ready(function($) {
	
	// Hide checkboxes and convert them to toggle switches
	$('.checkbox-toggle').hide();
	$('.checkbox-toggle').after(function() {
		if ($(this).is(":checked")) {
			return "<a href='#' class='toggle checked' ref='"+$(this).attr("id")+"'></a>";
		} else {
			return "<a href='#' class='toggle' ref='"+$(this).attr("id")+"'></a>";
		}
	});
	
	// When the toggle switch is clicked, check off / de-select the associated checkbox
	$('.toggle').click(function(e) {
		var checkboxID = $(this).attr("ref");
		var checkbox = $('#'+checkboxID);
		
		if (checkbox.is(":checked")) {
			checkbox.removeAttr("checked").change();
		} else {
			checkbox.attr("checked","checked").change();
		}
		$(this).toggleClass("checked");
		e.preventDefault();
	});

	// Fade out the save message
	$('.fade').delay(1000).fadeOut(1000);
	
	// Color Picker
	$('.colorSelector').each(function(){
		var Othis = this; //cache a copy of the this variable for use inside nested function
		var initialColor = $(Othis).next('input').attr('value');
		$(this).ColorPicker({
		color: initialColor,
		onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
		},
		onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
		},
		onChange: function (hsb, hex, rgb) {
		$(Othis).children('div').css('backgroundColor', '#' + hex);
		$(Othis).next('input').attr('value','#' + hex);
	}
	});
	}); //end color picker
	
	
	// Open/Close all tabs
	$('.cc-collapse').show();
	$('ul.cc-child').hide();
	$('#open-all-tabs').click(function(evt) {
		$('ul.cc-child').show();
		evt.preventDefault();	
	});
	$('#close-all-tabs').click(function(evt) {
		$('ul.cc-child').hide();
		evt.preventDefault();	
	});
	
	// Switches option sections
	$('.group').hide();
	
	var activetab = '';
	if (typeof(localStorage) != 'undefined' ) {
		activetab = localStorage.getItem("activetab");
	}
	
	if (activetab != '' && $(activetab).length ) {
		$(activetab).fadeIn(function(){
			cc_height($('.cc-content').height());
		});
	} else {
		$('.group:first').fadeIn(function(){
			cc_height($('.cc-content').height());
		});
	}
	
	$('.group .collapsed').each(function(){
		$(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).hasClass('last')) {
					$(this).removeClass('hidden');
						return false;
					}
				$(this).filter('.hidden').removeClass('hidden');
			});
	});
	
	if (activetab != '' && $(activetab + '-tab').length ) {
		$(activetab + '-tab').parent().addClass('cc-active');
	} else {
		$('.nav-tab-wrapper > li > a:first').parent().addClass('cc-active');
	}
	
	$('.nav-tab-wrapper > li > a').click(function(evt) {
		$('ul.cc-child').hide();
		$(this).siblings('.cc-child').show();
		if ( !$(this).parent().hasClass('cc-active') ) {
			
			$('.nav-tab-wrapper > li > a').parent().removeClass('cc-active');
			$(this).parent().addClass('cc-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem("activetab", $(this).attr('href'));
			}
			$('.group').hide();
			$(clicked_group).fadeIn(function(){
				cc_height($(clicked_group).height());
			});
		}
		evt.preventDefault();
		
		// Editor Height (needs improvement)
		$('.wp-editor-wrap').each(function() {
			var editor_iframe = $(this).find('iframe');
			if ( editor_iframe.height() < 30 ) {
				editor_iframe.css({'height':'auto'});
			}
		});
		
	});
	
	// Height function
	
	function cc_height (height) {
		var height_min = '';
		var total_height = '';
		var content_height = height;
		if(height < 207){
			height_min = 207;
		}
		else {
			height_min = 50;
		}
		total_height = height_min + content_height + 'px';
		
		$('.cc-left-menu').css('height', total_height);
	}
	
	
	// scroll to section
	$('.cc-child > li > a').click(function(evt) {
		var parent_tab = $(this).parent().parent().parent();
		var parent_group = $(this).parent().parent().siblings('a').attr('href');
		
		$(this).parent().parent().show();
		
		// fade in heading area if it is currently not open
		if ( !parent_tab.hasClass('cc-active') ) {
			
			$('.nav-tab-wrapper > li > a').parent().removeClass('cc-active');
			parent_tab.addClass('cc-active').blur();
			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem("activetab", parent_group);
			}
			
			$('.group').hide();
			$(parent_group).show();
		}
		
		var section_group = $(this).attr('href');
		$('html, body').animate({ scrollTop: $(section_group).offset().top - 30 }, 'slow');
		evt.preventDefault();
	});
    					
	$('.group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if ($(this).attr('checked')) {
			$(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else {
			$(this).parent().parent().parent().nextAll().each( 
			function(){
				if ($(this).filter('.last').length) {
					$(this).addClass('hidden');
					return false;		
					}
				$(this).addClass('hidden');
			});
           					
		}
	}
	
	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');		
	});
		
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();
		 		
});

// Section order function
jQuery(function($) {
	var initialize = function(id) {
		var el = $("#" + id);
		function update(base) {
			var hidden = base.find("input[type='hidden']");
			var val = [];
			base.find('.right_list .list_items span').each(function() {
				val.push($(this).data('key'));
			});
			hidden.val(val.join(",")).change();
			el.find('.right_list .action').show();
			el.find('.left_list .action').hide();
		}
		el.find(".left_list .list_items").delegate(".action", "click", function() {
			var item = $(this).closest('.list_item');
			$(this).closest('.section_order').children('.right_list').children('.list_items').append(item);
			update($(this).closest(".section_order"));
		});
		el.find(".right_list .list_items").delegate(".action", "click", function() {
			var item = $(this).closest('.list_item');
			$(this).val('Add');
			$(this).closest('.section_order').children('.left_list').children('.list_items').append(item);
			$(this).hide();
			update($(this).closest(".section_order"));
		});
		el.find(".right_list .list_items").sortable({
			update: function() {
				update($(this).closest(".section_order"));
			},
			connectWith: '#' + id + ' .left_list .list_items'
		});

		el.find(".left_list .list_items").sortable({
			connectWith: '#' + id + ' .right_list .list_items'
		});

		update(el);
	}

	$('.section_order').each(function() {
		initialize($(this).attr('id'));
	});

	$("input[name='ifeature[if_blog_section_order]']").change(function(){
		var show = $(this).val().split(",");
		var map = {
			synapse_blog_slider: "subsection-blogslider",
			synapse_callout_section: "subsection-calloutoptions",
			synapse_portfolio_element: "subsection-portfoliooptions",
			synapse_product_element: "subsection-productoptions",
			synapse_twitterbar_section: "subsection-twtterbaroptions",
			synapse_custom_html_element: "subsection-customhtml",
			synapse_index_carousel_section: "subsection-carouseloptions",
			synapse_blog_nivoslider: "subsection-nivoslider"
		};

		$.each(map, function(key, value) {
			$("#" + value).hide();
			$.each(show, function(i, show_key) {
				if(key == show_key)
					$("#" + value).show();
			});
		});
	}).trigger('change');
	
	$("input[name='ifeature[header_section_order]']").change(function(){
		var show = $(this).val().split(",");
		var map = {
			ifeature_sitename_contact: "section-if_header_contact",
			ifeature_custom_header_element: "section-if_custom_header_element",
			ifeature_banner: "subsection-banneroptions"
		};

		$.each(map, function(key, value) {
			$("#" + value).hide();
			$.each(show, function(i, show_key) {
				if(key == show_key)
					$("#" + value).show();
			});
		});
	}).trigger('change');
});

/* make left menu background same height as container */
jQuery(function($) {
	$('.cc-content').css('height').change(function(){
		alert($this);
		$('.cc-left-menu').css('height', $(this));
	});
});