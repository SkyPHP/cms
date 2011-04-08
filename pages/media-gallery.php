<?
$folder = media::get_vfolder($_POST['vfolder']);
$w = ($_POST['width']) ? $_POST['width'] : 100;
$h = ($_POST['height']) ? $_POST['height'] : null;
if (is_array($folder)) {
	$items = $folder['items'];
	if ($items) foreach ($items as $item) : 
		$i = media::get_item($item['media_item_id'],$w,$h);
	?>
		<div class="mediaItem" ide="<?=$item['media_item_ide']?>">
			<img src="<?=$i['src']?>" />
		</div>
	<? endforeach;
}