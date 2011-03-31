<?
if (!$selected_date) $selected_date = $_GET['date'];
for ($i=3; $i>0; $i--) {
    $date = strtotime('-'.$i.' months', $selected_date);
	$path = date('Y/m', strtotime('-'.$i.' months', $selected_date));
    $date_formatted = date('M y', strtotime('-'.$i.' months', $selected_date));
?>
	<a class="image-picker-month <? if ($date==$selected_date) echo 'selected'; ?>" href="javascript:imagePicker('<?=$date?>',0);"><?=$date_formatted?></a>
<?
}//for
for ($i=0; $i<4; $i++) {
    $date = strtotime('+'.$i.' months', $selected_date);
	$path = date('Y/m', strtotime('+'.$i.' months', $selected_date));
    $date_formatted = date('M y', strtotime('+'.$i.' months', $selected_date));
?>
	<a class="image-picker-month <? if ($date==$selected_date) echo 'selected'; ?>" href="javascript:imagePicker('<?=$date?>',0);"><?=$date_formatted?></a>
<?
}//for
?>