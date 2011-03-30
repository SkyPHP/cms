<?
if (!$selected_date) $selected_date = $_GET['date'];
$vfolder_path = '/blog/' . $website_id . '/' . date('Y/m', $selected_date);
$vf_info = array('name' => date('M y', $selected_date));
$new_vf = media::new_vfolder($vfolder_path, $vf_info);
$limit = 16;
$offset = $_POST['sky_qs'][0];
if (!is_numeric($offset)) $offset = 0;
$vf = media::get_vfolder($vfolder_path);
$c = count($vf['items']);
$vf = media::get_vfolder($vfolder_path, $limit, $offset, 'mod_time desc');
if ($c) {
	foreach ($vf['items'] as $image) {		
		$orig = media::get_item($image['id']);
		$aspect_ratio = $orig['width']/$orig['height'];
		$thumb = media::get_item($image['id'],70,70,true);
		
		$full_size_img = media::get_item($image['id']);

		if ($orig['width']>=600 && $aspect_ratio>=1) {
			$img = media::get_item($image['id'],600,400,true);
		} else if ($orig['width']>=250 || true) {
			if ($aspect_ratio>=0.5) {
				$img = media::get_item($image['id'],250);
			} else {
				$img = media::get_item($image['id'],250,500,true);
			}
		} else if ($orig['width']<250) {
			//image not wide enough
			$img['src'] = 'alert';
		}
		
?>
		<div class="img-item">
			<a href="javascript:void(0);" onclick="imageInsert('<?=$img['src']?>','<?=$img['width']?>','<?=$img['height']?>','<?=$image['media_item_ide']?>');"><?=$thumb['img']?></a>
			<a href="javascript:void(0);" onclick="imageInsert('<?=$full_size_img['src']?>','<?=$full_size_img['width']?>','<?=$full_size_img['height']?>','<?=$full_size_img['media_item_ide']?>');">fullsize</a>
		</div>
<?
	}//foreach
} else {
?>
	<div style="width:100%; text-align:center;">Folder contains no images.</div>
<?
}//if
?>
<div id="image-picker-pagination">
<? if ($offset>0) { ?>
	<a href="javascript:void(0);" onclick="imagePicker('<?=$selected_date?>','<?=($offset-$limit)?>');">&lt;&lt; prev</a>
<? }//if ?>
<? if (($limit+$offset)<$c) { ?>
	<a href="javascript:void(0);" onclick="imagePicker('<?=$selected_date?>','<?=($offset+$limit)?>');">next &gt;&gt;</a>
<? }//if ?>
</div>
<input type="hidden" id="selected_date" value="<?=$selected_date?>" />
<input type="hidden" id="vfolder_path" value="<?=$vfolder_path?>" />
<? $_SESSION['media_browse']['vfolder'] = $vfolder_path; ?>