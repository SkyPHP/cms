<?
	if (!$_POST['list']) exit('fail');
	else {
		$rs = aql::select("dup_sentence_group { group_number order by group_number desc limit 1 }");
		$num = $rs[0]['group_number'] + 1;
		foreach($_POST['list'] as $list) {
			$data = array(
				'order' => $list,
				'mod__person_id' => PERSON_ID
			);
			aql::insert("dup_sentence_group",$data);
			$num++;
		}	
	}
	
?>