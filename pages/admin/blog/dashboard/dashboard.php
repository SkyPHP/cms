		<fieldset>
			<legend>Blog</legend>
			<ul>
				<li><a href="/admin/blog">Blog Dashboard</a>
				<li><a href="/admin/blog/article/new">Write A New Article</a></li>
				<li><a href="/admin/blog/article/list">View Articles</a></li>
				<li><a href="/admin/blog/category">Category Management</a></li>
				<li><a href="/admin/blog/blogroll">Blogroll Management</a></li>
                <? if ( auth('blog_editor')): ?>
				<li><a href="/admin/blog/contributors">Contributors</a></li>
                <? endif; ?>
				<li><a href="http://www.google.com/trends/hottrends" target="_blank" class="external">Today's Hot Trends</a></li>
			</ul>
		</fieldset>