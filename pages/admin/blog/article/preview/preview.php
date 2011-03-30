<? 	
	/*	 lets save the article before Preving	*/
	
	if(trim($_POST['featured'])=="")
	{
		$_POST['featured'] =0;
	}
		
	if(trim($_POST['type'])=="add")
	{
			#saving data in the database
			$aql = "blog_article {
            					blog_id,
								author__person_id,
								title,
								content as article_content,
								blog_category_id,
								status,
								active,
								market_id,
								introduction,
								featured,
								media_item_id
								 
								order by blog_article_id DESC
           					}";
		   
		    

			aql::save();

			$rs_article = aql::select($aql);
 	
			#print_a($rs_article); 
	
			$article_inserted_id =$rs_article[0]['blog_article_id'];
			
			
			/*
				Now in the inserted mode we have the article id. so update blog_article_tag with the currenrent session id
							
			*/
			
			
			if(count($_SESSION['blog_article']['tags']))
			{
				
				
				
				foreach($_SESSION['blog_article']['tags'] as $tag_session_array_id){
					#inserting each 
					$table_name = 'blog_article_tag';
					 
					  
					
					$data_array = array(
    									'blog_article_id' => $article_inserted_id,  
    									'blog_tag_id' => $tag_session_array_id,
    									 
										);
					$rs = aql::insert($table_name, $data_array);
					
									
				
				
				
				}
	
	
				
	
	
	
			}
	
	 
	
	}else if(trim($_POST['type'])=="edit"){
	
	
	//if the condition is update
		$article_id = $_POST['blog_article_id'];
		$condition =" where blog_article.id = $article_id";
		
		
		
		 
		
		
		#saving data in the database
			$aql = "blog_article {
            					blog_id,
								author__person_id,
								title,
								content as article_content,
								blog_category_id,
								status,
								active,
								market_id,
								introduction,
								featured,
								media_item_id
								$condition
								order by blog_article_id DESC
           					}";
		   
		    

			aql::save();

			$rs_article = aql::select($aql);
			
 	
			 
			
			if(count($_SESSION['blog_article']['tags']))
			{
				
				foreach($_SESSION['blog_article']['tags'] as $tag_session_array_id){
					$blog_article_id =$article_id;
					$blog_tag_id =$tag_session_array_id;
					
					
					
					#search this combination in database
		 			 $aql_article_tag = "blog_article_tag{
								*
								where blog_article_tag.blog_article_id = '$blog_article_id' and blog_article_tag.blog_tag_id = '$blog_tag_id'
							}";
					$rs_article_tag = aql::select($aql_article_tag);
					if(count($rs_article_tag)> 0)
					{
						$blog_article_tag_id =$rs_article_tag[0]['blog_article_tag_id'];
						$table_name ='blog_article_tag';
						$data_array = array(
									'active' => 0
									);
						aql::update($table_name, $data_array, $blog_article_tag_id );
							
					
					}
				
				}
				
				
				
				
				
				
				
				foreach($_SESSION['blog_article']['tags'] as $tag_session_array_id){
					#inserting each 
					$table_name = 'blog_article_tag';
					 
					  
					
					$data_array = array(
    									'blog_article_id' => $article_id,  
    									'blog_tag_id' => $tag_session_array_id,
    									 
										);
					$rs = aql::insert($table_name, $data_array);
					
									
				
				
				
				}
	
	
				
	
	
	
			}
	
			 

	
	
		 
	
	
	}
	 
 
 
  	#get the blog name
	if(trim($rs_article[0]['blog_id'])!='')
				{
					$blog_id =trim($rs_article[0]['blog_id']);
					$aql_blog ="blog {
											name,
											slug
											where blog.id  ='$blog_id'
											}";
					 $rs_blog = aql::select($aql_blog);
					 $blog_name =$rs_blog[0]['slug'];
					 
					 }
					 
					 //getting the article ide
					 $article_ide =$rs_article[0]['blog_article_ide'];
					 
					 
					 
					 


	header("location:/$blog_name/$article_ide");
  
?>