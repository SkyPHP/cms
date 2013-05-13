<?

$template = ($this->is_ajax_request) ? 'skybox' : 'website';

$this->title = "Reset Your Password";
$this->template($template, 'top');

$person_id = decrypt($this->queryfolders[0], 'person');

?>

<div>

<?

if (!$person_id) {

	include 'pages/reset-password/includes/set_hash_form.php';

} else {

	$o = new person($person_id);

	if ($this->queryfolders[1] == $o->password_reset_hash) {

		include 'pages/reset-password/includes/set_password_form.php';

	} else {

		$has_mismatch = true;

		$o->update(array(
			'password_reset_hash' => null
		));

		include 'pages/reset-password/includes/set_hash_form.php';

	}

}

?>

</div>

<?

$this->template($template, 'bottom');
