<?
$model_name = $p->queryfolders[0];
if (!preg_match('/^[\w0-9]+$/', $model_name)) {
	$response = array(
		'status' => 'Error',
		'errors' => array('Invalid Model Name')
	);
} else if (!$_POST) {
	$response = array(
		'status' => 'Error',
		'errors' => array('No Data Submitted In Request')
	);
} else {
	$m = model::get($model_name);
	$response = $m->loadArray($_POST)->save();
}
if ($p->is_ajax_request) {
	exit_json($response);
} else {
	$to = ($_GET['return_uri']) ? $_GET['return_uri'] : $_SERVER['HTTP_REFERER'];
	if ($response['status'] == 'OK') {
		$get = array('status' => 'OK');
	} else {
		$get = $response;
	}
	$get = '?return='.rawurlencode(serialize($get));
	$p->redirect($to.$get, 302);
}