<?php

$this->template('xdm-provider', 'top');
?>

<script type="text/javascript">
    $(function(){

        var rpc = new easyXDM.Rpc({},{
            local: {
                getDiffs: function(post, successFn, errorFn){
                    // check to see if logged in
                    $.post('/ajax/login-skybox/authenticate',function(data){
                        if (data=='true') {
                            // get the diffs
                            $.post(
                                '/dev/db/sync/diff.json',
                                post,
                                function(data){
                                    rpc.renderDiffs(data);
                                    rpc.resetButton();
                                }
                            );
                        } else {
                            rpc.resetButton();
                            var domain = window.location.origin;
                            alert('You need to be logged in at ' + domain);
                        }
                    });
                }
            },
            remote: {
                renderDiffs:{},
                resetButton:{}
            }
        });

    });
</script>

<?
$this->template('xdm-provider', 'bottom');
