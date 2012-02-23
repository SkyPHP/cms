<?
class mediabox {
	public  $width,
			$height,
			$thumb_width,
			$interval,
			$data;

	function __construct( $vars = null, page $p ) {
		$p->js[] = '/lib/mediabox/standard/standard.js';
		$p->css[] = '/lib/mediabox/standard/standard.css';
		
		if(!$vars['width'])	$this->width = 667;
		else $this->width =  $vars['width'];
		
		if(!$vars['height']) $this->height = 270;
		else $this->height = $vars['height'];
		
		if(!$vars['thumb_width'] && $vars['thumb_width'] !== 0 ) $this->thumb_width = 75;
		else $this->thumb_width = $vars['thumb_width'];
		
		if(!$vars['interval'])
			$this->interval = 4000;
		elseif($vars['interval']<100)
			$this->interval = $vars['interval']*1000;
		else
			$this->interval = $vars['interval'];
		
		$this->data = $vars['data'];
		
		$this->num_thumbs = sizeof($vars['data']);
	}


	static function render( $vars = null, page $page= null ) {
		if (!$page) {
			global $p;
		} else {
			$p = $page;
		}
		$o = new mediabox($vars, $p);
		$o->display_html();
		//return $o; 	
	}
	
	function display_html() {
		include('lib/mediabox/standard/standard.php');
	}
	
	static function getProperties() {
		$reflection = new ReflectionClass('mediabox');
		return array_map( function($p) {
			return $p->getName();
		}, $reflection->getProperties());
	}


}