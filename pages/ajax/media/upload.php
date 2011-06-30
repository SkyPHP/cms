<?php

//    if (isset($_POST["PHPSESSID"])) {
//        session_id($_POST["PHPSESSID"]);
//    }

//    session_start();
$errors = array();
// Check the upload
if (!isset($_FILES["file"]) || !is_uploaded_file($_FILES["file"]["tmp_name"]) || $_FILES["file"]["error"] != 0) {
    $errors[] = 'Error: Invalid upload!';
}

if ($_FILES["file"]["tmp_name"] && !$errors) {
	//$ext = substr($_FILES["Filedata"]["name"],strrpos($_FILES["Filedata"]["name"],'.'));
	$uploaded_file = ini_get('upload_tmp_dir') . '/' . $_FILES["file"]["name"];
	//$uploaded_file = $sky_media_local_path . $_SESSION['media_browse']['vfolder'] . '/img_' . time() . rand(1000,9999) . $ext; 
	//$uploaded_file = $uploaded_file;
    move_uploaded_file($_FILES["file"]["tmp_name"], $uploaded_file);
    // print $uploaded_file;
} else {
    $errors[] = "Error: No file uploaded!";
}
 if (!$errors) {
    $vfolder_path = $_POST['vfolder'];
    if ( !$vfolder_path ) $vfolder_path = $_SESSION['media_browse']['vfolder'];

    $item = media::new_item($uploaded_file,$vfolder_path);
    if ($_POST['media_debug']) print_r($item);
    unlink($uploaded_file);

    // update the specified database field with the newly inserted media_item_id
    if ( $_POST['db_field'] != 'undefined' && $_POST['db_row_ide'] != 'undefined'):
    	$dot = strpos( $_POST['db_field'], '.' );
    	$table = substr( $_POST['db_field'], 0, $dot );
    	$field = substr( $_POST['db_field'], $dot + 1 );
    	$update = array( $field => $item[0]['media_item_id'] );
        $row_id = decrypt($_POST['db_row_ide'], $table);
    	$row_changed = aql::update($table,$update,$row_id);
        if ($row_changed) {
            $row_changed = array(
                'table' => $table,
                'field' => $field,
                'update' => $update,
                'row_id' => $row_id
            );
        }
    endif;

    $_SESSION['media']['upload']['media_item_id'] = $item[0]['media_item_id'];
    $_SESSION['media'][ 'upload_'.$_POST['unique'] ]['media_item_id'] = $item[0]['media_item_id'];
}
if ( media::$error ) {
	$errors[] = media::$error;
} 

if ($errors) {
    $re = array(
        'status' => 'Error',
        'errors' => $errors
    );
} else {
    $re = array(
        'status' => 'OK',
        'data' => $item[0],
        'row_changed' => $row_changed
    );
}
json_headers();
exit(json_encode($re));