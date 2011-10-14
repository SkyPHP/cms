<?

// this is the new upload script

include 'lib/vfolder/class.vf_upload_handler.php';

$handler = new vf_upload_handler($_POST, $_FILES);
$handler->validate(); // makes sure that upload is proper
$response = $handler->doUpload(); // will not run if there are errors
exit_json($response);