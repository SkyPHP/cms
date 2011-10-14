<?

//  gallery

global $dev, $is_dev;

$show_vf = ($dev || $is_dev);

if (!$gallery) {
	if (!$_POST['_token']) exit;
	$params = mem('vf_gallery:'.$_POST['_token']);
	$gallery = vf::gallery($params);
	$folder = $gallery->initFolder(true);
	$items = $folder->items;
} else {
	$items = $gallery->folder->items;
	if (!$items) $items = $gallery->items;
}

// print_pre($gallery);

if ($gallery->db_field && $gallery->db_row_id) {
	$items = array(
		array('_id' => aql::value($gallery->db_field, $gallery->db_row_id))
	);
}
$empty = (count($items) == 0);
?>
<div class="vf-gallery has-floats <?=($empty)?'vf-gallery-empty':''?>" id="<?=$gallery->identifier?>" 
	token="<?=$gallery->_token?>"
	<?=($show_vf) ? 'folders_path="'.$gallery->folder->folders_path.'"' : '' ?>
	<?=($gallery->contextMenu) ? 'context_menu="true"' : ''?>
><?
	if ($empty) {
		?><div class="vf-gallery-empty-message"><?
			echo $gallery->empty_message;
		?></div><?
	} else {
		$items = vf::getItem(vf_gallery_inc::itemsToFlatArray($items), array(
			'width' => $gallery->width,
			'height' => $gallery->height,
			'crop' => $gallery->crop	
		));
		// krumo($items);
		$items = call_user_func(function() use($items) {
			if ($items->items) return $items->items;
			return array($items);
		});
		foreach ($items as $i) {
			?><div class="vf-gallery-item" ide="<?=$i->items_id?>"><?
				echo $i->html;
			?></div><?
		}
	}
?></div>