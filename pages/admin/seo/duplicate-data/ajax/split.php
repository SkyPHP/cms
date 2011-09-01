<?
	$val = $_POST['val'];
	preg_match_all('~.*?[?.!]~s',$val,$sentences);
	foreach ($sentences[0] as $key => $sentence) {
?>
	<div id="sentence<?=$key?>-container" class="has-floats" style="margin-top:20px;">
    	<div>Sentence <?=$key+1?></div>
		<div style="float:left; margin-right:10px;">
        	<input type="text" id="sentence<?=$key?>" style="width:900px" value="<?=trim($sentence)?>" />
        </div>
        <div style="float:left;">
        	<input type="text" style="width:16px; text-align:right;" maxlength="2" value="<?=$key+1?>" />
        </div>
    </div>
<?		
	}
?>