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
		if ($rs) $exists[$key] = true;
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
	
	foreach ($words as $key => $phrase) {
		if ($exists[$key]) $status = "Duplicate";
		else $status = "OK";
		echo "<div>".$phrase." - ".$status."</div>";
	}
	
	print_a($final);
?>