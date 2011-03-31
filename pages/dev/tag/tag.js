function tag_new_version(form) {
    if ( confirm('Are you sure?') ) {
        $('#output').html('<img src="/images/loading.gif" />');
        $('#output').css('display','block');
        $.post(
            '/dev/tag/svn-tag',
            {
                codebase : form.codebase.value,
                version : form.version.value
            },
            function (data) {
                $('#output').html(data);
            }
        );
    }
}