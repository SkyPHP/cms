<?
$title = "Blog Listing";
template::inc('intranet','top');

?>
<div class = "has-floats">
	<div class = "float-right blog_listing">
		<div class = "content_listing">
			<input type = "button" onclick = "window.location = '/admin/blog/blogs/add-new'" value = "Add new blog"/>
<?
$cols = "	
			name		{label:	Name		;}
			description	{label:	Description	;}
                        slug            {label: Slug 		;}
                        status		{label: Status		;}
		";
if(auth('blog_author:editor')){
	$cols .= 'edit {}';
}
$clause = array(
		'blog_website' => array(
			'where'=>'blog_website.website_id='.$website_id
		));
$param = array('enable_sort'=>true);
$params = array (	
					"aql"=>'blog',
					'clause'=>$clause,
					"cols"=>$cols,
					"param"=>$param
				);
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
