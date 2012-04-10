<?

class MailerBlast {
	
	public $recipients = array();
	public $mailer_config = array();

	// what fields to look for for each recipient
	public $fields = array(
		'fname' => 'fname',
		'lname' => 'lname',
		'email' => 'email'
	);

	// if this is true emails "to" will be sent as to: fname lname <email>
	// instead of just to: email
	public $use_full_name = false;
	
	public function setRecipients($arr) {
		$this->recipients = $arr;
	}

	public function configureMailer($vars) {
		$this->mailer_config = array_merge($this->mailer_config, $vars);
	}

	public function sendBlast() {

		if (!$this->recipients) {
			throw new Exception('setRecipients should set $this->recipients');
		}

		if (!$this->mailer_config['template']) {
			throw new Exception('Cannot use MailerBlast without template configured');
		}

		set_time_limit(0);
		$count = 0;
		foreach ($this->recipients as $r) {

			if ($this->_test_config && $count >= $this->_test_config['limit']) break;

			$mlr = $this->getConfiguredMailer();
			$mlr->addTo($this->_getTo($row));
			$mlr->inc($this->mailer_config['template'], $r);
			$mlr->send();
			$count++;
		}

	}

	public function getConfiguredMailer() {

		if (!$this->mailer_config) {
			throw new Exception('MailerBlast needs to have the mailer configured.');
		}

		$mlr = new Mailer;
		foreach ($this->mailer_config as $k => $v) {
			switch ($k) {
				case 'cc': 				$mlr->addCc($v);					break;
				case 'bcc':				$mlr->addBcc($v);					break;
				case 'from':			$mlr->setFrom($v);					break;
				case 'replyto':			$mlr->setReplyTo($v);				break;
				case 'subject':			$mlr->setSubject($v);				break;
				case 'content-type':	$mlr->setContentType($v);			break;
				case 'headers':			$mlr->headers .= $v;				break;
				default:													break;
			}
		}
		return $mlr;
	}

	protected function _getTo($row) {

		if ($this->_test_config) return $this->_test_config['to'];
		if (!$this->use_full_name) return $this->_getToField($row, 'email');
		return vsprintf('%s %s <%s>', array(
			$this->_getToField($row, 'fname'),
			$this->_getToField($row, 'lname'),
			$this->_getToField($row, 'email')
		));
	}

	protected function _getToField($row, $field_name) {
		return $row[$this->fields[$field_name]];
	}

	public function sendTestBlast($to, $limit) {
		
		if (!$to) {
			throw new Exception('must set a *to* when sending test emails');
		}

		if (!$limit) {
			throw new Exception('must set a *limit* when sending test emails');
		}

		$this->_test_config = array(
			'to' => $to,
			'limit' => $limit
		);

		$this->sendBlast();
		
		$this->_test_config = array();

	}

}
