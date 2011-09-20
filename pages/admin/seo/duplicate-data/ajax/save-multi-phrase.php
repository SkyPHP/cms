<?
	// GET ALL THE POSTED IDS
	if ($_POST['phrase1_ids'])
		foreach ($_POST['phrase1_ids'] as $p1) {
			if ($_POST['phrase2_ids']) 
				foreach ($_POST['phrase2_ids'] as $p2) {
					$phrase_ids[] = $p1.','.$p2;
				}
		}
	
	if ($_POST['mod_ids']) {
		foreach ($phrase_ids as $id_group) {
			foreach ($_POST['mod_ids'] as $mod) {
				$final[] = $id_group.','.$mod;
			}
		}
	}
	else $final = $phrase_ids;
	
	// GET ALL THE POSTED VOLUMES AND SET THE TOTAL FOR EACH KEY
	foreach ($_POST['volume1'] as $volume) {
		$vol1[] = $volume;
	}
	foreach ($_POST['volume2'] as $volume) {
		$vol2[] = $volume;
	}
	
	foreach($vol1 as $key => $volume) {
		$total_volume[] = $volume + $vol2[$key];
	}
	
	// CHECK THE DB AND ENTER NON-DUPS
	foreach ($final as $key => $phrase) {
		$split = explode(',',$phrase);
		if ($split[2]) $mod_where = "and dup_modifier_id = ".$split[2];
		else $mod_where = "";
		$rs = aql::select("
			dup_phrase_group { 
				where ( phrase1__dup_phrase_data_id = ".$split[0]." or phrase2__dup_phrase_data_id = ".$split[0].") 
				and ( phrase1__dup_phrase_data_id = ".$split[1]." or phrase2__dup_phrase_data_id = ".$split[1].")
				{$mod_where}
			}
		");
		if ($rs || ($split[0] == $split[1])) $exists[$key] = true;
		else {
			$data = array(
				'name' => $_POST['group_name'],
				'seo_field' => $_POST['seo_field'],
				'phrase1__dup_phrase_data_id' => $split[0],
				'phrase2__dup_phrase_data_id' => $split[1],
				'dup_modifier_id' => $split[2],
				'mod__person_id' => PERSON_ID,
				'total_volume' => $total_volume[$key],
				'category' => $_POST['category'],
				'market_name' => $_POST['market_name'],
				'page'=>$_POST['page']
			);
			aql::insert('dup_phrase_group',$data);
		}	
	}		
	
	// SHOW THE POSTED STUFF
	if ($_POST['phrase1'])
		foreach ($_POST['phrase1'] as $p1) {
			if ($_POST['phrase2']) 
				foreach ($_POST['phrase2'] as $p2) {
					$phrases[] = $p1.','.$p2;
				}
		}
	
	if ($_POST['mods']) {
		foreach ($phrases as $phrase) {
			foreach ($_POST['mods'] as $mod) {
				$words[] = $phrase.','.$mod;
			}
		}
	}
	else $words = $phrases;
	
	
	if ($words) 
		foreach ($words as $key => $phrase) { if ($exists[$key]) $dup_count++; }
		if (!$dup_count) $style_attr = 'style="margin-bottom: 5px;"';
		else $style = ''; 
		echo '<div>'.count($words).' Selected Phrase Groups .</div>';
		echo '<div '.$style_attr.'> '.(count($words) - $dup_count).' Phrase Groups Saved.</div>';
		if ($dup_count > 1) echo '<div style="margin-bottom:5px;">'.$dup_count.' Duplicates.</div>';
		else if ($dup_count) echo '<div style="margin-bottom:5px;">'.$dup_count.' Duplicate.</div>';
		foreach ($words as $key => $phrase) {
			if ($exists[$key]) $status = "Duplicate";
			else $status = "OK";
			$split = explode(',',$phrase);
			$phrase_final = implode(' | ',$split);
			echo '<div class="has-floats"><div style="margin-bottom:2px; float:left; margin-right: 15px; width: 600px">'.ucwords($phrase_final).'</div><div style="float:left;">'.$status.'</div></div>';
		}
?>
<div style="margin: 10px 0;"><a style="cursor:pointer" id="clear-results" >Clear Results</a></div>