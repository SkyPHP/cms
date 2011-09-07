<?
	$val = $_POST['val'];
	if ($_POST['auto']) $auto = 'checked="checked"';
	else $man = 'checked="checked"';
	preg_match_all('~.*?[?.!]~s',$val,$sentences);
?>
	<div class="has-floats" style="margin-top: 20px;">
		<div style="float:left; width: 1000px; margin-right: 10px; font-weight:bold; font-size:18px;">Sentences</div>
    	<div style="float:left; font-weight:bold;">
        	<span style="font-size:18px">Order</span><br>
        	<input type="radio" name="auto-switch" value="man" <?=$man?>> Manual<br>
        	<input type="radio" name="auto-switch" value="auto"> Auto
        </div>
	</div>
<?
	$ids = array();
	foreach ($sentences[0] as $key => $sentence) {
		$data = array(
			'sentence' => addslashes($sentence),
			'name' => $_POST['name'],
			'source' => $_POST['source'],
			'mod__person_id' => PERSON_ID
		);
		$insert = aql::insert('dup_sentence_data',$data);
?>	
	<div id="sentence<?=$key?>-container" class="has-floats" style="margin:10px 0 20px 0;">
    	<div>Sentence <?=$key+1?></div>
		<div style="float:left; margin-right:10px;">
        	<input type="text" dup_sentence_data_id="<?=$insert[0]['dup_sentence_data_id']?>" id="sentence<?=$key?>" style="width:1000px" class="sentence" value="<?=trim($sentence)?>" />
        </div>
        <div style="float:left;" class="manual-order">
        	<input type="text" dup_sentence_data_id="<?=$insert[0]['dup_sentence_data_id']?>" style="width:16px; text-align:right;" maxlength="2" value="<?=$key+1?>" />
        </div>
    </div>
<?		
	}
?>
<input type="button" id="save-sentences" value="Save" style="margin-top:10px; margin-bottom:10px;" />
<div id="auto-sentences" style="display:none"></div>