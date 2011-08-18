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
?>
    <input type="hidden" id="or" value="<?=$or?>" />
	<fieldset>
    	<legend class="legend">Auto Permetation</legend>
<?
			foreach ($listing as $data) {
				$phrase = '';
				$phrase = $data[$field];
				$p1 = $phrase.' ';
				foreach ($listing as $data2) {
					$phrase .= $data2[$field];
					$p2 = $data2[$field];
					foreach($listing as $data3) {
						$phrase .= ' '.$data3[$field];
						$p3 = $data3[$field];
					}
				}
				echo '<input type="checkbox" id="{$counter}" phrase="{$phrase}" class="auto-phrase" /> '.$phrase.'<br>';
			}
?>
    </fieldset>