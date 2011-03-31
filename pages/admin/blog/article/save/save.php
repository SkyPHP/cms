<? 
	$_POST['post_time'] = strtotime($_POST['pub_date'] . ' ' . $_POST['pub_time']); 
	if(trim($_POST['featured'])==""){
		$_POST['featured'] =0;
	}
	if(!stripos($_REQUEST['article_content'],'rel="nofollow"')){
		$undesired = array('<a ', '&lt;a ');
		$_POST['article_content'] = str_replace($undesired, '<a rel="nofollow" ', $_REQUEST['article_content']);
	}
	if(trim($_REQUEST['type'])=="add")
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
								media_item_id,
								post_time,
								tweet_sent
								order by blog_article_id DESC
           					}";
		   
		    

			aql::save();
			$rs_article = aql::select($aql);
			#print_a($rs_article); 
	
			$article_inserted_id =$rs_article[0]['blog_article_id'];
			$article_inserted_ide =$rs_article[0]['blog_article_ide'];

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
	}else if(trim($_REQUEST['type'])=="edit"){
	//if the condition is update
		$article_id = $_REQUEST['blog_article_id'];
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
								media_item_id,
								post_time,
								tweet_sent
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
			// tweet if new posting in applicable market
			$age = floor( time() - strtotime($rs_article[0]['post_time']) ) / ( 60 * 60 * 24 );

			if ( $rs_article[0]['status']=='A' && $age < 2 && !$rs_article[0]['tweet_sent'] && $rs_article[0]['market_id'] ):
			
				$aql = "market {
							slug as market_slug,
							twitter_username,
							twitter_password
							where id = {$rs_article[0]['market_id']}
						}";
				$rs = aql::select($aql);
				$market = $rs[0];
				
				$arr = array('tweet_sent'=>'now()');
				aql::update('blog_article',$arr,$rs_article[0]['blog_article_id']);
				
				if ( $market['twitter_username'] ) {
				
					//tweet
					include('lib/class/class.twitter.php');
					$t = new Twitter($market['twitter_username'],$market['twitter_password']);
					
					$aql = "blog {
								name as blog_name,
								slug as blog_slug
								where id = {$rs_article[0]['blog_id']}
							}";
					$rs = aql::select($aql);
					$blog = $rs[0];
					
					$url = 'http://' . $market['market_slug'] . '.joonbug.com/' . $blog['blog_slug'] . '/' . $rs_article[0]['blog_article_ide'];
					
					$tiny_domain = 'joonb.ug';
					$tinyurl = uize($url,$tiny_domain);
					
					$status_msg = "[{$blog['blog_name']}] {$rs_article[0]['title']}, {$rs_article[0]['introduction']}";
					$status_msg = substr($status_msg,0,100);
					$status_msg .= " {$tinyurl}";
			
					$t->updateStatus($status_msg);
					echo 'Posted to twitter.com/' . $market['twitter_username'] . '<br />';

				}//if
			
			endif;
	echo '<span style="color: #ff0000;">Saved.</span>
	<input type="hidden" id="blog_article_ide" value="'.$article_inserted_ide.'" />';
?>