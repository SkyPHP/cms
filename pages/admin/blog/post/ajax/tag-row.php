<?
if(!$tag){
	$tag['tag_name'] = $_POST['blog_article_tag_name'];
	$tag['iorder'] = $_POST['iorder'];
}
$tag['tag_name_lower'] = strtolower($tag['tag_name']);
$id = $tag['iorder'] === '0'?"id = 'first_tag' ":'';
?>
<div <?=$id ?>class = "blog_article_tag round-corners" >
	<div class = "tag_name round-corners has-floats">
		<input class = "blog_article_tag_name" type = "hidden" value = "<?=$tag['tag_name'] ?>" name = "blog_article_tag_name[<?=$tag['iorder'] ?>]" />
		<?=$tag['tag_name_lower'] ?><span title = "Remove Tag" class = "x" onclick = "remove_tag(this)"></span>
	</div>
</div>
