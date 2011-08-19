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
		if ($type == 'paragraph') {
			$field = 'sentence';
			$listing = aql::select("dup_sentence { sentence, volume order by sentence asc }",array('dup_sentence'=>array('where'=>$w)));
		}
		else {
			$field = 'phrase';
			$width = 310;
			if ($or) {
				if (strpos($or,'OR') < 5) $or = preg_replace('/ OR /','',$or,1);
				$where = ' and ( '.$or.' ) ';
			}
			//echo $where;
			$listing = aql::select("dup_phrase_data { lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 {$where} order by volume DESC, phrase asc }");
		}
	}
	$phrases = array();
?>
    <input type="hidden" id="or" value="<?=$or?>" />
	<fieldset style="width:90%;">
    	<legend class="legend">Auto Permetation</legend>
<?
			foreach ($listing as $data) {
				$auto_data[]=$data[$field]; // get the phrase or sentence
				$count_data[] = $data['volume'];
			}
			
			$count = 10;
			for ($x=0;$x<$count;$x++) {
				for ($y=0;$y<$count;$y++) {
					for ($z=0;$z<$count;$z++) {
						if ($x != $y && $x != $z && $y != $z) {
							$phrases[] = $auto_data[$x].' '.$auto_data[$y].' '.$auto_data[$z];
							$p1[] = $auto_data[$x];
							$p2[] = $auto_data[$y];
							$p3[] = $auto_data[$z];
							$counts[] = $count_data[$x] + $count_data[$y] + $count_data[$z];
						}
					}
				}
			}
			$count = count($phrases);
?>
			<div style="float:left; margin-right:20px;">
<?
				for($key=0; $key<$count;$key++) {
?>
					<div style="width:70px; margin-right:5px; float:left">(<?=$counts[$key]?>)</div><div style="float:left"><input type="checkbox" p1="<?=$p1[$key]?>" p2="<?=$p2[$key]?>" p3="<?=$p3[$key]?>" value="<?=$phrases[$key]?>"> <?=$phrases[$key]?></div>
					<div class="clear"></div>
<?	
					if (intval($count /2) == $key) {
?>
						</div><div style="float:left;">
<?
					}
				}
?>
			</div>
    </fieldset>