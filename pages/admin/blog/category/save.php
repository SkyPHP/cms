<?


$_POST['blog_category_ide'] = $_POST['sky_ide'];



$aql = "blog_category {
			name,
			slug,
			blog_id,
			iorder
			 
			}";
aql::save();

echo '<span style="color: #ff0000;">Saved.</span>';
 

?>