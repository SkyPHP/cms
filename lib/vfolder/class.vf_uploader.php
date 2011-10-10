<?

// class 

class vf_uploader {
	
	public static $defaults = array(
		'buttonText' => 'Upload File(s)'
	);

	public $button;
	public $buttonText;
	public $class;
	public $db_field;
	public $db_row_id;
	public $fileTypes;
	public $gallery;
	public $maxFileSize;
	public $folder;
	public $folders_path;

	public $_token = null;

	public function __construct($args = array()) {
		
		global $disable_vf;
		if ($disable_vf) {

			?><strong style="color:red;">The Uploader Is Temporarily Disabled Due to Technical Difficulties.</strong><?

			return;
		}

		$this->setByArray(self::$defaults)->setByArray($args);

		if ($this->gallery && !$this->folder) {
			$this->folder = $this->gallery->folder;
		}

		$this->setMemToken();

		if (!$this->folder) {
			throw new Exception('class: <strong>vf_uploader</strong> requires a folder paramter');
		} else if (!is_object($this->folder)) {
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
		global $p, $dev, $is_dev;
		$show_vf = ($dev || $is_dev);
		if (!in_array('/lib/vfolder/css/vf.css', $p->css)) $p->css[] = '/lib/vfolder/css/vf.css';
		if (!in_array('/lib/vfolder/js/vf.js', $p->js)) $p->js[] = '/lib/vfolder/js/vf.js';
		
		if ($this->gallery) {
			$gallery_attr = 'refresh_gallery="'.$this->gallery->identifier.'"';
		}
		if ($show_vf) {
			$gallery_attr .= ' folders_path="'.$this->folder->folders_path.'"';
		}

		$this->button = '<div class="vf-uploader-button-container"><button type="button" class="vf-uploader '.$this->class.'" '
				.'uploader_token="'.$this->_token.'" '.$gallery_attr.'>' . $this->buttonText . '</button></div>';
	}

}