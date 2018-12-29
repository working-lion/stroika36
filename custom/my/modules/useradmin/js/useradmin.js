/**
 * Панель быстрого редактирования, JS-сценарий
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

var useradmin = {
	is_edit : false,
	is_toggle: 0,
	top: false,
	store: false,
	init: function(){
		useradmin.store = window.sessionStorage;

		try {
			useradmin.is_edit = parseInt(useradmin.store.getItem('useradmin_is_edit'), 10);
			useradmin.is_toggle = parseInt(useradmin.store.getItem('useradmin_is_toggle'), 10);
		} catch(e) {}

		useradmin.top = $("*").filter(function() {
			return $(this).css("position") === 'fixed' && $(this).css("top") === '0px';
		});

 		$("body").css("padding-top", "52px");
		if(useradmin.top.length)
		{
			useradmin.top.css("top", "52px");
		}

	$(".header__left, .header__top").css("top", "52px");
	//$("body").css("padding-top", "112px");
//
//  $(window).scroll(function(){
// 	var h=$(window).scrollTop();
// if(h<1)
// {
// 	$("body").removeClass("fixed")
// 	 $("body").css("padding-top", "52px");
// 	$("body .header .header_top").css("top", "52px");
// }
// else{
// 	    $("body").addClass("fixed");
// 		$("body.fixed .header .header_top").css("top", "52px");
// 		$("body.fixed").css("padding-top", "212px");
// 	};

//});


		$.ajax({
			url:window.location.href,
			type:"POST",
			dataType:"html",
			data:{ module: 'useradmin'},
			success:(function (response) {
				$("body").prepend(response);
				if (useradmin.is_toggle == 1)
				{
					useradmin.toggle();
				}
				if(useradmin.is_edit == 1)
				{
					useradmin.show_panel();
				}
			})
		});

		$(document).on('mouseover', ".useradmin_contener", function () {
			if (useradmin.is_edit == 1)
			{
				$(this).addClass("useradmin_active");
				return false;
			}
		});
		$(document).on('mouseout', ".useradmin_contener", function () {
			if (useradmin.is_edit == 1)
			{
				$(this).removeClass("useradmin_active");
				return false;
			}
		});
		$(document).on('click', ".useradmin_contener", function () {
			if (useradmin.is_edit == 1)
			{
				$.prettyPhoto.open($(this).attr('href'));
				return false;
			}
		});

		$(document).on('click', '.diafan-admin-toggle', function(){
			if (useradmin.is_toggle == 1) {
				useradmin.is_toggle = 0;
			}
			else
			{
				useradmin.is_toggle = 1;
			}
			useradmin.store.setItem('useradmin_is_toggle', useradmin.is_toggle);
			useradmin.toggle();
		});

		$(document).on('mouseover', '.diafan-admin-link', function() {
			$(this).find('.diafan-admin-popup').stop().fadeIn('fast');
		});
		$(document).on('mouseout', '.diafan-admin-link', function() {

			$(this).find('.diafan-admin-popup').stop().fadeOut('fast');
		});

		$(document).on('click', ".useradmin_panel .go_edit", useradmin.edit);
	},
	edit: function(){
		if (useradmin.is_edit)
		{
			useradmin.is_edit = 0;
			$(".useradmin_panel .diafan-admin-link_toggle").removeClass("diafan-admin-toggle-blink");
			$(".useradmin_meta").hide();
			$(".fa-toggle-off").show();
			$(".fa-toggle-on").hide();
		}
		else
		{
			useradmin.is_edit = 1;
			useradmin.show_panel();
		}
		useradmin.store.setItem('useradmin_is_edit', useradmin.is_edit);
		return false;
	},

	show_panel: function(){
		$(".useradmin_panel .diafan-admin-link_toggle").addClass("diafan-admin-toggle-blink");
		$(".useradmin_meta").show();
		$(".fa-toggle-off").hide();
		$(".fa-toggle-on").show();
		$(".useradmin_meta").html(
			'<table><tr><td class="useradmin_meta_first_td"><span class="useradmin_contener" href="'+$("meta[name=useradmin_title]").attr("content")+'">title:</span></td><td><span class="useradmin_contener" href="'+$("meta[name=useradmin_title]").attr("content")+'">'+$("title").text()+'</span></td></tr>'
			+
			'<tr><td class="useradmin_meta_first_td"><span class="useradmin_contener" href="'+$("meta[name=useradmin_keywords]").attr("content")+'">keywords:</span></td><td><span class="useradmin_contener" href="'+$("meta[name=useradmin_keywords]").attr("content")+'">'+$("meta[name=keywords]").attr("content")+'</span></td></tr>'
			+
			'<tr><td class="useradmin_meta_first_td"><span class="useradmin_contener" href="'+$("meta[name=useradmin_description]").attr("content")+'">description:</span></td><td><span class="useradmin_contener" href="'+$("meta[name=useradmin_description]").attr("content")+'">'+$("meta[name=description]").attr("content")+'</span></td></tr></table>'
		);
		/* $("body").css("padding-top", "52px"); */
	},

	toggle: function(){
		if (useradmin.is_toggle != 1) {
			//$("body").css("padding-top", "112px");
			$(".header__left, .header__top").css("top", "52px");

			if(useradmin.top.length)
			{
				useradmin.top.css("top", "52px");
				$(".header__left, .header__top").css("top", "52px");
		        //$("body").css("padding-top", "112px");

			}
			$("body").css("padding-bottom", "0px");
		}
		else
		{
			//$("body").css("padding-top", "112px");
			$(".header__left, .header__top").css("top", "0");
			if(useradmin.top.length)
			{
				useradmin.top.css("top", "0px");

			}

			$("body").css("padding-bottom", "52px");
		}
		$('.diafan-admin-panel').toggleClass('diafan-admin-panel_bottom');

	}
}

useradmin.init();
