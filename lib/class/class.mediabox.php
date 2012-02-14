<?
class mediabox {
	public  $width,
			$height,
			$thumb_width,
			$interval,
			$data;

	function __construct( $vars = null ) {
		if(!$vars['width'])	$this->width = 667;
		else $this->width =  $vars['width'];
		
		if(!$vars['height']) $this->height = 270;
		else $this->height = $vars['height'];
		
		if(!$vars['thumb_width']) $this->thumb_width = 75;
		else $this->thumb_width = $vars['thumb_width'];
		
		if(!$vars['interval']) $this->interval = 4000;
		else $this->interval = $vars['interval'];
		
		$this->data = $vars['data'];
		
		$this->num_thumbs = sizeof($vars['data']);
	}


	static function render( $vars = null ) {
		$o = new mediabox($vars);
		$o->display_html();
		//return $o; 	
	}
	
	function display_html() {
		include('/lib/mediabox/template.php');
	}

}