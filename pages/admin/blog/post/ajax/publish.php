<div id = "save_bg">
	<div id = "save_body">
		
<?
if(!$person_id)
	$person_id = $_SESSION['login']['person_id'];
if(!$blog_article){
	$blog_article_ide = $_POST['blog_article_ide'];
	$blog_article = aql::profile('blog_article',$blog_article_ide);
}
$status  = $blog_article['status'];
$trash = $status == 'T'?'Untrash':'Send to Trash';
$blog_editor = auth('blog_author:editor');
$blog_author = !$blog_editor && auth('blog_author:*') && $blog_article['author__person_id'] == $person_id;

if(!$blog_article['blog_article_id'] || $blog_author || $blog_editor){
	$save = $status == 'A'?'Save':'Save Draft';
	$approve =	$blog_editor?($status == 'A'?'Unpublish':'Publish')
							:($blog_author	?($status == 'P' || $status == 'A'?'Unsubmit':'Submit')
											:('No permission'));
?>
		<input type = "hidden" value = "blog_article_status" value = "<?=$status ?>"/>
		<div>
			<input type="button" value="<?=$save ?>" onclick="save_post(true,false,false)">
            <a id="preview_button" href="/<?=$blog_article['blog_slug']?>/<?=$blog_article['blog_article_ide']?>" target="blog_preview">Preview</a>
		</div>
		<div>
<?
	if($blog_article_ide){
?>
			<input type="button" value="<?=$approve ?>" onclick="save_post(true,'<?=($blog_author?($status == 'P' || $status == 'A'?'':'P')
																					:($status == 'A'?'':'A'))?>')">
	
<?
	}
	if($status != 'A'){
?>
			<input type="button" value="<?=$trash ?>" onclick="save_post(true,'<?=$status == 'T'?'':'T' ?>')">
<?
	}
?>
		</div>
<?
} else {
?>
			<div class = "aql_error">You can not make changes to this blog.</div>
<?
}
?>		
		
		<div id = "saving"><span>Saving...</span></div>
	</div>
</div>
