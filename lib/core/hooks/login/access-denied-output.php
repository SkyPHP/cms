<?

if ($_SESSION['login']['person_id']) {
    include( 'pages/401.php' );
} else {
    $p->template('html5','top');
    $p->script[] = "$.skybox('/ajax/login-skybox');";
    $p->template('html5','bottom');
}//if