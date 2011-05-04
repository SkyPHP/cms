<?

if ($_SESSION['login']['person_id']) {
    include( 'pages/401.php' );
} else {
    $p->css[] = '/pages/login/login.css';
    $p->template('html5','top');
    $p->script[] = "$.skybox('/login');";
    $p->template('html5','bottom');
}//if