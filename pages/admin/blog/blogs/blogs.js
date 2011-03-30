function delete_cat(obj){
	$(obj).parent().remove();
}
function insert_cat(){
	var cat_name = $('#blog_category_getter').val();
	
	$('#cats').append('<center id="loading"><img src="/images/loading2.gif"/></center>');
	$.post('/admin/blog/blogs/ajax/blog_category_row',{
														cat_name:cat_name
														},function(data){
															$('#loading').replaceWith(data);
															$('#blog_category_getter').val('');
														});
}