<?php

if (!class_exists('vf_gallery_inc')) {
	include 'lib/vfolder/class.vf_gallery_inc.php';
}

class vf_gallery extends vf_gallery_inc {
	
	public static $count = 0;

	public static $defaults = array(
		'html_include' => 'pages/ajax/vf/gallery.php',
		'contextMenu' => false,
		'fileName' => false
	);

	public $contextMenu;
	public $identifier;




}