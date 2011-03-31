<?
$title = "My Profile";
template::inc('intranet','top');

?>
<div class = "has-floats">
	<div class = "float-right blog_listing">
		<div class = "content_listing">
<?
aql::form('blog_author_personal');
?>	
			<div class = "has-floats">
				<div class = "float-left">
					<input value = "Save" type = "button" onclick = "tinyMCE.triggerSave(); save_form('blog_author_personal')"/>
				</div>
			</div>	
		</div>
	</div>
	<div class = "left_nav">
<?
include('pages/admin/blog/left-nav/left-nav.php');
?>
	</div>
</div>

<?

template::inc('intranet','bottom');
?>