<?

Login::make();

if ($_GET['logout']) {
    Login::unsetLogin();
}

if (!Login::isLoggedIn() && $_POST['login_username'] && $_POST['login_password']) {
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
    Login::setConstants();
}