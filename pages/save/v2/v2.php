<?
$model_name = $_POST['sky_qs'][0];
if (!preg_match('/^[\w0-9]+$/', $model_name)) exit;
if (!$_POST) exit;
$m = model::get($model_name);
$m->loadArray($_POST);
$arr = $m->save();
json_headers();
exit(json_encode($arr));