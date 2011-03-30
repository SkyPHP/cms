<?
$title = 'AQL Editor';
template::inc('intranet','top');
include(INCPATH.'/../dev-nav.php');
?>

<form method="post">
	<textarea name="aql" style="width:500px;height:500px;"><?=$_POST['aql']?></textarea>
	<input type="submit" value="Submit">
</form>

<?
if ($_POST['aql']) {
	
	$aql = $_POST['aql'];
	$rs = aql::select($aql);
	print_a($rs);
	
}//if

template::inc('intranet','bottom');
?>