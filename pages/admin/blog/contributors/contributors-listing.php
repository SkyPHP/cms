<?
$aql = "blog	{
					name
				}
		blog_website	{
							where website_id = $website_id
						}";
$rs = aql::select($aql);
$tabs = array();
$tabs['Unassigned'] = '/admin/blog/contributors/unassigned';
$blog_editor = auth('blog_author:editor');
if($rs){
	foreach($rs as $blog){
		$tabs[$blog['name']] = '/admin/blog/contributors/'.$blog['blog_ide'];
	}
	
}
$tabs['Inactive'] = '/admin/blog/contributors/inactive';


$where = array();

if ($_GET['q']) {
	$q = addslashes(trim(urldecode($_GET['q'])));
	$where[] = "(
			fname ilike '" . $q . "%'
			or fname ilike '% " . $q . "%'
			or lname ilike '" . $q . "%'
			or lname ilike '% " . $q . "%'
			or email_address ilike '" . $q . "%'
			or email_address ilike '% " . $q . "%'
			or position ilike '" . $q . "%'
			or position ilike '% " . $q . "%'
		 )";
}//if
$blog_ide = $_POST['sky_ide'];

switch ($blog_ide) {
    case 'unassigned':
        $where[] = "blog_author.status = 'A'";
        $where[] = "(blog_author.blog_id is null or  blog_author.blog_id = 0)";
        break;
    case 'inactive':
        $where[] = "( blog_author.status != 'A' or blog_author.status is null )";
        break;
    default:
        $where[] = "blog_author.status = 'A'";
        $where[] = "blog_author.blog_ide = $blog_ide";
        break;
}


snippet::tab_redirect($tabs);
$title = 'Editors &amp; Contributors';
template::inc('intranet','top');
?>
<div class = "has-floats">
	<div class = "float-right blog_listing">
		<div class = "content_listing">



<form method="get">
	<div class="filter_nav">

		<div class="col">
        	<input type="text" name="q" class="q" value="<?=$_GET['q']?>" /> 
			<input type = "button" onclick = "$('form').submit()" value = "Search" />
        </div>
		<div class = "col">
<?
	if($blog_editor){
?>
			<input type = "button" value ="Add New Staff Member" onclick = "window.location = 'add-new'"/>
<?
	}
?>
		</div>
		<div class="clear"></div>
		
	</div>
</form>

<?

$clause = array(
	'blog_author' => array(
		'where' => $where,
		'order by' => "(blog_author.access_group ilike '%editor%' and blog_author.access_group is not null) desc, iorder asc, lname asc"
	)
);
snippet::tabs($tabs);
$cols = "
	name_and_position	{	label: Name		;}
	username			{	label: Username	;}
	position			{	label: Position	;}
	email_address		{	label: Email	;}
";
if($blog_editor){
	$cols .= 'edit {}';
}
$model = 'blog_author';



$aql = "blog_author {
			access_group,
			person_id,
			position,
			iorder,
			website_id,
			blog_id
		}
		person {
			id as name_and_position,
			fname,
			lname,
			username,
			email_address
		}
		blog{
			name as blog_name
		}
                ";
$param = array('enable_sort'=>true);
$params = array (	
					"aql"=>$aql,
					"cols"=>$cols,
					"clause"=>$clause,
					"param"=>$param
				);
?>
	<div class = "clear"></div>
<?
aql::grid($params);
?>
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
