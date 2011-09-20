// JavaScript Document
$(function() {
	$('.assign').live('click',function() {
		var group_ide = $(this).attr('group_ide');
		$.skybox('/admin/seo/duplicate-data/phrase-groups/skybox/assign/'+ide);
	});
})