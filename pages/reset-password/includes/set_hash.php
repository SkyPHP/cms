<?

$errors = array();

$clause_array = array ( 'where' => "( person.email_address ilike '".$_POST['email_address']."' AND person.password_hash IS NOT NULL )",
						'limit' => 1 );

$person = person::getByClause($clause_array);

if (!$person->person_id) {
	$errors[] = 'The email adderss you entered is not registered with Crave Tickets.';
}

if ($errors) {
exit_json(array('status' => 'Error', 'errors' => $errors));
}

$o = new person;
$o->person_id = $person->person_id;
$o->_token = $o->getToken();
$o->password_reset_hash = sha1(mt_rand());
$re = $o->save();

if ($re['status'] != 'OK') {
exit_json($re);
}

// do emailing



exit_json($re);