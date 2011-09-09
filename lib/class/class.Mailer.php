<?

// Todo Attachments

class Mailer {

	public static $from_default = 'CraveTickets <info@cravetickets.com>';
	public static $contents = array(
		'html' => "MIME-Verson: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n",
		'text' => ''
	);

	public $to = array();
	public $from;
	public $subject;
	public $body;
	public $reply_to;
	public $cc = array();
	public $bcc = array();
	public $headers;
	public $content_type;

	public function __construct($to = null, $subject = null, $body = null, $from = null) {
		$this->from = self::$from_default;
		if ($to) $this->addTo($to);
		if ($subject) $this->subject = $subject;
		if ($body) $this->body = $body;
		if ($from) $this->from = $from;
	}

	public function setFrom($s) {
		$this->from = $s;
		return $this;
	}

	public function setSubject($s) {
		$this->subject = $s;
		return $this;
	}

	public function makeHeaders() {
		if ($this->headers) return $this->headers;
		$this->headers = $this->content_type
						.'From: '.$this->from."\r\n";
		foreach ($this->cc as $cc) {
			$this->headers .= 'Cc: '.$cc."\r\n";
		}
		foreach ($this->bcc as $bcc) {
			$this->headers .= 'Bcc: '.$bcc."\r\n";
		}
		return $this->headers;
	}

	public function setContentType($var) {
		$this->content_type = self::$contents[$var];
		return $this;
	}

	public function addCc() {
		return $this->_append('cc', func_get_args());
	}

	public function addBcc() {
		return $this->_append('bcc', func_get_args());
	}

	public function addTo() {
		return $this->_append('to', func_get_args());
	}

	private function _append($arr, $args) {
		foreach ($args as $arg) {
			if (!is_array($arg)) $arg = array($arg);
			foreach ($arg as $s) $this->{$arr}[] = $s;
		}
		return $this;
	}

	public function setBody($s) {
		$this->body = $s;
		return $this;
	}

	public function makeSubject() {
		return ($this->subject) ? $this->subject : '(no subject)';
	}

	public function makeTo() {
		return implode(',', $this->to);
	}

	public function send() {
		return @mail($this->makeTo(), $this->makeSubject(), $this->body, $this->makeHeaders());
	}

}