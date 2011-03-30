<? 
if($_REQUEST[blog_id]!='')
{

$aql = "blog {
           keywords
		   where id =$_REQUEST[blog_id]
             
        }";
$rs = aql::select($aql);

echo $rs[0]['keywords'];
}
?>