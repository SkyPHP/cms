    // login
    $('#login_username').livequery(function(){
        $(this).focus();
    });
    $('#login_password').live('keyup',function(e){
        if(e.which == 13){
            $('#login_form').submit();
            return false;
        }
    });
    $('#login_form').live('submit',function(){
        $('#login_box').html('<img src="/images/loading.gif" />');
        $.post(
            '/login/authenticate',
            $(this).serialize(),
            function(data){
                if (data=='true') {
                    url = removeParam(window.location.href,'logout');
                    url = removeParam(url,'skybox');
                    window.location.href = url;
                } else if (data=='false') {
                    $('#login_password').val('');
                    $('#login_message').html('Incorrect login.  Try again.');
                } else {
                    $('#login_password').val('');
                    $('#login_message').html(data);
                }
            }
        );
        return false;
    });