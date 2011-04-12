<?
$w = ($_POST['width']) ? $_POST['width'] : 100;
$h = ($_POST['height']) ? $_POST['height'] : null;
$empty = ($_POST['empty']) ? $_POST['empty'] : 'There are no images.';
$limit = (is_numeric($_POST['limit'])) ? $_POST['limit'] : null;
$folder = media::get_vfolder($_POST['vfolder'], $limit);
if (is_array($folder)) {
	$items = $folder['items'];
	if ($items) {
		foreach ($items as $item) : 
			$i = media::get_item($item['media_item_ide']);
		?>
			<li class="mediaItem" ide="<?=$item['media_item_ide']?>" instance_ide="<?=$i['media_instance_ide']?>">
				<img src="/media/<?=$i['media_instance_ide']?>" width="<?=$w?>" <?=($h)?'height="'.$h.'"':''?> />
			</li>
		<? endforeach;
	} else {
		echo $empty;
	}
} else {
	echo $empty;
}