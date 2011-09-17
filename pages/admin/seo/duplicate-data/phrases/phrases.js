// JavaScript Document
$(function() {
	$('.edit').live('click',function() {
		location.href='/admin/seo/duplicate-data/phrases/'+$(this).attr('phrase_ide');
	});
});