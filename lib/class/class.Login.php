<?

class Login {

	public static $session;
	public static $session_key;

	public $post_remember_me;
	public $post_username;
	public $post_password;

	public $_errors;
	public $person;

	public function __construct($username = null, $password = null, $remember_me = false) {
		$this->post_username = addslashes(trim($username));
		$this->post_password = addslashes(trim($password));
		$this->post_remember_me = ($remember_me) ? true : false;
	}

	public static function make() {
		self::$session_key = self::getSessionLoginKey();
		if ($_SESSION[self::$session_key]['person_id']) $_SESSION['login'] = &$_SESSION[self::$session_key];
		if (is_array($_SESSION['login'])) foreach ($_SESSION['login'] as $key => $v) {
			self::$session[$key] = $v;
		}
	}

	public static function isLoggedIn() {
		return (Login::get('person_id'));
	}

	public function checkLogin() {
		global $access_groups, $access_denied, $rs_logins;

		if (!$this->post_password) {
			$this->_errors[] = 'You need to enter a password.';
		} 
		if (!$this->post_username) {
			$this->_errors[] = 'You need to enter a username or email address';
		}

		if ($this->_errors) {
			return $this->r();
		}

		$aql = 	"
					person {
						where (
							email_address ilike '{$this->post_username}' or username ilike '{$this->post_username}'
							and password_hash is not null
						)
						order by id desc
					}
				";
		$rs_logins = aql::select($aql);
		if ($this->post_password) {
			$granted = false;
			foreach ($rs_logins as $p) {
				$this->person = new person($p['person_id'], null, true);
				if (!$this->person->person_id) continue;
				if ($this->_checkLogin($this->post_password)) {
					if (auth_person($access_groups, $this->person->person_id) || !$access_groups) {
						$access_denied = false;
						return $this->r(array('person_ide' => $this->person->person_ide));
					}
				}
			}
		}
		$this->_errors[] = 'Invalid Login';
		return $this->r();
	}

	public function _checkLogin($password) {
		$salt = $this->person->generateUserSalt();
		$pw = Login::generateHash($password, $salt);
		// return ($password == $this->person->password); // temp fix while new hash algo
		return ($pw == $this->person->password_hash);
	}

	public function doLogin() {
		Login::unsetLogin();
		$login = array(
			'person_id' => $this->person->person_id,
			'person_ide' => $this->person->person_ide,
			'fname' => $this->person->fname,
			'lname' => $this->person->lname,
			'email' => $this->person->email_address
		);
		$this->person->updateLastLoginTime();
		Login::mset($login);
		if ($this->post_remember_me) {
			person_cookie::create($this->person->person_id);
		}

	}

	public function keyTypeToLowerCase($ide) {
		return my_base_convert($ide, 62, 36);
	}

	public function keyTypeToIDE($val) {
		return my_base_convert($val, 36, 62);
	}

	public function getSessionLoginKey() {
		global $login_session_key_type;
		if (!$login_session_key_type) $login_session_key_type = 'person';
		$subdomain = page::getSubdomainName();
		if (!$subdomain) return '_login';
		$ide = self::keyTypeToIDE($subdomain);
		$id = decrypt($ide, $login_session_key_type);
		return is_numeric($id) ? $ide : '_login';
	}

	public function unsetLogin() {
		Login::$session = array();
		$o = person_cookie::getByCookie();
		if ($o) $o->delete();
		unset($_SESSION['login'], $_SESSION[self::$session_key], $_SESSION['remember_uri'], $_COOKIE['cookie'], $_COOKIE['person_ide'], $_COOKIE['token']);
		foreach (array('cookie', 'person_ide', 'token') as $c) {
			person_cookie::unsetCookie($c);	
		}
	}

	public function r($data = null) {
		if ($this->_errors) {
			return array(
				'status' => 'Error',
				'errors' => $this->_errors
			);
		}
		return array(
			'status' => 'OK',
			'data' => $data
		);
	}

	public static function generateUserSalt($val) {
		return encrypt($val);
	}

	public static function generateHash($password, $salt) {
		$prefix = '$2a$08$';	$suffix = '$';
		return crypt($password.$salt, $prefix.$salt.Login::GetGlobalSalt().$suffix);
	}

	public function getGlobalSalt() {
		global $person_encryption_key;
		return $person_encryption_key;
	}

	public static function set($k = null, $v = null) {
		if (!$k) return;
		// $_SESSION[self::$session_key][$k] = $v;
		$_SESSION['login'][$k] = $v;
		self::$session[$k] = $v;
	}

	public static function mset($arr = array()) {
		if (!$arr) return;
		foreach ($arr as $k => $v) {
			self::set($k, $v);
		}
	}

	public static function get($k) {
		return self::$session[$k];
	}

	public static function setConstants() {
		define('PERSON_ID', self::get('person_id'));
		define('PERSON_IDE', self::get('person_ide'));
	}

	public static function update() {
		// $_SESSION[self::$session_key] = $_SESSION['login'];
	}

}