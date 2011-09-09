<?
$title = "Terms & Conditions";
$p->template('website', 'top');
?>

<div class="center">

<?
if(decrypt($p->queryfolders[0],'person')) {
	$reset_hash = aql::value('person.password_reset_hash', $p->queryfolders[0]);
	if ($reset_hash == $p->queryfolders[1]) {
		include('pages/reset-password/includes/set_password_form.php');
	}
	else {
		$o = new person;
		$o->person_ide = $p->queryfolders[0];
		$o->_token = $o->getToken();
		$o->password_reset_hash = "";
		$re = $o->save();
		$hash_mismatch = TRUE;
		include('pages/reset-password/includes/set_hash_form.php');
	}
}
else {
	include('pages/reset-password/includes/set_hash_form.php');
}


?>

</div>

<?
$p->template('website', 'bottom');
?>