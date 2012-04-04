<?
class signature {

	public  $width = 600,
			$height = 150;

	public function __construct( $vars = null ) {
		global $p;
		$p->js[] = '/lib/js/signature/regenerate.js';
		$p->js[] = '/lib/js/signature/jquery.signaturepad.js';
		$p->js[] = '/lib/js/signature/json2.min.js';
		

		// set defaults if 
		$this->width = ($vars['width']) ?: 600;
		
		if ($vars['signature_data'])
			$this->data = $vars['signature_data'];
		elseif ($vars['ec_order_id'])
			$ec_order = new ec_order($vars['ec_order_id']);
		elseif ($vars['ec_order_ide'])
			$ec_order = new ec_order($vars['ec_order_ide']);
			
		if($ec_order)
			$this->data = $ec_order->signature;
	
	}

	public function render() {
		$x_min = $y_min = 10000;
		$sig_data = json_decode($this->data, true);

		foreach ($sig_data as $arr) {
			foreach ($arr as $key => $val) {
				if ($key == 'lx' || $key == 'mx' ) {
					if($val > $x_max)
						$x_max = $val;
					if($val < $x_min)
						$x_min = $val;					
				} else {
					if($val > $y_max)
						$y_max = $val;
					if($val < $y_min)
						$y_min = $val;
				}
			}
		}
		
		$current_height = $y_max-$y_min;
		$current_width = $x_max-$x_min;
		$current_ratio = ($current_width/$current_height);

		$this->height = ceil($this->width/$current_ratio);
		
		
		$modifier = ($this->width-10)/$current_width;
		
		
		foreach ($sig_data as $key1 => $arr) {
			foreach ($arr as $key2 => $val) {
				if ($key == 'lx' || $key == 'mx' )
					$zeroed_data[$key1][$key2] = $val - $y_min;
				else
					$zeroed_data[$key1][$key2] = $val - $x_min;
			}
		} 
		
		foreach ($zeroed_data as $key1 => $arr) {
			foreach ($arr as $key2 => $val) {
				if ($key == 'lx' || $key == 'mx' )
					$sig[$key1][$key2] = round($val*$modifier)+5;
				else
				 	$sig[$key1][$key2] = round($val*$modifier)+5;
			}
		}
		$signature_string = json_encode($sig);
		include 'lib/signature/signature.php';
		return $this; 
	}
}