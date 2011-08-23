<?

$items = $gallery->folder->items;
// print_r($items);

krumo($gallery->folder);
?>
<div class="vf-slideshow has-floats" 
	 transition="<?=$gallery->transition?>" 
	 delay="<?=$gallery->delay?>"
	 <?=($gallery->autostart)?'autostart="true"':''?>>
	<div class="vf-slideshow-main">
		<div class="vf-slideshow-image"><?
			foreach ($items as $i) {
				$item = vf::getItem($i['_id'], array(
					'width' => $gallery->width,
					'height' => $gallery->height, 
					'crop' => $gallery->crop
				));
				echo $item->html;
			}
		?></div>
		<div class="vf-slideshow-caption"></div>
		<div class="vf-slideshow-controls">
			<a href="#" class="vf-slideshow-play">play</a>
			<a href="#" class="vf-slidedhow-control">another</a>
		</div>
	</div>
	<div class="vf-slideshow-thumbs has-floats"><?
		foreach ($items as $k => $i) {
			$item = vf::getItem($i['_id'], array(
				'width' => $gallery->thumb_width,
				'height' => $gallery->thumb_height, 
				'crop' => true
			));
			if ($k == 0) $class = 'first selected';
			else if ($k == $gallery->folder->items_count - 1) $class = 'last';
			else $class = null;
			?><div class="vf-slideshow-thumb <?=$class?>" caption=""><?
				echo $item->html;
			?></div><?
		}
	?></div>
</div>