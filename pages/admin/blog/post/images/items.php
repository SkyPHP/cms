<?
$tab = $tab?$tab:$_POST['tab'];
if (!$media_upload['vfolder_path']) $media_upload['vfolder_path'] = $_POST['vfolder'];
$blog_article_ide = IDE?IDE:$_POST['blog_article_ide'];
$vfolder = $vfolder?$vfolder:media::get_vfolder($media_upload['vfolder_path']);

if (is_array($vfolder['items'])) {
	//check if there is a default image. If there is not, set one for blog_article.media_item_id
	if(!$blog_article){
		$blog_article = aql::profile('blog_article',$blog_article);
	}
	if(!$blog_article['media_item_id']){
		$fields = array (
							'media_item_id'=>$vfolder['items'][0]['media_item_id']
						);
		aql::update('blog_article',$fields,$blog_article_ide);	
		#echo $vfolder['items'][0]['media_item_id'];
		$blog_article['media_item_id'] = $vfolder['items'][0]['media_item_id'];
		$blog_article['media_item_ide'] = $vfolder['items'][0]['media_item_ide'];
	}	
	//go through each image in this blog's folder
	foreach ($vfolder['items'] as $item) {
		$img = media::get_item($item['id'],100,100,0);
		if($tab=='mp3'){
			include('pages/admin/blog/post/ajax/mp3-item.php');
		}else {
			include('item.php');
		}
	}//foreach
} else {
	echo 'No media uploaded.';
}//if
#print_a($blog_article);
?>
