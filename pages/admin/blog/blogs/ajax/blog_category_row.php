<?
if(!$cat){
	$cat['name'] = $_POST['cat_name'];
	$cat['blog_category_ide'] = $_POST['cat_ide'];
}
?>
<div class="blog_category">
	<?=$cat['name']?>
	<input type="hidden" name="blog_category_ide[<?=$cat['blog_category_ide']?>]" value="<?=$cat['name']?>"/>
	<span class="delete_blog_category" onclick="delete_cat(this)" title="Delete">X</span>
</div>