<?

/*

	SAMPLE USAGE:

	$parser = new CSVParser(array(
		'path' => 'path/to/csv',
		'map' => array(
			'fname' => 2,
			'lname' => 3,
			'email' => 4
		),
		'remote' => false // can be set to true, then path should have http://
	));

	$parsed = $parser->parse();

*/

class CSVParser {
	
	protected $path = null;
	protected $delimiter = ',';
	protected $remote = false;
	protected $has_headers = false;
	protected $distinct_field = null;
	protected $map = array();

	public $distincts = array();

	public function __construct($params = array()) {
		$this->_setProperties($params);
	}

	private function _setProperties($params) {
		if (!$params) return;
		$props = $this->getProperties();
		foreach ($params as $k => $v) {
			if (in_array($k, $props)) $this->{$k} = $v;
		}
	}

	public function getProperties() {
		$reflect = new ReflectionClass($this);
		return array_map(function($r) {
			return $r->getName();
		}, $reflect->getProperties(ReflectionProperty::IS_PROTECTED));
	}

	public function parse() {

		if (!$this->map) {
			throw new CSVParserException('$map must be defined.');
		}
		
		if (!is_assoc($this->map)) {
			throw new CSVParserException('$map must be associative.');
		}

		if (!$this->delimiter) {
			throw new CSVParserException('$delimiter must be defined.');
		}

		if (!$this->path) {
			throw new CSVParserException('$path must be defined.');
		}
		
		$csv = ($this->remote)
			? $this->_getRemoteCSV()
			: $this->_getLocalCSV();

		return $csv;

	}

	private function _getLocalCSV() {
		
		$file = fopen($this->path, 'r');
		if ($file === FALSE) {
			throw new CSVParserException('Cannot open file at: ' . $this->path);
		}

		$csv = array();
		$i = 0;
		
		while ( ($row = fgetcsv($file, 0, $this->delimiter)) !== FALSE) {
			// if headers, skip the first row
			if ($i && $this->has_headers) { $i++; continue; }
			$csv[] = $this->_mapRow($row);
		}

		fclose($file);
		return array_filter($csv);

	}

	public function getDistincts() {
		return $this->distincts;
	}

	private function _getRemoteCSV() {

		// only use " " as escaped
		$conf = array('ignore' => array("'", "("));
		$file = file_get_contents($this->path);
		$file = explodeOn("\n", $file, $conf);

		$csv = array();
		$i = 0;
		foreach ($file as $row) {
			// if headers, skip the first row
			if ($i && $this->has_headers) { $i++; continue; }
			$csv[] = $this->_mapRow(explodeOn($this->delimiter, $row, $conf));
		}

		return array_filter($csv);

	}

	private function _mapRow($row) {
		$row = array_map(function($r) use($row) {
			return $row[$r];
		}, $this->map);

		if (!$this->distinct_field) return $row;
		if ($this->distincts[$row[$this->distinct_field]]) return null;

		$this->distincts[$row[$this->distinct_field]] = true;
		return $row;
	}

}

class CSVParserException extends Exception { }
