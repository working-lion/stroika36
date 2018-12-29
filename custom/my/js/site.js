$(document).on('submit', 'form.ajax', function () { return diafan_ajax.init(this); });

function error_position(k, form) {
	var input = $("input[name=" + k + "], textarea[name=" + k + "], select[name=" + k + "]",form),
		error = $(".error_" + k,form);

	if (error.css("position") !== "absolute")
		return;

	var off = input.offset();
	off.top += input.outerHeight();
	off.left += 5;
	error.offset(off);
}

var diafan_ajax = {
	before: {},
	success: {},
	tag : false,
	init: function(form) {
		this.tag = $("input[name=module]", form).val() + "_" + $("input[name=action]", form).val();

		if(this.before[this.tag] && this.before[this.tag](form) === false)
		{
			return false;
		}
		$('input:submit', form).attr('disabled', 'disabled');
		$('.errors').hide();
		$('.error_input').removeClass('error_input');
		if (! $('input[name=ajax]', form).length)
		{
				$(form).append('<input type="hidden" name="ajax" value="1">');
		}
		$(form).ajaxSubmit({
			success: function (result, statusText, xhr, form) { diafan_ajax.result(form, result);}
		});
		$(":text, :password, input[type=email], input[type=tel], textarea, select, :radio, :checkbox", form).change(function(){
			$(".error_" + $(this).attr('name').replace(/\[|\]/gi, ""), form).hide();
			$(this).removeClass('error_input');
		});
		return false;
	},
	result: function (form, result) {
		$('input:submit', form).removeAttr('disabled');
		try {
			var response = $.parseJSON(result);
		} catch(err){
			$('body').append(result);
			$('.diafan_div_error').css('left', $(window).width()/2 - $('.diafan_div_error').width()/2);
			$('.diafan_div_error').css('top', $(window).height()/2 - $('.diafan_div_error').height()/2 + $(document).scrollTop());
			$('.diafan_div_error_overlay').css('height', $('body').height());
			return false;
		}
		if(this.success[this.tag] && this.success[this.tag](form, response) === false)
		{
			return false;
		}
		var captcha_update = $(form).find("input[name='captcha_update']").val();
		if (response.captcha) {
			if(response.captcha == "recaptcha")
			{
				var c = $('.js_captcha', form).find('div').first();
				grecaptcha.reset(recaptcha[c.attr("id")]);
			}
			else
			{
				$(".captcha", form).html(prepare(response.captcha)).show();
			}
		}
		if (! captcha_update && response.errors) {
			$.each(response.errors, function (k, val) {
				if(k == 0)
				{
					if(! $(".error", form).length)
					{
						$(form).parent().find(".error").addClass("error_message").html(prepare(val)).show();
					}
					else
					{
						$(".error", form).addClass("error_message").html(prepare(val)).show();
					}
				}
				else
				{
					var input = $("input[name="+k+"], textarea[name="+k+"], select[name="+k+"]", form);
					$(".error_" + k, form).addClass("error_message").html(prepare(val)).show();
					if (input.length)
					{
						input.addClass('error_input').addClass('focus_input');
						error_position(k, form);
					}
				}
			});
			$('.focus_input:first', form).focus();
			$('.focus_input').removeClass('focus_input');
		}
		if (response.result && response.result == 'success') {
			$(form).clearForm();
			$(form).find('.inpattachment input:file').each(function () {
				if($(this).parents('.inpattachment').is(":hidden"))
				{
					var clone = $(this).parents('.inpattachment');
					clone.before(clone.clone(true));
					clone.prev('.inpattachment').show();
					var name = str_replace('hide_', '', clone.prev('.inpattachment').find('input').val('').attr("name"), 0 );
					clone.prev('.inpattachment').find('input').val('').attr("name", name);
				}
				else
				{
					$(this).parents('.inpattachment').remove();
				}
			});
			$('input:file', form).removeClass('last');
			$('input:file', form).val('');
			if($('.inpimages', form).length){
				$('.images').text('');
			}
		}
		if (response.add) {
			$('.' + $(form).attr("id")).append(prepare(response.add)).show();
		}
		if (response.redirect) {
			window.location = prepare(response.redirect);
		}
		if (response.data)
		{
			$.each(response.data, function (k, val) {
				if(k == "form")
				{
					k = form;
				}
				if(val)
				{
					$(k).html(prepare(val)).show();
				}
				else
				{
					$(k).hide();
				}
			});
		}
		if(response.attachments){
			 $.each(response.attachments, function (k, val) {
				$(form).find(".attachment[name='"+k+"']").remove();
				$(form).find(".inpattachment input[name='"+k+"']").parents('.inpattachment').remove();
				$(form).find(".inpattachment input[name='hide_"+k+"']").parents('.inpattachment').before(prepare(val));
				$(form).find(".attachment[name='"+k+"']").show();
				if($(form).find(".inpattachment input[name='hide_"+k+"']").attr("max") > $(form).find(".attachment[name='"+k+"']").length)
				{
					var clone = $(form).find("input[name='hide_" + k + "']").parents('.inpattachment');
					clone.before(clone.clone(true));
					clone.prev('.inpattachment').show();
					clone.prev('.inpattachment').find('input').val('').attr("name", k);
				}
			});
		}
		if(response.images){
			 $.each(response.images, function (k, val) {
				$(form).find("input[name='"+k+"']").val('');
				$(form).find("input[name='"+k+"']").parents('div').first().find('.image').remove();
				if(val == false)
				{
					val = '';
				}
				$(form).find("input[name='"+k+"']").before(prepare(val));
			});
		}
		if (response.hash) {
			$('input[name=check_hash_user]').val(response.hash);
		}
		if (response.js) {
			$('body').append(prepare(response.js));
		}
        widt = $(window).width();
        res(widt);
		return false;
	},
}

