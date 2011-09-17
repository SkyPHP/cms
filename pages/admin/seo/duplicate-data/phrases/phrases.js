// JavaScript Document
$(function() {
	$('.edit').live('click',function() {
		$.skybox('/admin/seo/duplicate-data/phrases/skybox/phrase-skybox/'+$(this).attr('phrase_ide'));
	});
});