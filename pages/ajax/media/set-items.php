<?
$errors = array();
if (is_array($_POST['order'])) {
	$order = $_POST['order'];
	foreach ($order as $k => $ide) {
		$media_item_id = decrypt($ide, 'media_item');
		if (is_numeric($media_item_id)) {
			$fields = array( 'iorder' => $k + 1 );
			aql::update('media_item', $fields, $media_item_id);
		}
	}
	$re = array(
		'status' => 'OK'
	);
} else {
	$errors[] = 'No images to order.';
	$re = array(
		'status' => 'Error',
		'errors' => $errors
	);
}

json_headers();
exit(json_encode($re));