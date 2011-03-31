<?
$blog_roll_ide = $_POST['sky_ide'];
$blog_roll = aql::profile('blog_roll',$blog_roll_ide);

if (is_numeric($blog_roll['blog_roll_id'])) $title = $blog_roll['blog_roll_name'];
else $title = 'Add New Blogroll Link';

template::inc('intranet','top');

?>


<div class="col">
	<fieldset>
		<legend>Blogroll Link Information</legend>
<?
		include( 'aql/models/blog_roll/blog_roll_form.php' );
?>
		<input type="button" value="Save" onclick="save_primary_profile('blog_roll_form','blog_roll');">
	</fieldset>
</div>

<?

template::inc('intranet','bottom');
?>