(function($) {
	$.fn.saveForm = function(options) {

		var settings = {
			'onSuccess' : function(r, $status) {
				var t = 'Saved.';
				if (ui_widget && $.isFunction(ui_widget)) t = ui_widget(t);
				$status.html(t);
			},
			'onFail' : function(r, $status) {
				var m = '<ul class="error">';
				for ( i in r.errors) {
					m += '<li>' + r.errors[i] + '</li>';
				}
				if (ui_error && $.isFunction(ui_error)) m = ui_error(m);
				$status.html(m);
			},
			'beforeSend' : function($status) {
				$status.html('<img src="/images/loading.gif" />');
			},
			'onSave' : function($status) {
				$status.html('');
			}
		};

		return this.submit(function() {
			if (options) {
				$.extend(settings, options);
			}
			var $form = $(this),
				tag = this.tagName.toLowerCase();
				model_name = $form.attr('model');
			if (tag != 'form' || !model_name) return false;
			if ($.isFunction(tinyMCE)) {
				tinyMCE.triggerSave();
			}
			var data = $form.serialize() + '&_ajax=1';
			if (!$('#saveForm_message').length) {
				$form.prepend('<div id="saveForm_message"></div>');
			}
			var $status = $('#saveForm_message');
			if ($.isFunction(settings.beforeSave)) settings.beforeSave(data);
			$.ajax({
				type: 'POST',
				url: '/save/v2/' + model_name,
				data: data,
				beforeSend: settings.beforeSend($status),
				success: function(data, textstatus) {
					if ($.isFunction(settings.onSave)) settings.onSave($status);
					if (data.status == 'OK') {
						settings.onSuccess(data, $status);
					} else {
						settings.onFail(data, $status);
					}
				}
			});
			return false;
		});
	}
	
})(jQuery);