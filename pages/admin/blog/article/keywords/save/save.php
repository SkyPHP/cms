<? 
//This file will save keywords in the database 	 
	 
	
	#make a new keyword for a blog by adding existing keyword and new keyword
	if(trim($_POST['blog_keywords'])!="")
	{
		$_POST['keyword'] =$_POST['blog_keywords'].",".$_POST['keyword'];
	
	}
	
	 	
	 
	
	$blog_id = $_REQUEST['blog_id'];
	  $aql = "blog {
            keywords as keyword
			where id =$blog_id
        }";
	aql::save();
	$rs = aql::select($aql);
	 
	echo $rs[0]['keyword'];
?>
 