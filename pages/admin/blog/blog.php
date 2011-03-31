<?
redirect('/admin/blog/post');

$title = 'Blog Dashboard';
$no_sidebar = true;
template::inc('intranet','top');
?>

<h1 class="module-bar"><?=$title?></h1>

<fieldset>
	<legend>Articles</legend>
	<ul>
		<li><a href="/admin/blog/article/new">Compose Article</a></li>
		<li><a href="/admin/blog/article/list/A">View Published Articles</a></li>
		<li><a href="/admin/blog/article/list/P">View Pending Articles</a></li>
		<li><a href="/admin/blog/article/list/U">View Unpublished Articles</a></li>
	</ul>
</fieldset>

<fieldset>
	<legend>Category Management</legend>
	<ul>
		<li><a href="/admin/blog/category">Add Category</a></li>
		<li><a href="/admin/blog/category">View Categories</a></li>
	</ul>
</fieldset>

<fieldset>
	<legend>Blogroll Management</legend>
	<ul>
		<li><a href="/admin/blog/blogroll/add-new">Add Blogroll Link</a></li>
		<li><a href="/admin/blog/blogroll">View Blogroll</a></li>
	</ul>
</fieldset>

<? if ( auth('blog_editor')): ?>
<fieldset>
	<legend>Contributors</legend>
	<ul>
		<li><a href="/admin/blog/contributors/add-new">Add Contributor</a></li>
		<li><a href="/admin/blog/contributors">View Contributors</a></li>
	</ul>
</fieldset>

<fieldset>
	<legend>Blog Management</legend>
	<ul>
		<li><a href="">Edit Blog</a></li>
	</ul>
</fieldset>
<? endif; ?>

<div class="clear"></div>

<?
template::inc('intranet','bottom');
?>