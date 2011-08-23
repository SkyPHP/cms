<?php

if (!class_exists('vf_gallery_inc')) {
	include 'lib/vfolder/class.vf_gallery_inc.php';
}

class vf_slideshow extends vf_gallery_inc {
	
	public static $defaults = array(
		'autostart' => true,
		'delay' => 5000,
		'html_include' => 'pages/ajax/vf/slideshow.php',
		'thumb_type' => 'slide'
	);

	public static $thumb_types = array('slide', 'grid', 'none');

	public $autostart;
	public $delay;
	public $thumb_type;

	public function makeHTML() {
		global $p;
		parent::makeHTML();
		$p->css[] = '/lib/vfolder/css/vf.css';
		$p->js[] = '/lib/vfolder/js/vf.js';
	}

	public function validate() {
		if ($this->crop) return;
		if ($this->width) return;
		if ($this->height) return;
		throw new Exception('vf_slideshow expects a width or a height to be provided if crop is set to false.');
		return $this;
	}

}