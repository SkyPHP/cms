<?php

use \Sky\Model\person;

$_POST['refresh'] = 1 ;

$errors = array();

$email = trim($_POST['email_address']);

$clause_array = array(
    'where' => array(
        "person.email_address != ''",
        "person.email_address is not null",
        "person.email_address ilike '{$email}'"
    ),
    'limit' => 1
);

$person = person::getOne($clause_array);

if (!$person->id) {
    $errors[] = 'The email address you entered is not registered with us. Try creating a new account.';
}

if ($errors) {
    exit_json(array(
        'status' => 'Error',
        'errors' => $errors
    ));
}

$re = $person->update(array(
    'password_reset_hash' => sha1(mt_rand())
));

if ($re->_errors) {
    exit_json($re->_errors);
}

$mlr = new Mailer;
$mlr->addTo($person->email_address)
    ->setMethod('mandrill')
    ->setSubject("Password Recovery")
    ->setFrom('passwords@cravetickets.com')
    ->addBcc('passwords@cravetickets.com')
    ->setCredentials((object)['api'=>API_MANDRILL_SECRET])
    ->inc('includes/Mailers/reset-password.php', array(
        'person' => $person,
        'host' => $_SERVER['HTTP_HOST']
    ))->send();
exit_json([
    'status' => 'OK',
    'email_address' => $re->email_address
]);
