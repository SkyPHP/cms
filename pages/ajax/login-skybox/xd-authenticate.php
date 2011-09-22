<?

// cross domain login

if (!Login::isLoggedIn() && $_GET['login_username'] && $_GET['login_password']) {
    $o = new Login($_GET['login_username'], $_GET['login_password'], $_GET['remember_me']);
    $re = $o->checkLogin();
    if ($re['status'] == 'OK') {
        $o->doLogin();
    }
}

//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: x-requested-with");
header("Content-type: text/javascript");
?>
check_login('<?=is_numeric(PERSON_ID)?'true':'false'?>');