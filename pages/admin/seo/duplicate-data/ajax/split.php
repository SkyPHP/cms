<?
	$val = $_POST['val'];
	$sentences = explode('.',$val);
	foreach ($sentences as $key => $sentence) {
?>
	<div id="sentence<?=$key?>-container" class="has-floats" style="margin-bottom:20px;">
		<div style="float:left; margin-right:10px;">
        	<input type="text" id="sentence<?=$key?>" style="width:600px" value="<?=trim($sentence)?>." />
        </div>
        <div style="float:left;">
        	<input type="text" size="2" maxlength="2" value="<?=$key?>" />
        </div>
    </div>
<?	
	}
?>