$(document).on('click', '.error_message', function(){
	$(this).hide();
});

$(document).on('change', ".inpfiles", function () {
	var inpattachment = $(this).parents('.inpattachment');
	if (! $(this).attr("max") || $(this).parents('form').find('input[name="' + $(this).attr("name") + '"], .attachment[name="' + $(this).attr("name") + '"]').length < $(this).attr("max")) {
		var clone = $(this).parents('form').find('input[name="hide_' + $(this).attr("name") + '"]').parents('.inpattachment');
		clone.before(clone.clone(true));
		clone.prev('.inpattachment').show().find('input').val('').attr("name", $(this).attr("name"));
	}
	if(! inpattachment.find(".inpattachment_delete").length)
	{
		inpattachment.append(' <a href="javascript:void(0)" class="inpattachment_delete">x</a>');
	}
});

$(document).on('click', ".inpattachment_delete", function () {
	var inpattachment = $(this).parents('.inpattachment');
	var input = inpattachment.find('.inpfiles');
	var last_input = input.parents('form').find('input[name="' + input.attr("name") + '"]').last();
	if (last_input.val()) {
		var clone = $(this).parents('form').find('input[name="hide_' + input.attr("name") + '"]').parents('.inpattachment');
		clone.before(clone.clone(true));
		clone.prev('.inpattachment').show().find('input').val('').attr("name", input.attr("name"));
	}
	inpattachment.remove();
	return false;
});

$(document).on('click', ".attachment_delete", function(){
	var attachment = $(this).parents('.attachment');
	attachment.find("input[name='hide_attachment_delete[]']").attr("name", "attachment_delete[]");
	attachment.hide().removeClass('attachment');

	var last_input = attachment.parents('form').find('input[name="' + attachment.attr("name") + '"]').last();
	if(! last_input.length || last_input.val())
	{
		var clone = $(this).parents('form').find("input[name='hide_" + attachment.attr("name") + "']").parents('.inpattachment');
		clone.before(clone.clone(true));
		clone.prev('.inpattachment').show();
		clone.prev('.inpattachment').find('input').val('').attr("name", attachment.attr("name"));
	}
	return false;
});

