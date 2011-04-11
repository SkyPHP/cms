<?
$model = $_POST['sky_qs'][0];
$ide = $_POST['sky_qs'][1];
$p->title = 'Edit';
$p->template('skybox', 'top');
aql::form($model, $ide);
$p->template('skybox', 'bottom');