<?php

use \Sky\Model\person;
use \Sky\Model\person_cookie;
use \Sky\AQL;

class Login {

	public static $session;
	public static $session_key;

	public $post_remember_me;
	public $post_username;
	public $post_password;

	public $login_path;

	public $_errors;
	public $person;

	public function __construct($username = null, $password = null, $extra = array()) {

		// make sure there's no caching 
		$_GET['refresh'] = 1 ;

		$this->post_username = addslashes(trim($username));
		$this->post_password = addslashes(trim($password));
		$this->post_remember_me = $extra['remember_me'];
		$this->login_path = strtolower($extra['login_path']);

		global $person_encryption_key;
		if (!$person_encryption_key) {
			throw new Exception('class Login requries a <strong>$person_encryption_key</strong> to be set in your configuration to use as a salt.');
		}

	}

	public static function make() {
		self::$session_key = self::getSessionLoginKey();
		if ($_SESSION[self::$session_key]['person_id']) $_SESSION['login'] = &$_SESSION[self::$session_key];
		if (is_array($_SESSION['login'])) foreach ($_SESSION['login'] as $key => $v) {
			self::$session[$key] = $v;
		}
	}

	public static function isLoggedIn() {
		if ( is_numeric($_SESSION['login']['person_id']) ) return true;
		else return false;
	}

	public function checkLoginPath() {
		$n = $_SERVER['SERVER_NAME'];
		$explode = explode('?', $this->login_path);
		$this->login_path = reset($explode);
		if (stripos($this->login_path, $n) === false) return;
		if ('http://'.$n.$_SERVER['REQUEST_URI'] == $this->login_path) return;
		$name = str_replace(array($n, 'http://'), '', $this->login_path);
		$dirs = array_filter(explode('/', $name));

		global $access_groups;

		$path = null;
		foreach ($dirs as $dir) {
			if (!$path) $path = 'pages/'.$dir.'/';
			else $path .= $dir.'/';
			$settings_file = $path .$dir. '-settings.php';
			if (file_exists_incpath($settings_file)) {
				include $settings_file;
			}
		}
	}

	public function checkLogin() {
		global $access_groups, $access_denied, $rs_logins, $person_email_field;

		if (!$person_email_field) {
			$person_email_field = 'email_address';
		}

		if ($this->login_path) {
			$this->checkLoginPath();
		}

		if (!$this->post_password) {
			$this->_errors[] = 'You need to enter a password.';
		}
		if (!$this->post_username) {
			$this->_errors[] = 'You need to enter a username or email address';
		}

		if ($this->_errors) {
			return $this->r();
		}

		$username = trim(strtolower($this->post_username));

		$aql = 	"
					person { 
						password_hash
						order by id desc
					}
				";



		$rs_logins = AQL::select($aql, 
				['where' => 
					"$person_email_field ilike '{$username}' or username ilike '{$username}' and password_hash is not null"
				
			]);

		if ($this->post_password) {
			$granted = false;
			foreach ($rs_logins as $p) {
				
				$p->ide = encrypt($p->id, 'person');
				
				$this->person = $p ; // new person($p->person_id, null, true);

				//dd($this->person);
				if (!$this->person->person_id) continue;
				if ($this->_checkLogin($this->post_password)) {
					if (auth_person($access_groups, $this->person->person_id) || !$access_groups) {
						$this->person = new person($p->id); 
						$access_denied = false;
						//dd($access_groups, $this->person->person_id, $access_denied);
						return $this->r(array('person_ide' => $this->person->person_ide));
					}
				}
			}
		}
		$this->_errors[] = 'Invalid Login';
		return $this->r();
	}

	public function _checkLogin($password) {
		$salt = self::generateUserSalt($this->person->ide); //$this->person->generateUserSalt();
		$pw = Login::generateHash($password, $salt);
		// return ($password == $this->person->password); // temp fix while new hash algo
		//dd($password, $salt, $pw, $this->person->password_hash);
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
		#$this->person->updateLastLoginTime();
		//Login::mset($login);
		$_SESSION['login'] = $login;
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
		$subdomain = \Sky\Page::getSubdomainName();
		if (!$subdomain) return '_login';
		$ide = self::keyTypeToIDE($subdomain);
		$id = decrypt($ide, $login_session_key_type);
		return is_numeric($id) ? $ide : '_login';
	}

	public static function unsetLogin() {
		//Login::$session = array();
		$o = person_cookie::getByCookie();
		if ($o) $o->delete();
		$subdomain = \Sky\Page::getSubdomainName();
		unset(
			$_SESSION['login'],
			//$_SESSION[self::$session_key],
			$_SESSION['multi-session'][$subdomain]['login'],
			$_SESSION['remember_uri'],
			$_COOKIE['cookie'],
			$_COOKIE['person_ide'],
			$_COOKIE['token']
		);
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

	public static function getGlobalSalt() {
		global $person_encryption_key;
		return $person_encryption_key;
	}

	public static function set($k = null, $v = null) {
		if (!$k) return;
		// $_SESSION[self::$session_key][$k] = $v;
		$_SESSION['login'][$k] = $v;
		//self::$session[$k] = $v;
	}

	public static function mset($arr = array()) {
		if (!$arr) return;
		foreach ($arr as $k => $v) {
			self::set($k, $v);
		}
	}

	public static function get($k) {
		//return self::$session[$k];
		return $_SESSION['login'][$k];
	}

	public static function setConstants() {
		define('PERSON_ID', self::get('person_id'));
		define('PERSON_IDE', self::get('person_ide'));
	}

	public static function update() {
		// $_SESSION[self::$session_key] = $_SESSION['login'];
	}

}
