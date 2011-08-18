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
			$listing = aql::select("dup_sentence { sentence, volume order by sentence asc }",array('dup_sentence'=>array('where'=>$w)));
		else {
			$width = 310;
			if ($or) {
				if (strpos($or,'OR') < 5) $or = preg_replace('/ OR /','',$or,1);
				$where = ' and ( '.$or.' ) ';
			}
			//echo $where;
			$listing = aql::select("dup_phrase_data { lower(phrase) as lower_phrase, phrase, volume where market != '' and base != '' and volume > 0 {$where} order by volume DESC, phrase asc }");
		}
	}
?>
    <input type="hidden" id="or" value="<?=$or?>" />
	<fieldset>
    	<legend class="legend">Auto Permetation</legend>
<?
			foreach ($listing as $data) {
				$p1 = $data[$field];
				foreach ($listing as $data2) {
					if ($data2[$table.'_id'] != $data[$table.'_id']) $p2 = $data2[$field];
					foreach ($listing as $data3) {
						if ($data2[$table.'_id'] != $data[$table.'_id'] && $data2[$table.'_id'] != $data3[$table.'_id'] && $data3[$table.'_id'] != $data[$table.'_id'] ) $p3 = $data3[$field];
					}
				}
				$full_phrase[1][] = $p1.' '.$p2.' '.$p3;
				$full_phrase[2][] = $p1.' '.$p3.' '.$p2;
				$full_phrase[3][] = $p2.' '.$p3.' '.$p1;
				$full_phrase[4][] = $p2.' '.$p1.' '.$p3;
				$full_phrase[5][] = $p3.' '.$p2.' '.$p1;
				$full_phrase[6][] = $p3.' '.$p1.' '.$p2;
				
			}
			foreach ($full_phrase as $choice => $phrase) {
				foreach ($phrase as $ph) {
					echo "[".$choice."] ".$ph."<br>";
				}
			}
?>
    </fieldset>