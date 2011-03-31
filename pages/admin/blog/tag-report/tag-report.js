function delete_tag(tag_string, row_count) {
	var answer = confirm("Are you sure you want to delete this tag?")
	if (answer){
		AjaxRequest.post({
			url: '/admin/blog/tag-report/ajax/delete',
			tag_name: tag_string,
			onSuccess:	function(req) {
						document.getElementById('note_message').innerHTML = req.responseText;
						$("#" + row_count).fadeOut("slow");
			}
		});
	}
}