$(document).on('change', ".inpimages", function () {
	var form = $(this).parents('form');
	var self = $(this);
	form.ajaxSubmit({
		dataType:'json',
		data : {
			ajax: 1,
			images_param_id:self.attr('param_id'),
			images_prefix: self.attr('prefix'),
			action: 'upload_image'
		},

		beforeSubmit:function (a, form, o) {
			$('.errors').hide();
		},

		success:function (response) {
			if (response.hash)
			{
				$('input[name=check_hash_user]').val(response.hash);
			}
			if (response.data)
			{
				self.prev('.images').html(prepare(response.data));
				self.val('');
			}
			if (response.errors)
			{
				$.each(response.errors, function (k, val) {
					form.find(".error" + (k != 0 ? "_" + k : '')).html(prepare(val)).show();
				});
			}
		}
	});
});

$(document).on('click', ".image_delete", function(){
	var image= $(this).parents('.image');
	var form = $(this).parents('form');
	$.ajax({
		url : window.location.href,
		type : 'POST',
		dataType : 'json',
		data : {
			module:  form.find('input[name=module]').val(),
			action: 'delete_image',
			ajax: true,
			element_id: form.find('input[name=id]').val(),
			tmpcode: form.find('input[name=tmpcode]').val(),
			id: image.find('img').attr('image_id'),
			check_hash_user : $('input[name=check_hash_user]').val()
		},
		success : (function(response)
		{
			if (response.hash)
			{
				$('input[name=check_hash_user]').val(response.hash);
			}
		})
	});
	image.remove();
	return false;
});

$(".timecalendar").each(function () {
	var st = $(this).attr('showtime');

	if (st && st.match(/true/i)) {
		$(this).datetimepicker({
			dateFormat:'dd.mm.yy',
			timeFormat:'hh:mm'
		}).mask('99.99.9999 99:99');
	}
	else {
		$(this).datepicker({
			dateFormat:'dd.mm.yy'
		}).mask('99.99.9999');
	}
});

$(document).on('keydown', 'input[type=number], input.number', function (evt) {
	evt = (evt) ? evt : ((window.event) ? event : null);

	if (evt) {
		var elem = (evt.target)
			? evt.target
			: (
			evt.srcElement
				? evt.srcElement
				: null
			);

		if (elem) {
			var charCode = evt.charCode
				? evt.charCode
				: (evt.which
				? evt.which
				: evt.keyCode
				);

			if ((charCode < 32 ) ||
				(charCode > 44 && charCode < 47) ||
				(charCode > 95 && charCode < 106) ||
				(charCode > 47 && charCode < 58) || charCode == 188 || charCode == 191 || charCode == 190 || charCode == 110) {
				return true;
			}
			else {
				return false;
			}
		}
	}
});

$('input[type=tel]').mask('+9999 999 9999');

$('.js_mask').each(function(){
	$(this).mask($(this).attr('mask'));
});

$(".error:empty").hide();

$(document).on('click', 'a[rel=large_image]', function () {
	var self = $(this);
	window.open(self.attr("href"), '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + (self.attr("width") * 1 + 40) + ',height=' + (self.attr("height") * 1 + 40));
	return false;
});

function prepare(string) {
	string = str_replace('&lt;', '<', string);
	string = str_replace('&gt;', '>', string);
	string = str_replace('&amp;', '&', string);
	return string;
}

function str_replace(search, replace, subject, count) {
	f = [].concat(search),
		r = [].concat(replace),
		s = subject,
		ra = r instanceof Array, sa = s instanceof Array;
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}
	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}

function get_selected (element, parent)
{
	var option = (typeof element == 'string') ? $(element + " option:first-child", parent) : $("option:first-child", element);

	$(element, parent).find("option").each(function() {
		if($(this).attr("selected") === "selected") {
			option = $(this);
			return false;
		}
	});
	return option;
}
