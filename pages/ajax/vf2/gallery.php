<?php

//  gallery

$_GET['curl_timeout'] = 20;

if (!$_POST['_token']) {
	return;
}

$token = $_POST['_token'];

$pars = $_SESSION['VF']['gallery'][$token];

if (!$pars) {
	throw new \Exception('Invalid token to generate gallery.');
}

$pars = (array) $pars;

$pars['folder'] = $pars['folder']->path;

echo vf::gallery($pars)->html;
