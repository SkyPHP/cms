<?php

class vf_gallery_inc {
	
	public static $defaults = array(
		'crop' => true,
		'caption' => false,
		'enlarge' => true,
		'enlarge_width' => 800,
		'enlarge_height' => 800,
		'thumb_width' => 50,
		'thumb_height' => 50,
		'enlarge' => true
	);

	public $caption;
	public $class;
	public $crop;
	public $enlarge;
	public $enlarge_width;
	public $enlarge_height;
	public $filename;
	public $folder;
	public $height;
	public $html;
	public $html_include;
	public $items;
	public $limit;
	public $offset;
	public $thumb_width;
	public $thumb_height;
	public $width;

	public function __construct($args) {
		$c = get_class($this);
		$this->setByArray(vf_gallery_inc::$defaults)
			->setByArray($c::$defaults)
			->setByArray($args);
		
		if ($args['width'] && !$args['height']) {
			$this->height = null;
		}
		if ($args['height'] && !$args['width']) {
			$this->width = null;
		}

		if (!$this->folder && !$this->items) {
			throw new Exception('class: <strong>vf_uploader</strong> requires a folder parameter or items to be set');
		} else if (!is_object($this->folder) && !$this->items) {
			$this->folder = vf::getFolder($this->folder, $this->limit);
		}

		$this->validate();
		$this->makeHTML();
	}

	public function setByArray($arr = array()) {
		$props = get_object_vars($this);
		foreach ($arr as $k => $v) {
			if (!array_key_exists($k, $props)) continue;
			$this->{$k} = $v;
		}
		return $this;
	}

	public function validate() {
		// just in case
	}

	public function makeHTML() {
		if (!$this->html_include) return;
		$gallery = $this;
		ob_start();
		include $this->html_include;
		$this->html = ob_get_contents();
		ob_end_clean();
	}

}