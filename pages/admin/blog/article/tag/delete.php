<? 
	session_start();
	
 
	
	#seperating the add and edit section
	
	if(trim($_REQUEST['mode'])=='add')
	{
		$blog_tag_id =$_REQUEST['blog_tag_id'];
		
		#deleting a tag on add page
		
		
		/*
			On delete page.There is no entry in article_tag table.
			so just remove the id from session.		
		
		*/
		
		#removing that element from array
		$_SESSION['blog_article']['tags']  =array_values(array_diff($_SESSION['blog_article']['tags'],array($blog_tag_id)));
		
		
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
	
	
	}else if(trim($_REQUEST['mode'])=='edit'){
		#deleting a tag on edit page	
	
		 
	
		$blog_article_id =$_REQUEST['blog_article_id'];
		$blog_tag_id =$_REQUEST['blog_tag_id'];
		
		
		#search this combination in database
		  $aql_article_tag = "blog_article_tag{
								*
								where blog_article_tag.blog_article_id = '$blog_article_id' and blog_article_tag.blog_tag_id = '$blog_tag_id'
							}";
		$rs_article_tag = aql::select($aql_article_tag);
		
		  
		if(count($rs_article_tag)> 0)
		{
			 
			
			/*
				if the combination exist in the database, thn remove it from blog_article_tag table but do not touch blog_tag table
				Refer code in tag.php same in article for this
			*/
			#get the id of this combination needed for update
				$blog_article_tag_id =$rs_article_tag[0]['blog_article_tag_id'];
				
			#now delete this result from the same table
				$table_name ='blog_article_tag';
				$data_array = array(
							'active' => 0
							);
				aql::update($table_name, $data_array, $blog_article_tag_id );
		
		
		} 
		 
		
		#removing that element from array
		$_SESSION['blog_article']['tags']  =array_values(array_diff($_SESSION['blog_article']['tags'],array($blog_tag_id))); 
		
		
		
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
						onclick="deletetag(<?=$tag_info['blog_tag_id']?>, <?=$blog_article_id?>)" style="vertical-align:middle;"/>
						<span style="vertical-align:middle;"><?=$tag_info['name']?></span>
					</NOBR>
				<?			
				}
			}
	
	
	
	
	
	
	}
		 
	
	}
	
	
	 
	
	
?>