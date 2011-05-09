$(document).ready(function() {
	$('.aqlForm').livequery(function() {
       $(this).saveForm(); 
    });
});

(function($) {
	$.fn.saveForm = function(options) {

		var settings = {
			'onSuccess' : function(r, $status) {
				var t = 'Saved.';
				if (typeof (ui_widget) !== 'undefined' && $.isFunction(ui_widget)) t = ui_widget(t);
				$status.html(t);
			},
			'onSuccessFn2' : '',
			'onFail' : function(r, $status) {
				var m = '<ul class="error">';
				for ( i in r.errors) {
					m += '<li>' + r.errors[i] + '</li>';
				}
				m += '</ul>';
				if (typeof (ui_error) !== 'undefined' && $.isFunction(ui_error)) m = ui_error(m);
				$status.html(m);
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
			}
		};

		return this.submit(function(e) {
			e.preventDefault();
			if (options) {
				$.extend(settings, options);
			}
			var $form = $(this),
				tag = this.tagName.toLowerCase();
				model_name = $form.attr('model');
			if (tag != 'form' || !model_name) return false;
			if (typeof (tinyMCE) == 'function') {
				tinyMCE.triggerSave();
			}
			var data = $form.serialize() + '&_ajax=1';
			if (!$('#saveForm_message_' + model_name).length) {
				$form.prepend('<div id="saveForm_message_' + model_name + '"></div>');
			}
			var $status = $('#saveForm_message_' + model_name);
			if ($.isFunction(settings.beforeSave)) settings.beforeSave(data);
			var req = $.ajax({
				type: 'POST',
				url: '/save/v2/' + model_name,
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
						settings.onSuccess(data, $status);
						if ($.isFunction(settings.onSuccessFn2)) { 
							settings.onSuccessFn2(data);
						}
					} else {
						settings.onFail(data, $status);
					}
				}
			});
			return false;
		});
	}

})(jQuery);