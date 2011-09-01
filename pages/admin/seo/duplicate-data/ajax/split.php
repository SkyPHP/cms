<?
	$val = $_POST['val'];
	if ($_POST['auto']) $auto = 'checked="checked"';
	else $man = 'checked="checked"';
	preg_match_all('~.*?[?.!]~s',$val,$sentences);
?>
	<div class="has-floats" style="margin-top: 20px;">
		<div style="float:left; width: 900px; margin-right: 10px; font-weight:bold;">Sentences</div>
    	<div style="float:left; font-weight:bold;">Order<br><input type="radio" name="auto-switch" value="man" <?=$man?>> Manual&nbsp;&nbsp;&nbsp;<input type="radio" name="auto-switch" value="auto"> Auto</div>
	</div>
<?
	foreach ($sentences[0] as $key => $sentence) {
?>	
	<div id="sentence<?=$key?>-container" class="has-floats" style="margin:10px 0 20px 0;">
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