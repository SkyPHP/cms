<?

class CSVExport {
	
	public $data, $headers, $title, $tmp_filename;
	private $_title, $_tmpfile, $_tmpfilename;

	public function export() {
		header('Content-type:text/octet-stream');
		header('Content-Disposition:attachment;filename='.$this->_title.'.csv');
		fseek($this->_tmpfile, 0);
		fclose($this->_tmpfile);
		$tempfile = fopen($this->_tmpfilename, 'r');
		echo fread($tempfile, filesize($this->_tmpfilename));
		fclose($tempfile);
		unlink($this->_tmpfilename);
		return $this;
	}

	public function setHeaders($arr) {
		$this->headers = $arr;
		return $this;
	}

	public function setTitle($str) {
		$this->title = $str;
		return $this;
	}

	public function setTmpFilename($str) {
		$this->tmp_filename = $str;
		return $this;
	}

	public function startCSV() {
		if (!$this->title) {
			throw new Exception('Cannot generate a CSV without a filename');
			return;
		}

		$this->_title = slugize($this->title);

		if (!$this->tmp_filename) {
			$this->tmp_filename = 'csv-' . md5(time().rand(0,2345));
		}

		// set and make the tmp file for the csv
		$this->_tmpfilename = tempnam(sys_get_temp_dir(), $this->tmp_filename);
		$this->_tmpfile = fopen($this->_tmpfilename, 'w');

		// write theaders
		if ($this->headers) $this->_putcsv($this->headers);
		return $this;
	}

	public function setDownloadToken($name, $value) {
		setcookie($name, $value, null, '/');
		return $this;
	}

	public function addRows($rs, $callback = null) {
		foreach ($rs as $i => $r) {
			if ($callback) {
				$r = $callback($r, $i);
			} 
			$this->addRow($r);
		}
		return $this;
	}

	public function addRow($row) {

		if (is_callable($row)) {
			$row = $row();
		}

		$this->_putcsv($row);
		return $this;
	}

	private function _putcsv($arr) {
		if (!is_array($arr)) return;
		fputcsv($this->_tmpfile, $arr);
	}

}