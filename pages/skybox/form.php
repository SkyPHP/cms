<?php

$namespace = '\\Crave\\Model\\';

$model_name = $this->queryfolders[0];
$model = $namespace.$model_name;
$ide = $this->queryfolders[1];

$this->template('skybox', 'top', array(
	'title' => ($ide) ? 'Edit' : 'Add New'
));

?>

<form 
	model="<?=$model_name?>" 
	method="post" 
	class="aqlForm standard_form" 
	action="/aql/save/<?=$model_name?>"
	>
<?
	
	$o = ($ide) ? new $model($ide) : new $model;

	$this->form($o);

?>
	<div class="top-padding float-right">
		<button type="submit" class="button">Save</button>
	</div>
</form>

<?

$this->template('skybox', 'bottom');