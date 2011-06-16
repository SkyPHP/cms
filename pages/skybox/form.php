<?
$model = $p->queryfolders[0];
$ide = $p->queryfolders[1];
$p->title = ($ide) ? 'Edit' : 'Add New';
$p->template('skybox', 'top');
?><form model="<?=$model?>" method="post" class="aqlForm standard_form" action="/save/v2/<?=$model?>"><?
	aql::form($model, $ide);
?><div class="top-padding float-right"><button type="submit" class="button">Save</button></div></form><?
$p->template('skybox', 'bottom');