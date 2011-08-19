<?
	if (!$listing) {
		$or = $_POST['or'];
		$type == $_POST['type'];
		$o = explode(' OR ',$or);
		if ($_POST['sw'] == 'on') $o[] = $_POST['filter']." = '".addslashes($_POST['value'])."'";
		else if ($_POST['sw'] == 'off') {
			foreach($o as $key => $statement) {
				//echo $wh.' --- '.$_POST['filter']." = '".addslashes($_POST['value'])."'<br>";
				if ($statement == $_POST['filter']." = '".addslashes($_POST['value'])."'") unset($o[$key]); 
			}
		}
		$or = implode(' OR ',$o);
		if ($type == 'paragraph') 
			$listing = aql::select("dup_sentence { id as sentence_id, sentence, volume order by sentence asc }",array('dup_sentence'=>array('where'=>$w)));
		else {
			$width = 310;
			if ($or) {
				if (strpos($or,'OR') < 5) $or = preg_replace('/ OR /','',$or,1);
				$where = ' and ( '.$or.' ) ';
			}
			//echo $where;
			$listing = aql::select("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 {$where} order by volume DESC, phrase asc }");
		}
	}
	$count = count($listing);
?>
    <input type="hidden" id="or" value="<?=$or?>" />
    <input type="hidden" id="type" value="<?=$type?>" />
    <fieldset style="width:<?=$width?>px; border: solid 1px #CCCCCC; padding: 15px; float:left; margin-right:10px;">
    		<legend class="legend"><?=$type=='phrase'?'Phrase Part ':'Sentence #'?>1 (<?=$count?> Phrases)</legend>
<?
			$x=0;
			if ($listing) foreach($listing as $data) {
				
				$x++;
				if ($x == 1) print_pre($data);
				if ($x == 1) echo aql::sql("dup_phrase_data { id as phrase_id, lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 {$where} order by volume DESC, phrase asc }");
?>
				<div style="width:50px; float:left; margin-right:5px">(<?=$data['volume']?>)</div><div style="float:left;"> <input type="radio" name="phrase1" part="1" phrase="<?=$data['phrase']?>" phrase_id="<?=$data['phrase_id']?>" class="listing_radio" id="<?=$data['lower_phrase']?>" /> <label for="<?=$data['lower_phrase']?>"><?=$data['lower_phrase']?></label></div>
                <div class="clear"></div>
<?	
			}
?>
    	</fieldset>
        
        <fieldset style="width:<?=$width?>px; border: solid 1px #CCCCCC; padding: 15px; float:left; margin-right:10px;">
    		<legend class="legend"><?=$type=='phrase'?'Phrase Part ':'Sentence #'?>2 (<?=$count?> Phrases)</legend>
<?
			if ($listing) foreach($listing as $data) {
?>
				<div style="width:50px; float:left; margin-right:5px">(<?=$data['volume']?>)</div><div style="float:left;"> <input type="radio" name="phrase2" part="2" phrase="<?=$data['phrase']?>" phrase_id="<?=$data['phrase_id']?>" class="listing_radio" id="<?=$data['lower_phrase']?>2" /> <label for="<?=$data['lower_phrase']?>2"><?=$data['lower_phrase']?></label></div>
                <div class="clear"></div>
<?	
			}
?>
    	</fieldset>
        
        <fieldset style="width:<?=$width?>px; border: solid 1px #CCCCCC; padding: 15px; float:left; margin-right:10px;">
    		<legend class="legend"><?=$type=='phrase'?'Phrase Part ':'Sentence #'?>3 (<?=$count?> Phrases)</legend>
<?
			if ($listing) foreach($listing as $data) {
?>
				<div style="width:50px; float:left; margin-right:5px">(<?=$data['volume']?>)</div><div style="float:left;"> <input type="radio" name="phrase3" part="3" phrase="<?=$data['phrase']?>" phrase_id="<?=$data['phrase_id']?>" class="listing_radio" id="<?=$data['lower_phrase']?>3" /> <label for="<?=$data['lower_phrase']?>3"><?=$data['lower_phrase']?></label></div>
                <div class="clear"></div>
<?	
			}
?>
    	</fieldset>
        
<? if ($type == 'paragraph') { ?>
		<fieldset style="width:<?=$width?>px; border: solid 1px #CCCCCC; padding: 15px; float:left; margin-right:10px;">
    		<legend class="legend"><?=$type=='phrase'?'Phrase Part ':'Sentence #'?>4 (<?=$count?> Phrases)</legend>
<?
			if ($listing) foreach($listing as $data) {
?>
				<div style="width:50px; float:left; margin-right:5px">(<?=$data['volume']?>)</div><div style="float:left;"> <input type="radio" name="phrase4" part="4" phrase="<?=$data['phrase']?>" phrase_id="<?=$data['phrase_id']?>" class="listing_radio" id="<?=$data['lower_phrase']?>4" /> <label for="<?=$data['lower_phrase']?>4"><?=$data['lower_phrase']?></label></div>
                <div class="clear"></div>
<?	
			}
?>
    	</fieldset>
<?	} ?>