$(document).ready(function() {
	$('.aqlForm').livequery(function() {
       $(this).saveForm(); 
    });
});

(function($) {
	$.fn.saveForm = function(options) {

		var settings = {
			'onSuccessFn' : function(r, $status) {
				var t = this.saveText;
				if (typeof (ui_widget) !== 'undefined' && $.isFunction(ui_widget)) t = ui_widget(t);
				$status.addClass('saveForm_success').removeClass('saveForm_fail').html(t);
			},
			'onSuccessFn2' : '',
			'onFail' : function(r, $status) {
				var m = '<ul class="error">';
				for ( i in r.errors) {
					m += '<li>' + r.errors[i] + '</li>';
				}
				m += '</ul>';
				if (typeof (ui_error) !== 'undefined' && $.isFunction(ui_error)) m = ui_error(m);
				$status.addClass('saveForm_fail').removeClass('saveForm_fail').html(m);
			},
			'onFail2' : function(r, $status) {
				return;
			},
			'beforeSend' : function($status) {
				$status.html('<img src="/images/loading.gif" />');
			},
			'beforeSend2' : '',
			'onSave' : function($status) {
				$status.html('');
			},
			'onAbort' : function() {
				return;
			},
			'action' : null,
			'saveText' : 'Saved.'
		};

		this.unbind('submit'); // makes sure there are no more than 1 submit handler (in case of overrides)

		return this.submit(function(e) {
			e.preventDefault();
			if (options) {
				$.extend(settings, options);
			}
			var $form = $(this),
				tag = this.tagName.toLowerCase();
				model_name = $form.attr('model');
			if (!settings.action) settings.action = (!model_name) ? $form.attr('action') : '/save/v2/' + model_name;
			if (tag != 'form' || !settings.action) return false;
			if (typeof (tinyMCE) == 'function') {
				tinyMCE.triggerSave();
			}
			var data = $form.serialize() + '&_ajax=1';
			if (!$('#saveForm_message_' + model_name).length) {
				$form.prepend('<div id="saveForm_message_' + model_name + '" class="saveForm_message"></div>');
			}
			var $status = $('#saveForm_message_' + model_name).addClass('saveForm_message');
			if ($.isFunction(settings.beforeSave)) settings.beforeSave(data);
			var req = $.ajax({
				type: 'POST',
				url: settings.action,
				data: data,
				beforeSend: function() {
					settings.beforeSend($status);
					if ($.isFunction(settings.beforeSend2)) {
						var re = settings.beforeSend2($status);
						if (re === false) {
							if ($.isFunction(settings.onAbort)) settings.onAbort($status);
							return false;
						}	
					}
				},
				success: function(data, textstatus) {
					if ($.isFunction(settings.onSave)) settings.onSave($status);
					if (data.status == 'OK') {
						settings.onSuccessFn(data, $status);
						if ($.isFunction(settings.onSuccessFn2)) { 
							settings.onSuccessFn2(data);
						}
					} else {
						settings.onFail(data, $status);
						settings.onFail2(data, $status);
					}
				}
			});
			return false;
		});
	}

})(jQuery);