<?php

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

$person = person::getByClause($clause_array);

if (!$person->person_id) {
    $errors[] = 'The email address you entered is not registered with us. Try creating a new account.';
}

if ($errors) {
    exit_json(array(
        'status' => 'Error',
        'errors' => $errors
    ));
}

$re = $person->saveProperties(array(
    'password_reset_hash' => sha1(mt_rand())
));

if ($re['status'] != 'OK') exit_json($re);

$mlr = new Mailer;
$mlr->addTo($person->email_address)
    ->inc('reset-password', array(
        'person' => $person,
        'host' => $_SERVER['HTTP_HOST']
    ))->send();

exit_json($re);
