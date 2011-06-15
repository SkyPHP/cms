<?
$model = $p->queryfolders[0];
$ide = $p->queryfolders[1];
$p->title = ($ide) ? 'Edit' : 'Add New';
$p->template('skybox', 'top');
?><form model="<?=$model?>" method="post" class="aqlForm standard_form" action="/save/v2/<?=$model?>"><?
	aql::form($model, $ide);
?><input type="submit" value="Save" class="button" /></form><?
$p->template('skybox', 'bottom');