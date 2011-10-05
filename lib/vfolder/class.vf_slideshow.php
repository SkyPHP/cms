<?php

if (!class_exists('vf_gallery_inc')) {
	include 'lib/vfolder/class.vf_gallery_inc.php';
}

class vf_slideshow extends vf_gallery_inc {
	
	public static $defaults = array(
		'crop' => true,
		'autostart' => true,
		'delay' => 5000,
		'html_include' => 'pages/ajax/vf/slideshow.php',
		'thumb_type' => 'slide',
		'transition' => 'slide',
		'auto_hide_toolbar' => true,
		'captions' => true,
		'controls' => true
	);

	public static $thumb_types = array('slide', 'grid', 'none');
	public static $transition_types = array('slide', 'fade');

	public $autostart;
	public $auto_hide_toolbar;
	public $captions;
	public $controls;
	public $delay;
	public $thumb_type;
	public $transition;

	public function makeHTML() {
		global $p;
		parent::makeHTML();
		if (!in_array('/lib/vfolder/css/vf.css', $p->css)) $p->css[] = '/lib/vfolder/css/vf.css';
		if (!in_array('/lib/js/jquery.hoverIntent.js', $p->js)) $p->js[] = '/lib/js/jquery.hoverIntent.js';
		if (!in_array('/lib/vfolder/js/vf.js', $p->js)) $p->js[] = '/lib/vfolder/js/vf.js';
	}

	public function validate() {
		if ($this->crop) return $this;
		if ($this->width) return $this;
		if ($this->height) return $this;
		throw new Exception('vf_slideshow expects a width or a height to be provided if crop is set to false.');

		return $this;
	}

}