
    function forgotpw() {
        $.skybox('/reset-password');
    }

    // login
    $('#login_username:visible').livequery(function(){
        $(this).focus();
    });

    var fields = ['button', 'message', 'password', 'form', 'username'],
        uri = '/ajax/login-skybox/authenticate',
        messages = {
            'true' : 'You are being redirected to the requested page.',
            'false': 'Incorrect Login. Please try again.'
        },
        redirectToPage = function() {

            var uri = window.location.href;
            if (location.hash.substring(0, 2) != '#/') {
                uri = uri.substr(0, uri.indexOf('#'));
                uri = removeParam('skybox', uri);
                uri = removeParam('logout', uri);
            } else {
                uri = window.location.pathname;
            }

            window.location.href = uri;

        };

    $('#skybox').on('submit', '#login_form', function(e) {

        e.preventDefault();

        var $els = {}, i;

        $.each(fields, function(i, item) {
            $els[item] = $('#login_' + item);
        });

        if ($els.message.is(':visible')) {
            $els.message.fadeTo('fast', 0.01);
        }

        $els.button.val('Authenticating...').attr('disabled', true);

        sky.post(uri, $els.form.serialize(), function(re) {

            $els.message.html(messages[re] || re);

            if ($els.message.is(':visible')) {
                $els.message.fadeTo('fast', 1);
            } else {
                $els.message.slideDown('fast');
            }

            if (re == 'true') {
                $els.message.addClass('successful-login');
                setTimeout(redirectToPage, 500);
            } else {
                $els.password.val('');
                $els.button.val('Sign In').attr('disabled', false);
                if ($els.username.val() === '') {
                    $els.username.focus();
                } else {
                    $els.password.focus();
                }
            }

        });

    });
