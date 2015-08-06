<?php

if (Login::isLoggedIn()) {

	// access denied
    include 'pages/401.php';
    return;

} 

// otherwise do the login page
$p->template('html5', 'top', array(
	'title' => 'Login',
	'script' => array(
		"
			$(document).ready(function() {
				$.skybox('/ajax/login-skybox');
			});
		"
	)
))->template('html5', 'bottom');