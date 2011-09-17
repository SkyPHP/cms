// JavaScript Document
$(function() {
	$('.edit').live('click',function() {
		window.location('/admin/seo/duplicate-data/phrases/'+$(this).attr('phrase_ide'));
	});
});