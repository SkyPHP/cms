<?

$_POST['slug'] =slugize($_POST['name']);
$_POST['iorder'] =0;


 
$aql = "blog_category {
			name,
			slug,
			blog_id,
			iorder
			 
			}";

aql::save();



 

echo '<span style="color: #ff0000;">Saved.</span>'; 

?>