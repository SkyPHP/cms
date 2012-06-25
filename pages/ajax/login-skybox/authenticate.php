<?php

// allow cross domain ajax requests
header('Access-Control-Allow-Origin: *');

if (is_numeric($_SESSION['login']['person_id'])) {
	if ($_SESSION['login']['activation_required']) {
?>
		<font color="red">
        You must activate your account before signing in. <br />
        Click <a href="javascript:activation('<?=Login::get('person_ide')?>');">here</a> to resend activation email.
        </font>
<?php
		include 'pages/login/logout.php';
	}
	else {
		echo 'true';
	}
} else {
	echo 'false';
}
