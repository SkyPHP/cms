(function($) {

    /*
        To have the same callback style as aql.save /aql.remove
        and keep the originals backwards compatible.
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

    function messageDiv(model) {
        return '#saveForm_message_' + model;
    }

    $.fn.saveForm = function(options) {

        var settings = {
                success2: null,
                error2: null,
                beforeSend2: null,
                action: null,
                saveText: 'Saved.',
                messageDiv: null,
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

        // makes sure there are no more than 1 submit handler (in case of overrides)
        this.unbind('submit');

        return this.submit(function(e) {

            e.preventDefault();

            if (options) {
                options = mapSettings(options);
                $.extend(settings, options);
            }

            var $form = $(this),
                tag = this.tagName.toLowerCase(),
                model_name = $form.attr('model');

            var post_url =  settings.action || $form.attr('action') ||
                (model_name ? aql.savepath + '/' + model_name : false);

            if (tag != 'form' || !post_url) {
                return false;
            }

            if (typeof tinyMCE == 'function') {
                tinyMCE.triggerSave();
            }

            var selector = messageDiv(model_name);
            if (!settings.messageDiv) {
                if (!$(selector).length) {
                    $form.prepend('<div id="' + selector + '" />');
                }
            }

            var data = $form.serialize() + '&_ajax=1';
            var $status = (settings.messageDiv) ||
                $(selector).addClass('saveForm_message');

            var makeResponseHandler = function(response) {
                    return function(callbacks) {
                        for (var i in callbacks) {
                            sky.call(callbacks[i], settings, response, $status);
                        }
                    };
                },
                doSave = function() {
                    sky.post(post_url, data, function(response) {

                        sky.call(settings.onSave, settings, $status);

                        var handles = (response.status == 'OK') ?
                            [ settings.success, settings.success2 ] :
                            [ settings.error, settings.error2 ];

                        makeResponseHandler(response)(handles);
                    });
                };

            sky.call(settings.beforeSave);
            sky.call(settings.beforeSend, settings, $status);

            if (!sky.call(settings.beforeSend2, settings, $status, doSave, settings.onAbort)) {
                doSave();
            }

            return false;

        });
    };

    if (typeof $.livequery !== 'undefined') {
        $(function() {
            $('.aqlForm').livequery(function() {
               $(this).saveForm();
            });
        });
    }

})(jQuery);
