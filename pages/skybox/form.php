<?

$model = $this->queryfolders[0];
$ide = $this->queryfolders[1];

$this->template('skybox', 'top', array(
	'title' => ($ide) ? 'Edit' : 'Add New'
));

?>

<form 
	model="<?=$model?>" 
	method="post" 
	class="aqlForm standard_form" 
	action="/aql/save/<?=$model?>"
	>
<?
	
	$o = new $model;
	if ($ide) $o->loadDB($ide);

	$this->form($o);

?>
	<div class="top-padding float-right">
		<button type="submit" class="button">Save</button>
	</div>
</form>

<?

$this->template('skybox', 'bottom');