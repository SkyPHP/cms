<?
if (!$selected_date) $selected_date = strtotime(date('M Y'));
?>
<h2 class="module-bar">
	<span style="float: left">Image Picker</span> 
    <span id="image-picker-nav"><? include('image-picker-nav.php'); ?></span>
</h2>
<div class="module-body">
	<div class="module-container" id="image-picker">
		<? include('image-picker.php'); ?>
	</div>
    <div style="width:100%; text-align:center;">
		<?
        $media_upload['on_success_js'] = "imagePicker(document.getElementById('selected_date').value,0);";
        $media_upload['vfolder_js'] = "document.getElementById('vfolder_path').value";
        include('modules/media/upload/upload.php');
		?>
    </div>
</div>