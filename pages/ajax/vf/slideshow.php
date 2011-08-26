<?

$items = $gallery->folder->items;
if (!$items) $items = $gallery->items;
if (!$items) exit;
// print_r($items);
// krumo($gallery);
?>
<div class="vf-slideshow has-floats" 
	 transition="<?=$gallery->transition?>" 
	 delay="<?=$gallery->delay?>"
	 autohide="<?=($gallery->auto_hide_toolbar)?'yes':'no'?>"
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
		<div class="vf-slideshow-controls has-floats">
			<? if ($gallery->captions) : ?>
			<div class="vf-slideshow-caption float-left"></div>
			<? endif; ?>
			<? if ($gallery->controls) : ?>
			<div class="float-right">
				<? if ($gallery->enlarge) : ?>
				<a href="#enlarge" class="vf-slideshow-enlarge"></a>
				<? endif; ?>
				<a href="#next" class="vf-slideshow-next"></a>
				<a href="#playpause" class="vf-slideshow-pause"></a>
				<a href="#prev" class="vf-slideshow-prev"></a>
			</div>
			<? endif; ?>
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
			?><div class="vf-slideshow-thumb <?=$class?>" caption="<?=$item->extra['caption']?>" ide="<?=encrypt($item->items_id)?>"><?
				echo $item->html;
			?></div><?
		}
	?></div>
</div>