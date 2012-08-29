<?php

$handler = new \Sky\VF\UploadHandler($_POST, $_FILES);

// makes sure that upload is proper
$handler->validate();

// will not run if there are errors
exit_json($handler->doUpload());
