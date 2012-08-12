<?php

    global $jquery_version;
?>
<!doctype html>
<html>
<head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/<?=$jquery_version?>/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="/lib/js/jquery-<?=$jquery_version?>.min.js"%3E%3C/script%3E'))</script>
</head>
<body>
    <script>
        if ( typeof window.JSON === 'undefined' ) {
            document.write('<script src="/lib/history.js-1.5/json2.min.js"><\/script>');
        }
    </script>
    <script type="text/javascript" src="/lib/easyXDM/easyXDM.min.js"></script>
    <script type="text/javascript">
        $(function(){

            var rpc = new easyXDM.Rpc({},{
                local: {
                    getDiffs: function(post, successFn, errorFn){
                        console.log('authenticating...');
                        // check to see if logged in
                        $.post('/ajax/login-skybox/authenticate',function(data){
                            console.log(data);
                            if (data=='true') {
                                // get the diffs
                                $.post(
                                    '/dev/db/sync/diff',
                                    post,
                                    function(data){
                                        rpc.renderDiffs(data);
                                    }
                                );
                            } else {
                                var domain = window.location.origin;
                                alert('You need to be logged in at ' . domain);
                            }
                        });
                    },
                    logIn: function(post, brand, successFn, errorFn){
                        $.post('/ajax/login-skybox/authenticate', post, function(data){
                            if (data=='true') {
                                $.post('/toolbar?brand='+brand, function(html){
                                    rpc.refreshToolbarHTML(html);
                                });
                            } else {
                                console.log('unsuccessful login');
                            }
                        });
                    },
                    logOut: function(brand, successFn, errorFn){
                        $.post('/ajax/login-skybox/authenticate?logout=1', function(data){
                            $.post('/toolbar?brand='+brand, function(html){
                                rpc.refreshToolbarHTML(html);
                            });
                        });
                    }
                },
                remote: {
                    renderDiffs:{}
                }
            });

        });
    </script>

</body>
</html>
