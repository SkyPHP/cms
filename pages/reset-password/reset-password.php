<?
$title = "Terms & Conditions";
$p->template('website', 'top');
?>

<div class="center">

<?
if(!$p->queryfolders[0] || decrypt($p->queryfolders[0],'person'))
	// IF THERE IS A PERSON ID
	if(!$p->queryfolders[1]) {
		// IF THERE IS NO HASH LET THEM TRY TO RESET THEIR PASSWORD
		include('/pages/reset-password/includes/set_hash_form.php');
	}
	else {
		// IF THERE IS A HASH
		if( true ) {
			// IF THE HASH IS VALID
			include('/pages/reset-password/includes/set_password_form.php');
		}
		else {
			// IF THE HASH IS INVALID CLEAR HASH AND SHOW ERROR
		}
	}
else {
	// IF THERE IS NO PERSON ID
}


?>

</div>

<?
$p->template('website', 'bottom');
?>