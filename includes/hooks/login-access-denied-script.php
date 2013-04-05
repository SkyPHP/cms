<?

// check duplicate logins to see if another person (with same user/pass) has access then undeny access
if ( is_array($rs_logins) )
    foreach ( $rs_logins as $person ) {
    if ( auth_person( $access_groups, $person['person_id'] ) ) {
        $access_denied = false;
        $o = new Login;
        $o->person = new person($person['person_id']);
        $o->post_password = $_POST['login_password'];
        $o->post_remember_me = $_POST['remember_me'];
        if ($o->_checkLogin()) {
            $o->doLogin();
            break;
        }
    }
}

