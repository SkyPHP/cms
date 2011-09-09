<?

$errors = array();

$clause_array = array ( 'where' => "( person.email_address ilike '".$_POST['email_address']."' AND person.password_hash IS NOT NULL )",
						'limit' => 1 );

$person = person::getByClause($clause_array);

if (!$person->person_id) {
	$errors[] = 'The email address you entered is not registered with Crave Tickets. Try creating a new account.';
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
$body ='You recently asked to reset your Crave Tickets password. To complete your request, please follow this link:<br />
<br />
http://cravetickets.info/password-reset/'.$o->person_ide.'/'.$o->password_reset_hash.'<br />
<br />
If you did not request a new password, you may disregard this message or visit our Help Center at http://www.facebook.com/help/?topic=password_reset to learn more.'; // Not going recursive here. (in a heredoc) 
$mailer = new Mailer; 
$mailer->addTo($_POST['email_address'])
// can add multiple recipients
	->setBody($body) 
	->setSubject('Resetting your Cravetickets/Joonbug Password')
	->setFrom('Crave Tickets <info@cravetickets.com>') // defaults to info@cravetickets.com
	->setContentType('html') // accepts 'text' or 'html' 
	->send(); 


exit_json($re);