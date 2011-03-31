<?
unset($_SESSION['blog_article']);

if (!auth('admin','developer','blog_editor')) $_GET['author_ide'] = $_SESSION['login']['person_ide'];

$title = 'Blog Article Overview';
template::inc('intranet','top');

if ($_SERVER['QUERY_STRING']) $qs = '?'.$_SERVER['QUERY_STRING'];

$tabs = array(
	'All' => '/admin/blog/article/list'.$qs,
	'Published' => '/admin/blog/article/list/A'.$qs,
	'Pending Review' => '/admin/blog/article/list/P'.$qs,
	'Unpublished' => '/admin/blog/article/list/U'.$qs
);
snippet::tab_redirect($tabs);

$condition ="";

$order_by = "order by post_time desc, blog_name asc";

$condition = '';
if($_GET['blog_id']) $condition .= "and blog_article.blog_id = {$_GET['blog_id']}";
$_GET['author_id'] = decrypt($_GET['author_ide'],'person');
if(is_numeric($_GET['author_id'])) $condition .= "and blog_article.author__person_id = {$_GET['author_id']}";
$_GET['cat_id'] = decrypt($_GET['cat_ide'],'blog_category');
if(is_numeric($_GET['cat_id'])) $condition .= "and blog_article.blog_category_id = {$_GET['cat_id']}";

if ( $_POST['sky_ide']=='A' || $_POST['sky_ide']=='P' || $_POST['sky_ide']=='U' ) {
	$condition .= "and blog_article.status = '{$_POST['sky_ide']}'";
}//if

$q = '';

if ($_GET['q']) {
	$_GET['q'] = addslashes($_GET['q']);
	$condition .= "and ( 
			title ilike '" . $_GET['q'] . "%'
			or title ilike '% " . $_GET['q'] . "%'
			or blog_category.name ilike '" . $_GET['q'] . "%'
			or blog_category.name ilike '% " . $_GET['q'] . "%'
		 )";
}//if

$aql_blog_title = "blog_article {
						id as article_id,
						blog_id,
						blog_category_id,
						title,
						author__person_id,
						status,
						featured, 
						post_time 
						where true
						{$condition}
						{$order_by}
					}
					blog {
						name as blog_name
					}
					blog_website {
						where website_id = $website_id
					}
					blog_category {
						name as blog_cat
					}
					";

// step 2. specify which columns to add to the grid
		$cols = "article_id { label: Id; }
				blog_name { label: Blog; }
				blog_cat { label: Category; }
				title { label: Article; }
				author__person_id{ label: Author; }
				featured{ label: Featured; }		 
				status{ label: Status; }
				post_time{ label: Date; }
				";
 
    // step 3. display the data grid on the page
  		$param = array(
			'table_class' => 'listing'
		);
?>

<script language="javascript">
	add_javascript('/pages/admin/blog/article/list.js');
</script>

<h1><?=$title?></h1>
<a href ="/admin/blog/article/new">Write A New Article</a>

<form method="get">
	<div class="filter_nav">

		<div class="col">
        	<input type="text" name="q" class="q" value="<?=$_GET['q']?>" />
        </div>
        
        <div class="col">
        	<?
			$aql = "blog {
					 	id,
						name
					}
					blog_website {
						where website_id = $website_id
					}";
			$dropdown = array(
				'select_name' => 'blog_id',
				'value_field' => 'blog_id',
				'option_field' => 'name',
				'selected_value' => $_GET['blog_id'],
				'null_option' => 'All Blogs',
				'onchange' => 'this.form.submit();'
			);
			aql::dd($aql,$dropdown);
			?>
        </div>
        
        <? if (auth('admin','developer','blog_editor')): ?>
        <div class="col">
			<?
			$aql = "blog_author {
						person_id
						where website_id = $website_id
					}
					person {
						fname,
						lname
						order by lname asc
					}";
			$rs_author = aql::select($aql);
			?>
            <select name="author_ide" onchange="this.form.submit();">
            <option value="">All Contributors</option>
            <? if ($rs_author): foreach($rs_author as $author): ?>
            <option value="<?=$author['person_ide']?>" 
				<?=($author['person_ide']==$_GET['author_ide'])?'selected="selected"':''?> >
				<?=$author['fname']?> <?=$author['lname']?>
            </option>
            <? endforeach; endif; ?>
            </select>
        </div>
        <? endif; ?>
        
        <div class="col">
        	<?
			$aql = "blog_category {
						name
						order by name asc
					}
					blog_website on blog_category.blog_id = blog_website.blog_id {
						where website_id = $website_id
					}";
			$dropdown = array(
				'select_name' => 'cat_ide',
				'value_field' => 'blog_category_ide',
				'option_field' => 'name',
				'selected_value' => $_GET['cat_ide'],
				'null_option' => 'All Categories',
				'onchange' => 'this.form.submit();'
			);
			aql::dd($aql,$dropdown);
			?>
        </div>

		<div class="clear"></div>
		
	</div>
</form>

<? snippet::tabs($tabs); ?>

<div id="gridcontent">
<? aql::grid($aql_blog_title,$col,$param); ?>
</div>
		 
<?
template::inc('intranet','bottom');
?>