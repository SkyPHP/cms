<?
	if (!$_POST['list']) exit('Please Select a Paragraph');
	else {
		foreach($_POST['list'] as $list) {
			$data = array(
				'sentence_order' => $list,
				'mod__person_id' => PERSON_ID
			);
			aql::insert("dup_sentence_group",$data);
			$num++;
		}	
	}
	
?>