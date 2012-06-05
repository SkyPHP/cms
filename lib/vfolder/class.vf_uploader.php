<?php

class vf_uploader {

	/**
	 *	default values
	 *	@var array
	 */
	public static $defaults = array(
		'buttonText' => 'Upload File(s)'
	);

	/**
	 *	id for the button
	 *	@var string
	 */
	public $id;

	/**
	 *	button html
	 *	@var string
	 */
	public $button;

	/**
	 *	text that will be the button label
	 *	@var string
	 */
	public $buttonText;

	/**
	 *	user added button class
	 *	@var string
	 */
	public $class;

	/**
	 *	db field to update ex: table.field_id
	 *	@var string
	 */
	public $db_field;

	/**
	 *	row identifier for db_field
	 *	@var int
	 */
	public $db_row_id;

	/**
	 *	allowable to be uploaded
	 *	@var array
	 */
	public $fileTypes = array();

	/**
	 *	@var vf_gallery | null
	 */
	public $gallery;

	/**
	 *	@var string
	 */
	public $maxFileSize;

	/**
	 *	@var stdClass
	 */
	public $folder;

	/**
	 *	@var string
	 */
	public $folders_path;

	/**
	 *	@var string
	 */
	public $_token = null;

	/**
	 *	sets object params by the args array
	 *	@param	array	$args
	 *	@global $disable_vf
	 */
	public function __construct(array $args = array()) {

		global $disable_vf;

		if ($disable_vf) {
?>
			<strong style="color:red;">
				The Uploader Is Temporarily Disabled Due to Technical Difficulties.
			</strong>
<?php
			return;
		}

		$this->setByArray(self::$defaults)->setByArray($args);

		if ($this->gallery && !$this->folder) {
			$this->folder = $this->gallery->folder;
		}

		$this->setMemToken();

		if (!$this->folder) {
			throw new Exception('class: vf_uploader requires a folder paramter');
		} else if (!is_object($this->folder)) {
			$this->folder = vf::getFolder($this->folder);
		}

		if (!$this->folder->folders_path) {
			throw new Exception('Could not get folder object from server.');
		}

		$this->makeButton();
	}

	/**
	 *	@param 	array	$arr
	 *	@return $this
	 */
	public function setByArray($arr = array()) {
		$props = get_object_vars($this);
		foreach ($arr as $k => $v) {
			if (!array_key_exists($k, $props)) continue;
			$this->{$k} = $v;
		}
		return $this;
	}

	/**
	 *	@param 	string
	 *	@return string
	 */
	public function getMemKey($token) {
		return sprintf('vf_uploader:%s', $token);
	}

	/**
	 *	stores object variables in memcache
	 */
	public function setMemToken() {
		$vars = get_object_vars($this);
		$this->_token = md5(serialize($vars));
		mem($this->getMemKey($this->_token), $vars);
	}

	/**
	 *	@return Boolean
	 *	@global $dev
	 *	@global $is_dev
	 */
	public function isDev() {
		global $dev, $is_dev;
		return ($dev || $is_dev);
	}

	/**
	 *	@return \Sky\Page
	 *	@global $p
	 */
	public function getPage() {
		global $p;
		return $p;
	}

	/**
	 *	appends js/css to page, and creates the button html
	 */
	public function makeButton() {


		$p = $this->getPage();
		$show_vf = $this->isDev();

		if (!in_array('/lib/plupload/js/plupload.full.js', $p->js)) {
			$p->js[] = '/lib/plupload/js/plupload.full.js';
		}

		if (!in_array('/lib/vfolder/css/vf.css', $p->css)) {
			$p->css[] = '/lib/vfolder/css/vf.css';
		}

		if (!in_array('/lib/vfolder/js/vf.js', $p->js)) {
			$p->js[] = '/lib/vfolder/js/vf.js';
		}

		$gallery_attr = '';

		if ($this->gallery) {
			$gallery_attr .= 'refresh_gallery="'.$this->gallery->identifier.'"';
		}

		if ($show_vf) {
			$gallery_attr .= ' folders_path="'.$this->folder->folders_path.'"';
		}

		if ($this->id) {
			$gallery_attr = ' id="'. $this->id .'"';
		}

		$this->makeButtonHTML($gallery_attr);

	}

	/**
	 * 	@param	string	$gallery_attr
	 *	@return string
	 */
	protected function makeButtonHTML($gallery_attr) {
		ob_start();
?>
		<div class="vf-uploader-button-container">
			<button
				type="button"
				class="vf-uploader <?=$this->class?>"
				uploader-token="<?=$this->_token?>"
				<?=$gallery_attr?>
				>
				<?=$this->buttonText?>
			</button>
		</div>
<?php
		$this->button = ob_get_contents();
		ob_end_clean();

		return $this->button;
	}

}
