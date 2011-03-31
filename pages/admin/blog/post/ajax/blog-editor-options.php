<?
if(!$blog_article_ide){
	$blog_article_ide = $_POST['blog_article_ide'];
	$blog_editor = auth('blog_author:editor');
}
if($blog_editor && $blog_article_ide){
$aql = "blog_media	{
							approved,
							type
							where blog_media.blog_article_ide = $blog_article_ide 
							and (type = 'mebox' or type = 'tbox')
							and media_item_id is not null
						}";
	$rs = aql::select($aql);
	if($rs){
?>
<fieldset>
	<legend>Blog Editor Options</legend>
<?
	


		foreach($rs as $blog_media){
?>
			<div>
<?
			if($blog_media['type']=='tbox'){

				snippet::checkbox(array(
											'name'=>'approve_tbox',
											'label'=>'Display in topbox.',
											'checked'=>$blog_media['approved']
										));

			}elseif($blog_media['type']=='mebox'){
				snippet::checkbox(array(
											'name'=>'approve_mebox',
											'label'=>'Display in mediabox.',
											'checked'=>$blog_media['approved']
											));
			}
?>
			</div>
<?		
		
?>
		
<?
	}
?>
	</fieldset>
<?
	}
}
?>
