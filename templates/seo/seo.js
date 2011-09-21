$(function() {
	$('.edit').live('click',function() {
		var href = $(this).attr('href');
		var ide = $(this).attr('ide');
		$.skybox(href+ide);
	});
});