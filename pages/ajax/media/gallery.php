<?
$w = ($_POST['width']) ? $_POST['width'] : 100;
$h = ($_POST['height']) ? $_POST['height'] : null;
$empty = ($_POST['empty']) ? strip_tags($_POST['empty']) : 'There are no images.';
$empty = '<p class="emptyMediaGallery">'.$empty.'</empty>';
$limit = (is_numeric($_POST['limit']) && $_POST['limit'] > 0) ? $_POST['limit'] : null;
$folder = media::get_vfolder($_POST['vfolder'], $limit);

if ($_POST['db_field'] && $_POST['db_row_ide']) {
	$inf = explode('.', $_POST['db_field']);
	$table = $inf[0];
	$field = $inf[1];
	$db_row_id = decrypt($_POST['db_row_ide'], $table);
	if ($db_row_id) {
		$item = aql::value( " {$table} { {$field} as media_item_id } ", $db_row_id);
		if (!$item['media_item_id']) {
			echo $empty;
		} else {
			$i = media::get_item($item['media_item_ide'], $w, $h); 
			?><ul class="mediaItemGallery has-floats">
				<li class="mediaItem" id="order_<?=$item['media_item_ide']?>" ide="<?=$item['media_item_ide']?>" instance_ide="<?=$i['media_instance_ide']?>">
					<?=$i['html']?>
				</li>
			</ul><?
		}
	}
} else if (is_array($folder)) {
	$items = $folder['items'];
	if ($items) {
		?><ul class="mediaItemGallery has-floats"><?
		foreach ($items as $item) : 
			$i = media::get_item($item['media_item_ide'], $w, $h);
		?>
			<li class="mediaItem" id="order_<?=$item['media_item_ide']?>" ide="<?=$item['media_item_ide']?>" instance_ide="<?=$i['media_instance_ide']?>">
				<?=$i['html']?>
			</li>
		<? endforeach;
		?></ul><?
	} else {
		echo $empty;
	}
} else {
	echo $empty;
}