<?

$access_denied = ($access_groups) ? true : false;

Login::make();

if ($_GET['logout']) {
    Login::unsetLogin();
}

if ($_POST['login_username'] && $_POST['login_password']) {
    $o = new Login($_POST['login_username'], $_POST['login_password'], $_POST['remember_me']);
    $re = $o->checkLogin();
    if ($re['status'] == 'OK') {
        $o->doLogin();
    }
}

if (!Login::isLoggedIn()) {
    $o_cookie = person_cookie::getByCookie();
    if ($o_cookie) {
        if ($o_cookie->checkToken()) {
            $o = new Login;
            $o->person = new person($o_cookie->person_id);
            $o->doLogin();
        }
    }
}

if (Login::isLoggedIn()) {
    if ($access_groups) { 
        if (auth_person($access_groups, $_SESSION['login']['person_id'])) {
            $access_denied = false;
        }
    } 
    Login::setConstants();
}