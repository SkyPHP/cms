<?

//  gallery


if (!$gallery) {
	if (!$_POST['_token']) exit;
	$params = mem('vf_gallery:'.$_POST['_token']);
	$gallery = vf::gallery($params);
	$gallery->folder = $folder = vf::getFolder($gallery->folder->folders_id);
	$items = $folder->items;
} else {
	$items = $gallery->folder->items;
	if (!$items) $items = $gallery->items;
}

$empty = (count($items) == 0);

// krumo($gallery);
?>
<div class="vf-gallery has-floats <?=($empty)?'vf-gallery-empty':''?>" id="<?=$gallery->identifier?>" 
	token="<?=$gallery->_token?>"
	<?=($gallery->contextMenu) ? 'context_menu="true"' : ''?>
><?
	if ($empty) {
		?><div class="vf-gallery-empty-message"><?
			echo $gallery->empty_message;
		?></div><?
	} else {
		foreach ($items as $i) {
			$item = vf::getItem($i['_id'], array(
				'width' => $gallery->width,
				'height' => $gallery->height,
				'crop' => $gallery->crop
			));
			?><div class="vf-gallery-item" ide="<?=$item->items_id?>"><?
				echo $item->html;
			?></div><?
		}
	}
?></div>