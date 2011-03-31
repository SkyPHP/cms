<?
$func = $_POST['func'];
$media_item_ide = $_POST['media_item_ide'];
$media_item_id = decrypt($media_item_ide,'media_item');
$blog_article_ide = $_POST['blog_article_ide'];
$blog_article_id = decrypt($blog_article_ide,'blog_article');
$type = $_POST['type'];
$blog_editor = auth('blog_author:editor');

$person_id = $_SESSION['login']['person_id'];
$hacking = 'Hacking attempt has been detected. Developers and police have been notified.';

if($func == 'set_article_image'){
	$fields = array(
						'media_item_id'=>$media_item_id,
						'mod_time'=>'now()'
					);
	aql::update('blog_article',$fields,$blog_article_ide);
	exit('success');
} elseif ( $func == 'set_tbox' || $func == 'set_mebox' || $func == 'set_tnail' || $func == 'set_arimg'){
	$type = str_replace('set_','',$func);
	$aql = "blog_media {	
							where blog_article_id = $blog_article_id
							and type = '$type'
							limit 1
						}";
	$rs = aql::select($aql);
	if($rs){
		$fields = array	(
							'media_item_id'=>$media_item_id,
							'update_time'=>'now()'
						);
		aql::update('blog_media',$fields,$rs[0]['blog_media_id']);
	} else {
		$fields = array (
							'type'=>$type,
							'media_item_id'=>$media_item_id,
							'blog_article_id'=>$blog_article_id,
							'insert_time'=>'now()'
						);
		aql::insert('blog_media',$fields);
	}
	exit('success');
} elseif ($func == 'set_def'){
	$fields = array(
					'update_time'=>'now()',
					'media_item_id'=>$media_item_id,
					'mod__person_id'=>$person_id
					);
	aql::update('blog_article',$fields,$blog_article_id);
	exit('success');
} elseif ($func == 'insert_image') {
	$caption = $_POST['caption'];
	$credits = $_POST['credits'];
	$fields = array(
						'caption' => $caption,
						'credits' => $credits,
						'mod_time' => 'now()'
					);
	aql::update ('media_item',$fields,$media_item_id);
	if($_POST['tab'] == 'slideshow'){
		$aql = "blog_article{
								where blog_article.ide = $blog_article_ide 
								and media_item_ide = $media_item_ide
							}";
		$rs = aql::select($aql);
		if(!$rs){
			$vfolder_path = '/blog/blog_article/'.$blog_article_id.'/images';
			$img = media::get_item($media_item_ide);
			media::new_item($img['local_path'],$vfolder_path,$fields);
		}
	}
	$img = media::get_item($media_item_id,$_POST['width'],$_POST['height'],$_POST['crop']);
/*
?>
	<div class="article_image">
<?
*/
?>
		<img class="article_image" src="<?=$blog_img_absolute?'http://'.$_SERVER['SERVER_NAME']:''?><?=$img['src']?>" border="0" alt="" width="<?=$img['width']?>" height="<?=$img['height']?>"/>
<?
	if( false && $img['caption']){
?>
		<div class = "article_image_caption"><?=$img['caption'] ?></div>
<?
	} 
	if( false && $img['credits']){
?>
		<div class= "article_image_credits"><?=$img['credits'] ?></div>
<?
	}
