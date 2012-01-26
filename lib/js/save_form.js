(function($) {

	/*
		To have the same callback style as aql.save /aql.remove and keep the originals backwards compatible.
	*/

	function mapSettings(options) {
		
		var relation = {
				onSuccessFn: 'success',
				onSuccessFn2: 'success2',
				onFail: 'error',
				onFail2: 'error2'
			};
		
		options = options || {};

		for (var key in relation) {
			if (options[key]) {
				options[relation[key]] = options[key];
				delete options[key];
			}
		}

		return options;

	}

	$.fn.saveForm = function(options) {

		var settings = {
				success2: null,
				error2: null,
				beforeSend2: null,
				action: null,
				saveText: 'Saved.',
				success: function(r, $status) {
					aql.success($status, this.saveText);
				},
				error: function(r, $status) { 
					var errors = r.errors ? r.errors : ['Internal JSON Error'];
					aql.error($status, aql.json.errorHTML(errors));
				},
				beforeSend: function($status) {
					$status.html('<img src="/images/loading.gif" />');
				},
				onSave: function($status) {
					$status.html('');
				},
				onAbort: function() { 
					return; 
				}
			};

		this.unbind('submit'); // makes sure there are no more than 1 submit handler (in case of overrides)

		return this.submit(function(e) {

			e.preventDefault();
			
			if (options) {
				options = mapSettings(options);
				$.extend(settings, options);
			}

			var $form = $(this),
				tag = this.tagName.toLowerCase(),
				model_name = $form.attr('model');

                        var post_url = null;

			if (!settings.action) {
				//settings.action = (!model_name) ? $form.attr('action') : '/aql/save/' + model_name;
                        	post_url = $form.attr('action') || (model_name ? '/aql/save/' + model_name : false);
			}else{
                           post_url = settings.action;
                        }
			
			if (tag != 'form' || !post_url) return false;
			
			if (typeof (tinyMCE) == 'function') {
				tinyMCE.triggerSave();
			}

			if (!$('#saveForm_message_' + model_name).length) {
				$form.prepend('<div id="saveForm_message_' + model_name + '" class="saveForm_message"></div>');
			}
			
			var data = $form.serialize() + '&_ajax=1',
				$status = $('#saveForm_message_' + model_name).addClass('saveForm_message'),
				makeResponseHandler = function(response) {
					return function(callbacks) {
						for (var i in callbacks) {
							aql._callback(callbacks[i], settings, response, $status);			
						}
					};
				},
				doSave =  function() {
					$.post(post_url, data, function(response) {
						aql._callback(settings.onSave, settings, $status);
						
						var handler = makeResponseHandler(response),
							handles = (response.status == 'OK')
								? [ settings.success, settings.success2 ]
								: [ settings.error, settings.error2 ];
						
						handler(handles);

					});
				};

			aql._callback(settings.beforeSave);
			aql._callback(settings.beforeSend, settings, $status);
			
			if (!aql._callback(settings.beforeSend2, settings, $status, doSave, settings.onAbort)) {
				doSave();
			}

			return false;
		});
	}

})(jQuery);

$(document).ready(function() {
	$('.aqlForm').livequery(function() {
       $(this).saveForm(); 
    });
});
