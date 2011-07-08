<?

class validation {
	
	public static function validPhone($val) {
		$val = preg_replace('/\D/', '', $val);
		$count = (substr($val, 0, 1) == 1) ? 11 : 10;
		if (strlen($val) == $count) return true;
		return false;
	}

	public static function validEmail($val) {
		return filter_var($val, FILTER_VALIDATE_EMAIL);
	}

	public static function validDate($val, $format = 'm/d/Y') {
		if (!$val) return false;
		$time = strtotime($val);
		if (!$time) return false;
		return date($format, $time);
	}

	public static function validTime($val, $format = 'g:ia') {
		if (!$val) return false;
		$time = strtotime($val);
		if (!$time) return false;
		return date($format, $time);
	}

	public static function validIP($val) {
		$val = trim($val);
		return filter_var($val, FILTER_VALIDATE_IP);
	}

}