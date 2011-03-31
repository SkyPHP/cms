<?
$template_area = 'top';
include('templates/intranet/intranet.php');
?>

<?
$SQL = "select  blog.url
		from blog
		where blog.active = 1
		and blog.id = {$blog_id}";
$r_blog = $db->Execute($SQL) or die("$SQL<br>" . $db->ErrorMsg());
$blog_url = $r_blog->Fields('url');
?>

<script>
	set_title('Blog Management');
	// add_style('/pages/admin/blog/manage.css');
</script>
<h2>Blog Management</h2>

<div id="blog_mgmt_nav">
	<ul>
		<li><a href="/admin/blog/article" style="font-size:17px; font-weight:bold;">Write A New Article</a></li>
		<li><a href="/admin/blog/categories">Manage Categories</a> *coming soon</li>
		<li><a href="/admin/blog/tags">Manage Tags</a> *coming soon</li>
		<li><a href="/admin/blog/staff">Manage Authors/Editors</a> *coming soon</li>
	</ul>
</div>


<?
$SQL = "select  blog_article.id,
				blog_article.status,
				blog_article.title,
				person.fname,
				person.lname
		from blog_article
		left join person on person.id = blog_article.author__person_id and person.active = 1
		where blog_article.active = 1";
$r = $db->Execute($SQL) or die("$SQL<br>".$db->ErrorMsg());
?>
<table id="blog_articles" class="listing">
  <tr>
	<th>Status</th>
	<th>Title</th>
	<th>Categories</th>
	<th>Author</th>
	<th></th>
	<th></th>
	<th></th>
  </tr>
<?
while (!$r->EOF) {
?>
  <tr>
	<td><?=$r->Fields('status')?></td>
	<td><?=$r->Fields('title')?></td>
	<td>Nightlife News, Music</td>
	<td><?=$r->Fields('fname')?></td>
	<td><a href="<?=$blog_url?>/<?=encrypt($r->Fields('id'),'blog_article')?>">View</a></td>
	<td><a href="/admin/blog/article/<?=encrypt($r->Fields('id'),'blog_article')?>">Edit</a></td>
	<td>Delete</td>
  </tr>
<?
	$r->MoveNext();
}//while
?>
</table>


<?
$template_area = 'bottom';
include('templates/intranet/intranet.php');
?>