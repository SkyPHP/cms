<?
$model = $_POST['sky_qs'][0];
$ide = $_POST['sky_qs'][1];
$p->title = 'Edit';
$p->template('skybox', 'top');
?><form model="<?=$model?>" method="post" class="aqlForm standard_form" action="/save/v2/<?=$model?>"><?
	aql::form($model, $ide);
?><input type="submit" value="Save" class="button" /></form><?
$p->template('skybox', 'bottom');