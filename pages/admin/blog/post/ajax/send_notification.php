<?
if(!$blog_article){
	$blog_editor = auth('blog_author:editor');
	$blog_article = aql::profile('blog_article',$_POST['blog_article_ide']);
}
?>
<fieldset>
	<legend>Third-Party Contact Info</legend>
		<div class = "smalltext field">After publishing, you should send the templated follow-up email to this person.</div>
	
	<div class = "field">
		<label for = "notification_to">Email</label>
		<input class = "wide100" type = "text" id = "notification_to" name = "notification_to" value = "<?=$blog_article['notification_to'] ?>"/>
	</div>
	<div class = "field">
		<label for = "notification_name">Name</label>
		<input class = "wide100" type = "text" id = "notification_name" name = "notification_name" value = "<?=$blog_article['notification_name'] ?>"/>
	</div>

	<div class = "has-floats">
		<div class = "float-right" id = "send_notification_btn_container">
<?
	if(!$blog_article['notification_sent'] && $blog_editor && $blog_article['status']=='A'){
?>
			<input type = "button" value = "Send Notification" onclick  = "send_notification()"/>
<?
	} elseif($blog_article['notification_sent']) {
?>
		Notification has been sent on <?=date('m/d/Y \a\t G:ia',strtotime($blog_article['notification_sent'])) ?>
<?	
	} else {
?>
	
<?
	}
?>
		</div>
	</div>

</fieldset>