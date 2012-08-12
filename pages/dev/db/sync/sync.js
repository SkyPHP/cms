$(function(){

    $("#tabs").livequery(function(){
        $(this).tabs();
    });

    var mustache_file = '/pages/dev/db/sync/diffs.m';
    var please_wait_msg = 'Please wait...';
    var rpc;
    var btn = $('#submit-button');

    $('#diff-form').on('submit', function(){

        // please wait
        btn.attr('bval', btn.val());
        btn.val(please_wait_msg);

        // establish the cross domain rpc socket
        $.getScript('/lib/easyXDM/easyXDM.min.js', function(){
            rpc = new easyXDM.Rpc({
                // remote config
                remote: $('#remote-url').val()
            },{
                // remote function stubs
                remote: {
                    getDiffs: {}
                },
                // local functions
                local: {
                    renderDiffs: function(data){
                        // render the diffs into html
                        $.get(mustache_file + '?' + Math.random(), function(m){
                            var html = Mustache.render(m, data);
                            $('#diffs').html(html);
                        });
                    },
                    showLoading: function() {
                        $('#diffs').html('<img src="/images/loading.gif" />');
                    },
                    resetButton: function() {
                        btn.val(btn.attr('bval'));
                    }
                }
            });

            // get the diffs
            rpc.getDiffs($('#diff-form').serialize());
        });

        // don't actually submit the form
        return false;
    })



});
