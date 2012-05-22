<?
class mediabox {

	public  $width,
			$height,
			$thumb_width,
			$interval,
			$data;

	public function __construct( $vars = null, \Sky\Page $p ) {
		
		$p->js[] = '/lib/mediabox/standard/standard.js';
		$p->css[] = '/lib/mediabox/standard/standard.css';

		// set defaults if 
		$this->width = ($vars['width']) ?: 667;
		$this->height = ($vars['height']) ?: 270;
		$this->thumb_width = (!$vars['thumb_width'] && $vars['thumb_width'] !== 0)
			? 75 
			: $vars['thumb_width'];

		$this->interval = ($vars['interval']) ?: 4000;
		if ($this->interval < 100) $this->interval *= 1000;

		$this->data = $vars['data'];
		$this->num_thumbs = count($vars['data']);
		
	}


	public static function render( $vars = null, \Sky\Page $page= null ) {
		if (!$page) {
			global $p;
		} else {
			$p = $page;
		}
		$o = new mediabox($vars, $p);
		$o->display_html();
		return $o; 	
	}
	
	public function display_html() {
		include 'lib/mediabox/standard/standard.php';
		return $this;
	}
	
	public static function getProperties() {
		$reflection = new ReflectionClass('mediabox');
		return array_map( function($p) {
			return $p->getName();
		}, $reflection->getProperties());
	}


	/*
		used by subclasses of mediabox to separate 
			mediabox specific configuration (based on defined properties)
			from child class specs

			ie: blog_mediabox takes the same params as mediabox + blog_article::getList()

			this would return 
				array(
					'mediabox' => $mediabox_vars,
					'other' => $blog_articlegetlist_vars
				);
	*/
	public static function separateProperties($vars, $data = array()) {

		$props = self::getProperties();
		foreach ($vars as $key => $var) {
			if (in_array($key, $props)) continue;
			$data[$key] = $var;
			unset($vars[$key]);
		}

		return array(
			'mediabox' => $vars,
			'other' => $data
		);

	}

}