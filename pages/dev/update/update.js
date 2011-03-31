function update(codebase,type,obj) {
    $('#output').html('<img src="/images/loading.gif" />');
    $('#output').css('display','block');
    $.post(
        '/dev/update/svn-update',
        {
            codebase: codebase,
            type: type
        },
        function(data){
            $('#output').html(data);
        }
    );
}