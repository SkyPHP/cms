    // login
    $('#login_username').livequery(function(){
        $(this).focus();
    });
    $('#login_password').die().live('keyup',function(e){
        if(e.which == 13){
            $('#login_form').submit();
            return false;
        }
    });
    $('#login_form').die().live('submit',function(){
        $('#login_button').val('Authenticating...').css('color','gray');
        if ($('#login_message').is(":visible")) $('#login_message').fadeTo('fast',0.01);
        $.post(
            '/ajax/login-skybox/authenticate',
            $(this).serialize(),
            function(data){
                if (data=='true') {
                    url = window.location.href;
                    url = removeParam('skybox',url);
                    url = removeParam('logout',url);
                    window.location.href = url;
                } else {
                    if (data=='false') {
                        $('#login_message').html('Incorrect login.  Try again.');
                    } else {
                        $('#login_message').html(data);
                    }
                    if ( $('#login_message').is(':visible') ) $('#login_message').fadeTo('fast',1);
                    else $('#login_message').slideDown('fast');
                    $('#login_password').val('');
                    $('#login_button').val('Sign In').css('color','black');
                    if ( $('#login_username').val() == '' ) $('#login_username').focus();
                    else $('#login_password').focus();
                }
            }
        );
        return false;
    });