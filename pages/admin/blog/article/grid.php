<?
 
$blog_id =trim($_REQUEST['blog_id']);

#sorting variable
$sort_by =$_REQUEST['sort_by'];
$sort_type =$_REQUEST['sort_type'];

$order_by =$sort_by. " ".$sort_type; 
$condition ="";
if($blog_id!="")
{
	$condition = " where blog.id ='".$blog_id."'";

}

 


echo $aql_blog_title = "blog {
            name
        }
        blog_article {
            title             
        }";
		
 $rs_blog = aql::select($aql_blog_title);
 
 //print_a($rs_blog);

// step 2. specify which columns to add to the grid
    $cols = "id {}
				name {
                label: Blog;
            }
			title {
			label: Article;
		}
			
			";
 
    // step 3. display the data grid on the page
  $param = array(
	'table_class' => ''
);


 

grid::render($aql_blog_title,$col,$param);
 
 
?>