<?
$title = 'Blogroll Management';
template::inc('intranet','top');

?>
<div class = "has-floats">
	<div class = "float-right blog_listing">
		<div class = "content_listing">
<a href="/admin/blog/blogroll/add-new">Add New Blogroll Link</a>

<form method="get">
	<div class="filter_nav">

		<div class="col">
        	<input type="text" name="q" class="q" value="<?=$_GET['q']?>" />
			<input type="submit" value="Search" />
        </div>
		
		<div class="col">
			On Website: 
        	<?
			$arr = array(
				1 => 'Yes',
				0 => 'No'
			);
			$dd = array(
				'name' => 'on_website',
				'selected_value' => $_GET['on_website'],
				'null_option' => 'Both'
			);
			snippet::dropdown($arr,$dd);
			?>
        </div>

		<div class="clear"></div>
		
	</div>
</form>

<?
$q = '';

if ($_GET['q']) {
	$_GET['q'] = addslashes($_GET['q']);
	$q = "and ( 
			name ilike '" . $_GET['q'] . "%'
			or name ilike '% " . $_GET['q'] . "%'
			or url ilike '" . $_GET['q'] . "%'
			or url ilike '% " . $_GET['q'] . "%'
		 )";
}//if

$aql = "blog {
			id,
			name
		}
		blog_website {
			where website_id = $website_id
		}";
$rs = aql::select($aql);
	$tabs = array();
	$tabs['All'] = '/admin/blog/blogroll';
if($rs){
	foreach($rs as $blog):
		$tabs[$blog['name']] = '/admin/blog/blogroll/'.$blog['blog_ide'];
	endforeach;
	snippet::tab_redirect($tabs);
}
snippet::tabs($tabs);
$cols = "
	blog_roll_name {
		label: Name;
	}
	blog_roll_url {
		label: URL;
		script: components/blog_roll/blog_roll_url/blog_roll_url.php;
	}
	pagerank {
		label: PR;
	}
	mod_time {
		label: Date Added;
	}
	added_by__person_id {
		label: Added By;
	}
	status {
		label: On Website;
	}
";
if(auth('blog_author:editor;')){
	$cols .= 'edit {}';
	$cols .= 'delete {}';
}
$where = NULL;
if ( $_POST['sky_ide'] ) $where .= " and blog_roll.blog_ide =  '{$_POST['sky_ide']}'";
if ( $_GET['on_website'] !== NULL && is_numeric($_GET['on_website']) ) $where .= " and blog_roll.status = {$_GET['on_website']} ";
$clause = array(
		'blog_roll' => array(
			'where' => 'true ' . $where . $q,
			'order by' => 'blog_roll.name asc'
		),
		'blog_website' => array(
			'where' => "website_id = '{$website_id}'"
		)
	);

$model = 'blog_roll';
?>
			<div class = "clear"></div>
			<div>
<?
aql::grid($model,$cols,$clause);
?>
			</div>
		</div>
	</div>
		<div class = "left_nav">
<?
include('pages/admin/blog/left-nav/left-nav.php');
?>
	</div>
</div>
<?

template::inc('intranet','bottom');
?>
