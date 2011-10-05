<?

global $dev, $is_dev;

$show_vf = ($dev || $is_dev);

$items = $gallery->folder->items;
if (!$items) $items = $gallery->items;
if (!$items) exit;
// print_r($items);
// krumo($gallery);
$items_arr = array_map(function($i) {
	return $i['_id'];
}, $items);


// krumo($gallery);

// krumo($items_arr);

?>
<div class="vf-slideshow has-floats" 
	<?=($show_vf) ? 'folders_path="'.$gallery->folder->folders_path.'"':''?>
	transition="<?=$gallery->transition?>" 
	delay="<?=$gallery->delay?>"
	autohide="<?=($gallery->auto_hide_toolbar)?'yes':'no'?>"
	<?=($gallery->autostart)?'autostart="true"':''?>
	>
	<div class="vf-slideshow-main">
		<div class="vf-slideshow-image"><?
			elapsed('before getting main images in batch');
			$fetched = vf::getItem($items_arr, array(
				'width' => $gallery->width, 
				'height' => $gallery->height, 
				'crop' => $gallery->crop
			));
			krumo(vf::$client);
			elapsed('after main images in batch');
			// krumo($fetched);
			foreach ($fetched->items as $i) {
				if (!$i->html) continue;
				echo $i->html;
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
		elapsed('before getting thumbnails in batch');
		$fetched = vf::getItem($items_arr, array(
			'width' => $gallery->thumb_width,
			'height' => $gallery->thumb_height,
			'crop' => $gallery->crop
		));
		krumo(vf::$client);
		elapsed('after getting thunbnails in batch');
		// krumo($fetched);
		foreach ($fetched->items as $k => $i) {
			if ($k == 0) $class = 'first selected';
			else if ($k == $gallery->folder->items_count - 1) $class = 'last';
			else $class = null;
			?><div class="vf-slideshow-thumb <?=$class?>" caption="<?=$i->extra['caption']?>" ide="<?=encrypt($i->items_id)?>"><?
				echo $i->html;
			?></div><?
		}
	?></div>
</div>