<?
$blog_article_ide = $_POST['blog_article_ide'];
$blog_article_id = decrypt($blog_article_ide,'blog_article');
$type = trim($_POST['blog_media_type']);
if($type){
	//only allow one type per blog
	$aql = "blog_media	{
							where type = '$type'
							and blog_article_id = $blog_article_id
						}";
	$rs = aql::select($aql);
	if($rs){
		foreach($rs as $blog_media){
			$fields = array('active'=>0);
			aql::update('blog_media',$fields,$blog_media['blog_media_id']);
		}
	}
	$fields = array(
						'blog_article_id'=>$blog_article_id,
						'type'=>$type				
					);
	$row_id = aql::insert('blog_media',$fields);
	$_POST['db_row_id'] = $row_id[0]['blog_media_id'];
	$_POST['db_field'] = 'blog_media.media_item_id';
}
include ('pages/media/receive_file/receive_file.php');
?>