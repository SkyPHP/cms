<?

// class 

class vf_uploader {
	
	public static $defaults = array(
		'buttonText' => 'Upload File(s)'
	);

	public $button;
	public $buttonText;
	public $class;
	public $dbField;
	public $dbRowId;
	public $fileTypes;
	public $gallery;
	public $maxFileSize;
	public $folder;

	public $_token = null;

	public function __construct($args = array()) {
		
		$this->setByArray(self::$defaults)->setByArray($args);

		$this->setMemToken();

		if (!$this->folder) {
			throw new Exception('class: <strong>vf_uploader</strong> requires a folder paramter');
		} else if (!is_object($this->folder) || get_class($this->folder) != 'vf_folder') {
			$this->folder = vf::getFolder($this->folder);
		}
		
		$this->makeButton();
	}

	public function setByArray($arr = array()) {
		$props = get_object_vars($this);
		foreach ($arr as $k => $v) {
			if (!array_key_exists($k, $props)) continue;
			$this->{$k} = $v;
		}
		return $this;
	}

	public function setMemToken() {
		$vars = get_object_vars($this);
		$this->_token = md5(serialize($vars));
		$mem_key = 'vf_uploader:'.$this->_token;
		mem($mem_key, $vars);
	}

	public function makeButton() {
		if ($this->gallery) {
			$gallery_attr = 'refresh_gallery="'.$this->gallery->identifier.'"';
		}
		$this->button = '<button type="button" class="vf-uploader '.$this->class.'" '
				.'uploader_token="'.$this->_token.'" '.$gallery_attr.'>' . $this->buttonText . '</button>';
	}

}