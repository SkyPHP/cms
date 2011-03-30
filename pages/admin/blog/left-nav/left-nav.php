<div>
	<input type = "button" value = "Write New Post" onclick = "window.location = 'add-new';" />
</div>
<div>
<?
$tabs = array(
				'Blog Posts'=>'/admin/blog/post',
				'Editorial Staff'=>'/admin/blog/contributors',
				'Blogroll'=>'/admin/blog/blogroll',
				'Blog Settings'=>'/admin/blog/blogs',
                'Blog Stats'=>'/admin/blog/views',
		        'Tag Stats'=>'/admin/blog/tag-report',		
				'My Profile'=>'/admin/blog/myprofile'
			);
$param = array(
	'div_class' => 'vertical-tabs'
);
snippet::tabs($tabs,true,$param);

?>
</div>
