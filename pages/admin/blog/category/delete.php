<?



$_POST['blog_category_ide'] = $_POST['sky_ide'];
$_POST['active'] = 0;

 
$aql = "blog_category {
			name,
			active			 
			}";

aql::save();

echo "deleted";


 

?>