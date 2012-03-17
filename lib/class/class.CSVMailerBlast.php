<?

class CSVMailerBlast extends MailerBlast {
	
	private $map = array();
	private $has_headers = false;
	private $delimiter = ',';

	/*
		@setRecipients
		params include: 
			- path: if remote this is the url
			- remote: (bool) if true, we would use file_get_contents, otherwise we use fopen
			- map: key value pairs, 'email' => 0 
									'fname' => 1
				where the values are the positions in the csv
			- has_headers: if has_headers, we skip the first row
			- delimiter: defaults to ','

	*/
	public function setRecipients($a) {

		// sets private properties
		$this->_mapProperties($a);
		$this->recipients = ($a['remote'])
			? $this->_getRemoteCSV($a['path'])
			: $this->_getLocalCSV($a['path']);
		
	}

	private function _mapProperties($a) {
		$reflection = new ReflectionClass($this);
		$props = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
		$props = array_map(function($r) { return $r->getName(); }, $props);
		foreach ($a as $k => $v) {
			if (in_array($k, $props)) $this->{$k} = $v;
		}
	}

	private function validate() {
		if (!$this->map) {
			throw new Exception('Map must be passed to setRecipients');
		}

		if (!$this->delimiter) {
			throw new Exception('Deliiter is required!');
		}
	}

	private function _getRemoteCSV($path) {
		
		if (!$path) {
			throw new Exception('CSVMailerBlast::_getRemoteCSV() requires a path.');
		}

		$this->validate();

		$contents = file_get_contents($path);
		$contents = explode("\n", $contents);

		$csv = array();
		$i = 0;
		foreach ($contents as $row) {
			if (!$i && $this->has_headers)  {
				$i++; 
				continue;
			}
			$csv[] = $this->_map(explode($this->delimiter, $row));
		}
		return $csv;
	}

	private function _getLocalCSV($path) {
		$csv = array();
		
		if (!$path) {
			throw new Exception('CSVMailerBlast::_getLocalCSV() requires a path.');
		}

		$this->validate();

		$file = fopen($path, 'r');

		if ($file === FALSE) {
			throw new Exception('Cannot open file at: ' . $path);
		}

		$i = 0;
		while ( ($tmp = fgetcsv($file, 0, $this->delimiter)) !== FALSE) {
			// skip the first row if has_headers
			if (!$i && $this->has_headers) {
				$i++;
				continue;
			}
			$csv[] = $this->_map($tmp);
		}
		
		fclose($file);
		return $csv;

	}

	// map a row's num field to associative
	private function _map($row) {
		return array_map(function($r) use($row) {
			return $row[$r];
		}, $this->map);
	}

}