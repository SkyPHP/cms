<? 
	$params = tmp::cleanPost();
	// krumo($params); 
	// $vf = new vfolder($vfolder_client_config);
	$vfolder = $vf->get_folder($params['vfolder'], $params['num_thumbs']);
	// krumo($vfolder);

	// if (tmp::isNum('vfolder', 'width|height|num')) echo 'yes?';

	$num_items = count($vfolder['items']);
	if (!$num_items) exit('no images');


?>
	<div class="gallery" style="width:<?=$params['image_width']-2?>px; overflow:hidden;">	
		<div class="slides" style=" height:<?=$params['image_height']?>px; overflow:hidden;">
			<? 	foreach ($vfolder['items'] as $item) : 
				$img = $vf->get_item($item['_id'], $params['image_width'], $params['image_height'], true);	?>
				<div class="slide"><?=$img['html']?></div>
			<? 	endforeach; ?>
		</div>
		<div class="gallery-menu" <? if ($params['center_thumbs']) echo 'style="text-align:center"'?>>
			<ul>
				<li class="fbar">&nbsp;</li>
				<? 	foreach ($vfolder['items'] as $k => $item) : 
					// krumo($params);
					$img = $vf->get_item($item['_id'], $params['thumb_width'], $params['thumb_height'], true); ?>
					<li class="menuItem <?=(!$k)?'first':''?> <?=($k+1==$num_items)?'last':''?>"><a href="#"><?=$img['html']?></a></li>
				<? 	endforeach; ?>
			</ul>
		</div>
	</div>
<?

class tmp {
	
	public static function cleanPost() {
		$rs = array();
		$match = 'width|height|num';
		foreach ($_POST as $k => $v) {
			$v = addslashes(trim($v));
			if (self::isNum($k, $match) && !is_numeric($v)) continue;
			$rs[$k] = $v;
		}
		return $rs;
	}

	public static function isNum($field, $match) {
		$is = true;
		foreach (explode('|', $match) as $key) {
			if (self::matches($field, $key)) {
				// print_pre('matched: '.$field.' with '.$key);
				continue;
			}
			$is = false;
			break;	
		}
		return $is;
	}

	public static function matches($field, $val) {
		return (strpos($field, $val) !== false);
	}

}
