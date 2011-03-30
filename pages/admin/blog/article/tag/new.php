<? 
	session_start();
	##Retriving the name
	$name =trim($_POST['name']);
	
	if((trim($name) !="") && (trim($name) !="Add new tag"))
	{
		 
		#check if the string has comma , than split the string
		$tmp_array =array();
		if (strpos($name, ",") !== FALSE) {
		
		
			
			$tmp_array =explode(",", $name);		
		
		
		
		}else{
		
			$tmp_array[] =$name;
		
		}
		
		
		 
		foreach($tmp_array as $name){
			$name =trim($name);
			$tag_name = addslashes($name);
		
		#first search the tag in the table
		$aql_tag = "blog_tag {
							name
							where blog_tag.name ='$tag_name'
							order by blog_tag_id DESC
							}";	
		$rs_tag = aql::select($aql_tag);
		
		 
		
		if(count($rs_tag))
		{
			 
			/*
				This means the tag is already exist in the database
				Now check whether user has add this tag on currerent paghe or not
			
			
			
			
			*/
			
			/* Checking $_SESSION['blog_article']['tags'] is exist or not.  */
			
			if(count($_SESSION['blog_article']['tags']) >0){
			
				if(in_array($rs_tag[0]['blog_tag_id'], $_SESSION['blog_article']['tags'])){
					 	
				
				}else{
					 
				
					$_SESSION['blog_article']['tags'][] =$rs_tag[0]['blog_tag_id'];
				
				}
				
			
			}else{
				 
				$_SESSION['blog_article']['tags'][] =$rs_tag[0]['blog_tag_id'];
			
			}  
			
		}else{
				 
				
				/* IT A NEW ENTRY IN DATA BASE */
								
				$table_name = 'blog_tag';
					$data_array = array(
						'name' => $name				
						);
					$rs = aql::insert( $table_name, $data_array );				 	
					$_SESSION['blog_article']['tags'][] =$rs[0]['id'];	;
		
		
		
		}
		
	}
	
	}
#showing the tag	
	if(count($_SESSION['blog_article']['tags']))
	{
		
		foreach($_SESSION['blog_article']['tags'] as $tag_session_array_id){
			 $aql_tag = "blog_tag {
							name
							where blog_tag.id ='$tag_session_array_id'						 
						}";				
			$rs_tag = aql::select($aql_tag);
			
			foreach($rs_tag as $tag_info){
				?>
					<NOBR>
						<img 
						src="/images/cross_black.jpg" 
						alt="Click here to delete the Tag"
						title ="Click here to delete the Tag"
						onmouseover="this.src='/images/cross_red.jpg'"
						onmouseout="this.src='/images/cross_black.jpg'"
						onclick="deletetag(<?=$tag_info['blog_tag_id']?>)" style="vertical-align:middle;"/>
						<span style="vertical-align:middle;"><?=$tag_info['name']?></span>
					</NOBR>
				<?			
				}
			}
	
	
	
	
	
	
	}
	 
	
		

	 
?>