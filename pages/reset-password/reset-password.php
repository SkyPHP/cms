<?php

use \Sky\Model\person;

$_POST['refresh'] = 1 ;

$template = ($this->is_ajax_request) ? 'skybox' : 'website';

$this->title = "Reset Your Password";
$this->template($template, 'top');

$person_id = decrypt($this->queryfolders[0], 'person');

?>

<div class="center">

<?

//use 'skybox' to determine if "forgot password" was selected. Email hash link uses 'website' and will thus skip to the else.
if (!$person_id || $template == "skybox") {

	include 'pages/reset-password/includes/set_hash_form.php';

} else {

	$o = new person($person_id);


	if ($this->queryfolders[1] == $o->password_reset_hash) {
 
		include 'pages/reset-password/includes/set_password_form.php';

	} else {

		$has_mismatch = true;

		//$o->saveProperties(array( 'password_reset_hash' => null ));
		$o->update(array( 'password_reset_hash' => null ));

		include 'pages/reset-password/includes/set_hash_form.php';

	}

}

?>

</div>

<?

$this->template($template, 'bottom');
