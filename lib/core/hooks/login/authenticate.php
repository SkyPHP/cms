<?

// logout the current user if applicable
if ($_GET['logout']) {
    unset($_SESSION['login']);
    unset($_SESSION['remember_uri']);
    unset($_COOKIE['password']);
    @setcookie('password', "", time() - 3600, '/', $cookie_domain);
}

$login_username = $_POST['login_username'];
$login_password = $_POST['login_password'];

// auto-login the user if not logged in and there is a 'remember me' cookie
if ( !$_SESSION['login'] && $_COOKIE['password'] && !$login_username ) {
    $login_username = $_COOKIE['username'];
    $login_password = decrypt($_COOKIE['password']);
}

// user authentication
if ( $login_username && $login_password ) {

    $login_username = trim($login_username);
    $login_password = trim($login_password);

    $aql = 	"
        person {
            fname,
            lname,
            email_address,
            password
            where ((
                person.email_address ilike '".addslashes($login_username)."'
                and person.password like '".addslashes($login_password)."'
            ) or (
                person.username ilike '".addslashes($login_username)."'
                and person.password like '".addslashes($login_password)."'
            ))
        }";
    $rs_logins = aql::select($aql);
    $person = $rs_logins[0];
    if ($person) {
        unset($_SESSION['login']);
        $person['username'] = $login_username;
        login_person($person,$_POST['remember_me']);
    }//if
}//if

define( 'PERSON_ID', $_SESSION['login']['person_id'] );
define( 'PERSON_IDE', $_SESSION['login']['person_ide'] );