/*
?>
	</div>
<?
*/

} elseif ($func == 'iorder'){
	$media_item_ides = explode(',',$_POST['order']);
	foreach($media_item_ides as $key=>$media_item_ide){
		$fields = array('iorder'=>$key);
		aql::update('media_item',$fields,$media_item_ide);	
	}
	exit('success');
} elseif($func == 'save_youtube_video'){
	$blog_article_video_ide = $_POST['blog_article_video_ide'];
	$youtube_url = $_POST['youtube_url'];
	$title = $_POST['title'];
	$fields = array(
						'blog_article_id'=>$blog_article_id,
						'youtube_url'=>$youtube_url,
						'title'=>$title,
						'type'=>$type,
						'insert_time'=>'now()',
						'mod__person_id'=>$person_id
					);
	$rs = aql::insert('blog_article_video',$fields);
	exit('success');
} else if($func == 'delete_youtube_media'){
	$blog_article_video_ide = $_POST['blog_article_video_ide'];
	$fields = array(
						'active'=>0,
						'update_time'=>'now()',
						'mod__person_id'=>$person_id
					);
	$rs = aql::update('blog_article_video',$fields,$blog_article_video_ide);
	exit('success');
} elseif($func == 'suggest_venue'){
	$string = addslashes(trim($_POST['input']));
	$aql = "venue	{
						name,
						state,
						city
						where name ilike '%$string%'
						order by name asc
					}";
	$rs = aql::select($aql);
	if($rs){
?>
		<div class = "suggestions">
<?
		foreach($rs as $venue){
?>
			<a name = "<?=$venue['venue_ide'] ?>" href = "javascript:void(0)" onclick = "insert_venue(this)"><span class = "venue_name"><?=$venue['name'] ?></span> (<?=$venue['city'] ?>, <?=$venue['state'] ?>)</a>
<?
		}
?>
		</div>
<?
	}else{
?>	
		<div class = "suggestions">
			<input type = "button" value = "Add <?=$string ?>" onclick = "add_venue()"/>
		</div>
<?	
	}
}elseif ($func == 'set_venue'){
	$venue_id = decrypt($_POST['venue_ide'],'venue');
	$fields = array(
						'venue_id' => $venue_id,
						'mod_time' => 'now()',
						'mod__person_id' => $person_id
					);
	aql::update('blog_article',$fields,$blog_article_id);
	exit('success');
} elseif ( $func == 'suggest_tag'){
	$new_tag = addslashes(trim($_POST['input']));
	if($new_tag){
		$sql = "SELECT	COUNT(name) AS qty,
						LOWER(TRIM(name,' ')) AS name_trimmed
						FROM blog_article_tag
						WHERE name ILIKE '$new_tag%' OR name ILIKE '% $new_tag%'
							AND active = 1
						GROUP BY name_trimmed
						ORDER BY name_trimmed ASC,qty DESC
						LIMIT 100";
		$rs = $db->Execute($sql) or die("<pre>$sql<br>" . $db->ErrorMsg().'</pre>');
		if (!$rs->EOF){
			$rs = $rs->GetArray();
		}
		if(is_array($rs)){
?>
			<div class = "suggestions">
<?
			foreach($rs as $tag){
?>
				<a onclick = "insert_tag(this)" href="javascript:void(0)">
					<span class = "blog_article_tag_name"><?=$tag['name_trimmed']?></span>(<?=$tag['qty'] ?>)
				</a>
<?
			}
?>
			</div>
<?
		} else {
		}
	}
} elseif ($func == 'delete_media'){
	//see if deleted media is the default one, find another default image
	$aql = "blog_article{
							
						}
			media_item	{
							where media_item.id = $media_item_id
						}";
	$rs = aql::select($aql);
	if($rs) {
		//oh no, we are deleting default
		//find another media that is uploaded here and make it default
		$aql = "blog_media	{
								media_item_id
								where blog_article_id = $blog_article_id
								order by insert_time asc
								limit 1
							}";
		$rs = aql::select($aql);
		if($rs){
			//there is some media here
			$fields = array(
							'mod__person_id'=>$person_id,
							'media_item_id'=>$rs[0]['media_item_id']
							);
			aql::update ('blog_article',$fields,$blog_article_id);
			//now we can delete
		} else {
			//there is no more media so just set it to null
			$fields = array(
							'mod__person_id'=>$person_id,
							'media_item_id'=>null
							);
			aql::update ('blog_article',$fields,$blog_article_id);
		}
	}
	$fields = array(
						'active' => 0,
						'mod__person_id'=>$person_id,
						'update_time'=>'now()'
					);
	$aql = "blog_media	{
							where blog_article_id = $blog_article_id
							and media_item_id = $media_item_id
						}";
	$rs = aql::select($aql);
	if($rs){
		foreach($rs as $blog_media){
			//delete from blog_media
			aql::update('blog_media',$fields, $blog_media['blog_media_id']);
			//delete from media item
			aql::update('media_item',$fields, $media_item_id);
		}
	}
	aql::update('media_item', $fields, $media_item_id);
	exit('success');
} elseif ($func == 'set_post_status'){
	$aql = "blog_article{
							title,
							status,
							author__person_id,
							approved__person_id
							where blog_article.id = $blog_article_id
						}
			blog{
					notification_from
				}
			person on person.id = author__person_id	{
				email_address as author_email_address,
				fname as author_fname,
				lname as author_lname
			}";
	$rs = aql::select($aql);
	if($rs){
		$title = $rs[0]['title'];
		$cur_status = $rs[0]['status'];
		$author = $rs[0]['author__person_id'];
		$author_email = $rs[0]['author_email_address'];
		$author_name = $rs[0]['author_fname'].' '.$rs[0]['author_lname'];
		$notification_from = $rs[0]['notification_from'];
		$editor_id = $rs[0]['approved__person_id'];	
	} else {
		exit('Article could not be found!');
	}
	$blog_author =  !$blog_editor && auth('blog_author:*') && $author == $person_id;
	$status = $_POST['status'];
	
	if($blog_editor || ($blog_author && $status != 'A')) {	
		$fields = array(
							'status'=>$status,
							'mod__person_id'=>$person_id,
							'mod_time'=>'now()'
						);
		if($status == 'P' && $blog_author && $author_email && false){
			$url = $_SERVER['SERVER_NAME'].'/admin/blog/post/'.$blog_article_ide;
			$message = "Article, \"$title\", has been submitted for publishing.  You can publish it here: <a href = 'http://$url'>http://$url</a>.";
			$subject = "An article is ready for publishing.";
			$from = $author_email;
			//get all the editors and notify them
			$aql = "blog_author {
									blog_id,
									where website_id = $website_id
									and blog_author.access_group ilike '%editor%'
									and status = 'A'
								}
					person	{
								fname,
								lname,
								email_address
							}";
			$rs = aql::select($aql);
			if($rs){
				foreach($rs as $editor){
					$to = $editor['email_address'];
					email($message,$subject,$to,$from);
				}
			} else {
				exit("Error: There are no editors on this website, this article will never be published.");
			}
			
		}
		if($status = 'A' && $blog_editor){
			$fields['post_time']='now()';
			$fields['approved__person_id']=$person_id;
		}
		if($cur_status == 'A' && $blog_author){
			//get info from publisher and notify him that blog published by him has been edited
			$aql = "person {
								email_address,
								fname,
								lname
								where person.id = $editor_id
							}";
			$rs = aql::select($aql);
			if($rs){
				$to = $rs[0]['email_address'];
				$from = $notification_from;
				email($message,$subject,$to,$from);
			} else {
				exit("This blog has not been approved by anyone yet! ");
			}	
		}
		aql::update('blog_article',$fields,$blog_article_id);
		exit('success');
	} else {
		exit('There was an error running this page. Please contact the system administrator.');
	} 
} elseif ($func == 'send_notification'){
	$email = $_POST['notification_to'];
	$name = $_POST['notification_name'];
	//send notification
	if($email && $blog_editor){
		$aql = "blog_article{
								notification_sent
								where blog_article.id = $blog_article_id
								limit 1
							}
				blog{
						notification_from,
						notification_subject,
						notification_template
					}";
		$rs = aql::select($aql);

		if($rs[0]['notification_template'] && !$rs[0]['notification_sent']){
			// subject
			$to  = "{$name} <{$email}>";
			$subject = $rs[0]['notification_subject'];
			$message = $rs[0]['notification_template'];
			$from = $rs[0]['notification_from'];
			email($message,$subject,$to,$from);
			// update blog_article
			$fields = array(
							'notification_sent'=>'now()'
							);
			aql::update ('blog_article',$fields,$blog_article_id);
			exit('Notification has been sent.');
		} else {
			exit('Error: notification template has not been created or notifacation has already been sent.');
		}
	} else {
		exit("Error: Enter valid target email address.");
	}
} elseif ($func == 'set_blog_media_title'){
	$title = $_POST['title'];
	$type = $_POST['type'];
	$aql = "blog_media	{
							where media_item_ide = $media_item_ide
							and type = '$type'
						}";
	$rs = aql::select($aql);
	if($rs){
		$fields = array(
							'title'=>$title,
							'update_time'=>'now()',
							'mod__person_id'=>$person_id
						);
		aql::update('blog_media',$fields,$rs[0]['blog_media_id']);
		exit('success');
	}else{
		exit('Error: could not find this media file in database.');
	}	
} else {
	exit('Error: Unrecognized function.');
}
function email($message_text,$subject,$to,$from){
	// message
	$message = "
	<html>
	<head>
	  <title>{$subject}</title>
	</head>
	<body>
	  {$message_text}
	</body>
	</html>";

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= "From: {$from}" . "\r\n";

	// Mail it
	mail($to, $subject, $message, $headers);
}
?>